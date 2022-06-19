<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Ingredient;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(CartItem::whereUserId(Auth::id())->with('ingredient')->get());
    }

    public function store(Request $request): JsonResponse {
        $input = $request->validate([
            'menu_id' => 'required|numeric|integer|exists:menus,id',
            'amount' => 'required|numeric|integer|min:1',
            'consider_fridge' => 'required|boolean'
        ]);

        $menu = Menu::with('meals.items.dish.ingredients')->find($input['menu_id']);

        assert($menu instanceof Menu, '');

        $ingredients = $menu->meals->pluck('items');

        $ingredients = $ingredients->map(static function (Collection $items) {
            return $items->pluck('dish')->pluck('ingredients');
        })->flatten(2);

        CartItem::whereUserId(Auth::id())->delete();

        $results = [];

        foreach ($ingredients as $ingredient) {
            assert($ingredient instanceof Ingredient, '');

            if (!isset($results[$ingredient->id])) {
                $results[$ingredient->id] = 0;
            }

            $results[$ingredient->id] += $ingredient->pivot->amount;
        }

        if ($input['consider_fridge']) {
            $ingredients = Ingredient::whereIn('id', array_keys($results))->get();

            foreach ($results as $ingId => $amount) {
                $results[$ingId] -= $ingredients->where('id', $ingId)->first()->amount;
            }

            $results = collect($results)->filter(static fn ($a) => $a > 0)->all();
        }

        foreach ($results as $ingId => $amount) {
            CartItem::insert([
                'user_id' => Auth::id(),
                'ingredient_id' => $ingId,
                'amount' => $amount,
            ]);
        }

        return $this->index();
    }
}
