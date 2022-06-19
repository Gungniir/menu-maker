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

        foreach ($ingredients as $ingredient) {
            assert($ingredient instanceof Ingredient, '');

            $item = CartItem::firstOrCreate([
                'user_id' => Auth::id(),
                'ingredient_id' => $ingredient->id,
            ]);

            $item->amount += $ingredient->pivot->amount;

            $item->save();
        }

        return $this->index();
    }
}
