<?php

namespace App\Http\Controllers;

use App\Http\Requests\assemblies\BuyAssemblyRequest;
use Illuminate\Support\Facades\DB;

class BuyAssemblyController extends Controller
{
    public function store(BuyAssemblyRequest $request)
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
}
