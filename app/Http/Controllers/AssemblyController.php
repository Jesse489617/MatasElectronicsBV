<?php

namespace App\Http\Controllers;

use App\Actions\GenerateThumbnails;
use App\Http\Requests\Assemblies\IndexAssemblyRequest;
use App\Http\Requests\assemblies\StoreAssemblyRequest;
use App\Http\Requests\assemblies\UpdateAssemblyRequest;
use App\Http\Resources\Assembly as AssemblyResource;
use App\Models\Assembly;
use Illuminate\Database\Query\Builder;

class AssemblyController extends Controller
{
    public function index(IndexAssemblyRequest $request)
    {
        $assemblies = Assembly::query()
            ->when(
                $request->has('search') && $request->user(),
                fn (Builder $query) => $query->where('name', 'like', '%'.$request->search.'%')
            )
            ->get();

        return AssemblyResource::collection($assemblies);
    }

    public function show(Assembly $assembly)
    {
        $assembly->loadMissing('components');

        return new AssemblyResource($assembly);
    }

    public function store(StoreAssemblyRequest $request, GenerateThumbnails $generateThumbnails)
    {
        if ($request->hasFile('image')) {
            $filename = $generateThumbnails($request->file('image'), 'assemblies/');
        }

        $assembly = Assembly::create([
            'name' => $request->string('name'),
            'image' => $filename ?? null,
            'price' => $request->float('price'),
        ]);

        $letters = range('A', 'Z');
        $assemblyLetter = $letters[($assembly->id - 1) % 26];

        foreach ($request->components as $index => $componentId) {
            $location = $assemblyLetter.($index + 1);

            $assembly->components()->attach($componentId, ['location' => $location]);
        }

        return new AssemblyResource($assembly->loadMissing('components'));
    }

    public function update(UpdateAssemblyRequest $request, GenerateThumbnails $generateThumbnails, Assembly $assembly)
    {
        if ($request->hasFile('image')) {
            $filename = $generateThumbnails($request->file('image'), 'assemblies/', $assembly->image);
        }

        $assembly->update([
            'name' => $request->string('name'),
            'image' => $filename ?? $assembly->image,
            'price' => $request->float('price'),
        ]);

        $assembly->components()->detach();

        $letters = range('A', 'Z');
        $assemblyLetter = $letters[($assembly->id - 1) % 26];

        foreach ($request->components as $index => $componentId) {
            $location = $assemblyLetter.($index + 1);

            $assembly->components()->attach($componentId, [
                'location' => $location,
            ]);
        }

        return new AssemblyResource(
            $assembly->loadMissing('components')
        );
    }
}
