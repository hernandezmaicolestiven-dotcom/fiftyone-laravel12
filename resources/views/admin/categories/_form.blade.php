<div>
    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
        Nombre <span class="text-red-400">*</span>
    </label>
    <div class="relative group">
        <span class="absolute inset-y-0 left-4 flex items-center text-gray-300 group-focus-within:text-indigo-500 transition">
            <i class="fa-solid fa-tag text-sm"></i>
        </span>
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
               placeholder="Ej: Ropa Deportiva"
               class="w-full pl-11 pr-4 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition
                      {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-gray-100 bg-gray-50 focus:border-indigo-400 focus:bg-white' }}">
    </div>
    @error('name')
        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
        </p>
    @enderror
</div>

<div>
    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
        Descripción <span class="text-gray-300 font-normal normal-case tracking-normal">— opcional</span>
    </label>
    <div class="relative group">
        <span class="absolute top-3.5 left-4 text-gray-300 group-focus-within:text-indigo-500 transition">
            <i class="fa-solid fa-align-left text-sm"></i>
        </span>
        <textarea name="description" rows="4"
                  placeholder="Describe brevemente esta categoría..."
                  class="w-full pl-11 pr-4 py-3.5 border-2 rounded-xl text-sm font-medium focus:outline-none transition resize-none
                         border-gray-100 bg-gray-50 focus:border-indigo-400 focus:bg-white">{{ old('description', $category->description ?? '') }}</textarea>
    </div>
    @error('description')
        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
        </p>
    @enderror
</div>
