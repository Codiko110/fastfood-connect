@extends('layouts.table')

@section('title', 'Menu - FlashFood')
@section('table_title', 'Table ' . $tableNumber)

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    .primary-glow { box-shadow: 0 0 20px rgba(183, 26, 26, 0.3); }
    .gap-gutter { gap: 0.75rem; }
    @media (min-width: 768px) { .gap-gutter { gap: 1rem; } }
    @media (min-width: 1024px) { .gap-gutter { gap: 1.25rem; } }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush

@section('content')
<div class="px-4 py-4 max-w-7xl mx-auto">
    @if(session('success'))
    <div class="mb-4 p-4 rounded-2xl bg-[#e8f5e9] text-[#2e7d32] text-sm font-semibold flex items-center gap-2">
        <x-icon name="check-circle" class="text-lg" filled />
        {{ session('success') }}
    </div>
    @endif

    <div class="relative mb-5">
        <x-icon name="magnifying-glass" class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-xl" />
        <input type="text" id="searchInput" placeholder="Rechercher un plat..."
            class="w-full pl-12 pr-4 py-3.5 bg-surface-container-lowest border-2 border-outline-variant/20 rounded-2xl text-sm font-semibold text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary/50 focus:bg-surface-container-lowest transition-all">
    </div>

    <div class="overflow-x-auto scrollbar-hide -mx-4 px-4 mb-6">
        <div class="flex gap-2 pb-1" id="categoryPills">
            <button data-category="all" class="category-chip px-5 py-2.5 rounded-xl text-xs font-bold transition-all bg-primary text-on-primary shadow-sm">Tous</button>
            @foreach($categories as $cat)
            <button data-category="{{ $cat->slug }}"
                class="category-chip px-5 py-2.5 rounded-xl text-xs font-bold transition-all bg-surface-container-lowest text-on-surface-variant border border-outline-variant/20 hover:border-primary/30">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter" id="productGrid">
        @forelse($products as $product)
        <article class="product-card bg-surface-container-lowest rounded-2xl card-shadow overflow-hidden transition-all duration-300 hover:shadow-lg group"
            data-category="{{ $product->category->slug }}" data-available="{{ $product->is_available ? 'true' : 'false' }}">
            <a href="{{ $product->is_available ? route('table.detail', $product) : '#' }}" class="block {{ $product->is_available ? '' : 'pointer-events-none' }}">
                <div class="relative aspect-video bg-primary-fixed/30 overflow-hidden">
                    <img src="{{ productImageUrl($product) }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover">
                    @if(!$product->is_available)
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <span class="bg-error-container text-error px-4 py-1.5 rounded-full text-xs font-bold">INDISPONIBLE</span>
                    </div>
                    @endif
                    @if($product->is_available)
                    <div class="absolute top-2 left-2 bg-[#2e7d32] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">DISPONIBLE</div>
                    @endif
                    <div class="absolute inset-0 bg-primary/[0.08] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
            </a>
            <div class="p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-extrabold text-sm text-on-surface truncate">{{ $product->name }}</h3>
                        <p class="text-xs text-on-surface-variant/70 mt-1 line-clamp-2">{{ $product->description }}</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <span class="font-extrabold text-primary text-sm">{{ price($product->price) }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <span class="flex items-center gap-1 text-[10px] text-on-surface-variant/60 font-semibold">
                        <x-icon name="clock" class="text-xs" />
                        {{ $product->preparation_time ?? '10 min' }}
                    </span>
                    @if($product->is_available)
                    <form method="POST" action="{{ route('table.panier.ajouter', $product) }}">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                            class="flex items-center justify-center w-9 h-9 bg-primary text-on-primary rounded-full transition-all duration-300 hover:primary-glow hover:scale-105 active:scale-95 shadow-md">
                            <x-icon name="plus" class="text-lg" filled />
                        </button>
                    </form>
                    @else
                    <span class="flex items-center justify-center w-9 h-9 bg-outline/20 text-outline rounded-full">
                        <x-icon name="x-mark" class="text-lg" />
                    </span>
                    @endif
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
            <x-icon name="clipboard-document-list" class="text-5xl text-on-surface-variant/30 mb-4" :filled="0" />
            <p class="text-sm font-bold text-on-surface-variant/60">Aucun produit disponible pour le moment</p>
        </div>
        @endforelse
    </div>
</div>

<a href="{{ route('table.panier') }}"
    class="fixed bottom-24 right-6 z-50 flex items-center gap-2 bg-primary text-on-primary px-5 py-3.5 rounded-full shadow-xl primary-glow hover:bg-primary/90 transition-all duration-300 hover:scale-105 active:scale-95">
    <x-icon name="shopping-cart" filled />
    <span class="font-extrabold text-sm">Voir le panier</span>
    @if(session('cart_count', 0) > 0)
    <span class="bg-on-primary text-primary text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ session('cart_count') }}</span>
    @endif
</a>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chips = document.querySelectorAll('.category-chip');
        const cards = document.querySelectorAll('.product-card');
        const searchInput = document.getElementById('searchInput');

        chips.forEach(chip => {
            chip.addEventListener('click', function() {
                chips.forEach(c => {
                    c.classList.remove('bg-primary', 'text-on-primary', 'shadow-sm');
                    c.classList.add('bg-surface-container-lowest', 'text-on-surface-variant', 'border', 'border-outline-variant/20');
                });
                this.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant', 'border', 'border-outline-variant/20');
                this.classList.add('bg-primary', 'text-on-primary', 'shadow-sm');

                const category = this.dataset.category;
                filterProducts(category, searchInput.value.toLowerCase());
            });
        });

        searchInput.addEventListener('input', function() {
            const activeChip = document.querySelector('.category-chip.bg-primary');
            const category = activeChip ? activeChip.dataset.category : 'all';
            filterProducts(category, this.value.toLowerCase());
        });

        function filterProducts(category, query) {
            cards.forEach(card => {
                const cardCat = card.dataset.category;
                const name = card.querySelector('h3').textContent.toLowerCase();
                const catMatch = category === 'all' || cardCat === category;
                const searchMatch = name.includes(query);
                if (catMatch && searchMatch) {
                    card.style.display = '';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush
