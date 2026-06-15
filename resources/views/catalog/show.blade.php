<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $product->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="aspect-square overflow-hidden bg-gray-100 shadow sm:rounded-lg">
                @if ($product->image_path)
                    <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                @endif
            </div>
            <div class="space-y-6 bg-white p-6 shadow sm:rounded-lg">
                <div>
                    <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                    <h3 class="mt-2 text-2xl font-semibold text-gray-900">{{ $product->name }}</h3>
                    <p class="mt-3 text-xl font-semibold text-gray-900">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-gray-500">Stok {{ $product->stock }}</p>
                </div>
                <p class="text-sm leading-6 text-gray-600">{{ $product->description ?: 'Tidak ada deskripsi.' }}</p>
                @auth
                    @role('Customer')
                        <form method="POST" action="{{ route('cart.items.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div>
                                <x-input-label for="quantity" value="Jumlah" />
                                <x-text-input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" class="mt-1 block w-32" />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>
                            <x-primary-button @disabled($product->stock < 1)>Tambah ke Keranjang</x-primary-button>
                        </form>
                    @endrole
                @else
                    <a href="{{ route('login') }}" class="inline-flex rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Login untuk Belanja</a>
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
