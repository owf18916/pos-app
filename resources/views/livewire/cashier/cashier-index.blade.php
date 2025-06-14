<div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">

    {{-- ===================== KIRI: Daftar Produk ===================== --}}
    <div class="md:col-span-2 space-y-4 relative">
        {{-- Pencarian --}}
        <input
            type="text"
            wire:model="search"
            wire:keydown.enter="process"
            placeholder="Scan barcode atau ketik nama produk..."
            class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none" autofocus>

        @if(!empty($filteredProducts))
            <ul class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow mt-1 max-h-60 overflow-y-auto">
                @foreach($filteredProducts as $product)
                    <li wire:click="selectProduct({{ $product->id }})"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                        <div class="flex justify-between text-sm">
                            <div class="font-medium text-gray-700">
                                {{ $product->name }}
                                <div class="text-xs text-gray-400">Kode: {{ $product->product_code }}</div>
                                <div class="text-xs text-gray-400">Stock {{ $product->stock }}</div>
                            </div>
                            <div class="text-right text-gray-600 font-semibold">
                                Rp{{ number_format($product->price) }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Daftar Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @forelse (\App\Models\Product::where('name', 'like', "%{$search}%")->get() as $product)
                <button wire:click="addToCart({{ $product->id }})"
                        class="bg-white border rounded-xl p-3 text-left shadow-sm hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-700">{{ $product->name }}</h3>
                    <p class="text-sm font-semibold text-gray-500">Kode : {{ $product->product_code }}</p>
                    <p class="text-sm text-gray-500">Stock : {{ number_format($product->stock, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Harga : Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </button>
            @empty
                <div class="col-span-full text-center text-gray-400">
                    Tidak ada produk ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ===================== KANAN: Keranjang & Pembayaran ===================== --}}
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-700">Keranjang</h2>

        {{-- Daftar Keranjang --}}
        <div class="bg-white rounded-xl shadow-sm p-4 space-y-2 max-h-[300px] overflow-auto">
            @forelse ($cart as $productId => $item)
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <div class="font-semibold">{{ $item['product']->name }}</div>
                        <div class="text-gray-500">x{{ $item['quantity'] }} = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                    </div>
                    <button
                        x-data
                        x-on:click.prevent="
                            Swal.fire({
                                title: 'Yakin hapus produk?',
                                text: 'Data yang dihapus tidak bisa dikembalikan.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#6b7280',
                                confirmButtonText: 'Ya, hapus!',
                                cancelButtonText: 'Batal'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    $wire.removeFromCart({{ $productId }});
                                }
                            });
                        "
                        class="text-red-500 hover:text-red-600"
                        title="Hapus produk"
                        aria-label="Hapus produk">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </div>
            @empty
                <div class="text-gray-400 text-center text-sm">Keranjang kosong</div>
            @endforelse
        </div>

        {{-- Pembayaran --}}
        <div class="bg-white rounded-xl shadow-sm p-4 space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Total</span>
                <span class="font-bold">Rp {{ number_format($this->getTotal(), 0, ',', '.') }}</span>
            </div>

            <div class="mt-4">
                <label for="paid" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dibayar</label>
                <input
                    wire:model.lazy="paid"
                    x-data="{
                        displayValue: '{{ number_format((float) $paid, 0, ',', '.') }}',
                        format(value) {
                            let cleaned = value.replace(/\D/g, '');
                            return new Intl.NumberFormat('id-ID').format(cleaned);
                        }
                    }"
                    x-init="$watch('displayValue', value => {
                        let numeric = value.replace(/\D/g, '');
                        $wire.set('paid', parseInt(numeric || 0));
                    })"
                    x-model="displayValue"
                    @input="displayValue = format($event.target.value)"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
                    placeholder="Masukkan jumlah dibayar"
                />
            </div>

            <div class="mt-4 text-lg font-semibold">
                Kembalian: Rp {{ number_format($this->getChange(), 0, ',', '.') }}
            </div>

            @if (count($cart) > 0)
            <div class="flex items-center gap-2">
                <button
                    wire:click="clearCart"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center gap-1"
                    title="Kosongkan Keranjang">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="hidden sm:inline">Kosongkan</span>
                </button>
                <button wire:click="saveTransaction"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Simpan & Cetak Struk
                </button>
                <button
                    wire:click="printPreview"
                    class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 flex items-center gap-2">
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>

                    <!-- Text -->
                    <span>Preview</span>
                </button>
            </div>
            @endif
    </div>
</div>
