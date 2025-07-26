<?php

namespace App\Exports;

use App\Models\CashFlow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class CashFlowExport implements FromCollection, WithHeadings
{
    protected array $filter;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
    }

    public function collection(): Collection
    {
        $query = CashFlow::query();

        if (!empty($this->filter['date_start'])) {
            $query->whereDate('date', '>=', $this->filter['date_start']);
        }

        if (!empty($this->filter['date_end'])) {
            $query->whereDate('date', '<=', $this->filter['date_end']);
        }

        if (!empty($this->filter['type'])) {
            $query->where('type', $this->filter['type']);
        }

        if (!empty($this->filter['source'])) {
            $query->where('source', $this->filter['source']);
        }

        if (!empty($this->filter['description'])) {
            $query->where('description', 'like', '%' . $this->filter['description'] . '%');
        }

        return $query->latest()->get()->map(function ($item) {
            return [
                'Tanggal' => $item->date,
                'Tipe' => ucfirst($item->type),
                'Sumber' => ucfirst($item->source),
                'Nominal' => $item->amount,
                'Deskripsi' => $item->description,
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Tipe', 'Sumber', 'Nominal', 'Deskripsi'];
    }
}

