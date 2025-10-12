<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LmtsSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama LMTS saja
        DB::table('lmts')->truncate();

        // AMBIL DATA DARI TABEL YANG SUDAH ADA
        $existingData = DB::table('good_receipt_note_details as grnd')
            ->leftJoin('good_receipt_notes as grn', 'grnd.id_good_receipt_notes', '=', 'grn.id')
            ->leftJoin('master_product_fgs as mpf', 'grnd.id_master_products', '=', 'mpf.id')
            ->leftJoin('master_units as mu', 'mpf.id_master_units', '=', 'mu.id')
            ->select([
                'grnd.id as grnd_id',
                'grn.id as grn_id',
                'grn.receipt_number',        // dari good_receipt_notes
                'grnd.lot_number',                         // dari good_receipt_note_details
                'grnd.external_no_lot',                       // dari good_receipt_note_details
                'grnd.qty',                               // dari good_receipt_note_details
                'grnd.type_product',                      // dari good_receipt_note_details
                'mpf.description',                        // dari master_product_fgs
                'mu.unit_code',                               // dari master_units
                'mpf.id as master_product_id'
            ])
            ->whereNotNull('grnd.lot_number')
            ->limit(15) // Ambil maksimal 15 data
            ->get();

        if ($existingData->isEmpty()) {
            echo "❌ Tidak ditemukan data yang valid di tabel existing.\n";
            echo "💡 Pastikan ada data di good_receipt_note_details dengan lot_number\n";
            return;
        }

        echo "✅ Ditemukan " . $existingData->count() . " data existing yang akan digunakan\n";

        $lmtsData = [];
        $counter = 1;

        foreach ($existingData as $data) {
            $bulanRomawi = $this->toRoman(now()->format('n'));
            $tahun = now()->format('y');
            $noUrut = str_pad($counter, 3, '0', STR_PAD_LEFT);
            
            // SEMUA STATUS HOLD (0)
            $status = 0; // 0=Hold (semua data status Hold)
            
            // Button active untuk status Hold - semua aktif
            $buttonActive = $this->getButtonActive($status);

            $lmtsData[] = [
                // Data dari tabel existing
                'receipt_number' => $data->receipt_number,    // dari good_receipt_notes
                'lot_number' => $data->lot_number,           // dari good_receipt_note_details
                'external_lot' => $data->external_no_lot,       // dari good_receipt_note_details
                'description' => $data->description,         // dari master_product_fgs
                'qty' => $data->qty,                        // dari good_receipt_note_details
                'total_glq' => $data->qty,            // dari good_receipt_note_details
                'type_product' => $data->type_product,       // dari good_receipt_note_details
                'unit' => $data->unit_code ?? 'KG',             // dari master_units
                'id_good_receipt_notes' => $data->grn_id,
                'id_good_receipt_notes_details' => $data->grnd_id,
                'id_master_products' => $data->master_product_id,
                
                // Data dummy
                'no_lmts' => "{$noUrut}/Q&D/LMTS/{$bulanRomawi}/{$tahun}",
                'date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'status' => $status, // Semua Hold
                'remarks' => $this->getRandomRemarks($status), // Remarks untuk Hold
                'button_active' => json_encode($buttonActive),
                'lmts_notes' => null, // Null karena masih Hold
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            $counter++;
        }

        // Insert data LMTS dengan data real dari tabel existing
        DB::table('lmts')->insert($lmtsData);

        // Hitung breakdown per type
        $countByType = collect($lmtsData)->groupBy('type_product');
        
        echo "✅ LMTS dummy data berhasil dibuat: " . count($lmtsData) . " records\n";
        echo "📋 Semua data memiliki status: HOLD (0)\n";
        echo "🔘 Button Active: Return, Repair, Scrap semua aktif\n";
        echo "📋 Breakdown by Type Product:\n";
        foreach ($countByType as $type => $items) {
            echo "   - {$type}: " . count($items) . " records\n";
        }
        echo "🔗 Data diambil dari tabel existing yang valid\n";
    }

   private function getButtonActive($status)
{
    switch ($status) {
        case 0: // Hold - semua button aktif (bisa dipilih action)
            return [
                'is_return' => 1,
                'is_repair' => 1,
                'is_scrap' => 1
            ];
            
        case 1: // Scrap - sudah di-scrap, semua button non-aktif
            return [
                'is_return' => 0,
                'is_repair' => 0,
                'is_scrap' => 0
            ];
            
        case 2: // Return - sudah di-return, semua button non-aktif
            return [
                'is_return' => 0,
                'is_repair' => 0,
                'is_scrap' => 0
            ];
            
        case 3: // Repair - sudah di-repair, semua button non-aktif
            return [
                'is_return' => 0,
                'is_repair' => 0,
                'is_scrap' => 0
            ];
            
        default: // Default untuk Hold
            return [
                'is_return' => 1,
                'is_repair' => 1,
                'is_scrap' => 1
            ];
    }
}

    private function toRoman($number) {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$number] ?? 'I';
    }

    private function getRandomRemarks($status) {
        $remarksByStatus = [
            0 => [ // Hold - remarks untuk status Hold
                'Material inspection pending',
                'Awaiting chemical composition test',
                'Initial inspection in progress',
                'Coating thickness verification needed',
                'Dimensional inspection scheduled',
                'Waiting for quality control approval',
                'Pending laboratory test results',
                'Material verification in process'
            ],
            1 => [ // Scrap
                'Quality issue detected',
                'Failed pressure test',
                'Material defect found'
            ],
            2 => [ // Return
                'Returned to supplier',
                'Certification issue',
                'Specification mismatch'
            ],
            3 => [ // Repair
                'Minor repair completed',
                'Surface treatment applied',
                'Dimensional correction done'
            ]
        ];
        
        $remarks = $remarksByStatus[$status] ?? $remarksByStatus[0];
        return $remarks[array_rand($remarks)];
    }

    private function getRandomNotes($status) {
        $notesByStatus = [
            1 => [ // Scrap
                'Material tidak sesuai spesifikasi, terdapat cacat pada permukaan',
                'Gagal uji tekanan, material tidak memenuhi standar ASTM',
                'Kualitas material dibawah standar yang dibutuhkan'
            ],
            2 => [ // Return
                'Dimensi tidak sesuai purchase order, dikembalikan ke supplier',
                'Sertifikat material tidak lengkap, dikembalikan ke supplier untuk kelengkapan dokumen',
                'Material tidak sesuai spesifikasi yang diminta'
            ],
            3 => [ // Repair
                'Minor surface coating repair dilakukan di workshop',
                'Perbaikan dimensi telah selesai dilakukan',
                'Treatment tambahan untuk memenuhi spesifikasi'
            ]
        ];
        
        $notes = $notesByStatus[$status] ?? ['Standard notes'];
        return $notes[array_rand($notes)];
    }
}