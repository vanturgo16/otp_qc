<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataSample;
use App\Models\Marketing\salesOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DataSampleController extends Controller
{
    // ...existing methods...

    public function printPdf($id_so)
    {
        $data = DB::table('sales_orders')
            ->leftJoin('data_samples', 'sales_orders.id', '=', 'data_samples.id_so')
            ->leftJoin('master_customers', 'sales_orders.id_master_customers', '=', 'master_customers.id')
            ->leftJoin('master_salesmen', 'sales_orders.id_master_salesmen', '=', 'master_salesmen.id')
            ->leftJoin('master_product_fgs', 'sales_orders.id_master_products', '=', 'master_product_fgs.id')
            ->leftJoin('master_units', 'sales_orders.id_master_units', '=', 'master_units.id')
            ->leftJoin(DB::raw('(SELECT barcodes.id_sales_orders, GROUP_CONCAT(barcode_number SEPARATOR ", ") as all_barcodes FROM barcode_detail LEFT JOIN barcodes ON barcode_detail.id_barcode = barcodes.id GROUP BY barcodes.id_sales_orders) as barcode_data'), 'sales_orders.id', '=', 'barcode_data.id_sales_orders')
            ->select([
                'sales_orders.id as id_so',
                'data_samples.id as id_sample',
                'sales_orders.so_number',
                'data_samples.no_sample',
                'sales_orders.date as request_date',
                'master_customers.name as customer_name',
                'master_salesmen.name as sales_name',
                DB::raw('concat(master_product_fgs.product_code, " - ", master_product_fgs.description) as product_item'),
                'master_product_fgs.product_code',
                'master_product_fgs.description',
                'sales_orders.so_category as type',
                'master_product_fgs.perforasi',
                'sales_orders.qty',
                'master_units.unit',
                'barcode_data.all_barcodes',
                'master_product_fgs.weight',
                'master_product_fgs.type_product',
                'data_samples.sample_submission_date',
                'data_samples.done_duration',
                'data_samples.sample_done_date',
                'data_samples.remarks',
                'data_samples.created_at',
            ])
            ->where('sales_orders.id', $id_so)
            ->first();

        $pdf = Pdf::loadView('sample.print_pdf', compact('data'));
        return $pdf->stream('surat_permintaan_sample.pdf');
    }
    public function index(Request $request)
    {
        $subqueryBarcodes = DB::table('barcode_detail')
            ->leftJoin('barcodes', 'barcode_detail.id_barcode', '=', 'barcodes.id')
            ->select(
                'barcodes.id_sales_orders',
                DB::raw('GROUP_CONCAT(barcode_number SEPARATOR ", ") as all_barcodes')
            )
            ->whereIn('barcode_detail.id_barcode', function ($query) {
                $query->select('id')
                    ->from('barcodes')
                    ->whereIn('id_sales_orders', function ($subquery) {
                        $subquery->select('id')
                            ->from('sales_orders')
                            ->where('so_type', 'Sample');
                    });
            })
            ->groupBy('barcodes.id_sales_orders');

        $dataSoSamplesQuery = DB::table('sales_orders')
            ->leftJoin('data_samples', 'sales_orders.id', '=', 'data_samples.id_so')
            ->leftJoin('master_customers', 'sales_orders.id_master_customers', '=', 'master_customers.id')
            ->leftJoin('master_salesmen', 'sales_orders.id_master_salesmen', '=', 'master_salesmen.id')
            ->leftJoin('master_product_fgs', 'sales_orders.id_master_products', '=', 'master_product_fgs.id')
            ->leftJoin('master_units', 'sales_orders.id_master_units', '=', 'master_units.id')
            ->leftJoinSub($subqueryBarcodes, 'barcode_data', function ($join) {
                $join->on('sales_orders.id', '=', 'barcode_data.id_sales_orders');
            })
            ->select([
                'sales_orders.id as id_so',
                'data_samples.id as id_sample',
                'sales_orders.so_number',
                'data_samples.no_sample',
                'sales_orders.date as request_date',
                'master_customers.name as customer_name',
                'master_salesmen.name as sales_name',
                DB::raw('concat(master_product_fgs.product_code, " - ", master_product_fgs.description) as product_item'),
                'sales_orders.so_category as type',
                'master_product_fgs.perforasi',
                'sales_orders.qty',
                'master_units.unit',
                'barcode_data.all_barcodes',
                'master_product_fgs.weight',
                'master_product_fgs.type_product',
                'data_samples.sample_submission_date',
                'data_samples.done_duration',
                'data_samples.sample_done_date',
                'data_samples.remarks',
                'data_samples.created_at',
            ])
            ->where('sales_orders.so_type', 'Sample');

        // Filter by no_sample
        if ($request->filled('no_sample')) {
            $dataSoSamplesQuery->where('data_samples.no_sample', 'like', '%' . $request->no_sample . '%');
        }
        // Filter by sample_type (so_category)
        if ($request->filled('sample_type')) {
            $dataSoSamplesQuery->where('sales_orders.so_category', 'like', '%' . $request->sample_type . '%');
        }

        $dataSoSamples = $dataSoSamplesQuery->orderBy('data_samples.no_sample', 'asc')->get();

        //count table data_samples id nya kalau table masih kosong set variable $no = 01
        $count = DB::table('data_samples')->count();
        if ($count == 0) {
            $no = '01';
        } else {
            $no = DB::table('data_samples')->max('id') + 1;
            $no = str_pad($no, 2, '0', STR_PAD_LEFT);
        }

        //format current date MM/YYYY comtoh 07/2025
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->format('m/Y');

        return view('sample.index', compact('dataSoSamples', 'no', 'formattedDate'));
    }

    public function update(Request $request, $id_so){
        //dd($request->all(), $id_so);
        //cek id_so duadh ada atau belum di table data samples
        $existingSample = DataSample::where('id_so', $id_so)->count();

        if ($existingSample > 0) {
            // Jika sudah ada, update data yang submission date
            $validatedData = $request->validate([
                'submission_date' => 'required'
            ]);

            DataSample::where('id_so', $id_so)->update([
                'sample_submission_date' => $request->input('submission_date')
            ]);

            return redirect()->route('sample.index')->with('success', 'Success Update Data Sample');
        }

        // Validate and update the data sample
        $validatedData = $request->validate([
            'no_sample' => 'required|string|max:255|unique:data_samples,no_sample',
            'remarks' => 'nullable|string|max:255',
        ]);

        $no_sample = $validatedData['no_sample'];
        $remarks = $validatedData['remarks'];
        $done_date = $request->input('done_date');
        $submission_date = $request->input('submission_date');   
        //done duration lama hari done_date sampai now
        $done_duration = $done_date ? now()->diffInDays($done_date) : 0;

        //begin transaction
        DB::beginTransaction();
        try {
            //insert ke table data samples
            DataSample::create([
                'id_so' => $id_so,
                'no_sample' => $no_sample,
                'remarks' => $remarks,
                'sample_done_date' => $done_date,
                'sample_submission_date' => $submission_date,
                'done_duration' => $done_duration,
            ]);

            DB::commit();
            return redirect()->route('sample.index')->with('success', 'Success Create Data Sample');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->route('sample.index')->with('error', 'Failed to Create Data Sample');
        }
    }
}
