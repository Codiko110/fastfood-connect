@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Gestion des Catégories</h1>
            <p class="text-on-surface-variant mt-1">Organisez vos catégories de produits.</p>
        </div>
    </div>

    <x-flash-messages />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Category Form --}}
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
            <h3 class="text-lg font-bold text-on-surface mb-6">Ajouter une catégorie</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="cat-name" class="block text-sm font-semibold text-on-surface mb-2">Nom</label>
                    <input type="text" id="cat-name" name="name" value="{{ old('name') }}" placeholder="Ex: Burgers" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cat-description" class="block text-sm font-semibold text-on-surface mb-2">Description (optionnelle)</label>
                    <textarea id="cat-description" name="description" rows="2" placeholder="Description de la catégorie..." class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-bold transition-all hover:shadow-lg hover:scale-[1.02] primary-glow">
                    <x-icon name="plus" class="text-lg inline-block align-middle mr-1" />
                    Créer la catégorie
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse($categories as $cat)
                <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-5 card-shadow transition-all hover:-translate-y-0.5">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-base font-bold text-on-surface">{{ $cat->name }}</h4>
                            @if($cat->description)
                                <p class="text-sm text-on-surface-variant mt-0.5">{{ $cat->description }}</p>
                            @endif
                            <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary">{{ $cat->products_count ?? $cat->products->count() ?? 0 }} produit(s)</span>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <button onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')" class="p-2 rounded-lg hover:bg-surface-container-low transition-all" title="Modifier">
                                <x-icon name="pencil" class="text-lg text-on-surface-variant" />
                            </button>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all" title="Supprimer">
                                    <x-icon name="trash" class="text-lg text-on-surface-variant" />
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Form (hidden, shown via JS) --}}
                    <div id="edit-form-{{ $cat->id }}" class="hidden mt-4 pt-4 border-t border-outline-variant/10">
                        <form action="{{ route('admin.categories.update', $cat) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-semibold text-on-surface mb-2">Nom</label>
                                <input type="text" name="name" value="{{ $cat->name }}" class="w-full px-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-on-surface mb-2">Description</label>
                                <textarea name="description" rows="2" class="w-full px-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none">{{ $cat->description }}</textarea>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-sm font-semibold transition-all hover:shadow-lg">Enregistrer</button>
                                <button type="button" onclick="document.getElementById('edit-form-{{ $cat->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-outline-variant/30 text-on-surface text-sm font-semibold transition-all hover:bg-surface-container-high">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-surface-container-lowest rounded-2xl border border-outline-variant/10">
                    <x-icon name="squares-2x2" class="text-5xl text-on-surface-variant/40 mb-4" />
                    <p class="text-on-surface-variant">Aucune catégorie pour le moment</p>
                    <p class="text-xs text-on-surface-variant mt-1">Créez votre première catégorie à gauche</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editCategory(id, name, description) {
        const form = document.getElementById('edit-form-' + id);
        if (form) {
            form.classList.remove('hidden');
            form.querySelector('input[name="name"]').value = name;
            form.querySelector('textarea[name="description"]').value = description;
        }
    }

    document.querySelectorAll('.card-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 8px 30px rgba(0,0,0,0.08)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
</script>
@endpush
