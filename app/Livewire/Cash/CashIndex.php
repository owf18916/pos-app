<?php

namespace App\Livewire\Cash;

use Livewire\Component;
use App\Models\CashFlow;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\CashFlowExport;
use Maatwebsite\Excel\Facades\Excel;

class CashIndex extends Component
{
    use WithPagination;

    public $date, $type, $source, $amount, $description;
    // public $transactions;
    public $cashBalance = 0;
    public $bankBalance = 0;
    public $todayIncome = 0;
    public $todayExpense = 0;

    public $form = [
        'date' => '',
        'type' => 'in',
        'source' => 'cash',
        'description' => '',
        'amount' => '',
    ];
    public $isEditMode = false;
    public $editId = null;

    public $filter = [
        'date_start' => null,
        'date_end' => null,
        'type' => '',
        'source' => '',
        'description' => '',
    ];

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        // $this->loadTransactions();
    }

    // public function loadTransactions()
    // {
    //     $query = CashFlow::query();

    //     if ($this->filter['date_start']) {
    //         $query->whereDate('date', '>=', $this->filter['date_start']);
    //     }
    //     if ($this->filter['date_end']) {
    //         $query->whereDate('date', '<=', $this->filter['date_end']);
    //     }
    //     if ($this->filter['type']) {
    //         $query->where('type', $this->filter['type']);
    //     }
    //     if ($this->filter['source']) {
    //         $query->where('source', $this->filter['source']);
    //     }
    //     if ($this->filter['description']) {
    //         $query->where('description', 'like', '%' . $this->filter['description'] . '%');
    //     }

    //     $this->transactions = $query->orderByDesc('date')->paginate(10);;

    //     $cashIn = CashFlow::where('source', 'cash')->where('type', 'in')->sum('amount');
    //     $cashOut = CashFlow::where('source', 'cash')->where('type', 'out')->sum('amount');
    //     $this->cashBalance = $cashIn - $cashOut;

    //     $bankIn = CashFlow::where('source', 'bank')->where('type', 'in')->sum('amount');
    //     $bankOut = CashFlow::where('source', 'bank')->where('type', 'out')->sum('amount');
    //     $this->bankBalance = $bankIn - $bankOut;

    //     $today = now()->toDateString();
    //     $this->todayIncome = CashFlow::whereDate('date', $today)->where('type', 'in')->sum('amount');
    //     $this->todayExpense = CashFlow::whereDate('date', $today)->where('type', 'out')->sum('amount');
    // }

    public function save()
    {
        $this->validate([
            'form.date' => 'required|date',
            'form.type' => 'required|in:in,out',
            'form.source' => 'required|in:cash,bank',
            'form.amount' => 'required|numeric|min:0.01',
            'form.description' => 'nullable|string|max:255',
        ]);

        if ($this->isEditMode && $this->editId) {
            $trx = CashFlow::findOrFail($this->editId);

            if ($trx->sale_id) {
                $this->dispatch('swal', [
                    'title' => 'Gagal!',
                    'text' => 'Transaksi dari modul penjulan tidak bisa dirubah.',
                    'icon' => 'error',
                ]);

                return;
            }

            $trx->update($this->form);
        } else {
            $this->form['source_type'] = 'manual';
            CashFlow::create($this->form);
        }

        $this->resetForm();
        // $this->loadTransactions();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil '.$this->isEditMode ? 'diupdate.' : 'ditambahkan',
            'icon' => 'success',
        ]);
    }

    public function edit($id)
    {
        $trx = CashFlow::findOrFail($id);

        if ($trx->sale_id) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Transaksi dari modul penjulan tidak bisa dirubah.',
                'icon' => 'error',
            ]);
            
            return;
        }

        $this->isEditMode = true;
        $this->editId = $id;

        $this->form = [
            'date' => $trx->date,
            'type' => $trx->type,
            'source' => $trx->source,
            'description' => $trx->description,
            'amount' => $trx->amount,
        ];
    }

    public function resetForm()
    {
        $this->isEditMode = false;
        $this->editId = null;
        $this->form = [
            'date' => '',
            'type' => 'in',
            'source' => 'cash',
            'description' => '',
            'amount' => '',
        ];
    }

    public function delete($id)
    {
        $trx = CashFlow::findOrFail($id);

        if ($trx->sale_id) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Transaksi dari modul penjulan tidak bisa dihapus.',
                'icon' => 'error',
            ]);

            return;
        }

        $trx->delete();
        // $this->loadTransactions();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Transaksi berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function resetFilters()
    {
        $this->filter = [
            'date_start' => null,
            'date_end' => null,
            'type' => '',
            'source' => '',
            'description' => '',
        ];

        // $this->loadTransactions();
    }

    public function applyFilters()
    {
        $this->resetPage(); // Kalau pakai pagination Livewire
    }

    public function exportExcel()
    {
        return Excel::download(new CashFlowExport($this->filter), 'cash_flows.xlsx');
    }

    private function computeSummaries()
    {
        $this->cashBalance = CashFlow::where('source', 'cash')->where('type', 'in')->sum('amount')
                                - CashFlow::where('source', 'cash')->where('type', 'out')->sum('amount');

        $this->bankBalance = CashFlow::where('source', 'bank')->where('type', 'in')->sum('amount')
                                - CashFlow::where('source', 'bank')->where('type', 'out')->sum('amount');

        $today = now()->toDateString();

        $this->todayIncome = CashFlow::whereDate('date', $today)->where('type', 'in')->sum('amount');
        $this->todayExpense = CashFlow::whereDate('date', $today)->where('type', 'out')->sum('amount');
    }

    #[Title('Kas dan Saldo')]
    public function render()
    {
        $query = CashFlow::query();

        if ($this->filter['date_start']) {
            $query->whereDate('date', '>=', $this->filter['date_start']);
        }
        if ($this->filter['date_end']) {
            $query->whereDate('date', '<=', $this->filter['date_end']);
        }
        if ($this->filter['type']) {
            $query->where('type', $this->filter['type']);
        }
        if ($this->filter['source']) {
            $query->where('source', $this->filter['source']);
        }
        if ($this->filter['description']) {
            $query->where('description', 'like', '%' . $this->filter['description'] . '%');
        }

        $transactions = $query->orderByDesc('updated_at')->paginate(10);

        $this->computeSummaries();

        return view('livewire.cash.cash-index', [
            'transactions' => $transactions,
        ]);
    }
}



