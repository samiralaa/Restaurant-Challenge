<?php

namespace App\Jobs;

use App\Services\RevenueManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTotalRevenueReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 50;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public int $maxExceptions = 5;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @throws RequestException
     */
    public function handle(): void
    {
        $verificationResponse = $this->postVerification();

        $reportResponse = $this->postReport($verificationResponse);

        $this->postReportConfirmation($reportResponse);
    }

    /**
     * Perform HTTP POST to verification endpoint.
     *
     * @throws RequestException
     */
    private function postVerification(): array
    {
        return Http::post('https://revenue-verifier.com')->throw()->json();
    }

    /**
     * Perform HTTP POST to report endpoint.
     *
     * @param array $verificationResponse
     *
     * @return array
     * @throws RequestException
     */
    private function postReport(array $verificationResponse): array
    {
        return Http::post('https://revenue-reporting.com/reports', [
            'verification_id' => $verificationResponse['id'],
            'total_revenue' => RevenueManager::calculateTotalRevenue(),
        ])->throw()->json();
    }

    /**
     * Perform HTTP POST to report confirmation endpoint.
     *
     * @param array $reportResponse
     *
     * @return array
     * @throws RequestException
     */
    private function postReportConfirmation(array $reportResponse): array
    {
        return Http::post('https://revenue-reporting.com/reports/confirm', [
            'report_id' => $reportResponse['id'],
            'timestamp' => now()->timestamp,
        ])->throw()->json();
    }
}
