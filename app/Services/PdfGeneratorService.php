<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

class PdfGeneratorService
{
    /**
     * Generate printable PDF invoice in Bangla.
     */
    public function generateInvoice(Order $order): Response
    {
        return UnicodePdf::preset('bengali')
            ->loadView('pdf.invoice', ['order' => $order])
            ->a4()
            ->download("invoice-{$order->order_number}.pdf");
    }

    /**
     * Generate warehouse packing slip.
     */
    public function generatePackingSlip(Order $order): Response
    {
        return UnicodePdf::preset('bengali')
            ->loadView('pdf.packing-slip', ['order' => $order])
            ->a4()
            ->download("packing-slip-{$order->order_number}.pdf");
    }

    /**
     * Generate courier address label / sticker.
     */
    public function generateCourierSticker(Order $order): Response
    {
        return UnicodePdf::preset('bengali')
            ->loadView('pdf.courier-sticker', ['order' => $order])
            ->a4()
            ->download("courier-sticker-{$order->order_number}.pdf");
    }
}
