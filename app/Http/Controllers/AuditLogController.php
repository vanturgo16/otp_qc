<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    use AuditLogsTrait;

    public function index(){
        $logs = AuditLog::get();
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Audit Log';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('auditlog.index',compact('logs'));
    }
}
