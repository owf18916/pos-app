<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use App\Models\CashFlow;
use Livewire\Attributes\Title;

class DashboardIndex extends Component
{
    public $totalProduct;
    public $totalSales;
    public $activeCashiers;
    public $todayRevenue;
    public $todayProfit;
    public $cashBalance;
    public $bankBalance;
    public $totalBalance;

    public function mount()
    {
        $this->totalProduct = Product::count();

        $this->totalSales = Sale::whereDate('created_at', Carbon::today())->count();

        $this->activeCashiers = Sale::distinct('user_id')->count('user_id');

        $this->todayRevenue = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');

        $this->todayProfit = Sale::whereDate('created_at', Carbon::today())
            ->with('items')
            ->get()
            ->flatMap->items // gabungkan semua item dari seluruh sale
            ->sum('profit'); // jumlahkan kolom profit

        $this->cashBalance = CashFlow::where('source', 'cash')->sum('amount');
        $this->bankBalance = CashFlow::where('source', 'bank')->sum('amount');
        $this->totalBalance = $this->cashBalance + $this->bankBalance;
    }

    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.dashboard.dashboard-index');
    }
}
