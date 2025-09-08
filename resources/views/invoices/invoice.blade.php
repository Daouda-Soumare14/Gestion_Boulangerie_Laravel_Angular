<!DOCTYPE html>
<html>
<head>
    <title>Facture #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Facture #{{ $order->id }}</h1>
    <p><strong>Client :</strong> {{ $order->user->name }}</p>
    <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }} €</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Total à payer : {{ number_format($order->total, 2) }} €</h3>
</body>
</html>
