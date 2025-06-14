<?php

namespace App\Livewire\Dashboard;

use App\Models\Product;
use App\Models\Sale;
use Livewire\Component;
use Carbon\Carbon;

class DashboardIndex extends Component
{
    public $totalProduct;
    public $totalSales;
    public $activeCashiers;
    public $todayRevenue;

    public function mount()
    {
        $this->totalProduct = Product::count();

        $this->totalSales = Sale::whereDate('created_at', Carbon::today())->count();

        $this->activeCashiers = Sale::distinct('user_id')->count('user_id');

        $this->todayRevenue = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-index');
    }
}
