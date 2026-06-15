<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Katalog Produk</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('catalog.index') }}" class="rounded-md border px-3 py-2 text-sm {{ request()->missing('category') ? 'bg-gray-900 text-white' : 'bg-white text-gray-700' }}">Semua</a>
                @foreach ($categories as $category)
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="rounded-md border px-3 py-2 text-sm {{ request('category') === $category->slug ? 'bg-gray-900 text-white' : 'bg-white text-gray-700' }}">{{ $category->name }}</a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($products as $product)
                    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                        <a href="{{ route('catalog.show', $product) }}">
                            <div class="aspect-square bg-gray-100">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="space-y-2 p-4">
                                <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                                <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                                <p class="font-semibold text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">Stok {{ $product->stock }}</p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Produk belum tersedia.</p>
                @endforelse
            </div>

            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
