<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomAssembly;
use Illuminate\Support\Facades\DB;

class CartController
{
    public function index()
    {
        $cart = Cart::with([
            'items.assembly',
            'items.component',
            'items.customAssembly',
        ])->where('user_id', auth()->id())->first();

        return response()->json([
            'items' => $cart?->items ?? [],
        ]);
    }

    public function checkout()
    {
        $user = auth()->user();

        $cart = Cart::with('items')->where('user_id', $user->id)->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        DB::transaction(function () use ($cart, $user) {

            foreach ($cart->items as $item) {

                for ($i = 0; $i < $item->quantity; $i++) {
                    DB::table('user_assemblies')->insert([
                        'user_id' => $user->id,
                        'assembly_id' => $item->assembly_id,
                        'component_id' => $item->component_id,
                        'custom_assembly_id' => $item->customAssembly_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $cart->items()->delete();
        });

        return response()->json([
            'message' => 'Purchase successful.',
        ]);
    }

    public function removeItem($id)
    {
        $item = CartItem::whereHas('cart', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        if ($item->custom_assembly_id) {
            $customAssembly = CustomAssembly::where('id', $item->custom_assembly_id)
                ->where('user_id', auth()->id())
                ->first();

            if ($customAssembly) {
                $customAssembly->components()->delete();

                CartItem::where('custom_assembly_id', $customAssembly->id)->delete();

                $customAssembly->delete();
            }
        } else {
            $item->delete();
        }

        return response()->json(['message' => 'Item removed']);
    }
}
