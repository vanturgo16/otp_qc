<?php

namespace App\Http\Controllers\qc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Marketing\orderConfirmation;
use App\Models\Marketing\OrderConfirmationDetail;
class CoaController extends Controller
{
    public function index()
    {
        $query = DB::table('sales_orders as a')
            ->leftJoin('master_customers as b', 'a.id_master_customers', '=', 'b.id')
            ->leftJoin('master_salesmen as c', 'a.id_master_salesmen', '=', 'c.id')
            ->join(
                DB::raw(
                    '(SELECT id, product_code, description, id_master_units, \'FG\' as type_product, perforasi, weight 
                    FROM master_product_fgs WHERE status = \'Active\' 
                    UNION ALL 
                    SELECT id, wip_code as product_code, description, id_master_units, \'WIP\' as type_product, perforasi, weight 
                    FROM master_wips WHERE status = \'Active\' 
                    UNION ALL 
                    SELECT id, rm_code as product_code, description, id_master_units, \'RM\' as type_product, \'NULL\' as perforasi, weight 
                    FROM master_raw_materials WHERE status = \'Active\' 
                    UNION ALL 
                    SELECT id, code as product_code, description, id_master_units, \'AUX\' as type_product, \'NULL\' as perforasi, \'\' as weight 
                    FROM master_tool_auxiliaries) e'
                ), 
                function ($join) {
                    $join->on('a.id_master_products', '=', 'e.id');
                    $join->on('a.type_product', '=', 'e.type_product');
                }
            )
            ->join('master_units as f', 'a.id_master_units', '=', 'f.id')
            ->select(
                'a.id', 'a.id_order_confirmations', 'a.so_number', 'a.date', 'a.so_type', 'a.so_category', 
                'b.name as customer', 'c.name as salesman', 'a.reference_number', 'a.price', 'a.total_price', 
                'a.due_date', 'a.status', 'a.qty', 'a.outstanding_delivery_qty', 'e.product_code', 
                'e.description', 'f.unit_code', 'e.perforasi', 'e.weight'
            )
            ->whereNotNull('a.id_order_confirmations')
            // ->where('a.reference_number', '!=', '-')
            ->get();
    // dd($query);
        return view('coa.index', ['query' => $query]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'no_ko'                => 'required|string',
            'colour'                => 'required|string',
            'material'              => 'required|string',
            'id_work_centers'       => 'required|string',
            'thickness'             => 'required|numeric',
            'tensile_strength_md'   => 'required|numeric',
            'tensile_strength_td'   => 'required|numeric',
            'shrinkage_md'          => 'required|numeric',
            'shrinkage_td'          => 'required|numeric',
            'elongation_md'         => 'required|numeric',
            'elongation_td'         => 'required|numeric',
            'cof_static'            => 'required|numeric',
            'cof_kinetic'           => 'required|numeric',
        ]);

        // Store the data in the database
        DB::table('coas')->insert($validatedData);

        // return redirect()->back()->with('success', 'Data stored successfully!');
        return redirect('/coa')->with('success', 'Data stored successfully!');
    }
    public function show($id)
    {
        // Dekripsi data
        $oc_number = explode(',', Crypt::decryptString($id));

            $orderConfirmation = DB::table('sales_orders as a')
            ->leftJoin('master_customers as b', 'a.id_master_customers', '=', 'b.id')
            ->leftJoin('master_salesmen as c', 'a.id_master_salesmen', '=', 'c.id')
            ->join(
                DB::raw(
                    '(SELECT id, product_code, description, id_master_units, \'FG\' as type_product, perforasi, weight 
                    FROM master_product_fgs WHERE status = \'Active\' 
                    UNION ALL 
                    SELECT id, wip_code as product_code, description, id_master_units, \'WIP\' as type_product, perforasi, weight 
                    FROM master_wips WHERE status = \'Active\' 
                    UNION ALL 
                    SELECT id, rm_code as product_code, description, id_master_units, \'RM\' as type_product, \'NULL\' as perforasi, weight 
                    FROM master_raw_materials WHERE status = \'Active\' 
                    UNION ALL 
                    SELECT id, code as product_code, description, id_master_units, \'AUX\' as type_product, \'NULL\' as perforasi, \'\' as weight 
                    FROM master_tool_auxiliaries) e'
                ), 
                function ($join) {
                    $join->on('a.id_master_products', '=', 'e.id');
                    $join->on('a.type_product', '=', 'e.type_product');
                }
            )
            ->join('master_units as f', 'a.id_master_units', '=', 'f.id')
            ->select(
                'a.id', 'a.id_order_confirmations', 'a.so_number', 'a.date', 'a.so_type', 'a.so_category', 
                'b.name as customer', 'c.name as salesman', 'a.reference_number', 'a.price', 'a.total_price', 
                'a.due_date', 'a.status', 'a.qty', 'a.outstanding_delivery_qty', 'e.product_code', 
                'e.description', 'f.unit_code', 'e.perforasi', 'e.weight'
            )
            ->where('id_order_confirmations', $oc_number[0])
            ->first();

            $wc = DB::table('master_work_centers')->where('status', 'Active')->get();

        return view('coa.show_coa', compact('orderConfirmation','wc'));
    }


    
    public function print_coa()
    {
        return view('coa.print');
    }
    
   
}
