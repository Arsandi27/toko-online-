<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $cart = $this->activeCart($request)->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Keranjang masih kosong.']);
        }

        return view('checkout.create', [
            'cart' => $cart,
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = DB::transaction(function () use ($request): Order {
            $cart = $this->activeCart($request)->load('items.product');

            abort_if($cart->items->isEmpty(), 422, 'Keranjang masih kosong.');

            $subtotal = 0;

            foreach ($cart->items as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);

                if (! $product->is_active || $item->quantity > $product->stock) {
                    abort(422, "Stok {$product->name} tidak mencukupi.");
                }

                $subtotal += $product->price * $item->quantity;
            }

            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'order_number' => 'ORD-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'status' => 'pending_payment',
                'recipient_name' => $request->string('recipient_name')->toString(),
                'recipient_phone' => $request->string('recipient_phone')->toString(),
                'shipping_address' => $request->string('shipping_address')->toString(),
                'notes' => $request->string('notes')->toString() ?: null,
                'ordered_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);
                $product->decrement('stock', $item->quantity);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $product->price * $item->quantity,
                ]);
            }

            $order->payment()->create([
                'amount' => $order->total,
                'status' => 'waiting_proof',
            ]);

            $order->shipment()->create([
                'status' => 'pending',
            ]);

            $cart->update(['status' => 'checked_out']);

            return $order;
        });

        return redirect()->route('orders.show', $order)->with('status', 'Checkout berhasil. Silakan upload bukti pembayaran.');
    }

    private function activeCart(Request $request): Cart
    {
        return Cart::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'status' => 'active',
        ]);
    }
}
