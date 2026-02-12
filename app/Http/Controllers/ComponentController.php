<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComponentRequest;

class ComponentController extends Controller
{
    public function index()
    {
        return response()->json([
            'components' => Component::all(),
        ]);
    }

    public function show($id)
    {
        $component = Component::findOrFail($id);

        return response()->json([
            'component' => $component,
        ]);
    }

    public function store(StoreComponentRequest $request)
    {
        $component = Component::create($request->validated());

        return response()->json([
            'message' => 'Component created successfully',
            'component' => $component,
        ]);
    }

    public function update(StoreComponentRequest $request, $id)
    {
        $component = Component::findOrFail($id);

        $component->update($request->validated());

        return response()->json([
            'message' => 'Component updated successfully',
            'component' => $component,
        ]);
    }

}
