<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Order {{ $order->order_number }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <div class="space-y-6 lg:col-span-2">
                @if (session('status'))
                    <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
                @endif

                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Customer</h3>
                    <p class="mt-3 text-sm text-gray-600">{{ $order->user->name }} · {{ $order->user->email }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $order->recipient_name }} · {{ $order->recipient_phone }}</p>
                    <p class="mt-2 text-sm text-gray-600">{{ $order->shipping_address }}</p>
                </div>

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
                    <div class="mt-4 flex justify-between border-t pt-4 font-semibold text-gray-900">
                        <span>Total</span>
                        <span>Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Verifikasi Pembayaran</h3>
                    <p class="mt-2 text-sm text-gray-600">Status: {{ $order->payment?->status }}</p>
                    @if ($order->payment?->proof_path)
                        <a href="{{ asset('storage/'.$order->payment->proof_path) }}" target="_blank" class="mt-2 inline-flex text-sm font-medium text-indigo-600">Lihat bukti pembayaran</a>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Customer belum upload bukti pembayaran.</p>
                    @endif
                    <form method="POST" action="{{ route('admin.orders.payment.update', $order) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="verified">Verified</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <textarea name="notes" rows="3" placeholder="Catatan pembayaran" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $order->payment?->notes) }}</textarea>
                        <x-primary-button>Simpan Verifikasi</x-primary-button>
                    </form>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Status Order</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Order</dt><dd class="font-medium text-gray-900">{{ $order->status }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Pembayaran</dt><dd class="font-medium text-gray-900">{{ $order->payment?->status }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-gray-500">Pengiriman</dt><dd class="font-medium text-gray-900">{{ $order->shipment?->status }}</dd></div>
                    </dl>
                </div>

                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="font-medium text-gray-900">Update Pengiriman</h3>
                    <form method="POST" action="{{ route('admin.orders.status.update', $order) }}" class="mt-5 space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-input-label for="order_status" value="Status Order" />
                            <select id="order_status" name="order_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['pending_payment', 'processing', 'shipped', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="shipment_status" value="Status Pengiriman" />
                            <select id="shipment_status" name="shipment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['pending', 'packing', 'shipped', 'delivered', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($order->shipment?->status === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="courier" value="Kurir" />
                            <x-text-input id="courier" name="courier" value="{{ old('courier', $order->shipment?->courier) }}" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="tracking_number" value="Nomor Resi" />
                            <x-text-input id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->shipment?->tracking_number) }}" class="mt-1 block w-full" />
                        </div>
                        <x-primary-button>Simpan Status</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
