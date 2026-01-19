<?php

namespace App\Jobs;

use App\Mail\LowStockNotification;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendLowStockNotification implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public Product $product
    ) {
        //
    }

    public function handle(): void
    {
        $adminEmail = config('notifications.admin_email');
        $threshold = config('notifications.low_stock_threshold');

        if (!$adminEmail) {
            \Log::warning('Admin email not configured. Cannot send low stock notification.');
            return;
        }

        Mail::to($adminEmail)->send(
            new LowStockNotification($this->product, $threshold)
        );
    }
}
