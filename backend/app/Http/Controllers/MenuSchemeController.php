<?php

namespace App\Http\Controllers;

use App\Models\MenuScheme;
use App\Models\MenuSchemeMeal;
use App\Models\MenuSchemeRepeatGroup;
use App\Models\MenuSchemeRepeatItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuSchemeController extends Controller
{
    public function __construct() {
        $this->authorizeResource(MenuScheme::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(MenuScheme::whereUserId(Auth::id())->get());
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
            'meals' => 'required|array',
            'meals.*' => 'required|string',
            'groups' => 'required|array',
            'groups.*' => 'required|array',
            'groups.*.*.meal' => 'required|string|in_array:meals.*',
            'groups.*.*.day' => 'required|numeric|integer|min:0|max:6'
        ]);

        $menuScheme = MenuScheme::create([
            'user_id' => Auth::id(),
            'name' => $input['name'],
        ]);

        return $this->fillSchemeFromInput($input, $menuScheme);
    }

    /**
     * Display the specified resource.
     *
     * @param MenuScheme $menuScheme
     * @return JsonResponse
     */
    public function show(MenuScheme $menuScheme): JsonResponse
    {
        return response()->json($menuScheme->load('groups.items', 'meals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param MenuScheme $menuScheme
     * @return JsonResponse
     */
    public function update(Request $request, MenuScheme $menuScheme): JsonResponse
    {
        $input = $request->validate([
            'name' => 'required|string',
            'meals' => 'required|array',
            'meals.*' => 'required|string',
            'groups' => 'required|array',
            'groups.*' => 'required|array',
            'groups.*.*.meal' => 'required|string|in_array:meals.*',
            'groups.*.*.day' => 'required|numeric|integer|min:0|max:6'
        ]);

        $menuScheme->items()->delete();
        $menuScheme->meals()->delete();
        $menuScheme->groups()->delete();

        return $this->fillSchemeFromInput($input, $menuScheme);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MenuScheme $menuScheme
     * @return JsonResponse
     */
    public function destroy(MenuScheme $menuScheme): JsonResponse
    {
        $menuScheme->delete();

        return $this->show($menuScheme);
    }

    /**
     * @param array $input
     * @param MenuScheme $menuScheme
     * @return JsonResponse
     */
    private function fillSchemeFromInput(array $input, MenuScheme $menuScheme): JsonResponse
    {
        foreach ($input['meals'] as $meal) {
            MenuSchemeMeal::insert([
                'scheme_id' => $menuScheme->id,
                'name' => $meal,
            ]);
        }

        foreach ($input['groups'] as $group) {
            $groupId = MenuSchemeRepeatGroup::insertGetId([
                'scheme_id' => $menuScheme->id,
            ]);

            foreach ($group as $item) {
                MenuSchemeRepeatItem::insert([
                    'group_id' => $groupId,
                    'meal_id' => MenuSchemeMeal::whereName($item['meal'])->first()->id,
                    'day' => $item['day'],
                ]);
            }
        }

        return $this->show($menuScheme);
    }
}
