<?php

namespace App\Http\Controllers;

use App\Actions\GenerateThumbnails;
use App\Http\Requests\components\IndexComponentRequest;
use App\Http\Requests\components\StoreComponentRequest;
use App\Http\Requests\components\UpdateComponentRequest;
use App\Http\Resources\Component as ComponentResource;
use App\Models\Component;
use Illuminate\Database\Query\Builder;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ComponentController extends Controller
{
    public function index(IndexComponentRequest $request)
    {
        $components = Component::query()
            ->when(
                $request->has('search') && $request->user(),
                fn (Builder $query) => $query->where('name', 'like', '%'.$request->search.'%')
            )
            ->get();

        return ComponentResource::collection($components);
    }

    public function show(Component $component)
    {
        return new ComponentResource($component);
    }

    public function store(StoreComponentRequest $request, GenerateThumbnails $generateThumbnails)
    {
        if ($request->hasFile('image')) {
            $filename = $generateThumbnails($request->file('image'), 'components/');
        }

        $component = Component::create([
            'name' => $request->string('name'),
            'manufacturer_id' => $request->integer('manufacturer_id'),
            'type' => $request->string('type'),
            'image' => $filename ?? null,
            'price' => $request->float('price'),
        ]);

        return new ComponentResource($component);
    }

    public function update(UpdateComponentRequest $request, GenerateThumbnails $generateThumbnails, Component $component)
    {
        if ($request->hasFile('image')) {
            $filename = $generateThumbnails($request->file('image'), 'components/', $component->image);
        }

        $component->update([
            'name' => $request->string('name'),
            'manufacturer_id' => $request->integer('manufacturer_id'),
            'type' => $request->string('type'),
            'image' => $filename ?? $component->image,
            'price' => $request->float('price'),
        ]);

        return new ComponentResource($component);
    }
}
