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
            'Harga Kulak (HPP)',
            'Harga Jual',
            'Margin Laba',
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
                'Harga Kulak (HPP)' => $product->base_price,
                'Harga Jual' => $product->price,
                'Margin Laba' => $product->price - $product->base_price,
                'Stock' => $product->stock,
                'Tanghgal Entri' => $product->created_at,
                'Tanggal Update' => $product->updated_at
            ];
        }

        return collect($rows);
    }
}
