<?php

namespace App\Http\Controllers;

use App\Mail\OrderCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    // Générer et retourner PDF directement
    public function generatePdf(Order $order)
    {
        // Charger les relations pour éviter les "null"
        $order->load('items.product', 'user');

        $pdf = Pdf::loadView('invoices.invoice', compact('order'));
        return $pdf->download('facture_' . $order->id . '.pdf');
    }

    // Envoyer facture par email avec PDF en pièce jointe
    public function sendInvoiceEmail(Order $order)
    {
        // Charger relations
        $order->load('items.product', 'user');

        $pdf = Pdf::loadView('invoices.invoice', compact('order'));

        // Envoi email avec pièce jointe
        Mail::to($order->user->email)->send(
            (new OrderCreated($order))
                ->attachData($pdf->output(), 'facture_' . $order->id . '.pdf')
        );

        return response()->json(['message' => 'Facture envoyée par email']);
    }
}
