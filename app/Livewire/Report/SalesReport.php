<?php

namespace App\Livewire\Report;

use App\Models\Sale;
use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Exports\SalesReportExport;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class SalesReport extends Component
{
    public $startDate, $endDate;
    public $sales = [];

    public $selectedSale;
    public $saleDetails = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDay()->format('Y-m-d');
        $this->loadSales();
    }

    public function search()
    {
        $this->loadSales();
    }

    public function loadSales()
    {
        $this->sales = Sale::with(['user'])
            ->whereBetween('created_at', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->latest()
            ->get();
    }

    public function showDetails($saleId)
    {
        $this->selectedSale = Sale::with(['items.product', 'user'])->findOrFail($saleId);

        $this->saleDetails = $this->selectedSale->items->map(function ($item) {
            return [
                'name' => $item->product->name ?? '-',
                'quantity' => $item->quantity,
                'base_price' => $item->base_price,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'profit' => $item->price - $item->product_price,
            ];
        })->toArray();
    }

    public function closeDetails()
    {
        $this->selectedSale = null;
    }

    public function reprintReceipt($saleId)
    {
        $sale = Sale::with('items.product', 'user')->findOrFail($saleId);

        $items = $sale->items->map(function ($item) {
            return [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => number_format($item->price),
                'subtotal' => number_format($item->subtotal),
            ];
        });

        // $this->dispatch('print-struk', [
        //     'invoice' => $sale->invoice_number,
        //     'date' => $sale->created_at->format('d-m-Y H:i'),
        //     'cashier' => $sale->user->name,
        //     'items' => $items,
        //     'total' => number_format($sale->total_amount),
        //     'paid' => number_format($sale->amount_paid),
        //     'change' => number_format($sale->change),
        // ]);

        $data = [
            'invoice' => $sale->invoice_number,
            'date' => now()->format('d-m-Y H:i'),
            'cashier' => Auth::user()->name,
            'payment_method' => ucfirst($sale->payment_method),
            'discount' => number_format($sale->discount_amount ?? 0, 0, ',', '.'),
            'total' => number_format($sale->total_amount, 0, ',', '.'),
            'paid' => number_format($sale->paid_amount, 0, ',', '.'),
            'change' => number_format($sale->change_amount, 0, ',', '.'),
            'items' => $sale->items->map(fn($item) => [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => number_format($item->price, 0, ',', '.'),
                'subtotal' => number_format($item->subtotal, 0, ',', '.'),
            ]),
        ];

        $printer = new \App\Services\ThermalPrinterService();
        $printer->printReceipt($data);

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Struk berhasil dicetak ulang.',
            'icon' => 'info',
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new SalesReportExport($this->startDate, $this->endDate), 'laporan-penjualan.xlsx');
    }

    #[Title('Sales')]
    public function render()
    {
        return view('livewire.report.sales-report');
    }
}
