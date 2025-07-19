<div class="bg-white shadow-md rounded p-6 mb-6">
    <div class="p-4">
        <h2 class="text-xl font-semibold mb-4">Histori Pergerakan Stok</h2>
        
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

        <div class="bg-white shadow-md rounded p-4 overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Nama Produk</th>
                        <th class="px-4 py-2 border">Qty</th>
                        <th class="px-4 py-2 border">Tipe</th>
                        <th class="px-4 py-2 border">Catatan</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $index => $movement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center">{{ $movements->firstItem() + $index }}</td>
                            <td class="px-4 py-2 border">{{ $movement->product->name ?? '-' }}</td>
                            <td class="px-4 py-2 border text-center">{{ $movement->quantity }}</td>
                            <td class="px-4 py-2 border capitalize text-center">{{ $movement->type }}</td>
                            <td class="px-4 py-2 border">{{ $movement->note }}</td>
                            <td class="px-4 py-2 border text-sm text-gray-600">{{ $movement->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 p-4">Belum ada histori stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
