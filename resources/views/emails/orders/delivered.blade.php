@component('mail::message')
# Commande livrée

Bonjour {{ $order->user->name ?? 'Client' }},

Votre commande **#{{ $order->id }}** a été livrée avec succès.

**Détails :**
@foreach($order->items as $item)
- {{ $item->product->name ?? 'Produit supprimé' }} x {{ $item->quantity }} : {{ number_format($item->price * $item->quantity, 2) }} FCFA
@endforeach

Vous trouverez la facture en pièce jointe.

Cordialement,  
L’équipe Boulangerie 🍞
@endcomponent
