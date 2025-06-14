<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesSummarySheet implements FromCollection, WithHeadings, WithTitle, WithStrictNullComparison
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Summary Penjualan';
    }

    public function collection()
    {
        return Sale::with('user')
            ->when(!empty($this->startDate) && !empty($this->endDate), function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->get()
            ->map(fn($sale) => [
                'Invoice' => $sale->invoice_number,
                'Tanggal' => $sale->created_at->format('d-m-Y H:i'),
                'Kasir'   => $sale->user->name ?? '-',
                'Total'   => $sale->total_amount,
                'Bayar'   => $sale->paid_amount,
                'Kembali' => $sale->change_amount ?? 0,
            ]);
    }

    public function headings(): array
    {
        return ['Invoice', 'Tanggal', 'Kasir', 'Total', 'Bayar', 'Kembali'];
    }
}

