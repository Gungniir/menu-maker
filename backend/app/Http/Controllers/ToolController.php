<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Tool $tool
     * @return JsonResponse
     */
    public function show(Tool $tool): JsonResponse
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tool $tool
     * @return JsonResponse
     */
    public function destroy(Tool $tool): JsonResponse
    {
        //
    }
}
