<?php

namespace App\Http\Controllers;

use App\Enums\IngredientUnit;
use App\Models\Ingredient;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Ingredient::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $paginate = Ingredient::whereCreatorId(Auth::id())->orderBy('name')->paginate(50);

        return response()->json($paginate);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function indexTypes(): JsonResponse
    {
        $this->authorize('viewAny', Ingredient::class);

        $types = collect(DB::select(/** @lang PostgreSQL */ 'SELECT DISTINCT type FROM ingredients WHERE creator_id = :user_id', [
            'user_id' => Auth::id(),
        ]))->pluck('type')->values();

        return response()->json($types);
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
            'is_perishable' => 'required|boolean',
            'amount' => 'required|numeric|decimal:0,3|min:0',
            'type' => 'nullable|string',
            'unit' => [
                'required',
                Rule::in(IngredientUnit::values()),
            ],
        ]);

        $ingredient = Ingredient::create([
            ...$input,
            'creator_id' => Auth::id()
        ]);

        return response()->json($ingredient);
    }

    /**
     * Display the specified resource.
     *
     * @param Ingredient $ingredient
     * @return JsonResponse
     */
    public function show(Ingredient $ingredient): JsonResponse
    {
        return response()->json($ingredient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Ingredient $ingredient
     * @return JsonResponse
     */
    public function update(Request $request, Ingredient $ingredient): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'is_perishable' => 'required|boolean',
            'amount' => 'required|numeric|decimal:0,3|min:0',
            'type' => 'nullable|string',
            'unit' => [
                'required',
                Rule::in(IngredientUnit::values()),
            ],
        ]);

        $ingredient->update($input);

        return response()->json($ingredient);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ingredient $ingredient
     * @return JsonResponse
     */
    public function destroy(Ingredient $ingredient): JsonResponse
    {
        $dishes = $ingredient->dishes()->get();

        if ($dishes->count() > 0) {
            return response()->json($dishes, 409);
        }

        $ingredient->delete();

        return response()->json($ingredient);
    }
}
