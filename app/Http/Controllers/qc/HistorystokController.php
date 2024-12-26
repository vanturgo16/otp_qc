<?php

namespace App\Http\Controllers\qc;
use App\Traits\AuditLogsTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HistoryStock;
use Illuminate\Support\Facades\DB;
class HistorystokController extends Controller
{
    use AuditLogsTrait;

    // public function index()
    // {
    //     $rm = HistoryStock::select(
    //         'history_stocks.type_product',
    //         'history_stocks.id_master_products',
    //         'master_raw_materials.rm_code',
    //         'master_raw_materials.description',
    //         'master_raw_materials.stock',
    //         DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_in'),
    //         DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_out'),
    //         'master_raw_materials.id_master_departements',
    //         'master_departements.name as departement_name'
    //     )
    //         ->leftjoin('master_raw_materials', 'history_stocks.id_master_products', 'master_raw_materials.id')
    //         ->leftjoin('master_departements', 'master_raw_materials.id_master_departements', 'master_departements.id')
    //         ->where('history_stocks.type_product', "RM")
    //         ->groupBy(
    //             'history_stocks.type_product',
    //             'history_stocks.id_master_products',
    //             'master_raw_materials.rm_code',
    //             'master_raw_materials.description',
    //             'master_raw_materials.stock',
    //             'master_raw_materials.id_master_departements',
    //             'master_departements.name'
    //         )
    //         ->get();
    //     $wip = HistoryStock::select(
    //             'history_stocks.type_product',
    //             'history_stocks.id_master_products',
    //             'master_wips.wip_code',
    //             'master_wips.description',
    //             'master_wips.perforasi',
    //             'master_wips.stock',
    //             'master_wips.id_master_departements',
    //             'master_departements.name as departement_name'
    //         )
    //             ->leftjoin('master_wips', 'history_stocks.id_master_products', 'master_wips.id')
    //             ->leftjoin('master_departements', 'master_wips.id_master_departements', 'master_departements.id')
    //             ->where('history_stocks.type_product', "WIP")
    //             ->groupBy(
    //                 'history_stocks.type_product',
    //                 'history_stocks.id_master_products',
    //                 'master_wips.wip_code',
    //                 'master_wips.description',
    //                 'master_wips.perforasi',
    //                 'master_wips.stock',
    //                 'master_wips.id_master_departements',
    //                 'master_departements.name'
    //             )
    //             ->get();

    //             $fg = HistoryStock::select(
    //                 'history_stocks.type_product',
    //                 'history_stocks.id_master_products',
    //                 'master_product_fgs.product_code',
    //                 'master_product_fgs.description',
    //                 'master_product_fgs.perforasi',
    //                 'master_product_fgs.stock',
    //                 'master_product_fgs.id_master_departements',
    //                 'master_departements.name as departement_name'
    //             )
    //                 ->leftjoin('master_product_fgs', 'history_stocks.id_master_products', 'master_product_fgs.id')
    //                 ->leftjoin('master_departements', 'master_product_fgs.id_master_departements', 'master_departements.id')
    //                 ->where('history_stocks.type_product', "FG")
    //                 ->groupBy(
    //                     'history_stocks.type_product',
    //                     'history_stocks.id_master_products',
    //                     'master_product_fgs.product_code',
    //                     'master_product_fgs.description',
    //                     'master_product_fgs.perforasi',
    //                     'master_product_fgs.stock',
    //                     'master_product_fgs.id_master_departements',
    //                     'master_departements.name'
    //                 )
    //                 ->get();

    //                 $ta = HistoryStock::select(
    //                     'history_stocks.type_product',
    //                     'history_stocks.id_master_products',
    //                     'master_tool_auxiliaries.code',
    //                     'master_tool_auxiliaries.description',
    //                     'master_tool_auxiliaries.stock',
    //                     DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "TA" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_in'),
    //                     DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "TA" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_out'),
    //                     'master_tool_auxiliaries.id_master_departements',
    //                     'master_departements.name as departement_name'
    //                 )
    //                     ->leftjoin('master_tool_auxiliaries', 'history_stocks.id_master_products', 'master_tool_auxiliaries.id')
    //                     ->leftjoin('master_departements', 'master_tool_auxiliaries.id_master_departements', 'master_departements.id')
    //                     ->where('history_stocks.type_product', "TA")
    //                     ->groupBy(
    //                         'history_stocks.type_product',
    //                         'history_stocks.id_master_products',
    //                         'master_tool_auxiliaries.code',
    //                         'master_tool_auxiliaries.description',
    //                         'master_tool_auxiliaries.stock',
    //                         'master_tool_auxiliaries.id_master_departements',
    //                         'master_departements.name'
    //                     )
    //                     ->get();
    //     return view('histori.index',compact('rm','wip','fg','ta'));
    // }

    public function index()
{
    $types = [
        'RM' => ['table' => 'master_raw_materials', 'extra_fields' => ['rm_code as code', 'description', 'stock']],
        'WIP' => ['table' => 'master_wips', 'extra_fields' => ['wip_code as code', 'description', 'perforasi', 'stock']],
        'FG' => ['table' => 'master_product_fgs', 'extra_fields' => ['product_code as code', 'description', 'perforasi', 'stock']],
        'TA' => ['table' => 'master_tool_auxiliaries', 'extra_fields' => ['code', 'description', 'stock']],
    ];

    $results = [];

    foreach ($types as $typeKey => $typeValue) {
        $query = HistoryStock::select(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            DB::raw('CAST(SUM(CASE WHEN history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_in'),
            DB::raw('CAST(SUM(CASE WHEN history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_out'),
            'master_departements.name as departement_name'
        )
        ->leftJoin($typeValue['table'], 'history_stocks.id_master_products', '=', $typeValue['table'] . '.id')
        ->leftJoin('master_departements', $typeValue['table'] . '.id_master_departements', '=', 'master_departements.id')
        ->where('history_stocks.type_product', $typeKey)
        ->groupBy(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            'master_departements.name'
        );

        foreach ($typeValue['extra_fields'] as $field) {
            $query->addSelect($typeValue['table'] . '.' . $field);
        }

        $results[$typeKey] = $query->get();
    }

    return view('histori.index', compact('results'));
}


}
