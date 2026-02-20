<?php

namespace App\Http\Controllers;

use App\Http\Requests\buy\StoreBuyRequest;
use App\Models\UserAssembly;
use Illuminate\Http\JsonResponse;

class BuyController extends Controller
{
    public function store(StoreBuyRequest $request): JsonResponse
    {
        for ($i = 0; $i < $request->quantity; $i++) {
            UserAssembly::create([
                'user_id' => $request->user()->id,
                'assembly_id' => $request->assembly_id ?? null,
                'component_id' => $request->component_id ?? null,
                'custom_assembly_id' => null,
            ]);
        }

        $message = $request->assembly_id
            ? "Successfully purchased $request->quantity assembly(s)."
            : "Successfully purchased $request->quantity component(s).";

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
