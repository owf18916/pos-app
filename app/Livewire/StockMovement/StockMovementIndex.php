<?php

namespace App\Livewire\StockMovement;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\StockMovement;
use App\Exports\StockMovementExport;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;

class StockMovementIndex extends Component
{
    public $startDate, $endDate;

    protected $movements;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDay()->format('Y-m-d');
        $this->loadStock();
    }

    public function search()
    {
        $this->loadStock();
    }

    public function loadStock()
    {
        $this->movements = StockMovement::with('product')
                                        ->whereBetween('created_at', [
                                            Carbon::parse($this->startDate)->startOfDay(),
                                            Carbon::parse($this->endDate)->endOfDay()
                                        ])
                                        ->latest()
                                        ->paginate(15);
    }

    public function exportExcel()
    {
        return Excel::download(new StockMovementExport($this->startDate, $this->endDate), 'laporan-mutasi-stock.xlsx');
    }

    #[Title('Stock')]
    public function render()
    {
        if (!$this->movements) {
            $this->loadStock();
        }

        return view('livewire.stock-movement.stock-movement-index', ['movements' => $this->movements]);
    }
}
