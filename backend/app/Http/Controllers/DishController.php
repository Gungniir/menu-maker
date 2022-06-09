<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\DishIngredient;
use App\Models\Ingredient;
use App\Models\RecipeItem;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $pagination = Dish::whereCreatorId(Auth::id())->with('images', 'categories')->paginate(9);

        return response()->json($pagination);
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
            'cooking_time' => 'numeric|min:0|max:1440',
            'kcal' => 'numeric|min:0|max:32767',
            'proteins' => 'numeric|min:0|max:32767',
            'fats' => 'numeric|min:0|max:32767',
            'carbohydrates' => 'numeric|min:0|max:32767',
            'link' => 'string|max:1000'
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
            'cooking_time' => 'numeric|min:0|max:1440',
            'kcal' => 'numeric|min:0|max:32767',
            'proteins' => 'numeric|min:0|max:32767',
            'fats' => 'numeric|min:0|max:32767',
            'carbohydrates' => 'numeric|min:0|max:32767',
            'link' => 'string|max:1000',

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
}
