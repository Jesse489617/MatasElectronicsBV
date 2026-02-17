<?php

namespace App\Http\Controllers;

use App\Http\Requests\history\InvoiceHistoryRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class DownloadInvoiceController extends Controller
{
    public function __invoke(InvoiceHistoryRequest $request, $id)
    {
        $purchase = $request->purchase();

        abort_if(
            ! $purchase->assembly_id,
            Response::HTTP_BAD_REQUEST,
            __('Invoice is only available for assemblies.')
        );

        $pdf = PDF::loadHTML(
            view('invoices.assembly', [
                'assembly' => $purchase->assembly,
                'components' => $purchase->assembly->components,
                'user' => $request->user(),
                'purchase' => $purchase,
            ])->render()
        );

        return $pdf->download("invoice-{$purchase->id}.pdf");
    }
}
