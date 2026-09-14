<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $logs = AuditLog::query()
            ->with(['event.scenario', 'user'])
            ->when($request->query('action'), fn ($q, $action) => $q->where('action', $action))
            ->when($request->query('from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->query('to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->get();

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Fecha', 'Acción', 'Escenario', 'Usuario', 'Descripción']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->action,
                    $log->event?->scenario?->name ?? '—',
                    $log->user?->name ?? '—',
                    $log->description,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'auditoria.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
