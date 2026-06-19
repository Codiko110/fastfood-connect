@extends('layouts.client')

@section('title', 'FlashFood - Accueil')

@section('content')
    {{-- Hero Section --}}
    <section class="relative overflow-hidden rounded-b-3xl bg-gradient-to-br from-primary to-primary/80 px-6 pt-12 pb-20 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.1),transparent_60%)]"></div>
        <div class="relative z-10 mx-auto max-w-4xl text-center">
            <h1 class="mb-3 text-4xl font-extrabold tracking-tight md:text-5xl">FlashFood</h1>
            <p class="mb-8 text-lg font-light text-white/85">Des burgers qui font parler d'eux</p>
            <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('client.menu') }}" class="primary-glow inline-flex items-center gap-2 rounded-2xl bg-white px-8 py-4 font-semibold text-primary shadow-[0px_4px_20px_rgba(33,33,33,0.08)] transition-all hover:-translate-y-1 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
                    <x-icon name="truck" class="text-xl" />
                    Commander maintenant
                </a>
                <a href="{{ route('client.menu') }}" class="inline-flex items-center gap-2 rounded-2xl border-2 border-white/40 px-8 py-4 font-semibold text-white transition-all hover:-translate-y-1 hover:border-white">
                    Voir les menus
                </a>
            </div>
        </div>
    </section>

    {{-- Search Bar --}}
    <section class="relative z-20 mx-auto -mt-7 max-w-xl px-4">
        <form action="{{ route('client.menu') }}" method="GET">
            <div class="flex items-center gap-3 rounded-2xl bg-surface-container-lowest px-5 py-3 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <x-icon name="magnifying-glass" class="text-on-surface-variant" />
                <input type="search" name="search" placeholder="Rechercher un plat..." value="{{ request('search') }}" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                @if(request('search'))
                    <a href="{{ route('client.menu') }}" class="text-on-surface-variant hover:text-primary">
                        <x-icon name="x-mark" class="text-lg" />
                    </a>
                @endif
            </div>
        </form>
    </section>

    @if(session('success'))
        <div class="mx-auto mt-4 max-w-xl px-4">
            <div class="rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        </div>
    @endif

    {{-- Category Pills --}}
    <section class="mt-8 px-4">
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            @foreach($categories as $cat)
                <a href="{{ route('client.menu', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </section>

    {{-- Promotions --}}
    <section class="mt-8 px-4">
        <h2 class="mb-4 text-xl font-bold text-on-surface">Promotions</h2>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="glass-card rounded-2xl bg-surface-container-lowest/70 p-5 backdrop-blur-lg shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-secondary-container/20">
                    <x-icon name="truck" class="text-2xl text-secondary" />
                </div>
                <h3 class="font-bold text-on-surface">Livraison Offerte</h3>
                <p class="mt-1 text-sm text-on-surface-variant">Pour toute commande de plus de 15€</p>
            </div>
            <div class="glass-card rounded-2xl bg-surface-container-lowest/70 p-5 backdrop-blur-lg shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-secondary-container/20">
                    <x-icon name="percent-badge" class="text-2xl text-secondary" />
                </div>
                <h3 class="font-bold text-on-surface">Promo du Jour</h3>
                <p class="mt-1 text-sm text-on-surface-variant">-20% sur tous les burgers</p>
            </div>
        </div>
    </section>

    {{-- Popular Products --}}
    <section class="mt-10 px-4">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-on-surface">Les Plus Populaires</h2>
            <a href="{{ route('client.menu') }}" class="text-sm font-medium text-primary">Voir tout</a>
        </div>
        @if($popularProducts->isEmpty())
            <p class="text-sm text-on-surface-variant">Aucun produit populaire pour le moment.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($popularProducts as $product)
                    <x-product-card :product="$product" icon="" />
                @endforeach
            </div>
        @endif
    </section>

    {{-- Newsletter --}}
    <section class="mt-12 px-4">
        <div class="rounded-2xl bg-gradient-to-br from-primary to-primary/80 px-6 py-10 text-center text-white">
            <h2 class="text-xl font-bold">Restez Informé</h2>
            <p class="mt-1 text-sm text-white/80">Recevez nos offres exclusives</p>
            <form action="{{ route('client.newsletter') }}" method="POST" class="mx-auto mt-5 flex max-w-md gap-3">
                @csrf
                <input type="email" name="email" placeholder="Votre email" class="w-full rounded-xl bg-white/20 px-4 py-3 text-sm text-white placeholder:text-white/60 outline-none backdrop-blur-sm">
                <button type="submit" class="flex-shrink-0 rounded-xl bg-white px-6 py-3 font-semibold text-primary transition-all hover:bg-white/90">S'inscrire</button>
            </form>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="mt-12 border-t border-outline/10 bg-surface-container-low px-4 py-8">
        <div class="mx-auto max-w-6xl">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <h3 class="text-lg font-bold text-on-surface">FlashFood</h3>
                    <p class="mt-2 text-sm text-on-surface-variant">Des burgers qui font parler d'eux.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-on-surface">Liens Rapides</h4>
                    <ul class="mt-2 space-y-1.5 text-sm text-on-surface-variant">
                        <li><a href="{{ route('client.menu') }}" class="hover:text-primary">Menu</a></li>
                        <li><a href="{{ route('client.menu') }}" class="hover:text-primary">Promotions</a></li>
                        <li><a href="{{ route('client.accueil') }}" class="hover:text-primary">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-on-surface">Contact</h4>
                    <ul class="mt-2 space-y-1.5 text-sm text-on-surface-variant">
                        <li class="flex items-center gap-2">
                            <x-icon name="phone" class="text-sm" />
                            033 82 849 49
                        </li>
                        <li class="flex items-center gap-2">
                            <x-icon name="envelope" class="text-sm" />
                            contact@fastfoodconnect.mg
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-outline/10 pt-6 text-center text-sm text-on-surface-variant">
                &copy; {{ date('Y') }} FlashFood. Tous droits réservés.
            </div>
        </div>
    </footer>
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
