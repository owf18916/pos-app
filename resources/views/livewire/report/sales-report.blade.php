<div class="bg-white shadow-md rounded p-6 mb-6">
    <div class="p-4">
        <h1 class="text-xl font-bold mb-4">Laporan Penjualan</h1>

        {{-- Filter Tanggal --}}
        <div class="flex justify-between">
            <div class="flex items-center gap-4 mb-4">
                <div>
                    <label class="text-sm text-gray-600">Dari</label>
                    <input type="date" wire:model="startDate" class="border rounded px-3 py-2">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Sampai</label>
                    <input type="date" wire:model="endDate" class="border rounded px-3 py-2">
                </div>
                <div>
                    <button wire:click="search"
                        class="flex items-center gap-2 bg-gray-100 border text-gray-800 px-4 py-2 rounded hover:bg-gray-200 text-sm">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="size-6"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </button>
                </div>
            </div>

            <x-export-excel />

        </div>

        {{-- Tabel Penjualan --}}
        <table class="w-full border text-sm mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2 text-left">Invoice</th>
                    <th class="border px-3 py-2 text-left">Kasir</th>
                    <th class="border px-3 py-2 text-right">Total</th>
                    <th class="border px-3 py-2 text-left">Tanggal</th>
                    <th class="border px-3 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td class="border px-3 py-2">{{ $sale->invoice_number }}</td>
                        <td class="border px-3 py-2">{{ $sale->user->name }}</td>
                        <td class="border px-3 py-2 text-right">Rp {{ number_format($sale->total_amount) }}</td>
                        <td class="border px-3 py-2">{{ $sale->created_at->format('d-m-Y H:i') }}</td>
                        <td class="border px-3 py-2 text-center">
                            <button wire:click="showDetails({{ $sale->id }})"
                                class="text-blue-500 hover:underline text-sm">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Detail Transaksi --}}
        @if ($selectedSale)
        <div class="border p-4 mt-4 rounded bg-gray-50 shadow">
            <h3 class="text-lg font-bold mb-2">Detail Penjualan: {{ $selectedSale->invoice_number }}</h3>
            
            <p><strong>Kasir:</strong> {{ $selectedSale->user->name }}</p>
            <p><strong>Tanggal:</strong> {{ $selectedSale->created_at->format('d-m-Y H:i') }}</p>

            <table class="w-full mt-3 text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Produk</th>
                        <th class="border px-2 py-1 text-right">Qty</th>
                        <th class="border px-2 py-1 text-right">Harga</th>
                        <th class="border px-2 py-1 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($selectedSale->items as $item)
                        <tr>
                            <td class="border px-2 py-1">{{ $item->product->name }}</td>
                            <td class="border px-2 py-1 text-right">{{ $item->quantity }}</td>
                            <td class="border px-2 py-1 text-right">Rp {{ number_format($item->price) }}</td>
                            <td class="border px-2 py-1 text-right">Rp {{ number_format($item->subtotal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end mt-4 space-x-2">
                <button wire:click="reprintReceipt({{ $selectedSale->id }})"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 16h8M8 12h8m-8-4h8M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Cetak Ulang</span>
                </button>

                <button wire:click="closeDetails"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Tutup
                </button>
            </div>

        </div>
        @endif

    </div>
</div>
