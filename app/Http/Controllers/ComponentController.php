<?php

namespace App\Http\Controllers;

use App\Http\Requests\components\BuyComponentRequest;
use App\Http\Requests\components\IndexComponentRequest;
use App\Http\Requests\components\StoreComponentRequest;
use App\Http\Requests\components\UpdateComponentRequest;
use App\Http\Resources\Component as ComponentResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Component;
use DB;
use Faker\Guesser\Name;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function show($id)
    {
        $component = Component::findOrFail($id);

        return response()->json([
            'component' => $component,
        ]);
    }

    public function store(StoreComponentRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            $manager = new ImageManager(new Driver);

            $mainImage = $manager
                ->read($file->getPathname())
                ->cover(400, 400);

            Storage::disk('public')->put(
                "components/$filename",
                $mainImage->toJpeg(85)
            );

            $iconImage = $manager
                ->read($file->getPathname())
                ->cover(50, 50);

            Storage::disk('public')->put(
                "components/icons/$filename",
                $iconImage->toJpeg(85)
            );

            $data['image'] = "components/$filename";
        }

        $component = Component::create($data);

        return response()->json([
            'message' => 'Component created successfully',
            'component' => $component,
        ]);
    }

    public function update(UpdateComponentRequest $request, $id)
    {
        $component = Component::findOrFail($id);

        $data = $request->validated();
        unset($data['image']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid().'.'.$file->getClientOriginalExtension();

            $manager = new ImageManager(new Driver);

            if ($component->image) {
                if (\Storage::disk('public')->exists($component->image)) {
                    \Storage::disk('public')->delete($component->image);
                }

                $oldIconPath = str_replace('components/', 'components/icons/', $component->image);
                if (\Storage::disk('public')->exists($oldIconPath)) {
                    \Storage::disk('public')->delete($oldIconPath);
                }
            }

            $iconPath = str_replace('components/', 'components/icons/', "components/$filename");
            if (\Storage::disk('public')->exists($iconPath)) {
                \Storage::disk('public')->delete($iconPath);
            }

            $mainImage = $manager
                ->read($file->getPathname())
                ->cover(400, 400);

            \Storage::disk('public')->put(
                "components/$filename",
                $mainImage->toJpeg(85)
            );

            $iconImage = $manager
                ->read($file->getPathname())
                ->cover(50, 50);

            \Storage::disk('public')->put(
                "components/icons/$filename",
                $iconImage->toJpeg(85)
            );

            $data['image'] = "components/$filename";
        }

        $component->update($data);

        return response()->json([
            'message' => 'Component updated successfully',
            'component' => $component,
        ]);
    }
}
