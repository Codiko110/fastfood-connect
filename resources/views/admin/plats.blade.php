@extends('layouts.admin')

@section('title', isset($product) ? 'Modifier un Plat' : 'Ajouter un Plat')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto pb-24">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.menu') }}" class="text-on-surface-variant hover:text-primary transition-colors">Menu</a>
        <x-icon name="chevron-right" class="text-base text-on-surface-variant" />
        <span class="text-on-surface font-semibold">{{ isset($product) ? 'Modifier' : 'Ajouter' }} un plat</span>
    </nav>

    <x-flash-messages />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Right - Form --}}
        <div class="lg:col-span-7">
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                <h3 class="text-lg font-bold text-on-surface mb-6">Informations du plat</h3>

                <form action="{{ isset($product) ? route('admin.menu.update', $product) : route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    {{-- Catégorie --}}
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-on-surface mb-2">Catégorie</label>
                        <div class="relative">
                            <select id="category_id" name="category_id" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all appearance-none cursor-pointer">
                                <option value="" disabled {{ !isset($product) ? 'selected' : '' }}>Sélectionner une catégorie</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ isset($product) && $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <x-icon name="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none" />
                        </div>
                        @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nom --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-on-surface mb-2">Nom du plat</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Ex: Double Cheese Burger" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-on-surface mb-2">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Décrivez votre plat..." class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none">{{ old('description', $product->description ?? '') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Prix & Temps --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-semibold text-on-surface mb-2">Prix (Ar)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">Ar</span>
                                <input type="number" id="price" name="price" step="100" min="0" value="{{ old('price', isset($product) ? $product->price * 5000 : '') }}" placeholder="0" class="w-full pl-10 pr-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            </div>
                            @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="preparation_time" class="block text-sm font-semibold text-on-surface mb-2">Temps de préparation (min)</label>
                            <div class="flex items-center gap-4">
                                <input type="range" id="preparation_time" name="preparation_time" min="1" max="60" value="{{ old('preparation_time', $product->preparation_time ?? 15) }}" class="flex-1 accent-primary h-2 rounded-full appearance-none bg-surface-container-high cursor-pointer">
                                <span id="prep-time-value" class="text-sm font-bold text-primary min-w-[4rem] text-right">{{ old('preparation_time', $product->preparation_time ?? 15) }} min</span>
                            </div>
                            @error('preparation_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Image --}}
                    <div>
                        <label for="image" class="block text-sm font-semibold text-on-surface mb-2">Image du plat</label>
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-on-primary hover:file:bg-primary/90 transition-all cursor-pointer">
                                @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            @if(isset($product) && $product->image)
                                <div class="w-20 h-20 rounded-xl overflow-hidden border border-outline-variant/20 flex-shrink-0">
                                    <img src="{{ productImageUrl($product) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-on-surface-variant mt-1">Formats acceptés : JPG, PNG, WEBP. Taille max : 2 Mo.</p>
                    </div>

                    {{-- Availability Toggle --}}
                    <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low">
                        <div>
                            <p class="text-sm font-semibold text-on-surface">Disponible</p>
                            <p class="text-xs text-on-surface-variant">Le plat sera visible dans le menu</p>
                        </div>
                        <label class="toggle-switch relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_available" value="1" class="sr-only peer" {{ !isset($product) || $product->is_available ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-outline-variant/40 rounded-full peer peer-checked:bg-primary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:bg-white after:rounded-full after:shadow-sm after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.menu') }}" class="px-6 py-3 rounded-xl border border-outline-variant/30 text-on-surface font-semibold transition-all hover:bg-surface-container-high">Annuler</a>
                        <button type="submit" class="save-btn px-6 py-3 rounded-xl bg-primary text-on-primary font-bold transition-all hover:shadow-lg hover:scale-105 primary-glow inline-flex items-center gap-2">
                            <x-icon name="document-check" class="text-lg" />
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const prepTime = document.getElementById('preparation_time');
    const prepTimeValue = document.getElementById('prep-time-value');
    if (prepTime && prepTimeValue) {
        prepTime.addEventListener('input', function() {
            prepTimeValue.textContent = this.value + ' min';
        });
    }

    document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const track = this.nextElementSibling;
            if (this.checked) {
                track.classList.remove('bg-outline-variant/40');
                track.classList.add('bg-primary');
            } else {
                track.classList.remove('bg-primary');
                track.classList.add('bg-outline-variant/40');
            }
        });
    });
</script>
@endpush
