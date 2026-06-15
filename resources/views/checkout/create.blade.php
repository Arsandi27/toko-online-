<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Checkout</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-5 bg-white p-6 shadow sm:rounded-lg lg:col-span-2">
                @csrf
                <div>
                    <x-input-label for="recipient_name" value="Nama Penerima" />
                    <x-text-input id="recipient_name" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('recipient_name')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="recipient_phone" value="Nomor HP" />
                    <x-text-input id="recipient_phone" name="recipient_phone" value="{{ old('recipient_phone') }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('recipient_phone')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="shipping_address" value="Alamat Pengiriman" />
                    <textarea id="shipping_address" name="shipping_address" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('shipping_address') }}</textarea>
                    <x-input-error :messages="$errors->get('shipping_address')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="notes" value="Catatan" />
                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>
                <x-primary-button>Buat Pesanan</x-primary-button>
            </form>

            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="font-medium text-gray-900">Ringkasan</h3>
                <div class="mt-4 space-y-3">
                    @foreach ($cart->items as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <span class="text-gray-600">{{ $item->product->name }} x {{ $item->quantity }}</span>
                            <span class="font-medium text-gray-900">Rp{{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 border-t pt-4">
                    <div class="flex justify-between font-semibold text-gray-900">
                        <span>Total</span>
                        <span>Rp{{ number_format($cart->items->sum(fn ($item) => $item->unit_price * $item->quantity), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
