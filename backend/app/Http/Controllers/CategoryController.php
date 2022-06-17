<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Category::whereCreatorId(Auth::id())->paginate(9));
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
            'name' => 'required|string',
            'dishes' => 'array',
            'dishes.*' => 'required|numeric|integer|exists:dishes,id'
        ]);

        $category = Category::forceCreate([
            'creator_id' => Auth::id(),
            'name' => $input['name']
        ]);

        foreach ($input['dishes'] as $dishId) {
            $category->dishes()->attach($dishId);
        }

        return $this->show($category);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string',
            'dishes' => 'array',
            'dishes.*' => 'required|numeric|integer|exists:dishes,id'
        ]);

        $category->update([
            'name' => $input['name']
        ]);

        $category->dishes()->detach();

        foreach ($input['dishes'] as $dishId) {
            $category->dishes()->attach($dishId);
        }

        return $this->show($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return $this->show($category);
    }
}
