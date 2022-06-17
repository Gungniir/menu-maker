<?php

namespace App\Http\Controllers;

use App\Exceptions\AutoMenuException;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuMeal;
use App\Models\MenuScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\NotImplementedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Menu::whereUserId(Auth::id())->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AutoMenuException
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->validate([
            'scheme_id' => 'required|numeric|integer|exists:menu_schemes,id',
            'categories' => 'nullable|array',
            'categories.*' => 'required|numeric|integer|exists:categories,id',
            'amount' => 'required|numeric|integer|min:1',
            'wishes' => 'nullable|array',
            'start_date' => 'required|date',
            'wishes.*' => 'required|numeric|integer|exists:categories,id',
            'meal_categories' => 'nullable|array',
            'meal_categories.*.meal_id' => 'required|numeric|integer|exists:menu_scheme_meals,id',
            'meal_categories.*.category_id' => 'required|numeric|integer|exists:categories,id',
        ]);

        $scheme = MenuScheme::find($input['scheme_id']);
        $scheme->load('groups.items', 'meals.items', 'items');

        $requirements = [];
        $wishes = collect($input['wishes']);
        $categories = collect($input['categories']);
        $items = [];

        foreach ($scheme->meals as $meal) {
            $items[$meal->id] = [-1, -1, -1, -1, -1, -1, -1];
        }

        if (count($input['wishes']) > $scheme->groups_count) {
            throw new AutoMenuException('Количтсво желаний больше количества возможных блюд');
        }

        foreach ($scheme->groups as $group) {
            $places = collect();
            $meals = collect();

            foreach ($group->items as $item) {
                $places->push([
                    'meal_id' => $item->meal_id,
                    'day' => $item->day
                ]);

                if (!$meals->contains($item->meal_id)) {
                    $meals->push($item->meal_id);
                }

                $items[$item->meal_id][$item->day] = 0;
            }

            $mealCategories = collect($input['meal_categories'])->whereIn('meal_id', $meals)->pluck('category_id')->all();

            $requirements[] = [
                'places' => $places,
                'categories' => $categories->concat($mealCategories)->unique()->values()->all(),
            ];
        }

        foreach ($items as $menu_id => $days) {
            foreach ($days as $dayIndex => $day) {
                if ($day === -1) {
                    $items[$menu_id][$dayIndex] = 0;

                    $requirements[] = [
                        'places' => [[
                            'meal_id' => $menu_id,
                            'day' => $dayIndex
                        ]],
                        'categories' => $categories
                    ];
                }
            }
        }

        $response = [];
        $usedDishes = [];

        foreach ($requirements as $requirement) {
            if (count($requirement['categories']) > 0) {
                $dishes = Dish::fromQuery(/** @lang PostgreSQL */ '
                    SELECT d.* FROM dishes d
                        LEFT JOIN dish_category dc on d.id = dc.dish_id
                        LEFT JOIN categories c on dc.category_id = c.id
                    WHERE c.id = ANY(:cc) AND d.deleted_at IS NULL AND d.creator_id = :user_id AND d.id != ALL(:dd)
                    GROUP BY d.id
                        HAVING count(c.id) >= array_length(:cc, 1)
                ', [
                    'dd' => '{' . implode(',', $usedDishes) . '}',
                    'cc' => '{' . implode(',', $requirement['categories']) . '}',
                    'user_id' => Auth::id(),
                ]);
            } else {
                $dishes = Dish::whereCreatorId(Auth::id())->whereNotIn('id', $usedDishes)->get();
            }

            if ($dishes->count() === 0) {
                throw new AutoMenuException(
                    'Не удалось найти блюдо, входящее в следующие категории: ' .
                    implode(', ', Category::whereIn('id', $requirement['categories'])->get('name')->pluck('name')->all()) .
                    ', но не являющееся: ' .
                    implode(', ', Dish::whereIn('id', $usedDishes)->get('name')->pluck('name')->all())
                );
            }

            $dishes->load( 'categories');
            $dishes->loadCount('menu_items');
            $dishes = $dishes->sortBy('menu_items_count')->values();

            $dish = $dishes[0];

            foreach ($dishes as $curDish) {
                assert($curDish instanceof Dish, '');

                $foundedWishes = $curDish->categories->pluck('id')->values()->intersect($wishes);
                if ($foundedWishes->count() > 0) {
                    $dish = $curDish;
                    $wishes = $wishes->reject(fn($item) => $foundedWishes->contains($item));
                    break;
                }
            }

            assert($dish instanceof Dish, '');

            $usedDishes[] = $dish->id;
            $response[] = [
                'places' => $requirement['places'],
                'dish_id' => $dish->id
            ];
        }

        $menu = Menu::create([
            'user_id' => Auth::id(),
            'amount' => $input['amount'],
            'start_date' => $input['start_date']
        ]);

        $mealMap = [];

        foreach ($scheme->meals as $meal) {
            $mealMap[$meal->id] = MenuMeal::insertGetId([
                'menu_id' => $menu->id,
                'name' => $meal->name,
            ]);
        }

        foreach ($response as $item) {
            foreach ($item['places'] as $place) {
                MenuItem::insert([
                    'dish_id' => $item['dish_id'],
                    'meal_id' => $mealMap[$place['meal_id']],
                    'day' => $place['day'],
                ]);
            }
        }

        return $this->show($menu);
    }

    /**
     * Display the specified resource.
     *
     * @param Menu $menu
     * @return JsonResponse
     */
    public function show(Menu $menu): JsonResponse
    {
        $menu->load('meals.items.dish.images');

        return response()->json($menu);
    }

    /**
     * Получить меню по дате
     *
     * @param string $date
     * @return JsonResponse
     */
    public function showForDate(string $date): JsonResponse
    {
        $menu = Menu::whereStartDate($date)->whereUserId(Auth::id())->first();

        if (!$menu) {
            throw new NotFoundHttpException();
        }

        return $this->show($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Menu $menu
     * @return JsonResponse
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        throw new NotImplementedException();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Menu $menu
     * @return JsonResponse
     */
    public function destroy(Menu $menu): JsonResponse
    {
        throw new NotImplementedException();
    }
}
