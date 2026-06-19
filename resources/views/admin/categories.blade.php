@extends('layouts.admin')

@section('title', 'Gestion du Catalogue')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto pb-20">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Gestion du Catalogue</h1>
            <p class="text-on-surface-variant mt-1">Gérez vos produits et catégories.</p>
        </div>
    </div>

    <x-flash-messages />

    {{-- Search --}}
    <div class="mb-6">
        <form action="{{ route('admin.menu') }}" method="GET">
            <div class="relative max-w-md">
                <x-icon name="magnifying-glass" class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" />
                <input type="text" name="search" placeholder="Rechercher un produit..." value="{{ request('search') }}" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                @if(request('search'))
                    <a href="{{ route('admin.menu') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                        <x-icon name="x-mark" class="text-lg" />
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Category Filter Pills --}}
    @if($categories->count() > 0)
        <div class="overflow-x-auto pb-2 mb-6 scrollbar-hide">
            <div class="flex gap-2 min-w-max">
                <a href="{{ route('admin.menu', array_filter(['search' => request('search')])) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ !request('category') ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-high/80' }}">
                    Tous
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('admin.menu', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ request('category') === $cat->slug ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-high/80' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Product Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="group bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden card-shadow transition-all duration-300 hover:-translate-y-1 {{ !$product->is_available ? 'opacity-75 grayscale' : '' }}">
                    <div class="relative h-44 bg-surface-container-high flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ productImageUrl($product) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <x-icon name="cake" class="text-5xl text-on-surface-variant/40" />
                        @endif
                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-primary text-on-primary text-xs font-bold shadow-lg">
                            {{ price($product->price) }}
                        </div>
                        @if($product->category)
                            <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-secondary/90 text-white text-xs font-semibold">
                                {{ $product->category->name }}
                            </div>
                        @endif
                        @if(!$product->is_available)
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center rounded-2xl">
                                <span class="text-white font-bold text-sm bg-black/60 px-4 py-2 rounded-lg">Indisponible</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between mt-4">
                            <form action="{{ route('admin.menu.toggle', $product) }}" method="POST">
                                @csrf
                                <label class="toggle-switch relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" onchange="this.form.submit()" {{ $product->is_available ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-outline-variant/40 rounded-full peer peer-checked:bg-primary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-4 after:h-4 after:bg-white after:rounded-full after:shadow-sm after:transition-all peer-checked:after:translate-x-4"></div>
                                </label>
                            </form>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.menu.edit', $product) }}" class="p-1.5 rounded-lg hover:bg-surface-container-low transition-all" title="Modifier">
                                    <x-icon name="pencil" class="text-lg text-on-surface-variant" />
                                </a>
                                <form action="{{ route('admin.menu.destroy', $product) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 transition-all" title="Supprimer">
                                        <x-icon name="trash" class="text-lg text-on-surface-variant" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <x-icon name="clipboard-document-list" class="text-5xl text-on-surface-variant/40 mb-4" />
            <p class="text-on-surface-variant">Aucun produit trouvé</p>
        </div>
    @endif

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif

    {{-- FAB --}}
    <a href="{{ route('admin.menu.creer') }}" class="fixed bottom-6 right-6 md:right-8 w-14 h-14 rounded-full bg-primary text-on-primary shadow-lg flex items-center justify-center transition-all hover:scale-110 hover:shadow-xl primary-glow z-30">
        <x-icon name="plus" class="text-2xl" />
    </a>
</div>
@endsection

@push('scripts')
<script>
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

    document.querySelectorAll('.card-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 12px 40px rgba(0,0,0,0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });

    let searchTimeout;
    document.querySelector('input[name="search"]')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => this.form.submit(), 500);
    });
</script>
@endpush
