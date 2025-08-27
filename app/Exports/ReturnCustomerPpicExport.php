<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReturnCustomerPpicExport implements FromView
{
    protected $returns;

    public function __construct($returns)
    {
        $this->returns = $returns;
    }

    public function view(): View
    {
        return view('return_customers_ppic.export_excel', [
            'returns' => $this->returns
        ]);
    }
}