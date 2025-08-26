<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReturnCustomersPpic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class ReturnCustomerPPIC extends Controller
{
public function index()
{
    $dn_details = DB::table('delivery_note_details as dn_details')
        ->leftJoin('delivery_notes as dn', 'dn_details.id', '=', 'dn.id')
        ->leftJoin('master_customers as mc', 'dn.id_master_customers', '=', 'mc.id')
        ->leftJoin('sales_orders as so', 'dn_details.id_sales_orders', '=', 'so.id')
        ->leftJoin('master_product_fgs as mpf', 'so.id_master_products', '=', 'mpf.id')
        ->leftJoin('master_units as mu', 'so.id_master_units', '=', 'mu.id')
        ->where('dn.status', 'Posted')
        ->select(
            'dn_details.id as id_dn_details',
            'dn_details.po_number as no_po',
            'dn_details.id_sales_orders',
            'dn.id_master_customers',
            'dn.dn_number',
            'dn.status',
            'dn_details.id_delivery_notes',
            'mc.name as customer_name',
            'so.so_number',
            'so.id_master_products',
            'mpf.description as product_name',
            'mu.unit',
            'so.id_master_units'
        )
        ->get();


    // Data utama tabel return_customers_ppic
    $returns = ReturnCustomersPpic::leftjoin('master_customers as mc', 'return_customers_ppic.id_master_customers', '=', 'mc.id')
        ->leftJoin('sales_orders as so', 'return_customers_ppic.id_sales_orders', '=', 'so.id')
        ->leftJoin('master_units as mu', 'return_customers_ppic.id_master_units', '=', 'mu.id')
        ->leftJoin('delivery_notes as dn', 'return_customers_ppic.id_delivery_notes', '=', 'dn.id')
        ->select(
            'return_customers_ppic.*',
            'mc.name as customer_name',
            'so.so_number',
            'mu.unit',
            'dn.dn_number'

        )
        ->orderBy('return_customers_ppic.created_at', 'desc')
        ->get();

    return view('return_customers_ppic.index', compact('dn_details', 'returns'));
}


public function store(Request $request)
{

    dump($request->all());
   $request->validate([
    'id_delivery_note_details' => 'required|integer',
    'id_delivery_notes' => 'required|integer',
    'id_master_customers' => 'required|integer',
    'no_po' => 'required|string',
    'id_sales_orders' => 'required|integer',
    'tanggal' => 'required|date',
    'name' => 'required|string',
    'qty' => 'required|numeric|regex:/^\d+(\.\d{1,3})?$/',
    'id_master_units' => 'required|integer',
    'berat' => 'nullable|integer',
    'keterangan' => 'nullable|string',
]);

ReturnCustomersPpic::create([
    'id_delivery_note_details' => $request->id_delivery_note_details,
    'id_delivery_notes' => $request->id_delivery_notes,
    'id_master_customers' => $request->id_master_customers,
    'no_po' => $request->no_po,
    'id_sales_orders' => $request->id_sales_orders,
    'name' => $request->name,
    'qty' => $request->qty,
    'id_master_units' => $request->id_master_units,
    'tanggal' => $request->tanggal,
    'berat' => $request->berat,
    'keterangan' => $request->keterangan,
    'created_at' => now(),
    'updated_at' => now(),
]);

    return redirect()->route('return-customer-ppic.index')->with('pesan', 'Data return customer berhasil disimpan!');
}
}
