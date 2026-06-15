<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Kategori</h2>
            <a href="{{ route('admin.categories.create') }}" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">Tambah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">{{ session('status') }}</div>
            @endif
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-[1fr_auto_auto] md:items-center">
                            <div>
                                <p class="font-medium text-gray-900">{{ $category->name }}</p>
                                <p class="text-sm text-gray-500">{{ $category->products_count }} produk · {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                            </div>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-sm font-medium text-indigo-600">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm font-medium text-red-600">Hapus</button>
                            </form>
                        </div>
                    @empty
                        <p class="p-6 text-sm text-gray-500">Belum ada kategori.</p>
                    @endforelse
                </div>
            </div>
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
