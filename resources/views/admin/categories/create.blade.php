<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Tambah Kategori</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white p-6 shadow sm:rounded-lg">
                @include('admin.categories._form')
            </form>
        </div>
    </div>
</x-app-layout>
