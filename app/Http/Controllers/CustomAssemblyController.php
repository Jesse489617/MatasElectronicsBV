<?php

namespace App\Http\Controllers;

use App\Http\Requests\assemblies\StoreCustomAssemblyRequest;
use App\Models\Cart;
use App\Models\CustomAssembly;
use App\Models\CustomAssemblyComponent;

class CustomAssemblyController extends Controller
{
    public function store(StoreCustomAssemblyRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $customAssembly = CustomAssembly::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'price' => $data['price'],
        ]);

        foreach ($data['components'] as $componentId) {
            CustomAssemblyComponent::create([
                'custom_assembly_id' => $customAssembly->id,
                'component_id' => $componentId,
            ]);
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $cart->items()->create([
            'custom_assembly_id' => $customAssembly->id,
            'quantity' => 1,
        ]);

        return response()->json([
            'custom_assembly' => $customAssembly->load('components'),
            'message' => 'Custom assembly created and added to cart',
        ], 201);
    }
}
