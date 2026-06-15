<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        if ($request->user()->hasRole('Admin')) {
            return view('admin.dashboard', [
                'totalProducts' => Product::query()->count(),
                'pendingPayments' => Order::query()->where('status', 'pending_payment')->count(),
                'activeOrders' => Order::query()->whereIn('status', ['processing', 'shipped'])->count(),
                'recentOrders' => Order::query()
                    ->with(['user', 'payment', 'shipment'])
                    ->latest()
                    ->limit(5)
                    ->get(),
            ]);
        }

        return view('customer.dashboard', [
            'recentOrders' => $request->user()
                ->orders()
                ->with(['payment', 'shipment'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
