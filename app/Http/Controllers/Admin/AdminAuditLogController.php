<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $action = $request->query('action');
        $query = AdminAuditLog::with('admin.profile');

        if ($action) {
            $query->where('action', 'like', "%{$action}%");
        }

        $logs = $query->latest('id')->paginate(25)->withQueryString();

        return view('admin.audit-logs.index', compact('logs', 'action'));
    }
}
