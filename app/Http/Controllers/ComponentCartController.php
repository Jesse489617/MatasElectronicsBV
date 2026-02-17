<?php

namespace App\Http\Controllers;

use App\Http\Requests\components\BuyComponentRequest;
use App\Models\Cart;
use App\Models\CartItem;

class ComponentCartController extends Controller
{
    public function store(BuyComponentRequest $request)
    {
        $user = $request->user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'assembly_id' => null,
            'component_id' => $request->component_id,
            'custom_assembly_id' => null,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'message' => 'Component added to cart.',
        ]);
    }
}
