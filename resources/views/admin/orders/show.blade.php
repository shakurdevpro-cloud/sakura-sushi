<h1>Commande {{ $order->reference }}</h1>

<p>Statut : {{ $order->status->value }}</p>
<p>Client : {{ $order->user?->name ?? $order->guest_email }}</p>
<p>Total : {{ number_format($order->total / 100, 2) }} $</p>

<h3>Articles</h3>
<ul>
    @foreach ($order->items as $item)
        <li>{{ $item->quantity }} x {{ $item->name }} — {{ number_format($item->subtotal / 100, 2) }} $</li>
    @endforeach
</ul>

<form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
    @csrf
    @method('PATCH')
    <select name="status">
        <option value="confirmed">confirmed</option>
        <option value="preparing">preparing</option>
        <option value="ready">ready</option>
        <option value="delivered">delivered</option>
        <option value="cancelled">cancelled</option>
    </select>
    <input type="text" name="cancel_reason" placeholder="Raison (si annulation)">
    <button type="submit">Mettre à jour</button>
</form>

@if (session('status'))
    <p>{{ session('status') }}</p>
@endif