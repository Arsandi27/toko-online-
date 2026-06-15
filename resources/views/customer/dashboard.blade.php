<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard Customer</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 bg-white p-6 shadow sm:rounded-lg md:flex-row md:items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Mulai Belanja</h3>
                    <p class="mt-1 text-sm text-gray-500">Lihat katalog produk dan lanjutkan checkout dari keranjang.</p>
                </div>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Lihat Katalog</a>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-medium text-gray-900">Pesanan Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentOrders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between gap-4 p-6 hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->status }} · {{ $order->payment?->status }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                        </a>
                    @empty
                        <p class="p-6 text-sm text-gray-500">Belum ada pesanan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
