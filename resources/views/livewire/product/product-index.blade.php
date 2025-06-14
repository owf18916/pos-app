<div class="p-6">
    {{-- Form Input Produk --}}
    <div class="bg-white shadow-md rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">
            {{ $isEditMode ? 'Edit Produk' : 'Tambah Produk' }}
        </h2>

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Kode Produk</label>
                <input type="text" wire:model.defer="product_code"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('product_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" wire:model.defer="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" wire:model.defer="price"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stok</label>
                @if ($isEditMode)
                <input type="text" class="mt-1 block w-full bg-gray-300 rounded-md border-gray-300 shadow-sm px-3 py-2" disabled />
                <input type="text" wire:model.defer="stock" hidden>
                @else
                <input type="number" wire:model.defer="stock"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                @endif
            </div>

            <div class="md:col-span-2 flex justify-end mt-4 space-x-2">
                <button type="button" wire:click="resetForm"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ $isEditMode ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>


    {{-- Tabel Daftar Produk --}}
    <div class="bg-white shadow-md rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Produk</h2>

        <table class="w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Kode</th>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border w-30">Harga</th>
                    <th class="p-2 border">Stok</th>
                    <th class="p-2 border w-30"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $index => $product)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border">{{ $products->firstItem() + $index }}</td>
                        <td class="p-2 border">{{ $product->product_code }}</td>
                        <td class="p-2 border">{{ $product->name }}</td>
                        <td class="p-2 border">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-2 border">{{ $product->stock }}</td>
                        <td class="p-2 border text-center space-x-1">
                            <button wire:click="$dispatch('open-add-stock', { id: {{ $product->id }}, name: '{{ $product->name }}' })"
                                class="text-blue-500 hover:text-blue-600" title="Tambah Stok">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                            <button wire:click="edit({{ $product->id }})"
                                class="text-green-500 hover:text-green-600" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button
                                x-data
                                x-on:click="
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
                                            $wire.delete({{ $product->id }});
                                        }
                                    });
                                "
                                class="text-red-500 hover:text-red-600" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Add Stock --}}
    <div
        x-data="{ open: false, productId: null, productName: '', quantity: 1 }"
        x-on:open-add-stock.window="open = true; productId = $event.detail.id; productName = $event.detail.name"
        x-show="open"
        style="display: none;"
        class="fixed inset-0 flex items-center justify-center z-50"
    >
        <!-- Transparent click-blocking background -->
        <div class="fixed inset-0 bg-transparent z-40"></div>

        <!-- Modal box -->
        <div class="bg-white rounded border p-6 w-full max-w-md z-50">
            <h2 class="text-lg font-semibold mb-4">Tambah Stok untuk <span x-text="productName"></span></h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jumlah Stok</label>
                <input type="number" min="1" x-model="quantity"
                    class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
            </div>
            <div class="flex justify-end gap-2">
                <button @click="open = false"
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">Batal</button>
                <button @click="$wire.addStock(productId, quantity); open = false;"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Tambah</button>
            </div>
        </div>
    </div>

</div>
