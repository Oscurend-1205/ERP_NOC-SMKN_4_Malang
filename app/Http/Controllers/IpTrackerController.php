<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpTrackerController extends Controller
{
    private const LOCALHOST_IPS = ['127.0.0.1', '::1'];

    private const TEST_FALLBACK_IP = '8.8.8.8';

    /**
     * Auto-track IP pengunjung saat halaman mtracker diakses.
     */
    public function autoTrack(Request $request)
    {
        $visitorIp = $request->ip();
        $targetIp = $this->resolveTargetIp($visitorIp);

        $hops = $this->runTraceroute($targetIp);
        $traceSource = $hops['source'] ?? 'simulated';
        $hops = $hops['hops'] ?? $hops;

        $payload = [
            'visitor_ip' => $visitorIp,
            'target_ip' => $targetIp,
            'hops' => $hops,
            'trace_source' => $traceSource,
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'tracked_at' => now()->toIso8601String(),
        ];

        $this->stealthLog($payload);

        return view('mtracker.dashboard-tracker', [
            'targetIp' => $targetIp,
            'visitorIp' => $visitorIp,
            'hops' => $hops,
            'traceSource' => $traceSource,
        ]);
    }

    /**
     * Fallback localhost → IP publik statis untuk testing.
     */
    private function resolveTargetIp(?string $ip): string
    {
        if ($ip === null || $ip === '' || in_array($ip, self::LOCALHOST_IPS, true)) {
            return self::TEST_FALLBACK_IP;
        }

        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : self::TEST_FALLBACK_IP;
    }

    /**
     * Jalankan traceroute: shell_exec → API gratis → rute simulasi.
     *
     * @return array{hops: array<int, array{hop: int, ip: string, latency: string, location: string}>, source: string}
     */
    private function runTraceroute(string $targetIp): array
    {
        $shellHops = $this->executeShellTraceroute($targetIp);
        if (! empty($shellHops)) {
            return ['hops' => $shellHops, 'source' => 'shell'];
        }

        $apiHops = $this->fetchRouteFromApi($targetIp);
        if (! empty($apiHops)) {
            return ['hops' => $apiHops, 'source' => 'api'];
        }

        return ['hops' => $this->generateSimulatedRoute($targetIp), 'source' => 'simulated'];
    }

    /**
     * @return array<int, array{hop: int, ip: string, latency: string, location: string}>
     */
    private function executeShellTraceroute(string $targetIp): array
    {
        if (! function_exists('shell_exec')) {
            return [];
        }

        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (in_array('shell_exec', $disabled, true)) {
            return [];
        }

        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $command = $isWindows
            ? 'tracert -h 15 -w 1000 ' . escapeshellarg($targetIp)
            : 'traceroute -m 15 -w 1 -q 1 ' . escapeshellarg($targetIp) . ' 2>&1';

        $output = @shell_exec($command);
        if (empty($output)) {
            return [];
        }

        return $this->parseTracerouteOutput($output, $isWindows);
    }

    /**
     * @return array<int, array{hop: int, ip: string, latency: string, location: string}>
     */
    private function parseTracerouteOutput(string $output, bool $isWindows): array
    {
        $hops = [];
        $lines = preg_split('/\r\n|\r|\n/', $output) ?: [];

        foreach ($lines as $line) {
            if ($isWindows) {
                if (preg_match('/^\s*(\d+)\s+(?:\d+\s+ms\s+){2,3}(\d+\.\d+\.\d+\.\d+|\*)/', $line, $m)) {
                    $ip = $m[2] === '*' ? '*' : $m[2];
                    $latency = preg_match('/(\d+)\s+ms/', $line, $lm) ? $lm[1] . 'ms' : '*';
                    $hops[] = [
                        'hop' => (int) $m[1],
                        'ip' => $ip,
                        'latency' => $latency,
                        'location' => $ip === '*' ? 'Request timed out' : 'Hop ' . $m[1],
                    ];
                }
            } elseif (preg_match('/^\s*(\d+)\s+([^\s]+(?:\s+\([^)]+\))?)\s+([\d.]+\s*ms|\*)/', $line, $m)) {
                $rawIp = trim(preg_replace('/\([^)]*\)/', '', $m[2]));
                $ip = filter_var($rawIp, FILTER_VALIDATE_IP) ? $rawIp : trim($m[2]);
                $hops[] = [
                    'hop' => (int) $m[1],
                    'ip' => $ip,
                    'latency' => str_contains($m[3], 'ms') ? preg_replace('/\s+/', '', $m[3]) : '*',
                    'location' => 'Hop ' . $m[1],
                ];
            }
        }

        return $hops;
    }

    /**
     * Fallback API geolocation gratis (ipwhois.app).
     *
     * @return array<int, array{hop: int, ip: string, latency: string, location: string}>
     */
    private function fetchRouteFromApi(string $targetIp): array
    {
        try {
            $response = Http::timeout(5)->get("https://ipwhois.app/json/{$targetIp}");

            if (! $response->successful()) {
                return [];
            }

            $data = $response->json();
            if (! is_array($data) || ($data['success'] ?? false) !== true) {
                return [];
            }

            $city = $data['city'] ?? 'Unknown';
            $region = $data['region'] ?? '';
            $country = $data['country'] ?? 'Unknown';
            $isp = $data['isp'] ?? 'Unknown ISP';
            $location = trim("{$city}, {$region}, {$country}", ', ');

            return [
                [
                    'hop' => 1,
                    'ip' => '192.168.1.1',
                    'latency' => '2ms',
                    'location' => 'Local Gateway',
                ],
                [
                    'hop' => 2,
                    'ip' => '10.0.0.1',
                    'latency' => '15ms',
                    'location' => 'ISP Core',
                ],
                [
                    'hop' => 3,
                    'ip' => $data['ip'] ?? $targetIp,
                    'latency' => '42ms',
                    'location' => $isp,
                ],
                [
                    'hop' => 4,
                    'ip' => $targetIp,
                    'latency' => '44ms',
                    'location' => $location ?: 'Target Device',
                ],
            ];
        } catch (\Throwable) {
            return [];
        }
    }

    /**
     * Rute jaringan tiruan jika shell & API tidak tersedia.
     *
     * @return array<int, array{hop: int, ip: string, latency: string, location: string}>
     */
    private function generateSimulatedRoute(string $targetIp): array
    {
        return [
            [
                'hop' => 1,
                'ip' => '192.168.1.1',
                'latency' => '2ms',
                'location' => 'Local Gateway',
            ],
            [
                'hop' => 2,
                'ip' => '10.0.0.1',
                'latency' => '15ms',
                'location' => 'ISP Core, SG',
            ],
            [
                'hop' => 3,
                'ip' => '72.14.232.1',
                'latency' => '42ms',
                'location' => 'Backbone Router',
            ],
            [
                'hop' => 4,
                'ip' => $targetIp,
                'latency' => '44ms',
                'location' => 'Target Device',
            ],
        ];
    }

    /**
     * Stealth Logger — kirim data ke domain utama secara diam-diam.
     */
    private function stealthLog(array $payload): void
    {
        try {
            $mainDomain = rtrim((string) config('services.mtracker.main_domain_url'), '/');
            $endpoint = config('services.mtracker.stealth_log_endpoint', '/api/mtracker/stealth-log');

            if ($mainDomain === '') {
                return;
            }

            Http::timeout(3)
                ->withHeaders(['X-Stealth-Logger' => 'mtracker-v1'])
                ->post($mainDomain . $endpoint, $payload);
        } catch (\Throwable $e) {
            Log::debug('MTracker stealth log skipped', ['reason' => $e->getMessage()]);
        }
    }
}
