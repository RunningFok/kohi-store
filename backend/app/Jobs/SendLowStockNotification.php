<?php

namespace App\Jobs;

use App\Mail\LowStockNotification;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendLowStockNotification implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    public function __construct(
        public Product $product
    ) {
        //
    }

    public function handle(): void
    {
        try {
            Log::info('SendLowStockNotification: Starting job', [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name ?? 'Unknown',
            ]);

            $adminEmail = config('notifications.admin_email');
            $threshold = config('notifications.low_stock_threshold', 20);

            if (!$adminEmail) {
                Log::warning('SendLowStockNotification: Admin email not configured', [
                    'product_id' => $this->product->id,
                ]);
                return;
            }

            // Refresh the product to ensure we have the latest data
            $this->product->refresh();

            Log::info('SendLowStockNotification: Attempting to send email', [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
                'stock_quantity' => $this->product->stock_quantity,
                'threshold' => $threshold,
                'admin_email' => $adminEmail,
                'mail_mailer' => config('mail.default'),
            ]);

            Mail::to($adminEmail)->send(
                new LowStockNotification($this->product, $threshold)
            );

            Log::info('SendLowStockNotification: Email sent successfully', [
                'product_id' => $this->product->id,
                'admin_email' => $adminEmail,
            ]);
        } catch (Throwable $e) {
            Log::error('SendLowStockNotification: Failed to send email', [
                'product_id' => $this->product->id ?? 'Unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('SendLowStockNotification: Job failed permanently', [
            'product_id' => $this->product->id ?? 'Unknown',
            'error' => $exception->getMessage(),
        ]);
    }
}
