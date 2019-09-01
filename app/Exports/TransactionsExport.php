<?php

namespace App\Exports;

use App\Invoice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function query()
    {
        return Auth::user()
            ->transactions()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            '#',
            'User Id',
            'Rate (₦)',
            'Quantity (BTC)',
            'Type',
            'Charges',
            'Created At',
            'Updated At'
        ];
    }
}
