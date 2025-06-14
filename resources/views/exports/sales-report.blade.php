<table>
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Kasir</th>
            <th>Total</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->total_amount }}</td>
                <td>{{ $sale->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>