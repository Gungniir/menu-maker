<?php

namespace App\Http\Controllers;

use App\Models\Dish;
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
        return response()->json(Dish::whereCreatorId(Auth::id())->paginate(10));
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
}
