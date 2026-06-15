<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Order</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="grid grid-cols-1 gap-2 p-6 hover:bg-gray-50 md:grid-cols-5 md:items-center">
                            <div>
                                <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->name }}</p>
                            </div>
                            <p class="text-sm text-gray-600">{{ $order->status }}</p>
                            <p class="text-sm text-gray-600">{{ $order->payment?->status }}</p>
                            <p class="text-sm text-gray-600">{{ $order->shipment?->status }}</p>
                            <p class="font-semibold text-gray-900 md:text-right">Rp{{ number_format($order->total, 0, ',', '.') }}</p>
                        </a>
                    @empty
                        <p class="p-6 text-sm text-gray-500">Belum ada order.</p>
                    @endforelse
                </div>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
