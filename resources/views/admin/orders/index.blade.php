<h1>Commandes</h1>

<table border="1" cellpadding="6">
    <tr>
        <th>Référence</th>
        <th>Statut</th>
        <th>Type</th>
        <th>Total</th>
        <th>Créée le</th>
        <th></th>
    </tr>
    @foreach ($orders as $order)
        <tr>
            <td>{{ $order->reference }}</td>
            <td>{{ $order->status->value }}</td>
            <td>{{ $order->type }}</td>
            <td>{{ number_format($order->total / 100, 2) }} $</td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('admin.orders.show', $order) }}">Voir</a></td>
        </tr>
    @endforeach
</table>

{{ $orders->links() }}