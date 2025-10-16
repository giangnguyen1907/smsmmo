<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Service;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchBossOtpServices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // Số lần thử nếu thất bại
    public $timeout = 120; // Thời gian timeout job

    /**
     * Execute the job.
     */
    public function handle(): void
    {   
        $apiUrl = 'https://bossotp.net/api/v4/service-manager/services';
        $apiKey = config('services.bossotp.key'); // Thêm key vào config/services.php

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->timeout(60)->get($apiUrl);

            if (!$response->successful()) {
                Log::error('BossOTP API fetch failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return;
            }

            $services = $response->json();

            if (!is_array($services)) {
                Log::error('BossOTP API returned invalid data', [
                    'data' => $services,
                ]);
                return;
            }

            $count = 0;

            foreach ($services as $item) {
                // if (!isset($item['name'])) continue; // Bỏ qua nếu không có name
                // echo $item['name'].'<br>'; 
                Service::updateOrCreate(
                    ['name' => $item['name']], // unique key
                    [    
                        'service_id' => $item['_id'],
                        'status' => 1,
                        'description' => $item['description'] ?? null,
                        'price_per_unit' => $item['price'] ?? 0,
                        'duration_minutes' => $item['timeout'] ?? 0,
                    ]
                );

                $count++;
            } 

            Log::info("BossOTP: Imported/Updated {$count} services.");

        } catch (\Exception $e) {
            Log::error('BossOTP API fetch exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
