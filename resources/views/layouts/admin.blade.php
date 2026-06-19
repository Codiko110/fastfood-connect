<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - FlashFood')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background text-on-surface antialiased min-h-screen flex flex-col md:flex-row" x-data="adminNotifier('{{ route('api.admin.stats') }}', 8000)">
    {{-- Mobile Top Bar --}}
    <header class="md:hidden fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/20">
        <div class="flex items-center justify-between px-4 h-16">
            <button id="mobileMenuBtn" class="p-2 -ml-2 rounded-xl hover:bg-surface-container-low transition-colors">
                <x-icon name="bars-3" class="text-2xl" />
            </button>
            <span class="font-headline-lg text-lg font-bold text-primary">FlashFood</span>
            <button class="p-2 -mr-2 rounded-xl hover:bg-surface-container-low transition-colors relative">
                <x-icon name="bell" class="text-2xl" />
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-primary rounded-full"></span>
            </button>
        </div>
    </header>

    {{-- Desktop Sidebar --}}
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-72 bg-surface-container-lowest border-r border-outline-variant/10 z-40 hidden md:flex flex-col shadow-sm">
        <div class="p-6 border-b border-outline-variant/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                    <x-icon name="building-storefront" class="text-on-primary text-2xl" />
                </div>
                <div>
                    <h1 class="font-bold text-lg text-on-surface">FlashFood</h1>
                    <p class="text-xs text-on-surface-variant">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="rectangle-group" class="" :filled="request()->routeIs('admin.dashboard')" />
                <span class="font-label-sm">Tableau de bord</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="document-text" class="" :filled="request()->routeIs('admin.orders*')" />
                <span class="font-label-sm">Commandes</span>
                <span class="ml-auto bg-primary text-on-primary text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center" x-show="$store.admin.stats?.pending > 0" x-text="$store.admin.stats?.pending" style="display:none">0</span>
            </a>
            <a href="{{ route('admin.menu') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.menu*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="book-open" class="" :filled="request()->routeIs('admin.menu*')" />
                <span class="font-label-sm">Menu</span>
            </a>
            <a href="{{ route('admin.categories') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="squares-2x2" class="" :filled="request()->routeIs('admin.categories*')" />
                <span class="font-label-sm">Catégories</span>
            </a>
            <a href="{{ route('admin.tables') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tables*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="rectangle-stack" class="" :filled="request()->routeIs('admin.tables*')" />
                <span class="font-label-sm">Tables</span>
            </a>
            <a href="{{ route('admin.deliveries') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.deliveries*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="truck" class="" :filled="request()->routeIs('admin.deliveries*')" />
                <span class="font-label-sm">Livraisons</span>
            </a>
            <a href="{{ route('admin.statistics') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.statistics*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="chart-bar" class="" :filled="request()->routeIs('admin.statistics*')" />
                <span class="font-label-sm">Statistiques</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="nav-link flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface' }}">
                <x-icon name="cog-6-tooth" class="" :filled="request()->routeIs('admin.settings*')" />
                <span class="font-label-sm">Paramètres</span>
            </a>
        </nav>

        <div class="p-4 border-t border-outline-variant/10 space-y-3">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-surface-container-low">
                <div class="w-9 h-9 bg-primary/10 rounded-full flex items-center justify-center">
                    <x-icon name="user" class="text-primary text-lg" />
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-on-surface truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-on-surface-variant">Dernière connexion: {{ now()->format('d/m/Y \à H:i') }}</p>
                </div>
            </div>

            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 rounded-xl text-sm text-error hover:bg-error-container/20 transition-colors">
                    <x-icon name="arrow-left-end-on-rectangle" class="text-lg" />
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 md:ml-72 pt-16 md:pt-0 bg-surface">
        @yield('content')
    </main>

    {{-- Mobile Bottom Nav --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md border-t border-outline-variant/10">
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="rectangle-group" class="text-xl" :filled="request()->routeIs('admin.dashboard')" />
                <span class="text-[10px] font-semibold">Accueil</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('admin.orders*') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="document-text" class="text-xl" :filled="request()->routeIs('admin.orders*')" />
                <span class="text-[10px] font-semibold">Ventes</span>
            </a>
            <a href="{{ route('admin.menu') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('admin.menu*') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="clipboard-document-list" class="text-xl" :filled="request()->routeIs('admin.menu*')" />
                <span class="text-[10px] font-semibold">Cuisine</span>
            </a>
            <a href="{{ route('admin.settings') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('admin.settings*') ? 'text-primary' : 'text-on-surface-variant' }}">
                <x-icon name="ellipsis-horizontal" class="text-xl" :filled="request()->routeIs('admin.settings*')" />
                <span class="text-[10px] font-semibold">Plus</span>
            </a>
        </div>
    </nav>

    {{-- Mobile Menu Overlay --}}
    <div id="mobileMenuOverlay" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden" onclick="toggleMobileMenu()"></div>

    @stack('scripts')

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileMenuOverlay');
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('flex');
            overlay.classList.toggle('hidden');
        }

        document.getElementById('mobileMenuBtn')?.addEventListener('click', toggleMobileMenu);
    </script>
</body>
</html>
