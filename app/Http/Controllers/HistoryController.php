<?php

namespace App\Http\Controllers;

use App\Models\UserAssembly;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $history = UserAssembly::with([
            'assembly.components',
            'component',
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($row) {
                if ($row->assembly_id) {
                    return [
                        'id' => $row->id,
                        'type' => 'assembly',
                        'assembly' => $row->assembly,
                        'component' => null,
                        'created_at' => $row->created_at,
                    ];
                } elseif ($row->component_id) {
                    return [
                        'id' => $row->id,
                        'type' => 'component',
                        'assembly' => null,
                        'component' => $row->component,
                        'created_at' => $row->created_at,
                    ];
                }

                return [
                    'id' => $row->id,
                    'type' => 'unknown',
                    'assembly' => null,
                    'component' => null,
                    'created_at' => $row->created_at,
                ];
            });

        return response()->json([
            'history' => $history,
        ]);
    }
}
