<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPaymentProofRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()
                ->orders()
                ->with(['payment', 'shipment'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);

        return view('orders.show', [
            'order' => $order->load(['items', 'payment', 'shipment']),
        ]);
    }

    public function uploadPaymentProof(UploadPaymentProofRequest $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        $payment = $order->payment()->firstOrFail();

        if ($payment->proof_path) {
            Storage::disk('public')->delete($payment->proof_path);
        }

        $proofPath = $request->file('proof')->store('payment-proofs', 'public');

        $payment->update([
            'proof_path' => $proofPath,
            'status' => 'uploaded',
            'uploaded_at' => now(),
            'notes' => null,
        ]);

        return redirect()->route('orders.show', $order)->with('status', 'Bukti pembayaran berhasil diupload.');
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        abort_unless($order->user_id === $request->user()->id, 403);
    }
}
