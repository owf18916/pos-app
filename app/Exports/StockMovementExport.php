<?php

namespace App\Exports;

use App\Models\StockMovement;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockMovementExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->format('Y-m-d');
        $this->endDate = Carbon::parse($endDate)->format('Y-m-d');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Produk',
            'Nama Produk',
            'Tipe Transaksi',
            'Qty',
            'Note'
        ];
    }

    public function collection()
    {
        $stockMovement = StockMovement::with('product')
            ->when(!empty($this->startDate) && !empty($this->endDate), function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->get();

        $rows = [];

        foreach ($stockMovement as $movement) {
            $rows[] = [
                'Tanggal' => $movement->created_at->format('d-m-Y H:i'),
                'Kode Produk' => $movement->product->product_code,
                'Nama Produk' => $movement->product->name,
                'Tipe Transaksi' => $movement->type,
                'Qty' => $movement->quantity,
                'Note' => $movement->note
            ];
        }

        return collect($rows);
    }
}
