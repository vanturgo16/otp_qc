<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

class SampleHistoryExport implements \Maatwebsite\Excel\Concerns\FromView
{
    public function __construct($rows, $periodHuman, $totalIn, $totalOut)
    {
        $this->rows        = $rows;
        $this->periodHuman = $periodHuman;
        $this->totalIn     = $totalIn;
        $this->totalOut    = $totalOut;
    }

    public function view(): View
    {
        return view('history-stock-sample.export-excel', [
            'rows'        => $this->rows,
            'periodHuman' => $this->periodHuman,
            'totalIn'     => $this->totalIn,
            'totalOut'    => $this->totalOut,
        ]);
    }
}
