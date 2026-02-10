<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAssembly;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $history = UserAssembly::with(['assembly.components'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

        return response()->json([
            'history' => $history
        ]);
    }
}
