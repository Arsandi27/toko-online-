<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Pesanan {{ $order->order_number }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div class="space-y-6 lg:col-span-2">
                @if (session('status'))
                    <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
                @endif
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Item Pesanan</h3>
                    <div class="mt-4 divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <div class="flex justify-between gap-4 py-3 text-sm">
                                <span class="text-gray-600">{{ $item->product_name }} x {{ $item->quantity }}</span>
                                <span class="font-medium text-gray-900">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Upload Bukti Pembayaran</h3>
                    <form method="POST" action="{{ route('orders.payment-proof.store', $order) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <input name="proof" type="file" class="block w-full text-sm text-gray-700">
                        <x-input-error :messages="$errors->get('proof')" />
                        <x-primary-button>Upload Bukti</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Status</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Order</dt><dd class="font-medium text-gray-900">{{ $order->status }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Pembayaran</dt><dd class="font-medium text-gray-900">{{ $order->payment?->status }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Pengiriman</dt><dd class="font-medium text-gray-900">{{ $order->shipment?->status }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Total</dt><dd class="font-medium text-gray-900">Rp{{ number_format($order->total, 0, ',', '.') }}</dd></div>
                    </dl>
                </div>
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Pengiriman</h3>
                    <p class="mt-3 text-sm text-gray-600">{{ $order->recipient_name }} · {{ $order->recipient_phone }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $order->shipping_address }}</p>
                    @if ($order->shipment?->tracking_number)
                        <p class="mt-2 text-sm text-gray-600">{{ $order->shipment->courier }} · {{ $order->shipment->tracking_number }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
