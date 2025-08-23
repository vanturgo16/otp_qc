<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LptsController extends Controller
{
    //

 public function index(Request $request)
{
   $datas = DB::table('work_orders as wo')
        ->leftJoin('barcodes as b', 'wo.id_sales_orders', '=', 'b.id_sales_orders')
        ->leftJoin('barcode_detail as bd', 'b.id', '=', 'bd.id_barcode')
        ->leftJoin('packing_lists as pl', 'wo.id_sales_orders', '=', 'pl.id_sales_orders')
        ->leftJoin('master_product_fgs as mpf', 'wo.id_master_products', '=', 'mpf.id')
        ->leftJoin('master_group_subs as mgs', 'mpf.id_master_group_subs', '=', 'mgs.id')
        ->leftJoin('master_units as mu', 'wo.id_master_units', '=', 'mu.id')
        ->whereIn('wo.status', ['Request', 'Un posted']);

    // Filter by form modal
    if ($request->filled('packing_number')) {
        $datas->where('pl.packing_number', 'like', '%' . $request->packing_number . '%');
    }
    if ($request->filled('barcode_number')) {
        $datas->where('bd.barcode_number', 'like', '%' . $request->barcode_number . '%');
    }
    if ($request->filled('group_sub_name')) {
        $datas->where('mgs.name', 'like', '%' . $request->group_sub_name . '%');
    }
    if ($request->filled('thickness')) {
        $datas->where('mpf.thickness', 'like', '%' . $request->thickness . '%');
    }
    if ($request->filled('date_from')) {
        $datas->whereDate('wo.created_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $datas->whereDate('wo.created_at', '<=', $request->date_to);
    }


    $datas = $datas->select(
        'wo.id as work_order_id',
        'wo.wo_number',
        'wo.id_sales_orders',
        'wo.type_product',
        'wo.qty',
        'wo.created_at',
        'wo.status',
        'b.id as barcode_id',
        'bd.barcode_number',
        'pl.packing_number',
        'b.staff',
        'mpf.description',
        'mpf.thickness',
        'mgs.name as group_sub_name',
        'mu.unit',
        DB::raw("'0' as weight")
    )
    ->limit(100)
    ->get();

    // Tambahkan no_lpts seperti sebelumnya
    $bulanRomawi = $this->toRoman(now()->format('n'));
    $tahun = now()->format('y');
    foreach ($datas as $i => $row) {
        $no = str_pad($i + 1, 3, '0', STR_PAD_LEFT);
        $row->no_lpts = "{$no}/Q&D/LPTS/{$bulanRomawi}/{$tahun}";
    }
    
 // Filtering after generate
    $datas = collect($datas);
dd($datas->first());
    if ($request->filled('no_lpts')) {
        $datas = $datas->filter(function($item) use ($request) {
            // Case-insensitive, partial match
            return stripos($item->no_lpts, $request->no_lpts) !== false;
        })->values();
    }
    if ($request->filled('type_product')) {
        $datas = $datas->filter(function($item) use ($request) {
            return stripos($item->type_product, $request->type_product) !== false;
        })->values();
    }
    return view('lpts.index', compact('datas'));
}

/**
 * Convert angka ke romawi
 */
private function toRoman($num)
{
    $map = [
        'M'  => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
        'C'  => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
        'X'  => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1,
    ];
    $returnValue = '';
    while ($num > 0) {
        foreach ($map as $roman => $int) {
            if ($num >= $int) {
                $num -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

public function storeKeterangan(Request $request)
{
    dump($request->all());
    $request->validate([
        'no_lpts' => 'required|string',
        'id_wo' => 'required|integer',
        'keterangan' => 'nullable|string|max:1000',
    ]);

    DB::table('lpts')->insert([
        'no_lpts'     => $request->no_lpts,
        'id_wo'       => $request->id_wo,
        'keterangan'  => $request->keterangan,
        'created_by'  => auth()->user()->id,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    return back()->with('pesan', 'Keterangan berhasil ditambahkan!');
}

}
