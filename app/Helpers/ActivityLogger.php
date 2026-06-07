<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(string $action, string $description): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
