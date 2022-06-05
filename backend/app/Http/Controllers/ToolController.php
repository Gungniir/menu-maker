<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Tool::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $paginate = Tool::whereCreatorId(Auth::id())->paginate(9);

        return response()->json($paginate);
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
            'amount' => 'required|numeric|integer|min:0',
            'reusable' => 'required|boolean',
        ]);

        $tool = Tool::create([
            ...$input,
            'creator_id' => Auth::id()
        ]);

        return response()->json($tool);
    }

    /**
     * Display the specified resource.
     *
     * @param Tool $tool
     * @return JsonResponse
     */
    public function show(Tool $tool): JsonResponse
    {
        return response()->json($tool);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Tool $tool
     * @return JsonResponse
     */
    public function update(Request $request, Tool $tool): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|integer|min:0',
            'reusable' => 'required|boolean',
        ]);

        $tool->update([
            ...$input
        ]);

        return response()->json($tool);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tool $tool
     * @return JsonResponse
     */
    public function destroy(Tool $tool): JsonResponse
    {
        $dishes = $tool->dishes()->get();

        if ($dishes->count() > 0) {
            return response()->json($dishes, 409);
        }

        $tool->delete();

        return response()->json($tool);
    }
}
