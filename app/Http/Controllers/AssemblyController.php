<?php

namespace App\Http\Controllers;

use App\Models\Assembly;
use App\Http\Controllers\Controller;
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
        $assembly = Assembly::find($id);

        if (!$assembly) {
            return response()->json(['message' => 'Assembly not found'], 404);
        }

        $components = $assembly->components()->get();

        return response()->json([
            'assembly' => $assembly,
            'components' => $components
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
            'message' => "Successfully purchased {$quantity} assembly(s)."
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'components' => 'array',
            'components.*' => 'exists:components,id',
        ]);

        $assembly = Assembly::create($request->only('name', 'price'));

        if ($request->has('components')) {
            $assembly->components()->attach($request->components);
        }

        return response()->json(['assembly' => $assembly], 201);
    }


}
