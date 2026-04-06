{{-- Shared form fields for create/edit product --}}

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('name') border-red-400 @enderror"
           placeholder="Ej: Camiseta Dry-Fit Pro">
    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
    <textarea name="description" rows="3"
              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"
              placeholder="Descripción del producto...">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio <span class="text-red-500">*</span></label>
        <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-sm">$</span>
            <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}"
                   step="0.01" min="0" required
                   class="w-full pl-7 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('price') border-red-400 @enderror"
                   placeholder="0.00">
        </div>
        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
               min="0" required
               class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('stock') border-red-400 @enderror">
        @error('stock') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
    <select name="category_id"
            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <option value="">Sin categoría</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>

<div x-data="{ preview: '{{ isset($product) && $product->image ? (str_starts_with($product->image, "http") ? $product->image : Storage::url($product->image)) : "" }}' }">
    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>

    <div x-show="preview" class="mb-3">
        <img :src="preview" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
    </div>

    <label class="flex items-center gap-3 cursor-pointer border-2 border-dashed border-gray-200 hover:border-indigo-400 rounded-lg p-4 transition">
        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl"></i>
        <span class="text-sm text-gray-500">Haz clic para subir una imagen (JPG, PNG, WEBP — máx. 2MB)</span>
        <input type="file" name="image" accept="image/*" class="hidden"
               @change="preview = URL.createObjectURL($event.target.files[0])">
    </label>
    @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>
