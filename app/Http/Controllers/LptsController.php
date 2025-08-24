<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LptsController extends Controller
{
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

        $bulanRomawi = $this->toRoman(now()->format('n'));
        $tahun = now()->format('y');
        foreach ($datas as $i => $row) {
            $lpts = DB::table('lpts')->where('id_wo', $row->work_order_id)->first();
            if ($lpts) {
                $row->no_lpts = $lpts->no_lpts;
                $row->keterangan = $lpts->keterangan;
                $row->can_print = !empty($lpts->keterangan);
            } else {
                $no = str_pad($i + 1, 3, '0', STR_PAD_LEFT);
                $row->no_lpts = "{$no}/Q&D/LPTS/{$bulanRomawi}/{$tahun}";
                $row->keterangan = null;
                $row->can_print = false;
            }
            // Tambahkan properti baru untuk format tanggal
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

        // Ambil no_wo dari tabel work_orders
        $wo = DB::table('work_orders')->where('id', $request->id_wo)->first();
        $no_wo = $wo ? $wo->wo_number : null;

        // Ambil barcode_number gabungan jadi jika ada id_work_orders yang sama maka akan di gabung barcode_number nya
        $barcode_numbers = DB::table('barcodes')
            ->leftJoin('barcode_detail', 'barcodes.id', '=', 'barcode_detail.id_barcode')
            ->where('barcodes.id_work_orders', $request->id_wo)
            ->pluck('barcode_detail.barcode_number')
            ->toArray();

        $barcode_number_str = implode(',', $barcode_numbers);

        // Insert/update lpts
        $existing = DB::table('lpts')->where('id_wo', $request->id_wo)->first();
        if ($existing) {
            // Update
            DB::table('lpts')->where('id_wo', $request->id_wo)->update([
                'keterangan'     => $request->keterangan,
                'barcode_number' => $barcode_number_str,
                'no_wo'          => $no_wo,
                'updated_at'     => now(),
            ]);
        } else {
            // Insert
            DB::table('lpts')->insert([
                'no_lpts'        => $request->no_lpts,
                'id_wo'          => $request->id_wo,
                'no_wo'          => $no_wo,
                'barcode_number' => $barcode_number_str,
                'keterangan'     => $request->keterangan,
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
                'wo.created_at',
                'wo.status',
                'pl.packing_number',
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

        $pdf = Pdf::loadView('LPTS.print', ['data' => $data]);
        return $pdf->stream('LPTS.pdf');
    }
}