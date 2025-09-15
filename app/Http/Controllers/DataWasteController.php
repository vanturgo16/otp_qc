<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataWasteController extends Controller
{
   public function index(Request $request)
{
    // --- LPTS ---
    $lptsQuery = DB::table('data_waste as dw')
        ->leftJoin('lpts', function($join) {
            $join->on('dw.id_resource', '=', 'lpts.id')
                 ->where('dw.id_resource_column', '=', 'lpts_id');
        })
        ->leftJoin('work_orders as wo', 'lpts.id_wo', '=', 'wo.id')
        ->leftJoin('sales_orders as so', 'wo.id_sales_orders', '=', 'so.id')
        ->leftJoin('master_units as mu', 'wo.id_master_units', '=', 'mu.id')
        ->leftJoin('master_product_fgs as mpf', 'wo.id_master_products', '=', 'mpf.id')
        ->leftJoin('master_group_subs as mgs', 'mpf.id_master_group_subs', '=', 'mgs.id')
        ->leftJoin('master_work_centers as mwc', 'wo.id_master_work_centers', '=', 'mwc.id')
        ->leftJoin(DB::raw('
            (SELECT hs1.*
             FROM history_stocks hs1
             INNER JOIN (
                 SELECT id_master_products, MAX(id) AS max_id
                 FROM history_stocks
                 GROUP BY id_master_products
             ) hs2 ON hs1.id_master_products = hs2.id_master_products AND hs1.id = hs2.max_id
            ) as hs
        '), 'wo.id_master_products', '=', 'hs.id_master_products')
        ->leftJoin('delivery_notes as dn', 'so.id', '=', 'dn.id_sales_orders')
        ->select([
            'dw.waste_date',
            'dw.no_report',
            'so.so_number as no_so',
            'mwc.work_center',
            'dw.weight',
            'mu.unit',
            'mgs.name as group_sub',
            'mpf.type_product',
            'hs.remarks as remark',
            'dw.type_stock',
            'dw.status',
            DB::raw("'LPTS' as sumber"),
            'dw.created_at',
        ])
        ->where('dw.id_resource_column', 'lpts_id');

    // --- RETURN ---
    $returnQuery = DB::table('data_waste as dw')
        ->leftJoin('return_customers_ppic as rcp', function($join) {
            $join->on('dw.id_resource', '=', 'rcp.id')
                 ->where('dw.id_resource_column', '=', 'return_customers_ppic_id');
        })
        ->leftJoin('delivery_note_details as dnd', 'rcp.id_delivery_note_details', '=', 'dnd.id')
        ->leftJoin('delivery_notes as dn', 'dnd.id_delivery_notes', '=', 'dn.id')
        ->leftJoin('sales_orders as so', 'dnd.id_sales_orders', '=', 'so.id')
        ->leftJoin('master_product_fgs as mpf', 'so.id_master_products', '=', 'mpf.id')
        ->leftJoin('master_group_subs as mgs', 'mpf.id_master_group_subs', '=', 'mgs.id')
        ->leftJoin('master_units as mu', 'dnd.id_master_units', '=', 'mu.id')
        ->leftJoin('work_orders as wo', 'rcp.id_sales_orders', '=', 'wo.id_sales_orders')
        ->leftJoin('master_work_centers as mwc', 'wo.id_master_work_centers', '=', 'mwc.id')
        ->leftJoin(DB::raw('
            (SELECT hs1.*
             FROM history_stocks hs1
             INNER JOIN (
                 SELECT id_master_products, MAX(id) AS max_id
                 FROM history_stocks
                 GROUP BY id_master_products
             ) hs2 ON hs1.id_master_products = hs2.id_master_products AND hs1.id = hs2.max_id
            ) as hs
        '), 'mpf.id', '=', 'hs.id_master_products')
        ->select([
            'dw.waste_date',
            'dw.no_report',
            'so.so_number as no_so',
            'mwc.work_center',
            'dw.weight',
            'mu.unit',
            'mgs.name as group_sub',
            'mpf.type_product',
            'hs.remarks as remark',
            'dw.type_stock',
            'dw.status',
            DB::raw("'RETURN' as sumber"),
            'dw.created_at',
        ])
        ->where('dw.id_resource_column', 'return_customers_ppic_id');

    // --- ADD / MANUAL ---
    $manualQuery = DB::table('data_waste as dw')
        ->select([
            'dw.waste_date',
            'dw.no_report',
            DB::raw('NULL as no_so'),
            DB::raw('NULL as work_center'),
            'dw.weight',
            DB::raw("'kg' as unit"),
            DB::raw('NULL as group_sub'),
            'dw.type_product',
            'dw.remark as remark',
            'dw.type_stock',
            'dw.status',
            DB::raw("'ADD' as sumber"),
            'dw.created_at',
        ])
        ->where('dw.id_resource_column', 'manual');

    // ==== FILTERING (samakan dengan style lama) ====
    // by Type Product
    if ($request->type_product) {
        $lptsQuery->where('mpf.type_product', $request->type_product);
        $returnQuery->where('mpf.type_product', $request->type_product);
        $manualQuery->where('dw.type_product', $request->type_product);
    }
    // by Group Sub
    if ($request->group_sub) {
        $lptsQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
        $returnQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
        // manual tidak punya group_sub → exclude manual saat filter group_sub
        $manualQuery->whereRaw('1=0');
    }
    // by Work Center (hanya LPTS, return/manual NULL)
    if ($request->work_center) {
        $lptsQuery->where('mwc.work_center', 'like', '%'.$request->work_center.'%');
    }
    // by Type Stock
    if ($request->type_stock) {
        $lptsQuery->where('dw.type_stock', $request->type_stock);
        $returnQuery->where('dw.type_stock', $request->type_stock);
        $manualQuery->where('dw.type_stock', $request->type_stock);
    }
    // by Status
    if ($request->status) {
        $lptsQuery->where('dw.status', $request->status);
        $returnQuery->where('dw.status', $request->status);
        $manualQuery->where('dw.status', $request->status);
    }
    // by Date Range
    if ($request->date_from && $request->date_to) {
        $lptsQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $returnQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $manualQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $lptsQuery->where('dw.waste_date', '>=', $request->date_from);
        $returnQuery->where('dw.waste_date', '>=', $request->date_from);
        $manualQuery->where('dw.waste_date', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $lptsQuery->where('dw.waste_date', '<=', $request->date_to);
        $returnQuery->where('dw.waste_date', '<=', $request->date_to);
        $manualQuery->where('dw.waste_date', '<=', $request->date_to);
    }
    // Searching by no_report
    if ($request->no_report) {
        $lptsQuery->where('dw.no_report', 'like', '%'.$request->no_report.'%');
        $returnQuery->where('dw.no_report', 'like', '%'.$request->no_report.'%');
        $manualQuery->where('dw.no_report', 'like', '%'.$request->no_report.'%');
    }
    // Searching by no_so
    if ($request->no_so) {
        $lptsQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
        $returnQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
        // manual tidak punya no_so → exclude manual saat filter no_so
        $manualQuery->whereRaw('1=0');
    }

    // === UNION ALL & ambil data ===
    $datas = $lptsQuery
        ->unionAll($returnQuery)
        ->unionAll($manualQuery)
        ->orderByDesc('created_at')
        ->get();

  $stockWaste = DB::table('data_stock_waste');
            $stockWaste = $stockWaste->select(
                'type_product',
                'stock',
                'updated_at'
            )->get();



    // === data bantu untuk view ===
    $typeProducts = DB::table('master_product_fgs')
        ->select('type_product')->distinct()->pluck('type_product');

    // nomor otomatis untuk request add
    $count = DB::table('data_waste')
        ->whereYear('created_at', date('Y'))
        ->where('status', 'REQUEST')
        ->max('indexing_numbers');

    $no = $count ? str_pad($count + 1, 6, '0', STR_PAD_LEFT) : '000001';
    $report_number = 'TW'.Carbon::now()->format('ym').$no;



    // preload stok untuk modal (key uppercase biar cocok sama JS)
    $stockMap = DB::table('data_stock_waste')
        ->select('type_product','stock')
        ->get()
        ->mapWithKeys(fn($r) => [strtoupper($r->type_product) => (float)$r->stock]);

    return view('data-waste.index', compact('datas', 'typeProducts', 'report_number', 'stockMap','stockWaste'));
}



public function print(Request $request)
{
    // --- LPTS (tetap) ---
    $lptsQuery = DB::table('data_waste as dw')
        ->leftJoin('lpts', function($join){
            $join->on('dw.id_resource','=','lpts.id')
                 ->where('dw.id_resource_column','=','lpts_id');
        })
        ->leftJoin('work_orders as wo','lpts.id_wo','=','wo.id')
        ->leftJoin('sales_orders as so','wo.id_sales_orders','=','so.id')
        ->leftJoin('master_units as mu','wo.id_master_units','=','mu.id')
        ->leftJoin('master_product_fgs as mpf','wo.id_master_products','=','mpf.id')
        ->leftJoin('master_work_centers as mwc','wo.id_master_work_centers','=','mwc.id')
        ->leftJoin(DB::raw('
            (SELECT hs1.*
             FROM history_stocks hs1
             INNER JOIN (
                 SELECT id_master_products, MAX(id) AS max_id
                 FROM history_stocks
                 GROUP BY id_master_products
             ) hs2 ON hs1.id_master_products = hs2.id_master_products AND hs1.id = hs2.max_id
            ) as hs
        '),'wo.id_master_products','=','hs.id_master_products')
        ->leftJoin('delivery_notes as dn','so.id','=','dn.id_sales_orders')
        ->select([
            'dw.waste_date as tanggal',
            'mwc.work_center',
            'dw.weight as weight',
            'mu.unit',
            'dw.status',
            'mpf.type_product',
            'hs.remarks as keterangan',
            'dw.type_stock',
            'dw.no_report as report_number',
            DB::raw("'LPTS' as sumber"),
        ])
        ->where('dw.id_resource_column','lpts_id');

    // --- RETURN (tetap) ---
    $returnQuery = DB::table('data_waste as dw')
        ->leftJoin('return_customers_ppic as rcp', function($join){
            $join->on('dw.id_resource','=','rcp.id')
                 ->where('dw.id_resource_column','=','return_customers_ppic_id');
        })
        ->leftJoin('delivery_note_details as dnd','rcp.id_delivery_note_details','=','dnd.id')
        ->leftJoin('delivery_notes as dn','dnd.id_delivery_notes','=','dn.id')
        ->leftJoin('sales_orders as so','dnd.id_sales_orders','=','so.id')
        ->leftJoin('master_product_fgs as mpf','so.id_master_products','=','mpf.id')
        ->leftJoin('master_units as mu','dnd.id_master_units','=','mu.id')
        ->leftJoin('work_orders as wo','rcp.id_sales_orders','=','wo.id_sales_orders')
        ->leftJoin('master_work_centers as mwc','wo.id_master_work_centers','=','mwc.id')
        ->leftJoin(DB::raw('
            (SELECT hs1.*
             FROM history_stocks hs1
             INNER JOIN (
                 SELECT id_master_products, MAX(id) AS max_id
                 FROM history_stocks
                 GROUP BY id_master_products
             ) hs2 ON hs1.id_master_products = hs2.id_master_products AND hs1.id = hs2.max_id
            ) as hs
        '),'mpf.id','=','hs.id_master_products')
        ->select([
            'dw.waste_date as tanggal',
            'mwc.work_center',
            'dw.weight as weight',
            'mu.unit',
            'dw.status',
            'mpf.type_product',
            'hs.remarks as keterangan',
            'dw.type_stock',
            'dw.no_report as report_number',
            DB::raw("'RETURN' as sumber"),
        ])
        ->where('dw.id_resource_column','return_customers_ppic_id');

    // --- MANUAL (baru) ---
    $manualQuery = DB::table('data_waste as dw')
        ->select([
            'dw.waste_date as tanggal',
            DB::raw('NULL as work_center'),
            'dw.weight as weight',
            DB::raw("'kg' as unit"),
            'dw.status',
            'dw.type_product',
            'dw.remark as keterangan',
            'dw.type_stock',
            'dw.no_report as report_number',
            DB::raw("'ADD' as sumber"),
        ])
        ->where('dw.id_resource_column','manual');

    // === FILTER ===
    // type_product (print mendukung multi-select)
    $typeProductsSelected = $request->type_product ?? [];
    if (!is_array($typeProductsSelected)) $typeProductsSelected = [$typeProductsSelected];
    if (count($typeProductsSelected) > 0) {
        $lptsQuery->whereIn('mpf.type_product', $typeProductsSelected);
        $returnQuery->whereIn('mpf.type_product', $typeProductsSelected);
        $manualQuery->whereIn('dw.type_product', $typeProductsSelected);
    }

    // date range
    if ($request->date_from && $request->date_to) {
        $lptsQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $returnQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $manualQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $lptsQuery->where('dw.waste_date', '>=', $request->date_from);
        $returnQuery->where('dw.waste_date', '>=', $request->date_from);
        $manualQuery->where('dw.waste_date', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $lptsQuery->where('dw.waste_date', '<=', $request->date_to);
        $returnQuery->where('dw.waste_date', '<=', $request->date_to);
        $manualQuery->where('dw.waste_date', '<=', $request->date_to);
    }

    // === UNION 3 sumber ===
    $datas = $lptsQuery
        ->unionAll($returnQuery)
        ->unionAll($manualQuery)
        ->orderByDesc('tanggal')
        ->get();

    // total waste (opsional, tetap)
    $selectedType = $typeProductsSelected ?: null;
    $totalWaste = 0;
    foreach ($datas as $row) $totalWaste += floatval($row->weight);

    return Pdf::loadView('data-waste.print', [
        'datas' => $datas,
        'typeProductsSelected' => $typeProductsSelected,
        'totalWaste' => $totalWaste
    ])->setPaper('legal', 'landscape')->stream('stock_card_waste.pdf');
}


    public function exportExcel(Request $request)
{
    // --- LPTS ---
    $lptsQuery = DB::table('data_waste as dw')
        ->leftJoin('lpts', function($join){
            $join->on('dw.id_resource','=','lpts.id')
                 ->where('dw.id_resource_column','=','lpts_id');
        })
        ->leftJoin('work_orders as wo','lpts.id_wo','=','wo.id')
        ->leftJoin('sales_orders as so','wo.id_sales_orders','=','so.id')
        ->leftJoin('master_units as mu','wo.id_master_units','=','mu.id')
        ->leftJoin('master_product_fgs as mpf','wo.id_master_products','=','mpf.id')
        ->leftJoin('master_group_subs as mgs','mpf.id_master_group_subs','=','mgs.id')
        ->leftJoin('master_work_centers as mwc','wo.id_master_work_centers','=','mwc.id')
        ->leftJoin(DB::raw('
            (SELECT hs1.*
             FROM history_stocks hs1
             INNER JOIN (
                 SELECT id_master_products, MAX(id) AS max_id
                 FROM history_stocks
                 GROUP BY id_master_products
             ) hs2 ON hs1.id_master_products = hs2.id_master_products AND hs1.id = hs2.max_id
            ) as hs
        '),'wo.id_master_products','=','hs.id_master_products')
        ->leftJoin('delivery_notes as dn','so.id','=','dn.id_sales_orders')
        ->select([
            'so.so_number as no_so',
            'dw.no_report as no_report',
            'dn.dn_number as no_dn',
            'mwc.work_center as work_center',
            'dw.weight as weight',
            'mu.unit as unit',
            'mgs.name as group_sub',
            'mpf.type_product',
            'hs.remarks as remark',
            DB::raw('dw.type_stock as type_stock'), // pastikan jadi dw.type_stock
            'dw.status',
            DB::raw("'LPTS' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column','lpts_id');

    // --- RETURN ---
    $returnQuery = DB::table('data_waste as dw')
        ->leftJoin('return_customers_ppic as rcp', function($join){
            $join->on('dw.id_resource','=','rcp.id')
                 ->where('dw.id_resource_column','=','return_customers_ppic_id');
        })
        ->leftJoin('delivery_note_details as dnd','rcp.id_delivery_note_details','=','dnd.id')
        ->leftJoin('delivery_notes as dn','dnd.id_delivery_notes','=','dn.id')
        ->leftJoin('sales_orders as so','dnd.id_sales_orders','=','so.id')
        ->leftJoin('master_product_fgs as mpf','so.id_master_products','=','mpf.id')
        ->leftJoin('master_group_subs as mgs','mpf.id_master_group_subs','=','mgs.id')
        ->leftJoin('master_units as mu','dnd.id_master_units','=','mu.id')
        ->leftJoin('work_orders as wo','rcp.id_sales_orders','=','wo.id_sales_orders')
        ->leftJoin('master_work_centers as mwc','wo.id_master_work_centers','=','mwc.id')
        ->leftJoin(DB::raw('
            (SELECT hs1.*
             FROM history_stocks hs1
             INNER JOIN (
                 SELECT id_master_products, MAX(id) AS max_id
                 FROM history_stocks
                 GROUP BY id_master_products
             ) hs2 ON hs1.id_master_products = hs2.id_master_products AND hs1.id = hs2.max_id
            ) as hs
        '),'mpf.id','=','hs.id_master_products')
        ->select([
            'so.so_number as no_so',
            'dw.no_report as no_report',
            'dn.dn_number as no_dn',
            'mwc.work_center as work_center',
            'dw.weight as weight',
            'mu.unit as unit',
            'mgs.name as group_sub',
            'mpf.type_product',
            'hs.remarks as remark',
            DB::raw('dw.type_stock as type_stock'),
            'dw.status',
            DB::raw("'RETURN' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column','return_customers_ppic_id');

    // --- MANUAL (baru) ---
    $manualQuery = DB::table('data_waste as dw')
        ->select([
            DB::raw('NULL as no_so'),
            'dw.no_report as no_report',
            DB::raw('NULL as no_dn'),
            DB::raw('NULL as work_center'),
            'dw.weight as weight',
            DB::raw("'kg' as unit"),
            DB::raw('NULL as group_sub'),
            'dw.type_product',
            'dw.remark as remark',
            DB::raw('dw.type_stock as type_stock'),
            'dw.status',
            DB::raw("'ADD' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column','manual');

    // === FILTERS (samakan yang relevan ke MANUAL) ===
    if ($request->type_product) {
        $lptsQuery->where('mpf.type_product', $request->type_product);
        $returnQuery->where('mpf.type_product', $request->type_product);
        $manualQuery->where('dw.type_product', $request->type_product);
    }
    if ($request->group_sub) {
        $lptsQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
        $returnQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
        // manual: tidak ada group_sub → abaikan
    }
    if ($request->work_center) {
        $lptsQuery->where('mwc.work_center', 'like', '%'.$request->work_center.'%');
        // manual/return: biarkan
    }
    if ($request->type_stock) {
        $lptsQuery->where('dw.type_stock', $request->type_stock);
        $returnQuery->where('dw.type_stock', $request->type_stock);
        $manualQuery->where('dw.type_stock', $request->type_stock);
    }
    if ($request->status) {
        $lptsQuery->where('dw.status', $request->status);
        $returnQuery->where('dw.status', $request->status);
        $manualQuery->where('dw.status', $request->status);
    }
    if ($request->date_from && $request->date_to) {
        $lptsQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $returnQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $manualQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $lptsQuery->where('dw.waste_date', '>=', $request->date_from);
        $returnQuery->where('dw.waste_date', '>=', $request->date_from);
        $manualQuery->where('dw.waste_date', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $lptsQuery->where('dw.waste_date', '<=', $request->date_to);
        $returnQuery->where('dw.waste_date', '<=', $request->date_to);
        $manualQuery->where('dw.waste_date', '<=', $request->date_to);
    }
    if ($request->no_report) {
        $lptsQuery->where('dw.no_report', 'like', '%'.$request->no_report.'%');
        $returnQuery->where('dw.no_report', 'like', '%'.$request->no_report.'%');
        $manualQuery->where('dw.no_report', 'like', '%'.$request->no_report.'%');
    }
    if ($request->no_so) {
        $lptsQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
        $returnQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
        // manual: tidak ada no_so → abaikan
    }

    // === UNION 3 sumber ===
    $datas = $lptsQuery
        ->unionAll($returnQuery)
        ->unionAll($manualQuery)
        ->orderByDesc('created_at')
        ->get();

    return Excel::download(new \App\Exports\DataWasteExport($datas), 'data_waste.xlsx');
}


    public function storeAddWaste(Request $request)
{
    // 1) Validasi dasar
    $request->validate([
        'waste_date'   => 'required|date',
        'type_product' => 'required|string|in:PP,POF,Crosslink',
        'qty_take'     => 'required|numeric|min:0.01',
        'remark'       => 'nullable|string|max:1000',
    ]);

    // 2) Buat nomor berurutan (indexing_numbers) dan no_report
    $count = DB::table('data_waste')
        ->whereYear('created_at', date('Y'))
        ->where('status', 'REQUEST')
        ->max('indexing_numbers');

    if (empty($count)) {
        $nextIndex = 1;
    } else {
        $nextIndex = $count + 1;
    }

    $seq6 = str_pad((string)$nextIndex, 6, '0', STR_PAD_LEFT);
    $ym   = Carbon::now()->format('ym');          // contoh: 2509
    $no_report = 'TW' . $ym . $seq6;              // contoh: TW2509000001

    // 3) (Opsional tapi bagus) Cek stok dari data_stock_waste kalau ada
    $stockRow = DB::table('data_stock_waste')
        ->where('type_product', $request->type_product)
        ->first();

    $currentStock = (float)($stockRow->stock ?? 0);
    $qty = (float)$request->qty_take;

    if ($currentStock < $qty) {
        return back()->withErrors(['qty_take' => 'Stock tidak cukup.'])->withInput();
    }

    // 4) Insert ke data_waste (Add manual = OUT, status REQUEST)
    DB::table('data_waste')->insert([
        'id_resource'        => null,
        'id_resource_column' => 'manual',
        'no_report'          => $no_report,
        'indexing_numbers'   => $nextIndex,
        'status'             => 'REQUEST',
        'remark'             => $request->remark,
        'waste_date'         => Carbon::parse($request->waste_date)->format('Y-m-d'),
        'weight'             => $qty,
        'type_stock'         => 'OUT',
        'type_product'       => $request->type_product,
        'created_at'         => now(),
        'updated_at'         => now(),
    ]);

    // 5) Kurangi stok sederhana
    DB::table('data_stock_waste')
        ->updateOrInsert(
            ['type_product' => $request->type_product],
            [
                'stock'      => max(0, $currentStock - $qty),
                'updated_at' => now(),
                'created_at' => $stockRow?->created_at ?? now(),
            ]
        );

    return back()->with('pesan', "Berhasil menambahkan Data Waste. No Report: {$no_report}");
}


}
