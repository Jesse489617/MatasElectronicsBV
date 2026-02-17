<?php

namespace App\Http\Controllers;

use App\Http\Requests\history\IndexHistoryRequest;
use App\Http\Requests\history\InvoiceHistoryRequest;
use App\Models\UserAssembly;
use Barryvdh\DomPDF\Facade\PDF;

class HistoryController extends Controller
{
    public function index(IndexHistoryRequest $request)
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
