<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;

class HistoryStockFgController extends Controller
{
    public function index(Request $request)
    {
        $searchDate = $request->input('searchDate', 'All'); // All | Custom
        $month      = $request->input('month');             // YYYY-MM

        $q = DB::table('history_stocks as hs')
            ->join('packing_lists as pl', 'pl.packing_number', '=', 'hs.id_good_receipt_notes_details')
            ->leftJoin('sales_orders as so', 'pl.id_sales_orders', '=', 'so.id')
            ->leftJoin('master_product_fgs as mpf', 'so.id_master_products', '=', 'mpf.id')
            ->leftJoin('master_customers as mc', 'so.id_master_customers', '=', 'mc.id')
            ->where('so.so_type', 'Reguler')
            ->selectRaw("
                hs.id,
                hs.id_good_receipt_notes_details,
                hs.type_product,
                hs.qty,
                hs.weight,
                hs.type_stock,
                hs.date,
                hs.remarks,

                pl.packing_number,
                pl.id_sales_orders,
                pl.status,

                so.id as so_id,

                mpf.product_code,
                mpf.description,

                mc.name as customer_name,

                COALESCE(pl.packing_number, hs.id_good_receipt_notes_details) as lot_number
            ");

        if ($searchDate === 'Custom' && !empty($month)) {
            try {
                $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();
                $end   = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();
                $q->whereBetween(DB::raw('DATE(hs.date)'), [$start, $end]);
            } catch (\Throwable $e) {
                // ignore invalid month
            }
        }

        $rows = $q->orderByDesc('hs.date')->get();

        return view('history-stock-fg.index', compact('rows', 'month', 'searchDate'));
    }

    public function show(string $hash, Request $request)
    {


        // decrypt id
        try {
            $id = decrypt($hash);
        } catch (\Throwable $e) {
            abort(404);
        }


        // query detail (sesuai permintaan)
        $row = DB::table('history_stocks as hs')
            ->join('packing_lists as pl', 'pl.packing_number', '=', 'hs.id_good_receipt_notes_details')
            ->leftJoin('sales_orders as so', 'pl.id_sales_orders', '=', 'so.id')
            ->leftJoin('master_product_fgs as mpf', 'so.id_master_products', '=', 'mpf.id')
            ->leftJoin('master_customers as mc', 'so.id_master_customers', '=', 'mc.id')
            ->where('so.so_type', 'Reguler')
            ->where('hs.id', $id)
            ->selectRaw("
                hs.id,
                hs.date,
                hs.remarks,

                pl.packing_number,
                pl.status,

                mpf.product_code,
                mpf.description,

                mc.name as customer_name
            ")
            ->first();

        if (!$row) abort(404);

        // opsional: keep filter di tombol Back
        $searchDate = $request->query('searchDate');
        $month      = $request->query('month');

        return view('history-stock-fg.show', compact('row', 'searchDate', 'month', 'hash'));
    }
}
