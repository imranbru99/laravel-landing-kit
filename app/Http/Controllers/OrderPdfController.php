<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PdfGeneratorService;
use Symfony\Component\HttpFoundation\Response;

class OrderPdfController extends Controller
{
    public function invoice(Order $order, PdfGeneratorService $pdf): Response
    {
        return $pdf->generateInvoice($order);
    }

    public function packingSlip(Order $order, PdfGeneratorService $pdf): Response
    {
        return $pdf->generatePackingSlip($order);
    }

    public function courierSticker(Order $order, PdfGeneratorService $pdf): Response
    {
        return $pdf->generateCourierSticker($order);
    }
}
