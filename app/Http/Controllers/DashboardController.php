<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:PPIC']);
       
    }
    public function index(){
         // Fetch distinct type_stock values
        $typeStocks = DB::table('history_stocks')
                        ->distinct()
                        ->pluck('type_stock');
        
        // Fetch counts grouped by type_product and type_stock
        $data = DB::table('history_stocks')
                    ->select('type_product', 'type_stock', DB::raw('count(*) as count'))
                    ->groupBy('type_product', 'type_stock')
                    ->get();

        // Prepare chart data dynamically
        $chartData = [];
        foreach (['FG', 'WIP', 'RM'] as $typeProduct) {
            foreach ($typeStocks as $typeStock) {
                $chartData[$typeProduct][$typeStock] = $data->where('type_product', $typeProduct)->where('type_stock', $typeStock)->first()->count ?? 0;
            }
        }
        // dd($chartData);
        return view('dashboard.index',compact('chartData', 'typeStocks'));

    }
}
