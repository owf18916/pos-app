<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesDetailSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function title(): string
    {
        return 'Detail Penjualan';
    }

    public function collection()
    {
        $sales = Sale::with(['items.product'])
            ->when(!empty($this->startDate) && !empty($this->endDate), function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->get();

        $rows = [];

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $rows[] = [
                    'Invoice'   => $sale->invoice_number,
                    'Tanggal'   => $sale->created_at->format('d-m-Y H:i'),
                    'Produk'    => $item->product->name ?? '-',
                    'Qty'       => $item->quantity,
                    'Harga'     => $item->price,
                    'Subtotal'  => $item->subtotal,
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return ['Invoice', 'Tanggal', 'Produk', 'Qty', 'Harga', 'Subtotal'];
    }
}

