<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lmts;
use Maatwebsite\Excel\Facades\Excel;

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
                'lmts.id_master_products', // tambahkan ini
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
                'qty' => $lmts->qty,
                'weight' => null, // kosongkan dulu
                'is_closed' => null,// kosongkan dulu
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
                ->decrement('stock', floatval($lmts->qty));

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
                'qty' => $lmts->qty,
                'weight' => null, // kosongkan dulu
                'is_closed' => null,// kosongkan dulu
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
                ->decrement('stock', floatval($lmts->qty));

            return back()->with('pesan', 'Data LMTS berhasil di-return ke supplier dan stock telah dikurangi!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function unposted($id)
    {
        DB::table('lmts')->where('id', $id)->delete();

        return back()->with('pesan', 'Data LMTS berhasil dihapus!');
    }
}
