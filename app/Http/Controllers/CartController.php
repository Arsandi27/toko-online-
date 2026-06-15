<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        return view('cart.index', [
            'cart' => $this->activeCart($request)->load('items.product'),
        ]);
    }

    public function store(StoreCartItemRequest $request): RedirectResponse
    {
        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($request->integer('product_id'));

        $cart = $this->activeCart($request);
        $item = $cart->items()->firstOrNew(['product_id' => $product->id]);
        $newQuantity = (int) $item->quantity + $request->integer('quantity');

        if ($newQuantity > $product->stock) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi stok produk.']);
        }

        $item->fill([
            'quantity' => $newQuantity,
            'unit_price' => $product->price,
        ])->save();

        return redirect()->route('cart.index')->with('status', 'Produk ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);

        if ($request->integer('quantity') > $cartItem->product->stock) {
            return back()->withErrors(['quantity' => 'Jumlah melebihi stok produk.']);
        }

        $cartItem->update([
            'quantity' => $request->integer('quantity'),
            'unit_price' => $cartItem->product->price,
        ]);

        return redirect()->route('cart.index')->with('status', 'Keranjang diperbarui.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeCartItem($request, $cartItem);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('status', 'Item dihapus dari keranjang.');
    }

    private function activeCart(Request $request): Cart
    {
        return Cart::query()->firstOrCreate([
            'user_id' => $request->user()->id,
            'status' => 'active',
        ]);
    }

    private function authorizeCartItem(Request $request, CartItem $cartItem): void
    {
        abort_unless(
            $cartItem->cart()->where('user_id', $request->user()->id)->where('status', 'active')->exists(),
            403,
        );
    }
}
