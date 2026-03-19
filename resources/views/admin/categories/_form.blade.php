{{-- Shared form fields for create/edit category --}}

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('name') border-red-400 @enderror"
           placeholder="Ej: Ropa Deportiva">
    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
    <textarea name="description" rows="3"
              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"
              placeholder="Descripción opcional...">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
</div>
