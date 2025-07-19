<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings 
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Harga',
            'Stock',
            'Tanghgal Entri',
            'Tanggal Update'
        ];
    }

    public function collection()
    {
        $products = Product::all();

        $rows = [];

        foreach ($products as $product) {
            $rows[] = [
                'Kode Produk' => $product->product_code,
                'Nama Produk' => $product->name,
                'Harga' => $product->price,
                'Stock' => $product->stock,
                'Tanghgal Entri' => $product->created_at,
                'Tanggal Update' => $product->updated_at
            ];
        }

        return collect($rows);
    }
}
