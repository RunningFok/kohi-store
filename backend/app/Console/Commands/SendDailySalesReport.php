<?php

namespace App\Console\Commands;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:report-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = config('notifications.admin_email');

        if (!$adminEmail) {
            $this->error('Admin email not configured. Please set ADMIN_EMAIL in your .env file.');
            return Command::FAILURE;
        }

        // Get today's orders
        $orders = Order::whereDate('created_at', today())
            ->with(['orderItems.product'])
            ->get();

        // Calculate totals
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $totalItemsSold = $orders->sum(function ($order) {
            return $order->orderItems->sum('quantity');
        });

        // Group products sold
        $productsSold = [];
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $productId = $item->product_id;
                $productName = $item->product->name ?? 'Unknown Product';
                
                if (!isset($productsSold[$productId])) {
                    $productsSold[$productId] = [
                        'name' => $productName,
                        'quantity' => 0,
                        'revenue' => 0,
                    ];
                }
                
                $productsSold[$productId]['quantity'] += $item->quantity;
                $productsSold[$productId]['revenue'] += $item->subtotal;
            }
        }

        // Prepare sales data
        $salesData = [
            'date' => today()->format('F j, Y'),
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_items_sold' => $totalItemsSold,
            'orders' => $orders,
            'products_sold' => array_values($productsSold),
        ];

        // Send email
        try {
            Mail::to($adminEmail)->send(new DailySalesReport($salesData));
            $this->info("Daily sales report sent successfully to {$adminEmail}");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to send daily sales report: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
