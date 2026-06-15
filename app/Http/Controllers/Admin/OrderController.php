<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Requests\VerifyPaymentRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', [
            'orders' => Order::query()
                ->with(['user', 'payment', 'shipment'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['user', 'items', 'payment', 'shipment']),
        ]);
    }

    public function verifyPayment(VerifyPaymentRequest $request, Order $order): RedirectResponse
    {
        $payment = $order->payment()->firstOrFail();

        $payment->update([
            'status' => $request->string('status')->toString(),
            'notes' => $request->string('notes')->toString() ?: null,
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        if ($request->string('status')->toString() === 'verified') {
            $order->update(['status' => 'processing']);
        }

        return redirect()->route('admin.orders.show', $order)->with('status', 'Status pembayaran diperbarui.');
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $order->update([
            'status' => $request->string('order_status')->toString(),
        ]);

        $shipment = $order->shipment()->firstOrCreate([]);
        $shipmentStatus = $request->string('shipment_status')->toString();

        $shipment->update([
            'status' => $shipmentStatus,
            'courier' => $request->string('courier')->toString() ?: null,
            'tracking_number' => $request->string('tracking_number')->toString() ?: null,
            'shipped_at' => $shipmentStatus === 'shipped' && $shipment->shipped_at === null ? now() : $shipment->shipped_at,
            'delivered_at' => $shipmentStatus === 'delivered' && $shipment->delivered_at === null ? now() : $shipment->delivered_at,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('status', 'Status pesanan diperbarui.');
    }
}
