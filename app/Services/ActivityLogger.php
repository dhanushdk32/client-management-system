<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $module, $description)
    {
        $logData = [
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
        ];

        if (Auth::guard('admin')->check()) {
            $logData['admin_id'] = Auth::guard('admin')->id();
        } elseif (Auth::guard('client')->check()) {
            $user = Auth::guard('client')->user();
            $logData['user_id'] = $user->id;
            $logData['client_id'] = $user->client_id;
        }

        ActivityLog::create($logData);
    }
}
