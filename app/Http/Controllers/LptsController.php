<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class LptsController extends Controller
{

    public function exportExcel(Request $request)
    {
        // Query sama seperti index, tapi ambil semua hasil filter
        $datas = DB::table('work_orders as wo')
            ->leftJoin('barcodes as b', 'wo.id', '=', 'b.id_work_orders')
            ->leftJoin('barcode_detail as bd', 'b.id', '=', 'bd.id_barcode')
            ->leftJoin('packing_lists as pl', 'wo.id_sales_orders', '=', 'pl.id_sales_orders')
            ->leftJoin('master_product_fgs as mpf', 'wo.id_master_products', '=', 'mpf.id')
            ->leftJoin('master_group_subs as mgs', 'mpf.id_master_group_subs', '=', 'mgs.id')
            ->leftJoin('master_units as mu', 'wo.id_master_units', '=', 'mu.id')
            ->leftJoin('lpts', 'wo.id', '=', 'lpts.id_wo')
            ->whereIn('wo.status', ['Request', 'Un posted']);

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
            DB::raw('GROUP_CONCAT(DISTINCT bd.barcode_number SEPARATOR ", ") as barcode_number'),
            'pl.packing_number',
            'b.staff',
            'mpf.description',
            'mpf.thickness',
            'mgs.name as group_sub_name',
            'mu.unit',
            'mpf.weight',
            'lpts.qc_status',
        )
        ->groupBy(
            'wo.id',
            'wo.wo_number',
            'wo.id_sales_orders',
            'wo.type_product',
            'wo.qty',
            'wo.created_at',
            'wo.status',
            'b.id',
            'pl.packing_number',
            'b.staff',
            'mpf.description',
            'mpf.thickness',
            'mgs.name',
            'mu.unit',
            'mpf.weight',
            'lpts.qc_status'
        )
        ->get();

        foreach ($datas as $row) {
            $lpts = DB::table('lpts')->where('id_wo', $row->work_order_id)->first();
            if ($lpts) {
                $row->no_lpts = $lpts->no_lpts;
                $row->keterangan = $lpts->keterangan;
                $row->can_print = !empty($lpts->keterangan);
            } else {
                $row->no_lpts = "Belum Ada";
                $row->keterangan = null;
                $row->can_print = false;
            }
            $row->created_at_formatted = $this->formatTanggal($row->created_at);
        }

        // Filtering after generate
        $datas = collect($datas);
        if ($request->filled('no_lpts')) {
            $datas = $datas->filter(function($item) use ($request) {
                return stripos($item->no_lpts, $request->no_lpts) !== false;
            })->values();
        }
        if ($request->filled('type_product')) {
            $datas = $datas->filter(function($item) use ($request) {
                return stripos($item->type_product, $request->type_product) !== false;
            })->values();
        }

        return Excel::download(new \App\Exports\LptsExport($datas), 'lpts_data.xlsx');
    }
    // Fungsi reusable untuk format tanggal
    private function formatTanggal($datetime, $format = 'Y-m-d') {
        return $datetime ? \Carbon\Carbon::parse($datetime)->format($format) : '-';
    }

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

    public function index(Request $request)
    {
        $datas = DB::table('work_orders as wo')
            ->leftJoin('barcodes as b', 'wo.id', '=', 'b.id_work_orders')
            ->leftJoin('barcode_detail as bd', 'b.id', '=', 'bd.id_barcode')
            ->leftJoin('packing_lists as pl', 'wo.id_sales_orders', '=', 'pl.id_sales_orders')
            ->leftJoin('master_product_fgs as mpf', 'wo.id_master_products', '=', 'mpf.id')
            ->leftJoin('master_group_subs as mgs', 'mpf.id_master_group_subs', '=', 'mgs.id')
            ->leftJoin('master_units as mu', 'wo.id_master_units', '=', 'mu.id')
            ->leftJoin('lpts', 'wo.id', '=', 'lpts.id_wo')
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
            DB::raw('GROUP_CONCAT(DISTINCT bd.barcode_number SEPARATOR ", ") as barcode_number'),
            'pl.packing_number',
            'b.staff',
            'mpf.description',
            'mpf.thickness',
            'mgs.name as group_sub_name',
            'mu.unit',
            'mpf.weight',
            'lpts.qc_status',
        )
        ->groupBy(
            'wo.id',
            'wo.wo_number',
            'wo.id_sales_orders',
            'wo.type_product',
            'wo.qty',
            'wo.created_at',
            'wo.status',
            'b.id',
            'pl.packing_number',
            'b.staff',
            'mpf.description',
            'mpf.thickness',
            'mgs.name',
            'mu.unit',
            'mpf.weight',
            'lpts.qc_status'
        )
        ->limit(50)
        ->get();


        $stock_waste = DB::table('data_stock_waste');
            $stock_waste = $stock_waste->select(
                'type_product',
                'stock',
                'updated_at'
            )->get();


       $bulanRomawi = $this->toRoman(now()->format('n'));
        $tahun = now()->format('y');
        $urut = 1; // urutan hanya untuk WO yang belum punya LPTS
   foreach ($datas as $row) {
    $lpts = DB::table('lpts')->where('id_wo', $row->work_order_id)->first();
    if ($lpts) {
        $row->id = $lpts->id;
        $row->no_lpts = $lpts->no_lpts;
        $row->keterangan = $lpts->keterangan;
        $row->can_print = !empty($lpts->keterangan);
        $row->qc_status = $lpts->qc_status;
    } else {
        $row->id = null;
        $row->no_lpts = "Belum Ada";
        $row->keterangan = null;
        $row->can_print = false;
        $row->qc_status = null;
    }
    $row->created_at_formatted = $this->formatTanggal($row->created_at);
}
        // Filtering after generate
        $datas = collect($datas);
        if ($request->filled('no_lpts')) {
            $datas = $datas->filter(function($item) use ($request) {
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

   public function storeKeterangan(Request $request)
{
    $request->validate([
        'no_lpts'    => 'required|string',
        'id_wo'      => 'required|integer',
        'keterangan' => 'nullable|string|max:1000',
    ]);

    // Ambil WO dengan JOIN ke master_product_fgs untuk mendapatkan weight
    $wo = DB::table('work_orders as wo')
        ->leftJoin('master_product_fgs as mpf', 'wo.id_master_products', '=', 'mpf.id')
        ->where('wo.id', $request->id_wo)
        ->select('wo.id','wo.wo_number','wo.type_product','wo.qty','wo.id_master_products', 'mpf.weight')
        ->first();

    $no_wo = $wo ? $wo->wo_number : null;
    $type_product = $wo->type_product ?? null;
    $qty = $wo->qty ?? 0;
    $weight = $wo->weight ?? 0; // weight dari master_product_fgs

    // Ambil id_master_products dari WO
    $id_master_products = $wo->id_master_products ?? null;

    // Cari id_history_stock yang sesuai dengan id_master_products
    $history_stock = DB::table('history_stocks')
        ->where('id_master_products', $id_master_products)
        ->orderByDesc('id') // ambil yang paling baru, jika ada lebih dari satu
        ->first();

    $id_history_stock = $history_stock ? $history_stock->id : null;

    // Gabung barcode_number
    $barcode_numbers = DB::table('barcodes')
        ->leftJoin('barcode_detail', 'barcodes.id', '=', 'barcode_detail.id_barcode')
        ->where('barcodes.id_work_orders', $request->id_wo)
        ->pluck('barcode_detail.barcode_number')
        ->toArray();
    $barcode_number_str = implode(',', $barcode_numbers);

    // Insert/update lpts
    $existing = DB::table('lpts')->where('id_wo', $request->id_wo)->first();
    if (!$existing) {
        $bulanRomawi = $this->toRoman(now()->format('n'));
        $tahun = now()->format('y');
        $noUrut = str_pad($this->getUrutanLPTSBaru(), 3, '0', STR_PAD_LEFT);
        $no_lpts = "{$noUrut}/Q&D/LPTS/{$bulanRomawi}/{$tahun}";

        DB::table('lpts')->insert([
            'no_lpts'        => $no_lpts,
            'id_wo'          => $request->id_wo,
            'id_history_stock' => $id_history_stock, // <-- isi dari hasil di atas
            'id_master_products' => $id_master_products,
              'no_wo'          => $no_wo,
            'type_product'   => $type_product,
            'qty'            => $qty,
            'weight'         => $weight,
            'barcode_number' => $barcode_number_str,
            'keterangan'     => $request->keterangan,
            'qc_status'      => 'checked',
            'created_by'     => auth()->user()->id,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
    return back()->with('pesan', 'Keterangan berhasil disimpan!');
}



    public function printLpts($work_order_id)
    {
        // Ambil dari lpts
        $lpts = DB::table('lpts')->where('id_wo', $work_order_id)->first();
        if (!$lpts || empty($lpts->keterangan)) {
            return back()->with('error', 'Isi keterangan dulu untuk bisa print LPTS!');
        }

        // Gabungkan barcode_number via subquery GROUP_CONCAT
        $data = DB::table('work_orders as wo')
            ->leftJoin('barcodes as b', 'wo.id', '=', 'b.id_work_orders')
            ->leftJoin('packing_lists as pl', 'wo.id_sales_orders', '=', 'pl.id_sales_orders')
            ->leftJoin('master_product_fgs as mpf', 'wo.id_master_products', '=', 'mpf.id')
            ->leftJoin('master_group_subs as mgs', 'mpf.id_master_group_subs', '=', 'mgs.id')
            ->leftJoin('master_units as mu', 'wo.id_master_units', '=', 'mu.id')
            // Join subquery barcode_data
            ->leftJoin(DB::raw('(
                SELECT barcodes.id_work_orders, GROUP_CONCAT(barcode_number SEPARATOR ", ") as all_barcodes
                FROM barcode_detail
                LEFT JOIN barcodes ON barcode_detail.id_barcode = barcodes.id
                GROUP BY barcodes.id_work_orders
            ) as barcode_data'), 'wo.id', '=', 'barcode_data.id_work_orders')
            ->where('wo.id', $work_order_id)
            ->select(
                'wo.id as work_order_id',
                'wo.wo_number',
                'wo.id_sales_orders',
                'wo.type_product',
                'wo.qty',
                'wo.qty_needed',
                'wo.created_at',
                'wo.status',
                'pl.packing_number',
                'mpf.product_code',
                'mpf.description',
                'mpf.thickness',
                'mgs.name as group_sub_name',
                'mu.unit',
                'barcode_data.all_barcodes as barcode_numbers' // hasil gabungan
            )
            ->first();

        // Gabungkan data lpts ke $data
        $data->no_lpts = $lpts->no_lpts;
        $data->keterangan = $lpts->keterangan;
        // Tambahkan format tanggal jika perlu print
        $data->created_at_formatted = $this->formatTanggal($data->created_at);

        $pdf = Pdf::loadView('lpts.print', ['data' => $data]);
        return $pdf->stream('LPTS.pdf');
    }

public function scrap($id)
{
    $lpts = DB::table('lpts')->where('id', $id)->first();
    if (!$lpts) {
        return back()->with('error', 'Data LPTS tidak ditemukan!');
    }

    // Ambil WO dan type_product dari master_product_fgs
    $wo = DB::table('work_orders')
        ->leftJoin('master_product_fgs as mpf', 'work_orders.id_master_products', '=', 'mpf.id')
        ->leftJoin('lpts', 'work_orders.id', '=', 'lpts.id_wo')
        ->where('work_orders.id', $lpts->id_wo)
        ->select('work_orders.*', 'mpf.type_product', 'lpts.no_lpts as no_report')
        ->first();

    $id_master_products = $wo ? $wo->id_master_products : null;
    $qty = $wo ? $wo->qty : 0;
    $type_product = $wo ? $wo->type_product : null;
    $no_report = $wo ? $wo->no_report : null;
    $remark = null;
    if ($id_master_products) {
        $history = DB::table('history_stocks')
            ->where('id_master_products', $id_master_products)
            ->orderByDesc('id')
            ->first();
        $remark = $history ? $history->remarks : null;
    }

    // Ambil tanggal scrap dari request, default ke hari ini jika tidak ada
    $waste_date = request('waste_date') ?? now()->format('Y-m-d');

    DB::table('data_waste')->insert([
        'id_resource'         => $lpts->id,
        'id_resource_column'  => 'lpts_id',
        'no_report'           => $no_report,
        'status'              => 'QC',
        'remark'              => $remark,
        'waste_date'          => $waste_date,
        'weight'              => $qty ? $qty : null,
        'type_stock'          => 'IN',
        'type_product'        => $type_product, // <-- tambahkan ini
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
    DB::table('lpts')->where('id', $id)->update([
        'qc_status' => 'scrap'
    ]);

    return back()->with('success', 'Data LPTS berhasil di scrap ke Waste dan QC status diupdate!');
}

public function rework($id)
{
    $lpts = DB::table('lpts')->where('id', $id)->first();
    if (!$lpts) {
        return back()->with('error', 'Data LPTS tidak ditemukan!');
    }


  // Ambil tanggal rework dari request, default ke hari ini jika tidak ada
    $rework_date = request('rework_date') ?? now()->format('Y-m-d');


    $id_master_products = $lpts->id_master_products;
    // Update qty dan weight di master_product_fgs
    if($id_master_products){
        $master_product_fgs = DB::table('master_product_fgs')->where('id', $id_master_products)->first();

        if($master_product_fgs) {
            // Tambahkan qty dan weight dari LPTS ke master_product_fgs
            DB::table('master_product_fgs')->where('id', $id_master_products)->update([
                'stock' => ($master_product_fgs->stock ?? 0) + ($lpts->qty ?? 0),
                'weight' => ($master_product_fgs->weight ?? 0) + ($lpts->weight ?? 0),
                'updated_at' => now()
            ]);
        }
    }


    // Update status QC di LPTS
    DB::table('lpts')->where('id', $id)->update([
        'qc_status' => 'rework',
        'updated_at' => now()
    ]);

    // Insert ke history_stocks sebagai rework
    DB::table('history_stocks')->insert([
        'id_good_receipt_notes_details' => $lpts->no_lpts,
        'id_master_products' => $lpts->id_master_products,
        'type_product' => $lpts->type_product,
        'usage_to' => null,
        'qty' => $lpts->qty,
        'weight' => $lpts->weight,
        'type_stock' => 'RETURN',
        'barcode' => $lpts->barcode_number,
        'date' => $rework_date,
        'remarks' => $lpts->keterangan ? $lpts->keterangan : 'Rework dari LPTS',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Data LPTS berhasil di-rework, QC status diupdate, dan stock master product bertambah!');
}

    private function getUrutanLPTSBaru() {
    // Ambil urutan terbesar dari DB
    $lastNo = DB::table('lpts')
        ->select(DB::raw('MAX(SUBSTRING_INDEX(no_lpts, "/", 1)) AS last_no'))
        ->first();
    $nomor = $lastNo && $lastNo->last_no ? (int)$lastNo->last_no : 0;
    return $nomor + 1;
}
}
