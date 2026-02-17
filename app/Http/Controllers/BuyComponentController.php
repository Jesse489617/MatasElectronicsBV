<?php

namespace App\Http\Controllers;

use App\Http\Requests\components\BuyComponentRequest;
use Illuminate\Support\Facades\DB;

class BuyComponentController extends Controller
{
    public function store(BuyComponentRequest $request)
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
                'custom_assembly_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('user_assemblies')->insert($rows);

        return response()->json([
            'message' => "Successfully purchased $quantity component(s).",
        ]);
    }
}
