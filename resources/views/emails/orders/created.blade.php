@component('mail::message')
# Nouvelle commande créée

Bonjour {{ $order->user->name ?? 'Client' }},

Votre commande **#{{ $order->id }}** a bien été enregistrée.

**Détails :**
@foreach($order->items as $item)
- Produit : {{ $item->product->name ?? 'Produit supprimé' }}
- Quantité : {{ $item->quantity }}
- Prix total : {{ number_format($item->price * $item->quantity, 2) }} FCFA
@endforeach

Merci pour votre confiance.

@component('mail::button', ['url' => route('orders.show', $order->id)])
Voir ma commande
@endcomponent

Cordialement,  
L’équipe Boulangerie 🍞
@endcomponent
