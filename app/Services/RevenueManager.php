<?php

namespace App\Services;

use App\Models\Order;

class RevenueManager
{
    /**
     * Calculate total revenue for all orders.
     *
     * @return float
     */
    public static function calculateTotalRevenue(): float
    {
        $totalRevenue = 0.0;

        Order::query()->chunk(100, function ($orders) use (&$totalRevenue) {
            $orders->each(function ($order) use (&$totalRevenue) {
                $order->items()->each(function ($orderItem) use (&$totalRevenue) {
                    $totalRevenue += $orderItem->quantity * $orderItem->price;
                });
            });
        });

        return $totalRevenue;
    }
}
