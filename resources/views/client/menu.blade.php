@extends('layouts.client')

@section('title', 'Menu - FlashFood')

@section('back', route('client.accueil'))

@section('content')
    <div class="px-4 pt-4">
        {{-- Search --}}
        <form action="{{ route('client.menu') }}" method="GET">
            <div class="flex items-center gap-3 rounded-2xl bg-surface-container-lowest px-5 py-3 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <x-icon name="magnifying-glass" class="text-on-surface-variant" />
                <input type="search" name="search" placeholder="Rechercher dans le menu..." value="{{ request('search') }}" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                @if(request('search'))
                    <a href="{{ route('client.menu', array_filter(['search' => null])) }}" class="text-on-surface-variant hover:text-primary">
                        <x-icon name="x-mark" class="text-lg" />
                    </a>
                @endif
            </div>
        </form>

        @if(session('success'))
            <div class="mt-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif

        {{-- Category Filter Pills --}}
        <div class="mt-5 flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <a href="{{ route('client.menu', array_filter(['search' => request('search')])) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ !request('category') ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-high/80' }}">
                Tous
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('client.menu', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition-all {{ request('category') === $cat->slug ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface hover:bg-surface-container-high/80' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- Sort Controls --}}
        <div class="mt-5 flex items-center justify-end">
            <form action="{{ route('client.menu') }}" method="GET">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <select name="sort" onchange="this.form.submit()" class="rounded-xl border border-outline/30 bg-surface-container-lowest px-4 py-2.5 text-sm text-on-surface outline-none">
                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Trier par défaut</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix : croissant</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix : décroissant</option>
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nom : A-Z</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Nom : Z-A</option>
                </select>
            </form>
        </div>

        {{-- Product Grid --}}
        @if($products->isEmpty())
            <div class="mt-10 text-center">
                <x-icon name="magnifying-glass-minus" class="text-5xl text-outline/50" />
                <p class="mt-3 text-sm text-on-surface-variant">Aucun produit trouvé.</p>
            </div>
        @else
            <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <x-product-card :product="$product" show-category show-description aspect="4/3" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    let searchTimeout;
    document.querySelector('input[name="search"]')?.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => this.form.submit(), 500);
    });
</script>
@endpush
