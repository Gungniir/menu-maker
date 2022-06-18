<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\DishImage;
use App\Models\DishIngredient;
use App\Models\Image;
use App\Models\Ingredient;
use App\Models\RecipeItem;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DishController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Dish::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function index(Request $request): JsonResponse
    {
        $input = $request->validate([
            'category_id' => 'numeric|integer|exists:categories,id',
            'filter' => 'string'
        ]);

        if ($request->has('category_id')) {
            $category = Category::findOrFail($input['category_id']);

            if ($category->creator_id !== Auth::id()) {
                throw new AuthenticationException();
            }

            $builder = $category->dishes()->where('creator_id', Auth::id())->with('images', 'categories')->orderBy('name');
        } else {
            $builder = Dish::whereCreatorId(Auth::id())->with('images', 'categories');
        }

        if ($request->has('filter')) {
            $builder = $builder->where('name', 'ilike', '%' . $input['filter'] . '%');
        }

        return response()->json($builder->orderBy('name')->paginate(9));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'cooking_time' => 'nullable|numeric|min:0|max:1440',
            'kcal' => 'nullable|numeric|min:0|max:32767',
            'proteins' => 'nullable|numeric|min:0|max:32767',
            'fats' => 'nullable|numeric|min:0|max:32767',
            'carbohydrates' => 'nullable|numeric|min:0|max:32767',
            'link' => 'nullable|string|max:1000'
        ]);

        $dish = Dish::create([
            ...$input,
            'creator_id' => Auth::id(),
        ]);

        return response()->json($dish);
    }

    /**
     * Display the specified resource.
     *
     * @param Dish $dish
     * @return JsonResponse
     */
    public function show(Dish $dish): JsonResponse
    {
        $dish->load('recipeItems', 'preparations', 'images', 'categories', 'tools', 'ingredients');

        return response()->json($dish);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Dish $dish
     * @return JsonResponse
     */
    public function update(Request $request, Dish $dish): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'cooking_time' => 'nullable|numeric|min:0|max:1440',
            'kcal' => 'nullable|numeric|min:0|max:32767',
            'proteins' => 'nullable|numeric|min:0|max:32767',
            'fats' => 'nullable|numeric|min:0|max:32767',
            'carbohydrates' => 'nullable|numeric|min:0|max:32767',
            'link' => 'nullable|string|max:1000',

            'is_archive' => 'boolean'
        ]);

        $dish->update($input);

        return response()->json($dish);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Dish $dish
     * @return JsonResponse
     */
    public function destroy(Dish $dish): JsonResponse
    {
        $response = $dish->delete();

        return response()->json($response);
    }

    /**
     * Добавить или изменить ингредиент.
     *
     * @param Request $request
     * @param Dish $dish
     * @param Ingredient $ingredient
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeIngredient(Request $request, Dish $dish, Ingredient $ingredient): JsonResponse
    {
        $this->authorize('storeIngredient', [$dish, $ingredient]);

        $input = $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        DishIngredient::updateOrCreate([
            'dish_id' => $dish->id,
            'ingredient_id' => $ingredient->id
        ], ['amount' => $input['amount']]);

        return $this->show($dish);
    }

    /**
     * Удалить ингредиент.
     *
     * @param Dish $dish
     * @param Ingredient $ingredient
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroyIngredient(Dish $dish, Ingredient $ingredient): JsonResponse
    {
        $this->authorize('destroyIngredient', [$dish, $ingredient]);

        $dish->ingredients()->detach([$ingredient->id]);

        return $this->show($dish);
    }

    /**
     * Добавить элемент рецепта к блюду
     *
     * @param Request $request
     * @param Dish $dish
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeRecipeItem(Request $request, Dish $dish): JsonResponse
    {
        $this->authorize('storeRecipeItem', [$dish]);

        $input = $request->validate([
            'item' => 'required|string',
        ]);

        RecipeItem::create([
            'dish_id' => $dish->id,
            'item' => $input['item'],
            'order' => -1,
        ]);

        return $this->show($dish);
    }

    /**
     * Добавить элемент рецепта к блюду
     *
     * @param Request $request
     * @param Dish $dish
     * @param RecipeItem $recipeItem
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updateRecipeItem(Request $request, Dish $dish, RecipeItem $recipeItem): JsonResponse
    {
        $this->authorize('updateRecipeItem', [$dish, $recipeItem]);

        $input = $request->validate([
            'item' => 'required|string',
        ]);

        $recipeItem->update([
            'item' => $input['item']
        ]);

        return $this->show($dish);
    }

    /**
     * Удалть элемент рецепта у блюда
     *
     * @param Dish $dish
     * @param RecipeItem $recipeItem
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroyRecipeItem(Dish $dish, RecipeItem $recipeItem): JsonResponse
    {
        $this->authorize('destroyRecipeItem', [$dish, $recipeItem]);

        $recipeItem->delete();

        return $this->show($dish);
    }

    /**
     * Прикрепить изображение к блюду
     *
     * @param Dish $dish
     * @param Image $image
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function attachImage(Dish $dish, Image $image): JsonResponse
    {
        $this->authorize('attachImage', [$dish, $image]);

        DishImage::firstOrCreate([
            'dish_id' => $dish->id,
            'image_id' => $image->id
        ]);

        return $this->show($dish);
    }

    /**
     * Открепить изображение от блюда
     *
     * @param Dish $dish
     * @param Image $image
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function detachImage(Dish $dish, Image $image): JsonResponse
    {
        $this->authorize('detachImage', [$dish, $image]);

        DishImage::where('dish_id', $dish->id)->where('image_id', $image->id)->delete();

        return $this->show($dish);
    }
}
