<?php

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use Livewire\Component;
use Illuminate\Support\Carbon;

class SalesChart extends Component
{
    public $weeklySales = [];

    public function mount()
    {
        $this->loadWeeklySales();
    }

    public function loadWeeklySales()
    {
        $this->weeklySales = collect(range(0, 6))->map(function ($i) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $total = Sale::whereDate('created_at', $date)->sum('total_amount');
            return [
                'date' => Carbon::parse($date)->format('D'),
                'total' => $total,
            ];
        });
    }

    public function render()
    {
        return view('livewire.dashboard.sales-chart');
    }
}
