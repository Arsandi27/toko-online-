<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Keranjang Belanja</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif
            <x-input-error :messages="$errors->all()" />

            <div class="bg-white shadow sm:rounded-lg">
                @php($total = $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price))
                <div class="divide-y divide-gray-100">
                    @forelse ($cart->items as $item)
                        <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-[1fr_auto_auto] md:items-center">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">Rp{{ number_format($item->unit_price, 0, ',', '.') }} · Stok {{ $item->product->stock }}</p>
                            </div>
                            <form method="POST" action="{{ route('cart.items.update', $item) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <x-text-input name="quantity" type="number" min="1" max="{{ $item->product->stock }}" value="{{ $item->quantity }}" class="w-24" />
                                <x-secondary-button>Update</x-secondary-button>
                            </form>
                            <form method="POST" action="{{ route('cart.items.destroy', $item) }}">
                                @csrf
                                @method('DELETE')
                                <x-danger-button>Hapus</x-danger-button>
                            </form>
                        </div>
                    @empty
                        <p class="p-6 text-sm text-gray-500">Keranjang masih kosong.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col justify-between gap-4 bg-white p-6 shadow sm:rounded-lg md:flex-row md:items-center">
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp{{ number_format($total, 0, ',', '.') }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Lanjut Belanja</a>
                    @if ($cart->items->isNotEmpty())
                        <a href="{{ route('checkout.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Checkout</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
