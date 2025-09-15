<?php

namespace App\Http\Controllers;

use App\Exports\ReturnCustomerPpicExport;
use App\Http\Controllers\Controller;
use App\Models\ReturnCustomersPpic;
use Barryvdh\DomPDF\Facade\Pdf;

use function Laravel\Prompts\select;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


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


    return view('return_customers_ppic.index', compact('dn_details', 'returns'));
}


public function store(Request $request)
{

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

// Ambil type_product dari delivery_note_details dan id_master_products dari sales_orders
$delivery_detail = DB::table('delivery_note_details as dnd')
    ->leftJoin('sales_orders as so', 'dnd.id_sales_orders', '=', 'so.id')
    ->leftJoin('master_product_fgs as mpf', 'so.id_master_products', '=', 'mpf.id')
    ->where('dnd.id', $request->id_delivery_note_details)
    ->select(
        'mpf.type_product',
        'so.id_master_products',

    )->first();

$type_product = $delivery_detail->type_product ?? null;
$id_master_products = $delivery_detail->id_master_products ?? null;

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
    'type_product' => $type_product, // Tambahkan kolom baru dari dn_details
    'id_master_products' => $id_master_products, // Tambahkan kolom baru dari sales_orders
    'created_at' => now(),
    'updated_at' => now(),
]);

    return redirect()->route('return-customer-ppic.index')->with('pesan', 'Data return customer berhasil disimpan!');
}public function printReturn($hash)
{

    try {
        $id_delivery_note_details = decrypt($hash);
    } catch (\Exception $e) {
        return back()->with('error', 'ID Delivery Note Details tidak valid!');
    }

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
        $customer  = $datas->pluck('customer_name')->unique()->implode(', ');
        $tanggal = $datas->pluck('date_return') // gunakan 'date_return' jika itu field tanggal Anda
         ->map(function($tgl) {
        return $tgl ? Carbon::parse($tgl)->format('Y-m-d') : null;
        })
        ->filter()
        ->unique()
        ->implode(', ');
        $dn_number = $datas->pluck('dn_number')->unique()->implode(', ');




    // Generate PDF menggunakan blade print dan kirim data yang dibutuhkan
    $pdf = Pdf::loadView('return_customers_ppic.print', compact('datas', 'customer', 'tanggal', 'dn_number'))
        ->setPaper('a4', 'landscape');
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

    // Ambil type_product dan id_master_products langsung dari return_customers_ppic
    $type_product = $return->type_product;
    $id_master_products = $return->id_master_products;
    $qty = $return->qty;

    // Ambil no_report dari delivery note
    $delivery_detail = DB::table('delivery_note_details as dnd')
        ->leftJoin('delivery_notes as dn', 'dnd.id_delivery_notes', '=', 'dn.id')
        ->where('dnd.id', $return->id_delivery_note_details)
        ->select('dn.dn_number as no_report')
        ->first();

        $no_report = $delivery_detail->no_report ?? null;
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

public function rework($id)
{
    $return = DB::table('return_customers_ppic')->where('id', $id)->first();
    if (!$return) {
        return back()->with('error', 'Data Return Customer tidak ditemukan!');
    }

    // Ambil type_product dan id_master_products langsung dari return_customers_ppic
    $type_product = $return->type_product;
    $id_master_products = $return->id_master_products;

    // Ambil no_report dari delivery note untuk history
    $delivery_detail = DB::table('delivery_note_details as dnd')
        ->leftJoin('delivery_notes as dn', 'dnd.id_delivery_notes', '=', 'dn.id')
        ->where('dnd.id', $return->id_delivery_note_details)
        ->select('dn.dn_number as no_report')
        ->first();

    $no_report = $delivery_detail->no_report ?? null;

    // Update qty dan weight di master_product_fgs jika ada id_master_products
    if($id_master_products){
        $master_product_fgs = DB::table('master_product_fgs')->where('id', $id_master_products)->first();

        if($master_product_fgs) {
            // Tambahkan qty dan weight dari return customer ke master_product_fgs
            DB::table('master_product_fgs')->where('id', $id_master_products)->update([
                'stock' => ($master_product_fgs->stock ?? 0) + ($return->qty ?? 0),
                'weight' => ($master_product_fgs->weight ?? 0) + ($return->weight ?? 0),
                'updated_at' => now()
            ]);
        }
    }

    // Ambil tanggal rework dari request, default ke hari ini jika tidak ada
    $rework_date = request('rework_date') ?? now()->format('Y-m-d');

    // Update status QC di return_customers_ppic
    DB::table('return_customers_ppic')->where('id', $id)->update([
        'qc_status' => 'rework',
        'updated_at' => now()
    ]);

    // Insert ke history_stocks sebagai rework
    DB::table('history_stocks')->insert([
        'id_good_receipt_notes_details' => $no_report,
        'id_master_products' => $id_master_products,
        'type_product' => $type_product,
        'usage_to' => null,
        'qty' => $return->qty,
        'weight' => $return->weight,
        'type_stock' => 'RETURN',
        'barcode' => null, // Return customer tidak punya barcode
        'date' => $rework_date,
        'remarks' => $return->keterangan ? $return->keterangan : 'Rework dari Return Customer',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Data Return Customer berhasil di-rework, QC status diupdate, dan stock master product bertambah!');
}
}

