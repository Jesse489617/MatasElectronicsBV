<?php

namespace App\Http\Controllers;

use App\Http\Requests\Assemblies\BuyAssemblyRequest;
use App\Http\Requests\assemblies\BuyCustomAssemblyRequest;
use App\Http\Requests\Assemblies\IndexAssemblyRequest;
use App\Http\Requests\assemblies\StoreAssemblyRequest;
use App\Http\Requests\assemblies\StoreCustomAssemblyRequest;
use App\Http\Requests\assemblies\UpdateAssemblyRequest;
use App\Models\Assembly;
use App\Models\AssemblyComponent;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\CustomAssembly;
use App\Models\CustomAssemblyComponent;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AssemblyController extends Controller
{
    public function index(IndexAssemblyRequest $request)
    {
        $query = Assembly::query();

        if ($request->filled('search') && $request->user()) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        return response()->json([
            'assemblies' => $query->get(),
        ]);
    }

    public function show($id)
    {
        $assembly = Assembly::with('components')->find($id);

        if (! $assembly) {
            return response()->json(['message' => 'Assembly not found'], 404);
        }

        return response()->json([
            'assembly' => $assembly,
        ]);
    }

    public function buy(BuyAssemblyRequest $request)
    {
        $user = $request->user();
        $assemblyId = $request->assembly_id;
        $quantity = $request->quantity;

        $rows = [];
        for ($i = 0; $i < $quantity; $i++) {
            $rows[] = [
                'user_id' => $user->id,
                'assembly_id' => $assemblyId,
                'component_id' => null,
                'custom_assembly_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('user_assemblies')->insert($rows);

        return response()->json([
            'message' => "Successfully purchased $quantity assembly(s).",
        ]);
    }

    public function addCart(BuyAssemblyRequest $request)
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

    public function store(StoreAssemblyRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            $manager = new ImageManager(new Driver);

            $image = $manager->read($file->getPathname());

            $mainImage = $image->cover(400, 400);

            Storage::disk('public')->put(
                "assemblies/$filename",
                $mainImage->toJpeg(85)
            );

            $iconImage = $image->cover(50, 50);

            Storage::disk('public')->put(
                "assemblies/icons/$filename",
                $iconImage->toJpeg(85)
            );

            $data['image'] = "assemblies/$filename";
        }

        $assembly = Assembly::create([
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
            'price' => $data['price'],
        ]);

        $letters = range('A', 'Z');
        $assemblyLetter = $letters[($assembly->id - 1) % 26];

        foreach ($request->components as $index => $componentId) {
            $location = $assemblyLetter.($index + 1);

            AssemblyComponent::create([
                'assembly_id' => $assembly->id,
                'component_id' => $componentId,
                'location' => $location,
            ]);
        }

        return response()->json([
            'assembly' => $assembly->load('components'),
        ], 201);
    }

    public function update(UpdateAssemblyRequest $request, $id)
    {
        $assembly = Assembly::findOrFail($id);

        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();

            $manager = new ImageManager(new Driver);

            if ($assembly->image) {
                if (\Storage::disk('public')->exists($assembly->image)) {
                    \Storage::disk('public')->delete($assembly->image);
                }

                $oldIconPath = str_replace('assemblies/', 'assemblies/icons/', $assembly->image);
                if (\Storage::disk('public')->exists($oldIconPath)) {
                    \Storage::disk('public')->delete($oldIconPath);
                }
            }

            $mainImage = $manager->read($file->getPathname())->cover(400, 400);
            \Storage::disk('public')->put("assemblies/$filename", $mainImage->toJpeg(85));

            $iconImage = $manager->read($file->getPathname())->cover(50, 50);
            \Storage::disk('public')->put("assemblies/icons/$filename", $iconImage->toJpeg(85));

            $data['image'] = "assemblies/$filename";
        }

        $assembly->update($data);

        if ($request->has('components')) {
            $assembly->components()->detach();
            $letters = range('A', 'Z');
            $assemblyLetter = $letters[($assembly->id - 1) % 26];

            foreach ($request->components as $index => $componentId) {
                AssemblyComponent::create([
                    'assembly_id' => $assembly->id,
                    'component_id' => $componentId,
                    'location' => $assemblyLetter.($index + 1),
                ]);
            }
        }

        return response()->json(['assembly' => $assembly]);
    }

    public function custom(StoreCustomAssemblyRequest $request)
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

