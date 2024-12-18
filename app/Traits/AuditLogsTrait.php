<?php

namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\AuditLog;

trait AuditLogsTrait {
    public function auditLogs($username,$ipAddress,$location,$access_from,$activity)
    {
        $insert_auditLog=AuditLog::create([
            'username' => $username,
            'ip_address' => $ipAddress,
            'location' => $location,
            'access_from' => $access_from,
            'activity' => $activity
        ]);
    }
}