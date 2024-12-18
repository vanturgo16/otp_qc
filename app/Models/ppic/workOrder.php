<?php

namespace App\Models\ppic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workOrder extends Model
{
    use HasFactory;
    protected $table = 'work_orders';
    protected $guarded = [
        'id'
    ];

    public function masterUnit()
    {
        return $this->belongsTo(\App\Models\MstUnits::class, 'id_master_units', 'id');
    }

    public function masterUnitNeeded()
    {
        return $this->belongsTo(\App\Models\MstUnits::class, 'id_master_units_needed', 'id');
    }

    public function masterProcessProduction()
    {
        return $this->belongsTo(\App\Models\MstProcessProductions::class, 'id_master_process_productions', 'id');
    }
    
    public function masterWorkCenter()
    {
        return $this->belongsTo(\App\Models\MstWorkCenters::class, 'id_master_work_centers', 'id');
    }

    public function workOrderDetails()
    {
        return $this->hasMany(\App\Models\ppic\workOrderDetails::class, 'id', 'id_work_orders');
    }
}
