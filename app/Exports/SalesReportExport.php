<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SalesReportExport implements WithMultipleSheets
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = Carbon::parse($startDate)->format('Y-m-d');
        $this->endDate = Carbon::parse($endDate)->format('Y-m-d');
    }

    public function sheets(): array
    {
        return [
            new SalesSummarySheet($this->startDate, $this->endDate),
            new SalesDetailSheet($this->startDate, $this->endDate),
        ];
    }
}


