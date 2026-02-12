<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssemblyRequest;
use App\Models\Assembly;
use App\Models\AssemblyComponent;
use Illuminate\Http\Request;

class AssemblyController extends Controller
{
    public function index(Request $request)
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

    public function buy(Request $request)
    {
        $request->validate([
            'assembly_id' => 'required|exists:assemblies,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $assemblyId = $request->assembly_id;
        $quantity = $request->quantity;

        $rows = [];
        for ($i = 0; $i < $quantity; $i++) {
            $rows[] = [
                'user_id' => $user->id,
                'assembly_id' => $assemblyId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        \DB::table('user_assemblies')->insert($rows);

        return response()->json([
            'message' => "Successfully purchased {$quantity} assembly(s).",
        ]);
    }

    public function store(StoreAssemblyRequest $request)
    {
        $data = $request->validated();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('assemblies', 'public');
            $data['image'] = $path;
        }

        $assembly = Assembly::create([
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
            'price' => $data['price'],
        ]);

        $letters = range('A', 'Z');
        $assemblyLetter = $letters[($assembly->id - 1) % 26];

        foreach ($request->components as $index => $componentId) {
            $location = $assemblyLetter . ($index + 1);

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

    public function update(StoreAssemblyRequest $request, $id)
    {
        $assembly = Assembly::findOrFail($id);

        $assembly->update([
            'name' => $request->name,
            'image' => $request->image,
            'price' => $request->price,
        ]);

        if ($request->has('components')) {
            $assembly->components()->detach();

            $letters = range('A', 'Z');
            $assemblyLetter = $letters[($assembly->id - 1) % 26];

            foreach ($request->components as $index => $componentId) {
                AssemblyComponent::create([
                    'assembly_id' => $assembly->id,
                    'component_id' => $componentId,
                    'location' => $assemblyLetter . ($index + 1),
                ]);
            }
        }

        return response()->json(['assembly' => $assembly], 200);
    }

}
