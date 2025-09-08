<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDelivered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $pdf;

    public function __construct(Order $order)
    {
        $this->order = $order->load('user', 'items.product');
        $this->pdf = Pdf::loadView('invoices.invoice', ['order' => $this->order]);
    }

    public function build()
    {
        return $this->subject('Votre commande a été livrée')
                    ->markdown('emails.orders.delivered')
                    ->attachData($this->pdf->output(), 'facture_' . $this->order->id . '.pdf');
    }
}
 