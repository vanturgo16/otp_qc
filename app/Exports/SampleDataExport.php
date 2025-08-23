<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SampleDataExport implements FromView
{
    protected $dataSoSamples;

    public function __construct($dataSoSamples)
    {
        $this->dataSoSamples = $dataSoSamples;
    }

    public function view(): View
    {
        return view('sample.export_excel', [
            'dataSoSamples' => $this->dataSoSamples
        ]);
    }
}
