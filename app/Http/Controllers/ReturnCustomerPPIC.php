<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReturnCustomersPpic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;
use App\Exports\ReturnCustomerPpicExport;
use Maatwebsite\Excel\Facades\Excel;

use function Laravel\Prompts\select;

class ReturnCustomerPPIC extends Controller
{
public function index( Request $request)
{
    $dn_details = DB::table('delivery_note_details as dn_details')
        ->leftJoin('delivery_notes as dn', 'dn_details.id_delivery_notes', '=', 'dn.id')
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
        ->leftJoin('delivery_note_details as dn_details', 'return_customers_ppic.id_delivery_note_details', '=', 'dn_details.id')
        ->leftJoin('delivery_notes as dn', 'dn_details.id_delivery_notes', '=', 'dn.id')
        ->select(
            'return_customers_ppic.*',
            'mc.name as customer_name',
            'so.so_number',
            'mu.unit',
            'dn.dn_number'


        );
        // Filter dari request
    if ($request->dn_number) {
        $returns->where('dn.dn_number', 'like', '%' . $request->dn_number . '%');
    }
    if ($request->customer_name) {
        $returns->where('mc.name', 'like', '%' . $request->customer_name . '%');
    }
    if ($request->no_po) {
        $returns->where('return_customers_ppic.no_po', 'like', '%' . $request->no_po . '%');
    }
    if ($request->so_number) {
        $returns->where('so.so_number', 'like', '%' . $request->so_number . '%');
    }
    if ($request->date_from && $request->date_to) {
        $returns->whereBetween('return_customers_ppic.date_return', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $returns->where('return_customers_ppic.date_return', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $returns->where('return_customers_ppic.date_return', '<=', $request->date_to);
    }

     if ($request->no_dn) {
        $returns->where('dn.dn_number', 'like', '%' . $request->no_dn . '%');
    }
    if ($request->product_name) {
        $returns->where('return_customers_ppic.name', 'like', '%' . $request->product_name . '%');
    }

    $returns = $returns->orderBy('return_customers_ppic.created_at', 'desc')->get();
        dump($returns);

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
    'date_return' => $request->tanggal,
    'weight' => $request->berat,
    'keterangan' => $request->keterangan,
    'qc_status' => 'checked',
    'created_at' => now(),
    'updated_at' => now(),
]);

    return redirect()->route('return-customer-ppic.index')->with('pesan', 'Data return customer berhasil disimpan!');
}

public function printReturn($id_delivery_note_details)
{
    // Ambil semua data return customer yang terkait dengan detail DN tertentu
    // Join ke master_customers, sales_orders, master_units, delivery_note_details, dan delivery_notes
    $datas = ReturnCustomersPpic::leftjoin('master_customers as mc', 'return_customers_ppic.id_master_customers', '=', 'mc.id')
        ->leftJoin('sales_orders as so', 'return_customers_ppic.id_sales_orders', '=', 'so.id')
        ->leftJoin('master_units as mu', 'return_customers_ppic.id_master_units', '=', 'mu.id')
        ->leftJoin('delivery_note_details as dn_details', 'return_customers_ppic.id_delivery_note_details', '=', 'dn_details.id')
        ->leftJoin('delivery_notes as dn', 'dn_details.id_delivery_notes', '=', 'dn.id')
        ->select(
            'return_customers_ppic.*',
            'mc.name as customer_name',
            'so.so_number',
            'mu.unit',
            'dn.dn_number'
        )
        // Filter hanya data return dengan id_delivery_note_details yang dipilih
        ->where('return_customers_ppic.id_delivery_note_details', $id_delivery_note_details)
        ->orderBy('so.so_number')
        ->get();

    // Ambil data customer, tanggal, dan nomor DN dari data pertama (untuk header laporan)
    $customer = $datas->first()->customer_name ?? '';
    $tanggal = $datas->first()->created_at ?? '';
    $dn_number = $datas->first()->dn_number ?? '';

    // Generate PDF menggunakan blade print dan kirim data yang dibutuhkan
    $pdf = Pdf::loadView('return_customers_ppic.print', compact('datas', 'customer', 'tanggal', 'dn_number'));
    return $pdf->stream('return_customers_ppic.pdf');
}

public function exportExcel(Request $request)
{
    // Query sama seperti index, filter sesuai request
    $returns = ReturnCustomersPpic::leftjoin('master_customers as mc', 'return_customers_ppic.id_master_customers', '=', 'mc.id')
        ->leftJoin('sales_orders as so', 'return_customers_ppic.id_sales_orders', '=', 'so.id')
        ->leftJoin('master_units as mu', 'return_customers_ppic.id_master_units', '=', 'mu.id')
        ->leftJoin('delivery_note_details as dn_details', 'return_customers_ppic.id_delivery_note_details', '=', 'dn_details.id')
        ->leftJoin('delivery_notes as dn', 'dn_details.id_delivery_notes', '=', 'dn.id')
        ->select(
            'return_customers_ppic.*',
            'mc.name as customer_name',
            'so.so_number',
            'mu.unit',
            'dn.dn_number'
        );

    // Filter dari request
    if ($request->dn_number) {
        $returns->where('dn.dn_number', 'like', '%' . $request->dn_number . '%');
    }
    if ($request->customer_name) {
        $returns->where('mc.name', 'like', '%' . $request->customer_name . '%');
    }
    if ($request->no_po) {
        $returns->where('return_customers_ppic.no_po', 'like', '%' . $request->no_po . '%');
    }
    if ($request->so_number) {
        $returns->where('so.so_number', 'like', '%' . $request->so_number . '%');
    }
    if ($request->date_from && $request->date_to) {
        $returns->whereBetween('return_customers_ppic.date_return', [$request->date_from, $request->date_to]);
    } elseif ($request->date_from) {
        $returns->where('return_customers_ppic.date_return', '>=', $request->date_from);
    } elseif ($request->date_to) {
        $returns->where('return_customers_ppic.date_return', '<=', $request->date_to);
    }
    if ($request->no_dn) {
        $returns->where('dn.dn_number', 'like', '%' . $request->no_dn . '%');
    }
    if ($request->product_name) {
        $returns->where('return_customers_ppic.name', 'like', '%' . $request->product_name . '%');
    }

    $returns = $returns->orderBy('return_customers_ppic.created_at', 'desc')->get();

    return Excel::download(new ReturnCustomerPpicExport($returns), 'return_customer_ppic.xlsx');
}

public function scrap($id)
{
    $return = DB::table('return_customers_ppic')->where('id', $id)->first();
    if (!$return) {
        return back()->with('error', 'Data Return Customer tidak ditemukan!');
    }

    $delivery_detail = DB::table('delivery_note_details as dnd')
    ->leftjoin('sales_orders as so', 'dnd.id_sales_orders', '=', 'so.id')
    ->leftJoin('master_product_fgs as mpf', 'so.id_master_products', '=', 'mpf.id')
    ->leftJoin('delivery_notes as dn', 'dnd.id_delivery_notes', '=', 'dn.id')
    ->leftJoin('return_customers_ppic as rcp', 'dnd.id', '=', 'rcp.id_delivery_note_details')
    ->where('dnd.id', $return->id_delivery_note_details)
    ->select('dn.dn_number as no_report',
            'mpf.type_product as type_product',
            'rcp.qty',
            'dnd.id_sales_orders'
            )->first();
          
    $id_sales_orders = $delivery_detail ? $delivery_detail->id_sales_orders : null;
    $type_product = $delivery_detail ? $delivery_detail->type_product : null;
    $no_report = $delivery_detail ? $delivery_detail->no_report : null;
    $qty = $delivery_detail ? $delivery_detail->qty : null;

    $id_master_products = null;
    if ($id_sales_orders) {
        $sales_order = DB::table('sales_orders')->where('id', $id_sales_orders)->first();
        $id_master_products = $sales_order ? $sales_order->id_master_products : null;
    }

    $remark = null;
    if ($id_master_products) {
        $history = DB::table('history_stocks')
            ->where('id_master_products', $id_master_products)
            ->orderByDesc('id')
            ->first();
        $remark = $history ? $history->remarks : null;
    }
    

    $waste_date = request('waste_date') ?? now()->format('Y-m-d');

    DB::table('data_waste')->insert([
        'id_resource'         => $return->id,
        'id_resource_column'  => 'return_customers_ppic_id',
        'no_report'           => $no_report,
        'status'              => 'RETURN',
        'remark'              => $remark,
        'waste_date'          => $waste_date,
        'weight'              => $qty ? $qty : null,
        'type_stock'          => 'IN',
        'type_product'        => $type_product,
        'created_at'          => now(),
        'updated_at'          => now(),
    ]);

     // Update Insert ke data_stock_waste
    $existingStock = DB::table('data_stock_waste')->where('type_product', $type_product)->first();
    if ($existingStock) {
        // Update: tambah stock
        DB::table('data_stock_waste')
            ->where('type_product', $type_product)
            ->update([
                'stock' => $existingStock->stock + ($qty ? $qty : 0),
                'updated_at' => now(),
            ]);
    } else {
        // Insert baru
        DB::table('data_stock_waste')->insert([
            'type_product' => $type_product,
            'stock' => $qty ? $qty : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    DB::table('return_customers_ppic')->where('id', $id)->update([
        'qc_status' => 'scrap'
    ]);

    return back()->with('success', 'Data Return Customer berhasil di scrap ke Waste dan QC status diupdate!');
}
}

