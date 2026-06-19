<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Table - FlashFood')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-background text-on-surface antialiased min-h-screen" style="min-height: max(884px, 100dvh);" x-init="$store.app.tableId = {{ session('table_id') ?? 'null' }}">
    {{-- Top App Bar --}}
    <header class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/10">
        <div class="flex items-center justify-between px-4 h-16">
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
                    <span class="font-bold text-lg">@yield('table_title', 'Table ' . (session('table_id') ?? 'N/A'))</span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ route('table.quitter') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-xl hover:bg-surface-container-low transition-colors flex items-center gap-1 text-xs font-semibold text-on-surface-variant hover:text-error">
                        <x-icon name="arrow-left-end-on-rectangle" class="text-xl" />
                        <span class="hidden sm:inline">Quitter</span>
                    </button>
                </form>
                <a href="{{ route('table.panier') }}" class="p-2 rounded-xl hover:bg-surface-container-low transition-colors relative">
                    <x-icon name="shopping-cart" class="text-2xl" />
                    <span class="absolute -top-0.5 -right-0.5 bg-secondary-container text-on-secondary-container text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-show="$store.app.cartCount > 0" x-text="$store.app.cartCount" style="display:none">{{ session('cart_count') }}</span>
                </a>
                <div class="relative" id="notificationBell" x-data="tableNotifier('{{ route('api.table.orders') }}', 6000)">
                    <button @click="toggleNotifications()" class="p-2 rounded-xl hover:bg-surface-container-low transition-colors relative">
                        <x-icon name="bell" class="text-2xl" />
                        <span class="notification-badge absolute -top-0.5 -right-0.5 bg-primary text-on-primary text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center" x-show="$store.app.notifCount > 0" x-text="$store.app.notifCount" style="display:none">{{ ($activeOrders ?? collect())->count() }}</span>
                        <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-secondary rounded-full animate-ping" x-show="$store.app.readyCount > 0" style="display:none"></span>
                    </button>
                    <div id="notifDropdown" class="hidden absolute right-0 top-full mt-2 w-80 bg-surface-container-lowest rounded-2xl shadow-xl border border-outline-variant/10 overflow-hidden z-50" style="transform-origin: top right;">
                        <div class="p-4 border-b border-outline-variant/10">
                            <p class="text-sm font-bold text-on-surface">Mes commandes</p>
                            <p class="text-xs text-on-surface-variant mt-0.5" x-text="$store.app.notifCount + ' commande(s) en cours'">{{ ($activeOrders ?? collect())->count() }} commande(s) en cours</p>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <template x-for="order in $store.app.activeOrders" :key="order.id">
                                <a :href="'/table/commande/' + order.id + '/suivis'" class="flex items-start gap-3 p-4 hover:bg-surface-container-low transition-colors border-b border-outline-variant/5" :class="order.status === 'ready' ? 'bg-secondary-container/20' : ''">
                                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <x-icon name="clock" class="text-primary text-lg" x-show="order.status === 'pending'" />
                                        <x-icon name="check-circle" class="text-primary text-lg" x-show="order.status === 'confirmed'" />
                                        <x-icon name="fire" class="text-primary text-lg" x-show="order.status === 'preparing'" />
                                        <x-icon name="briefcase" class="text-primary text-lg" x-show="order.status === 'ready'" />
                                        <x-icon name="document-text" class="text-primary text-lg" x-show="!['pending', 'confirmed', 'preparing', 'ready'].includes(order.status)" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-on-surface truncate" x-text="'#' + order.order_number"></p>
                                            <span class="text-xs font-bold" :class="order.status === 'ready' ? 'text-secondary' : 'text-primary'" x-text="order.total"></span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant mt-0.5" x-text="order.items_count + ' article(s)'"></p>
                                        <div class="mt-1.5">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold" :class="order.status === 'ready' ? 'bg-secondary-container text-secondary animate-pulse' : (order.status === 'preparing' ? 'bg-[#ff9800]/10 text-[#cc7a00]' : 'bg-surface-container-high text-on-surface-variant')">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#cc7a00] animate-pulse" x-show="order.status === 'preparing'"></span>
                                                <span x-text="order.status_label"></span>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </template>
                            <div class="p-6 text-center" x-show="$store.app.notifCount === 0">
                                <x-icon name="check-circle" class="text-3xl text-on-surface-variant/30" />
                                <p class="text-sm text-on-surface-variant mt-2">Aucune commande en cours</p>
                            </div>
                        </div>
                        <a href="{{ route('table.historique') }}" class="block p-3 text-center text-xs font-semibold text-primary hover:bg-surface-container-low transition-colors border-t border-outline-variant/10">
                            Voir l'historique
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="pt-16 pb-24">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-md border-t border-outline-variant/10 md:hidden">
        <div class="flex items-center justify-around h-16 px-2">
            <a href="{{ route('table.menu') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('table.menu*') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant' }}">
                <x-icon name="book-open" class="text-xl" :filled="request()->routeIs('table.menu*')" />
                <span class="text-[10px] font-semibold">Menu</span>
            </a>
            <a href="{{ route('table.service') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('table.service') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant' }}">
                <x-icon name="user-group" class="text-xl" :filled="request()->routeIs('table.service')" />
                <span class="text-[10px] font-semibold">Service</span>
            </a>
            <a href="{{ route('table.historique') }}" class="flex flex-col items-center gap-0.5 px-3 py-1 rounded-xl {{ request()->routeIs('table.historique') ? 'bg-primary-container text-on-primary-container' : 'text-on-surface-variant' }}">
                <x-icon name="document-text" class="text-xl" :filled="request()->routeIs('table.historique')" />
                <span class="text-[10px] font-semibold">Commandes</span>
            </a>
        </div>
    </nav>

    <div class="h-24 md:h-0"></div>

    <script>
        function toggleNotifications() {
            const dropdown = document.getElementById('notifDropdown');
            if (!dropdown) return;
            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                dropdown.style.transition = 'all 0.2s ease-out';
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'scale(0.95)';
                requestAnimationFrame(() => {
                    dropdown.style.opacity = '1';
                    dropdown.style.transform = 'scale(1)';
                });
                window.Alpine && (window.Alpine.$store.app.readyPing = false);
            } else {
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'scale(0.95)';
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            }
        }
        document.addEventListener('click', function(e) {
            const bell = document.getElementById('notificationBell');
            if (bell && !bell.contains(e.target)) {
                const dropdown = bell.querySelector('#notifDropdown');
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.style.opacity = '0';
                    dropdown.style.transform = 'scale(0.95)';
                    setTimeout(() => dropdown.classList.add('hidden'), 200);
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
