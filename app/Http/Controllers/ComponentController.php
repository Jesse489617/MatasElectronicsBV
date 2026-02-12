<?php

namespace App\Http\Controllers;

use App\Http\Requests\components\BuyComponentRequest;
use App\Http\Requests\components\StoreComponentRequest;
use App\Http\Requests\components\UpdateComponentRequest;
use App\Models\Component;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

    public function buy(BuyComponentRequest $request)
    {
        $user = $request->user();
        $componentId = $request->component_id;
        $quantity = $request->quantity;

        $rows = [];
        for ($i = 0; $i < $quantity; $i++) {
            $rows[] = [
                'user_id' => $user->id,
                'assembly_id' => null,
                'component_id' => $componentId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('user_assemblies')->insert($rows);

        return response()->json([
            'message' => "Successfully purchased $quantity component(s).",
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
