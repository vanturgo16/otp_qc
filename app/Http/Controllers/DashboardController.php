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
        //  // Fetch distinct type_stock values
        // $typeStocks = DB::table('history_stocks')
        //                 ->distinct()
        //                 ->pluck('type_stock');
        
        // // Fetch counts grouped by type_product and type_stock
        // $data = DB::table('history_stocks')
        //             ->select('type_product', 'type_stock', DB::raw('count(*) as count'))
        //             ->groupBy('type_product', 'type_stock')
        //             ->get();

        // // Prepare chart data dynamically
        // $chartData = [];
        // foreach (['FG', 'WIP', 'RM'] as $typeProduct) {
        //     foreach ($typeStocks as $typeStock) {
        //         $chartData[$typeProduct][$typeStock] = $data->where('type_product', $typeProduct)->where('type_stock', $typeStock)->first()->count ?? 0;
        //     }
        // }
        // dd($chartData);
       // ========== LPTS Statistics ==========
        
        // Total LPTS
        $totalLpts = DB::table('lpts')->count();
        
        // LPTS Checked (status = 'checked')
        $lptsChecked = DB::table('lpts')->where('qc_status', 'checked')->count();
        
        // LPTS Scrap (status = 'scrap')
        $lptsScrap = DB::table('lpts')->where('qc_status', 'scrap')->count();
        
        // LPTS Rework (status = 'rework')
        $lptsRework = DB::table('lpts')->where('qc_status', 'rework')->count();

        // LPTS Hari Ini - Checked
        $lptsCheckedToday = DB::table('lpts')
            ->where('qc_status', 'checked')
            ->whereDate('created_at', today())
            ->count();
        
        // LPTS Hari Ini - Scrap
        $lptsScrapToday = DB::table('lpts')
            ->where('qc_status', 'scrap')
            ->whereDate('updated_at', today())
            ->count();
        
        // LPTS Hari Ini - Rework
        $lptsReworkToday = DB::table('lpts')
            ->where('qc_status', 'rework')
            ->whereDate('updated_at', today())
            ->count();

        // LPTS Total Hari Ini
        $lptsTotalToday = DB::table('lpts')
            ->whereDate('created_at', today())
            ->count();

        // ========== LMTS Statistics ==========
        
        // Total LMTS
        $totalLmts = DB::table('lmts')->count();
        
        // LMTS Hold (status = 0)
        $lmtsHold = DB::table('lmts')->where('status', 0)->count();
        
        // LMTS Scrap (status = 1)
        $lmtsScrap = DB::table('lmts')->where('status', 1)->count();
        
        // LMTS Return (status = 2)
        $lmtsReturn = DB::table('lmts')->where('status', 2)->count();
        
        // LMTS Repair (status = 3)
        $lmtsRepair = DB::table('lmts')->where('status', 3)->count();

        // LMTS Hari Ini - Hold
        $lmtsHoldToday = DB::table('lmts')
            ->where('status', 0)
            ->whereDate('created_at', today())
            ->count();
        
        // LMTS Hari Ini - Scrap
        $lmtsScrapToday = DB::table('lmts')
            ->where('status', 1)
            ->whereDate('updated_at', today())
            ->count();
        
        // LMTS Hari Ini - Return
        $lmtsReturnToday = DB::table('lmts')
            ->where('status', 2)
            ->whereDate('updated_at', today())
            ->count();
        
        // LMTS Hari Ini - Repair
        $lmtsRepairToday = DB::table('lmts')
            ->where('status', 3)
            ->whereDate('updated_at', today())
            ->count();

        // LMTS Total Hari Ini
        $lmtsTotalToday = DB::table('lmts')
            ->whereDate('created_at', today())
            ->count();

        // ========== Return Customer PPIC Statistics ==========
        
        // Total Return Customer
        $totalReturnCustomer = DB::table('return_customers_ppic')->count();
        
        // Return Customer Checked (qc_status = 'checked')
        $returnChecked = DB::table('return_customers_ppic')
            ->where('qc_status', 'checked')
            ->count();
        
        // Return Customer Scrap (qc_status = 'scrap')
        $returnScrap = DB::table('return_customers_ppic')
            ->where('qc_status', 'scrap')
            ->count();
        
        // Return Customer Rework (qc_status = 'rework')
        $returnRework = DB::table('return_customers_ppic')
            ->where('qc_status', 'rework')
            ->count();

        // Return Customer Hari Ini - Checked
        $returnCheckedToday = DB::table('return_customers_ppic')
            ->where('qc_status', 'checked')
            ->whereDate('created_at', today())
            ->count();
        
        // Return Customer Hari Ini - Scrap
        $returnScrapToday = DB::table('return_customers_ppic')
            ->where('qc_status', 'scrap')
            ->whereDate('updated_at', today())
            ->count();
        
        // Return Customer Hari Ini - Rework
        $returnReworkToday = DB::table('return_customers_ppic')
            ->where('qc_status', 'rework')
            ->whereDate('updated_at', today())
            ->count();

        // Return Customer Total Hari Ini
        $returnTotalToday = DB::table('return_customers_ppic')
            ->whereDate('created_at', today())
            ->count();

        return view('dashboard.index', compact(
            // LPTS
            'totalLpts',
            'lptsChecked',
            'lptsScrap',
            'lptsRework',
            'lptsCheckedToday',
            'lptsScrapToday',
            'lptsReworkToday',
            'lptsTotalToday',
            
            // LMTS
            'totalLmts',
            'lmtsHold',
            'lmtsScrap',
            'lmtsReturn',
            'lmtsRepair',
            'lmtsHoldToday',
            'lmtsScrapToday',
            'lmtsReturnToday',
            'lmtsRepairToday',
            'lmtsTotalToday',

            // Return Customer PPIC
            'totalReturnCustomer',
            'returnChecked',
            'returnScrap',
            'returnRework',
            'returnCheckedToday',
            'returnScrapToday',
            'returnReworkToday',
            'returnTotalToday'
        ));
          
    }
}
