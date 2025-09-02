<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataWasteController extends Controller
{
    public function index(Request $request)
{
    // Query LPTS
    $lptsQuery = DB::table('data_waste as dw')
        ->leftJoin('lpts', function($join) {
            $join->on('dw.id_resource', '=', 'lpts.id')
                 ->where('dw.id_resource_column', '=', 'lpts_id');
        })
        ->leftJoin('work_orders as wo', 'lpts.id_wo', '=', 'wo.id')
        ->leftJoin('sales_orders as so', 'wo.id_sales_orders', '=', 'so.id')
        ->leftJoin('work_order_details as wod', 'wo.id', '=', 'wod.id_work_orders')
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
            'so.so_number as no_so',
            'lpts.no_lpts as no_report',
            'dn.dn_number as no_dn',
            'mwc.work_center as work_center',
            'wod.qty as weight',
            'mu.unit as unit',
            'mgs.name as group_sub',
            'wo.type_product',
            'hs.remarks as remark',
            'hs.type_stock',
            'dw.status',
            DB::raw("'LPTS' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column', 'lpts_id');

    // Query Return Customer
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
            'so.so_number as no_so',
            'dn.dn_number as no_report',
            'dn.dn_number as no_dn',
            'mwc.work_center as work_center',
            'dnd.qty as weight',
            'mu.unit as unit',
            'mgs.name as group_sub',
            'dnd.type_product',
            'hs.remarks as remark',
            'hs.type_stock',
            'dw.status',
            DB::raw("'RETURN' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column', 'return_customers_ppic_id');

    // FILTERING
    // by Type Product
    if ($request->type_product) {
        $lptsQuery->where('wo.type_product', $request->type_product);
        $returnQuery->where('dnd.type_product', $request->type_product);
    }
    // by Group Sub
    if ($request->group_sub) {
        $lptsQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
        $returnQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
    }
    // by Work Center (hanya LPTS, return NULL)
    if ($request->work_center) {
        $lptsQuery->where('mwc.work_center', 'like', '%'.$request->work_center.'%');
    }
    // by Type Stock
    if ($request->type_stock) {
        $lptsQuery->where('hs.type_stock', $request->type_stock);
        $returnQuery->where('hs.type_stock', $request->type_stock);
    }
    // by Status
    if ($request->status) {
        $lptsQuery->where('dw.status', $request->status);
        $returnQuery->where('dw.status', $request->status);
    }
    // by Date Range
    if ($request->date_from && $request->date_to) {
        $lptsQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $returnQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $lptsQuery->where('dw.waste_date', '>=', $request->date_from);
        $returnQuery->where('dw.waste_date', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $lptsQuery->where('dw.waste_date', '<=', $request->date_to);
        $returnQuery->where('dw.waste_date', '<=', $request->date_to);
    }
    // Searching by no_report
    if ($request->no_report) {
        $lptsQuery->where('lpts.no_lpts', 'like', '%'.$request->no_report.'%');
        $returnQuery->where('dn.dn_number', 'like', '%'.$request->no_report.'%');
    }
    // Searching by no_so
    if ($request->no_so) {
        $lptsQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
        $returnQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
    }

    // Gabung hasil (UNION ALL)
    $datas = $lptsQuery
        ->unionAll($returnQuery)
        ->orderByDesc('created_at')
        ->get();
// $typeProducts = DB::table('master_product_fgs')
//         ->select('type_product')
//         ->distinct()
//         ->pluck('type_product');

// Ambil semua type_product untuk filter
    $typeProductsLPTS = DB::table('work_orders')->select('type_product')->distinct()->pluck('type_product');
    $typeProductsReturn = DB::table('delivery_note_details') ->whereNotNull('type_product')->select('type_product')->distinct()->pluck('type_product');
    $typeProducts = $typeProductsLPTS->merge($typeProductsReturn)->unique()->sort()->values();

    return view('data-waste.index', compact('datas', 'typeProducts'));
}

public function print(Request $request)
{
    // Query LPTS
    $lptsQuery = DB::table('data_waste as dw')
        ->leftJoin('lpts', function($join) {
            $join->on('dw.id_resource', '=', 'lpts.id')
                 ->where('dw.id_resource_column', '=', 'lpts_id');
        })
        ->leftJoin('work_orders as wo', 'lpts.id_wo', '=', 'wo.id')
        ->leftJoin('sales_orders as so', 'wo.id_sales_orders', '=', 'so.id')
        ->leftJoin('work_order_details as wod', 'wo.id', '=', 'wod.id_work_orders')
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
            'dw.waste_date as tanggal',
            'mwc.work_center',
            'wod.qty as weight',
            'mu.unit',
            'dw.status',
            'wo.type_product',
            'hs.remarks as keterangan',
            'hs.type_stock',
            'dn.dn_number as report_number',
            DB::raw("'LPTS' as sumber"),
        ])
        ->where('dw.id_resource_column', 'lpts_id');

    // Query Return Customer
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
            'dw.waste_date as tanggal',
            'mwc.work_center',
            'dnd.qty as weight',
            'mu.unit',
            'dw.status',
            'dnd.type_product',
            'hs.remarks as keterangan',
            'hs.type_stock',
            'dn.dn_number as report_number',
            DB::raw("'RETURN' as sumber"),
        ])
        ->where('dw.id_resource_column', 'return_customers_ppic_id');

    // FILTERING BY master_product_fgs
    // if ($request->type_product) {
    //     $lptsQuery->where('mpf.type_product', $request->type_product);
    //     $returnQuery->where('mpf.type_product', $request->type_product); // pastikan filter dari master_product_fgs
    // }


 // Ambil array type_product dari request
$typeProductsSelected = $request->type_product ?? [];
if (!is_array($typeProductsSelected)) {
    $typeProductsSelected = [$typeProductsSelected];
}

// Filter query
if ($typeProductsSelected && count($typeProductsSelected) > 0) {
    $lptsQuery->whereIn('wo.type_product', $typeProductsSelected);
    $returnQuery->whereIn('dnd.type_product', $typeProductsSelected);
}
    if ($request->date_from && $request->date_to) {
        $lptsQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $returnQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $lptsQuery->where('dw.waste_date', '>=', $request->date_from);
        $returnQuery->where('dw.waste_date', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $lptsQuery->where('dw.waste_date', '<=', $request->date_to);
        $returnQuery->where('dw.waste_date', '<=', $request->date_to);
    }

    // Gabung hasil
    $datas = $lptsQuery
        ->unionAll($returnQuery)
        ->orderByDesc('tanggal')
        ->get();

    // Hitung total waste hanya untuk type yang dipilih
    $selectedType = $request->type_product ?? null;
    $totalWaste = 0;
    foreach ($datas as $row) {
        if ($selectedType) {
            if ($row->type_product == $selectedType) {
                $totalWaste += floatval($row->weight);
            }
        } else {
            $totalWaste += floatval($row->weight);
        }
    }

    return Pdf::loadView('data-waste.print', [
        'datas' => $datas,
        'typeProductsSelected' => $typeProductsSelected, // array type yang dipilih
        'totalWaste' => $totalWaste
    ])->setPaper('legal', 'landscape')->stream('stock_card_waste.pdf');
}


    public function exportExcel(Request $request)
    {
        // Query LPTS
    $lptsQuery = DB::table('data_waste as dw')
        ->leftJoin('lpts', function($join) {
            $join->on('dw.id_resource', '=', 'lpts.id')
                 ->where('dw.id_resource_column', '=', 'lpts_id');
        })
        ->leftJoin('work_orders as wo', 'lpts.id_wo', '=', 'wo.id')
        ->leftJoin('sales_orders as so', 'wo.id_sales_orders', '=', 'so.id')
        ->leftJoin('work_order_details as wod', 'wo.id', '=', 'wod.id_work_orders')
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
            'so.so_number as no_so',
            'lpts.no_lpts as no_report',
            'dn.dn_number as no_dn',
            'mwc.work_center as work_center',
            'wod.qty as weight',
            'mu.unit as unit',
            'mgs.name as group_sub',
            'wo.type_product',
            'hs.remarks as remark',
            'hs.type_stock',
            'dw.status',
            DB::raw("'LPTS' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column', 'lpts_id');

    // Query Return Customer
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
            'so.so_number as no_so',
            'dn.dn_number as no_report',
            'dn.dn_number as no_dn',
            'mwc.work_center as work_center',
            'dnd.qty as weight',
            'mu.unit as unit',
            'mgs.name as group_sub',
            'dnd.type_product',
            'hs.remarks as remark',
            'hs.type_stock',
            'dw.status',
            DB::raw("'RETURN' as sumber"),
            'dw.created_at',
            'dw.waste_date'
        ])
        ->where('dw.id_resource_column', 'return_customers_ppic_id');

    // FILTERING
    // by Type Product
    if ($request->type_product) {
        $lptsQuery->where('wo.type_product', $request->type_product);
        $returnQuery->where('dnd.type_product', $request->type_product);
    }
    // by Group Sub
    if ($request->group_sub) {
        $lptsQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
        $returnQuery->where('mgs.name', 'like', '%'.$request->group_sub.'%');
    }
    // by Work Center (hanya LPTS, return NULL)
    if ($request->work_center) {
        $lptsQuery->where('mwc.work_center', 'like', '%'.$request->work_center.'%');
    }
    // by Type Stock
    if ($request->type_stock) {
        $lptsQuery->where('hs.type_stock', $request->type_stock);
        $returnQuery->where('hs.type_stock', $request->type_stock);
    }
    // by Status
    if ($request->status) {
        $lptsQuery->where('dw.status', $request->status);
        $returnQuery->where('dw.status', $request->status);
    }
    // by Date Range
    if ($request->date_from && $request->date_to) {
        $lptsQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
        $returnQuery->whereBetween('dw.waste_date', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $lptsQuery->where('dw.waste_date', '>=', $request->date_from);
        $returnQuery->where('dw.waste_date', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $lptsQuery->where('dw.waste_date', '<=', $request->date_to);
        $returnQuery->where('dw.waste_date', '<=', $request->date_to);
    }
    // Searching by no_report
    if ($request->no_report) {
        $lptsQuery->where('lpts.no_lpts', 'like', '%'.$request->no_report.'%');
        $returnQuery->where('dn.dn_number', 'like', '%'.$request->no_report.'%');
    }
    // Searching by no_so
    if ($request->no_so) {
        $lptsQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
        $returnQuery->where('so.so_number', 'like', '%'.$request->no_so.'%');
    }

    // Gabung hasil (UNION ALL)
    $datas = $lptsQuery
        ->unionAll($returnQuery)
        ->orderByDesc('created_at')
        ->get();
       return Excel::download(new \App\Exports\DataWasteExport($datas), 'data_waste.xlsx');
    }

}