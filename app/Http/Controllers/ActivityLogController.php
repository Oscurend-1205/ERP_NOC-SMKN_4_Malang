<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Halaman daftar activity log (Audit Trail).
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type (modul)
        if ($request->filled('model_type')) {
            $query->where('model_type', 'like', '%' . $request->model_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->paginate(20)->withQueryString();

        // Data untuk filter dropdown
        $users = User::orderBy('name')->get(['id', 'name', 'role']);
        $actions = ['created', 'updated', 'deleted', 'login', 'logout', 'exported'];
        $modelTypes = [
            'Item' => 'Barang',
            'Peminjaman' => 'Peminjaman',
            'Perawatan' => 'Perawatan',
            'ItemMovement' => 'Pergerakan Barang',
            'User' => 'Pengguna',
            'StockTake' => 'Stok Opname',
        ];

        return view('activity-log.index', compact('logs', 'users', 'actions', 'modelTypes'));
    }

    /**
     * Detail satu log entry.
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return view('activity-log.show', compact('log'));
    }

    /**
     * Export audit trail ke CSV.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(5000)->get();

        // Log the export action
        ActivityLog::record('exported', 'Mengekspor data Audit Trail (' . $logs->count() . ' record)');

        $filename = 'audit_trail_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['Waktu', 'User', 'Aksi', 'Modul', 'Deskripsi', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? 'Sistem',
                    $log->action_label,
                    $log->model_name,
                    $log->description,
                    $log->ip_address,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
