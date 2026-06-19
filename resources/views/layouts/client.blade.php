<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FlashFood')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background text-on-surface antialiased min-h-screen flex flex-col">
    {{-- Top App Bar --}}
    <header id="mainHeader" class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/10 transition-shadow duration-300">
        <div class="max-w-[1280px] mx-auto px-container-padding-mobile md:px-container-padding-desktop">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    @hasSection('back')
                        <a href="@yield('back')" class="p-2 -ml-2 rounded-xl hover:bg-surface-container-low transition-colors">
                            <x-icon name="arrow-left" class="text-2xl" />
                        </a>
                    @endif
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <x-icon name="building-storefront" class="text-on-primary text-lg" />
                        </div>
                        <span class="font-bold text-lg text-primary">FlashFood</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('client.panier') }}" class="p-2 rounded-xl hover:bg-surface-container-low transition-colors relative">
                        <x-icon name="shopping-cart" class="text-2xl" />
                        @if(session('cart_count', 0) > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-secondary-container text-on-secondary-container text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ session('cart_count') }}</span>
                        @endif
                    </a>
                    <button class="p-2 rounded-xl hover:bg-surface-container-low transition-colors relative">
                        <x-icon name="bell" class="text-2xl" />
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 pt-16 pb-24 md:pb-12">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md border-t border-outline-variant/10">
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('client.accueil') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('client.accueil') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="home" class="text-xl" :filled="request()->routeIs('client.accueil')" />
                <span class="text-[10px] font-semibold">Accueil</span>
            </a>
            <a href="{{ route('client.commandes') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('client.commandes*') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="document-text" class="text-xl" :filled="request()->routeIs('client.commandes*')" />
                <span class="text-[10px] font-semibold">Commandes</span>
            </a>
            <a href="{{ route('client.menu') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('client.menu') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="magnifying-glass" class="text-xl" :filled="request()->routeIs('client.menu')" />
                <span class="text-[10px] font-semibold">Recherche</span>
            </a>
            <a href="{{ route('client.profil') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('client.profil') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="user" class="text-xl" :filled="request()->routeIs('client.profil')" />
                <span class="text-[10px] font-semibold">Profil</span>
            </a>
        </div>
    </nav>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('mainHeader');
            let lastScroll = 0;

            window.addEventListener('scroll', function() {
                const currentScroll = window.scrollY;
                if (currentScroll > 20) {
                    header.classList.add('shadow-[0px_4px_20px_rgba(33,33,33,0.08)]');
                } else {
                    header.classList.remove('shadow-[0px_4px_20px_rgba(33,33,33,0.08)]');
                }
                lastScroll = currentScroll;
            });
        });
    </script>
</body>
</html>
