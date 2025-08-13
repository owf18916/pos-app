<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Form Input Kas -->
    <div class="bg-white shadow-md rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">
            {{ $isEditMode ? 'Edit Transaksi Kas' : 'Tambah Transaksi Kas' }}
        </h2>

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" wire:model.defer="form.date"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('form.date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe Transaksi</label>
                <select wire:model.defer="form.type"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2">
                    <option value="in">Pemasukan</option>
                    <option value="out">Pengeluaran</option>
                </select>
                @error('form.type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Sumber Dana</label>
                <select wire:model.defer="form.source"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2">
                    <option value="cash">Cash</option>
                    <option value="bank">Bank</option>
                </select>
                @error('form.source') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" wire:model.defer="form.amount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('form.amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <input type="text" wire:model.defer="form.description"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2" />
                @error('form.description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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


    <!-- Tabel Transaksi -->
    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Saldo Kas (Cash)</div>
                <div class="text-xl font-semibold text-green-600">Rp {{ number_format($this->cashBalance, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Saldo Bank</div>
                <div class="text-xl font-semibold text-green-600">Rp {{ number_format($this->bankBalance, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Pemasukan Hari Ini</div>
                <div class="text-xl font-semibold text-blue-600">Rp {{ number_format($this->todayIncome, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-500 text-sm">Pengeluaran Hari Ini</div>
                <div class="text-xl font-semibold text-red-600">Rp {{ number_format($this->todayExpense, 0, ',', '.') }}</div>
            </div>
        </div>

        <h2 class="text-lg font-semibold text-gray-700 mb-4">Daftar Transaksi Kas</h2>

        {{-- Filter --}}
        <div class="bg-white p-4 rounded-md shadow mb-4">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex flex-col">
                    <label for="date_start" class="text-sm text-gray-600 mb-1">Tanggal Mulai</label>
                    <input type="date" id="date_start" wire:model.defer="filter.date_start"
                        class="border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex flex-col">
                    <label for="date_end" class="text-sm text-gray-600 mb-1">Tanggal Akhir</label>
                    <input type="date" id="date_end" wire:model.defer="filter.date_end"
                        class="border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex flex-col">
                    <label for="type" class="text-sm text-gray-600 mb-1">Tipe</label>
                    <select id="type" wire:model.defer="filter.type" class="border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        <option value="in">Pemasukan</option>
                        <option value="out">Pengeluaran</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="source" class="text-sm text-gray-600 mb-1">Sumber</label>
                    <select id="source" wire:model.defer="filter.source" class="border-gray-300 rounded-md shadow-sm">
                        <option value="">Semua</option>
                        <option value="cash">Kas</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>
                <div class="flex flex-col">
                    <label for="description" class="text-sm text-gray-600 mb-1">Deskripsi</label>
                    <input type="text" id="description" wire:model.defer="filter.description"
                        class="border-gray-300 rounded-md shadow-sm">
                </div>

                <div class="flex gap-2">
                    <button wire:click="applyFilters"
                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded shadow hover:bg-blue-700">
                        Terapkan
                    </button>
                    <button wire:click="resetFilters"
                        class="px-4 py-2 bg-gray-300 text-sm rounded hover:bg-gray-400">
                        Reset
                    </button>
                    <button wire:click="exportExcel"
                        class="px-4 py-2 bg-green-600 text-white text-sm rounded shadow hover:bg-green-700">
                        Export
                    </button>
                </div>

            </div>
        </div>


        <table class="w-full table-auto text-sm text-left border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Jenis Transaksi</th>
                    <th class="p-2 border">Sumber Dana</th>
                    <th class="p-2 border">Jumlah</th>
                    <th class="p-2 border">Deskripsi</th>
                    <th class="p-2 border"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $trx)
                    <tr class="{{ $trx->type == 'in' ? 'bg-green-50' : 'bg-red-50' }}">
                        <td class="p-2 border">{{ \Carbon\Carbon::parse($trx->date)->translatedFormat('d F Y') }}</td>
                        <td class="p-2 border capitalize">{{ $trx->type == 'in' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                        <td class="p-2 border capitalize">{{ $trx->source }}</td>
                        <td class="p-2 border text-right">Rp {{ number_format($trx->amount, 2, ',', '.') }}</td>
                        <td class="p-2 border">{{ $trx->description }}</td>
                        <td class="p-2 border text-center space-x-1">
                            @if (is_null($trx->sale_id))
                            <button wire:click="edit({{ $trx->id }})"
                                class="text-green-500 hover:text-green-600" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button
                                x-data
                                x-on:click="
                                    Swal.fire({
                                        title: 'Yakin hapus transaksi?',
                                        text: 'Data yang dihapus tidak bisa dikembalikan.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#6b7280',
                                        confirmButtonText: 'Ya, hapus!',
                                        cancelButtonText: 'Batal'
                                    }).then(result => {
                                        if (result.isConfirmed) {
                                            $wire.delete({{ $trx->id }});
                                        }
                                    });
                                "
                                class="text-red-500 hover:text-red-600" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            @else
                                <span class="text-gray-400 text-xs italic">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
