<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lmts;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LmtsController extends Controller
{
    // Export Excel LMTS
    public function exportExcel(Request $request)
    {
        // Query sama seperti index, tapi ambil semua hasil filter
        $query = DB::table('lmts')
            ->leftJoin('good_receipt_notes as grn', 'lmts.id_good_receipt_notes', '=', 'grn.id')
            ->leftJoin('master_suppliers as ms', 'grn.id_master_suppliers', '=', 'ms.id')
            ->select([
                'lmts.id',
                'lmts.no_lmts',
                'lmts.receipt_number',
                'lmts.lot_number',
                'lmts.external_lot',
                'lmts.description',
                'lmts.date',
                'lmts.total_glq',
                'lmts.unit',
                'lmts.type_product',
                'lmts.status',
                'lmts.remarks',
                'lmts.button_active',
                'lmts.id_good_receipt_notes',
                'lmts.id_master_products',
                'grn.id_master_suppliers',
                'ms.name',
                'lmts.lmts_notes',
                'lmts.qty',
                'lmts.created_at'
            ])
            ->orderByDesc('lmts.created_at');

        // Apply same filters as index
        if ($request->no_lmts) {
            $query->where('lmts.no_lmts', 'like', "%{$request->no_lmts}%");
        }
        if ($request->receipt_number) {
            $query->where('lmts.receipt_number', 'like', "%{$request->receipt_number}%");
        }
        if ($request->lot_number) {
            $query->where('lmts.lot_number', 'like', "%{$request->lot_number}%");
        }
        if ($request->description) {
            $query->where('lmts.description', 'like', "%{$request->description}%");
        }
        if ($request->type_product) {
            $query->where('lmts.type_product', 'like', "%{$request->type_product}%");
        }
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('lmts.date', [$request->date_from, $request->date_to]);
        } elseif ($request->date_from) {
            $query->where('lmts.date', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->where('lmts.date', '<=', $request->date_to);
        }

        $datas = $query->get();

        // Format data untuk Excel
        foreach ($datas as $data) {
            // Format status text untuk Excel
            switch($data->status) {
                case 0:
                    $data->status_text = 'Hold';
                    break;
                case 1:
                    $data->status_text = 'Scrap';
                    break;
                case 2:
                    $data->status_text = 'Return';
                    break;
                case 3:
                    $data->status_text = 'Repair';
                    break;
                default:
                    $data->status_text = 'Unknown';
            }

            // Format tanggal
            $data->created_at_formatted = $data->created_at ?
                \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') : '-';
            $data->date_formatted = $data->date ?
                \Carbon\Carbon::parse($data->date)->format('Y-m-d') : '-';
        }

        return Excel::download(new \App\Exports\LmtsExport($datas), 'lmts_data.xlsx');
    }

    // LMTS Index: list data with joins similar to LPTS/Return Customer
    public function index(Request $request)
    {
        // Base query as provided
        $query = DB::table('lmts')
            ->leftJoin('good_receipt_notes as grn', 'lmts.id_good_receipt_notes', '=', 'grn.id')
            ->leftJoin('master_suppliers as ms', 'grn.id_master_suppliers', '=', 'ms.id')
            ->select([
                'lmts.id',
                'lmts.no_lmts',
                'lmts.receipt_number',
                'lmts.lot_number',
                'lmts.external_lot',
                'lmts.description',
                'lmts.date',
                'lmts.total_glq',
                'lmts.unit',
                'lmts.type_product',
                'lmts.status',
                'lmts.remarks',
                'lmts.button_active',
                'lmts.id_good_receipt_notes',
                'lmts.id_master_products', // tambahkan ini
                'grn.id_master_suppliers',
                'ms.name',
                'lmts.lmts_notes',
                'lmts.qty',
            ])
            ->orderByDesc('lmts.created_at');

        // Filters based on LMTS requirements
        if ($request->no_lmts) {
            $query->where('lmts.no_lmts', 'like', "%{$request->no_lmts}%");
        }
        if ($request->receipt_number) {
            $query->where('lmts.receipt_number', 'like', "%{$request->receipt_number}%");
        }
        if ($request->lot_number) {
            $query->where('lmts.lot_number', 'like', "%{$request->lot_number}%");
        }
        if ($request->description) {
            $query->where('lmts.description', 'like', "%{$request->description}%");
        }
        if ($request->type_product) {
            $query->where('lmts.type_product', 'like', "%{$request->type_product}%");
        }
        if ($request->date_from && $request->date_to) {
            $query->whereBetween('lmts.date', [$request->date_from, $request->date_to]);
        } elseif ($request->date_from) {
            $query->where('lmts.date', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->where('lmts.date', '<=', $request->date_to);
        }

        $datas = $query->get();

        return view('lmts.index', compact('datas'));
    }

    public function scrap(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'lmts_notes' => 'required|string|min:5',
            'scrap_date' => 'required|date',
        ], [
            'lmts_notes.required' => 'Catatan LMTS wajib diisi',
            'lmts_notes.min' => 'Catatan LMTS minimal 5 karakter',
            'scrap_date.required' => 'Tanggal scrap wajib diisi',
        ]);

        try {
            // Ambil data LMTS
            $lmts = DB::table('lmts')->where('id', $id)->first();

            if (!$lmts) {
                return back()->with('error', 'Data LMTS tidak ditemukan!');
            }

            // Cari master product berdasarkan id_master_products dari LMTS
            if (!$lmts->id_master_products) {
                return back()->with('error', 'LMTS tidak memiliki referensi master product!');
            }

            $masterProduct = DB::table('master_product_fgs')
                ->where('id', $lmts->id_master_products)
                ->first();

            if (!$masterProduct) {
                return back()->with('error', 'Master product tidak ditemukan!');
            }

            // Validasi stock - pastikan tidak minus (gunakan kolom 'stock' bukan 'qty')
            if (floatval($masterProduct->stock) < floatval($lmts->qty)) {
                return back()->with('error', 'Stock tidak mencukupi! Stock tersedia: ' . $masterProduct->stock . ', Stock yang akan di-scrap: ' . $lmts->qty);
            }

            // KONVERSI QTY DARI DECIMAL KE FORMAT YANG BENAR
            $qtyValue = floatval($lmts->qty); // Konversi ke float dulu
            // $qtyFormatted = number_format($qtyValue, 1, '.', ''); // Format ke 1 desimal tanpa koma ribuan


            // Update status LMTS menjadi 1 (Scrap) dan isi lmts_notes
            DB::table('lmts')->where('id', $id)->update([
                'status' => 1,
                'lmts_notes' => $request->lmts_notes,
                'updated_at' => now()
            ]);

            // Insert ke history_stocks
            DB::table('history_stocks')->insert([
                'id_good_receipt_notes_details' => $lmts->no_lmts, // sementara pakai no_lmts
                'usage_to' => null,
                'type_product' => $lmts->type_product,
                'id_master_products' => $lmts->id_master_products, // pakai dari LMTS langsung
                'qty' => $qtyValue, // gunakan qty yang sudah diformat
                'weight' => null, // kosongkan dulu
                'is_closed' => 1, // tandai sebagai closed
                'type_stock' => 'OUT',
                'date' => $request->scrap_date,
                'barcode' => null, // kosongkan
                'remarks' => 'Scrap dari LMTS: ' . $lmts->no_lmts . ' - ' . $request->lmts_notes,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update stock master_product_fgs (kurangi stock) berdasarkan id_master_products dari LMTS
            DB::table('master_product_fgs')
                ->where('id', $lmts->id_master_products)
                ->decrement('stock', $qtyValue);

            return back()->with('pesan', 'Data LMTS berhasil di-scrap dan stock telah dikurangi!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function return(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'return_reason' => 'required|string|min:5',
            'return_date' => 'required|date',
        ], [
            'return_reason.required' => 'Alasan return wajib diisi',
            'return_reason.min' => 'Alasan return minimal 5 karakter',
            'return_date.required' => 'Tanggal return wajib diisi',
        ]);

        try {
            // Ambil data LMTS
            $lmts = DB::table('lmts')->where('id', $id)->first();

            if (!$lmts) {
                return back()->with('error', 'Data LMTS tidak ditemukan!');
            }

            // Cari master product berdasarkan id_master_products dari LMTS
            if (!$lmts->id_master_products) {
                return back()->with('error', 'LMTS tidak memiliki referensi master product!');
            }

            $masterProduct = DB::table('master_product_fgs')
                ->where('id', $lmts->id_master_products)
                ->first();

            if (!$masterProduct) {
                return back()->with('error', 'Master product tidak ditemukan!');
            }

            // Validasi stock - pastikan tidak minus (gunakan kolom 'stock' bukan 'qty')
            if (floatval($masterProduct->stock) < floatval($lmts->qty)) {
                return back()->with('error', 'Stock tidak mencukupi! Stock tersedia: ' . $masterProduct->stock . ', Stock yang akan di-return: ' . $lmts->qty);
            }

            // KONVERSI QTY DARI DECIMAL KE FORMAT YANG BENAR
        $qtyValue = floatval($lmts->qty); // Konversi ke float dulu
        // $qtyFormatted = number_format($qtyValue, 1, '.', ''); // Format ke 1 desimal tanpa koma ribuan


            // Update status LMTS menjadi 2 (Return) dan isi lmts_notes
            DB::table('lmts')->where('id', $id)->update([
                'status' => 2,
                'lmts_notes' => $request->return_reason,
                'updated_at' => now()
            ]);

            // Insert ke history_stocks
            DB::table('history_stocks')->insert([
                'id_good_receipt_notes_details' => $lmts->no_lmts, // sementara pakai no_lmts
                'usage_to' => null,
                'type_product' => $lmts->type_product,
                'id_master_products' => $lmts->id_master_products, // pakai dari LMTS langsung
                'qty' => $qtyValue, // gunakan qty yang sudah diformat
                'weight' => null, // kosongkan dulu
                'is_closed' => 1, // tandai sebagai closed
                'type_stock' => 'OUT',
                'date' => $request->return_date,
                'barcode' => null, // kosongkan
                'remarks' => 'Return ke Supplier dari LMTS: ' . $lmts->no_lmts . ' - ' . $request->return_reason,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Update stock master_product_fgs (kurangi stock) berdasarkan id_master_products dari LMTS
            DB::table('master_product_fgs')
                ->where('id', $lmts->id_master_products)
                ->decrement('stock', $qtyValue);

            return back()->with('pesan', 'Data LMTS berhasil di-return ke supplier dan stock telah dikurangi!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function repair(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'repair_notes' => 'required|string|min:5',
        'repair_date' => 'required|date',
    ], [
        'repair_notes.required' => 'Catatan repair wajib diisi',
        'repair_notes.min' => 'Catatan repair minimal 5 karakter',
        'repair_date.required' => 'Tanggal repair wajib diisi',
    ]);

    try {
        // Ambil data LMTS
        $lmts = DB::table('lmts')->where('id', $id)->first();

        if (!$lmts) {
            return back()->with('error', 'Data LMTS tidak ditemukan!');
        }

        // Cek apakah masih status Hold (0)
        if ($lmts->status != 0) {
            return back()->with('error', 'Data LMTS sudah diproses sebelumnya!');
        }

        // Cari master product berdasarkan id_master_products dari LMTS
        if (!$lmts->id_master_products) {
            return back()->with('error', 'LMTS tidak memiliki referensi master product!');
        }

        $masterProduct = DB::table('master_product_fgs')
            ->where('id', $lmts->id_master_products)
            ->first();

        if (!$masterProduct) {
            return back()->with('error', 'Master product tidak ditemukan!');
        }

        // KONVERSI QTY DARI DECIMAL KE FORMAT YANG BENAR
        $qtyValue = floatval($lmts->qty); // Konversi ke float dulu
        // $qtyFormatted = number_format($qtyValue, 1, '.', ''); // Format ke 1 desimal tanpa koma ribuan

        // Update status LMTS menjadi 3 (Repair) dan isi lmts_notes
        DB::table('lmts')->where('id', $id)->update([
            'status' => 3,
            'lmts_notes' => $request->repair_notes,
            'updated_at' => now()
        ]);

        // Insert ke history_stocks dengan type_stock = 'IN' (barang repair masuk kembali ke inventory)
        DB::table('history_stocks')->insert([
            'id_good_receipt_notes_details' => $lmts->no_lmts, // sementara pakai no_lmts
            'usage_to' => null,
            'type_product' => $lmts->type_product,
            'id_master_products' => $lmts->id_master_products,
            'qty' => $qtyValue, // gunakan qty yang sudah diformat
            'weight' => null,
            'is_closed' => 1,
            'type_stock' => 'IN', // IN karena barang repair kembali masuk ke inventory
            'date' => $request->repair_date,
            'barcode' => null,
            'remarks' => 'Repair dari LMTS: ' . $lmts->no_lmts . ' - ' . $request->repair_notes,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Update stock master_product_fgs (TAMBAH stock) karena barang repair kembali ke inventory
        DB::table('master_product_fgs')
            ->where('id', $lmts->id_master_products)
            ->increment('stock', $qtyValue);

        return back()->with('pesan', 'Data LMTS berhasil di-repair dan stock telah ditambahkan kembali ke inventory!');

    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    public function unposted($id)
    {
        DB::table('lmts')->where('id', $id)->delete();

        return back()->with('pesan', 'Data LMTS berhasil dihapus!');
    }


    public function printLmts($id)
{
    // Ambil data LMTS
    $lmts = DB::table('lmts')->where('id', $id)->first();
    if (!$lmts) {
        return back()->with('error', 'Data LMTS tidak ditemukan!');
    }

    // Validasi: hanya bisa print jika sudah diproses (status != 0)
    if ($lmts->status == 0) {
        return back()->with('error', 'LMTS hanya bisa di-print setelah diproses (scrap/return/repair)!');
    }

    // Ambil data tambahan dari tabel referensi
    $data = DB::table('lmts')
        ->leftJoin('good_receipt_notes as grn', 'lmts.id_good_receipt_notes', '=', 'grn.id')
        ->leftJoin('master_suppliers as ms', 'grn.id_master_suppliers', '=', 'ms.id')
        ->select(
            'lmts.*',
            'ms.name as supplier_name',
        )
        ->first();

    if (!$data) {
        return back()->with('error', 'Data LMTS tidak lengkap!');
    }

    // Format tanggal untuk print
    $data->created_at_formatted = $data->created_at ? 
        \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') : '-';
    $data->date_formatted = $data->date ? 
        \Carbon\Carbon::parse($data->date)->format('d-m-Y') : '-';
   

    // Format status untuk checkbox
    $data->status_text = match($data->status) {
        1 => 'Scrap',
        2 => 'Return',
        3 => 'Repair',
        default => 'Hold'
    };
// PERBAIKAN: Replace karakter "/" dan "\" dengan "-" untuk nama file
    $safeFileName = str_replace(['/', '\\'], '-', $data->no_lmts);
    $pdf = Pdf::loadView('lmts.print', ['data' => $data]);
    return $pdf->stream('LMTS-' . $safeFileName . '.pdf');
}
}
