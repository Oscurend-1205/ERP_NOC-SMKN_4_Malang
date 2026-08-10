<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StLogController extends Controller
{
    /**
     * Endpoint penerima data stealth log dari instance mtracker.
     */
    public function store(Request $request): JsonResponse
    {
        Log::channel('single')->info('MTracker stealth access', [
            'visitor_ip' => $request->input('visitor_ip'),
            'target_ip' => $request->input('target_ip'),
            'trace_source' => $request->input('trace_source'),
            'hops' => $request->input('hops'),
            'user_agent' => $request->input('user_agent'),
            'referer' => $request->input('referer'),
            'tracked_at' => $request->input('tracked_at'),
        ]);

        return response()->json(['status' => 'ok'], 200);
    }
}
