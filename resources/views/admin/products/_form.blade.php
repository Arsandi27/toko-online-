@csrf
<div class="space-y-5">
    <div>
        <x-input-label for="category_id" value="Kategori" />
        <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="name" value="Nama Produk" />
        <x-text-input id="name" name="name" value="{{ old('name', $product->name ?? '') }}" class="mt-1 block w-full" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <x-input-label for="price" value="Harga" />
            <x-text-input id="price" name="price" type="number" min="0" value="{{ old('price', $product->price ?? 0) }}" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('price')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="stock" value="Stok" />
            <x-text-input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" class="mt-1 block w-full" />
            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
        </div>
    </div>
    <div>
        <x-input-label for="description" value="Deskripsi" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="image" value="Gambar Produk" />
        <input id="image" name="image" type="file" class="mt-1 block w-full text-sm text-gray-700">
        <x-input-error :messages="$errors->get('image')" class="mt-2" />
    </div>
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <span class="text-sm text-gray-700">Aktif</span>
    </label>
    <div class="flex gap-3">
        <x-primary-button>Simpan</x-primary-button>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</a>
    </div>
</div>
