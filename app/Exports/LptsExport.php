<?php
namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
class LptsExport implements FromView
{
    protected $datas;
    public function __construct($datas)
    {
        $this->datas = $datas;
    }
    public function view(): View
    {
        return view('lpts.export_excel', [
            'datas' => $this->datas
        ]);
    }
}
