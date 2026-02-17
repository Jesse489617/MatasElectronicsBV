<?php

namespace App\Http\Controllers;

use App\Http\Requests\assemblies\BuyAssemblyRequest;
use App\Models\Cart;
use App\Models\CartItem;

class AssemblyCartController extends Controller
{
    public function store(BuyAssemblyRequest $request)
    {
        $user = $request->user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'assembly_id' => $request->assembly_id,
            'component_id' => null,
            'custom_assembly_id' => null,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'message' => 'Assembly added to cart.',
        ]);
    }
}
