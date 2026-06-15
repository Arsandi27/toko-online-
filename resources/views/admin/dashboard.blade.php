<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard Admin</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <p class="text-sm text-gray-500">Menunggu Pembayaran</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $pendingPayments }}</p>
                </div>
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <p class="text-sm text-gray-500">Order Aktif</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $activeOrders }}</p>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-medium text-gray-900">Order Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between gap-4 p-6 hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->name }} · {{ $order->status }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                        </a>
                    @empty
                        <p class="p-6 text-sm text-gray-500">Belum ada order.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
