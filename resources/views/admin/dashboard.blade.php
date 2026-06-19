@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Bonjour, {{ auth()->user()->name }}</h1>
            <p class="text-on-surface-variant mt-1">{{ now()->isoFormat('dddd D MMMM YYYY') }}</p>
        </div>
    </div>



    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div class="group relative overflow-hidden rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center shadow-md shadow-primary/20">
                    <x-icon name="document-text" class="text-on-primary text-2xl" filled />
                </div>
                <div>
                    <p class="text-sm font-medium text-on-surface-variant">Commandes</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ $totalOrders }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">{{ $pendingOrders }} en attente</span>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-32 h-32 bg-secondary/5 rounded-bl-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-secondary to-secondary/70 flex items-center justify-center shadow-md shadow-secondary/20">
                    <x-icon name="currency-dollar" class="text-on-secondary text-2xl" filled />
                </div>
                <div>
                    <p class="text-sm font-medium text-on-surface-variant">CA du jour</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ price($dailyRevenue) }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">Chiffre d'affaires aujourd'hui</span>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#ff9800]/5 rounded-bl-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#ff9800] to-[#ff9800]/70 flex items-center justify-center shadow-md shadow-[#ff9800]/20">
                    <x-icon name="clock" class="text-white text-2xl" filled />
                </div>
                <div>
                    <p class="text-sm font-medium text-on-surface-variant">En attente</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ $pendingOrders }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">Commandes à traiter</span>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#2196f3]/5 rounded-bl-full -translate-y-8 translate-x-8 group-hover:scale-110 transition-transform duration-500"></div>
            <div class="relative flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#2196f3] to-[#2196f3]/70 flex items-center justify-center shadow-md shadow-[#2196f3]/20">
                    <x-icon name="fire" class="text-white text-2xl" filled />
                </div>
                <div>
                    <p class="text-sm font-medium text-on-surface-variant">En préparation</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ $preparingOrders }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">Commandes en cuisine</span>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-on-surface">Commandes Récentes</h3>
                <a href="{{ route('admin.orders') }}" class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors">Voir tout →</a>
            </div>
            @if($recentOrders->count() > 0)
                <div class="space-y-2">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.details', $order) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface-container-low transition-all duration-200 group">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                                <x-icon name="document-text" class="text-primary text-lg" filled />
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-on-surface truncate">#{{ $order->order_number }}</p>
                                    <span class="text-xs font-semibold text-on-surface-variant">{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $order->items->count() }} art. · {{ price($order->total) }} · {{ ucfirst($order->type) }}</p>
                            </div>
                            <x-icon name="chevron-right" class="text-on-surface-variant/40 group-hover:text-primary transition-colors text-lg" />
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <x-icon name="document-text" class="text-4xl text-on-surface-variant/20" filled />
                    <p class="text-sm text-on-surface-variant mt-2">Aucune commande récente</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-primary to-primary/90 p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/5 rounded-full translate-y-16 -translate-x-16"></div>
            <div class="relative">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-white text-xs font-semibold mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    En direct
                </span>
                <h3 class="text-xl font-extrabold mb-2">FlashFood</h3>
                <p class="text-white/80 text-sm leading-relaxed">Gérez vos commandes, tables et livraisons en temps réel depuis un seul tableau de bord.</p>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('admin.orders') }}" class="flex-1 text-center py-2.5 rounded-xl bg-white/20 text-white text-sm font-bold hover:bg-white/30 transition-all">Commandes</a>
                    <a href="{{ route('admin.tables') }}" class="flex-1 text-center py-2.5 rounded-xl bg-white/20 text-white text-sm font-bold hover:bg-white/30 transition-all">Tables</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.menu') }}" class="flex items-center gap-4 p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/10 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
                <x-icon name="book-open" class="text-primary text-2xl" filled />
            </div>
            <div>
                <p class="text-sm font-bold text-on-surface">Gérer le Menu</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Plats, catégories et disponibilités</p>
            </div>
        </a>
        <a href="{{ route('admin.statistics') }}" class="flex items-center gap-4 p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/10 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center">
                <x-icon name="chart-bar" class="text-secondary text-2xl" filled />
            </div>
            <div>
                <p class="text-sm font-bold text-on-surface">Statistiques</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Revenus et performances</p>
            </div>
        </a>
        <a href="{{ route('admin.deliveries') }}" class="flex items-center gap-4 p-5 rounded-2xl bg-surface-container-lowest border border-outline-variant/10 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-[#ff9800]/10 flex items-center justify-center">
                <x-icon name="truck" class="text-[#ff9800] text-2xl" filled />
            </div>
            <div>
                <p class="text-sm font-bold text-on-surface">Livraisons</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Suivi des livreurs</p>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.card-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 12px 40px rgba(0,0,0,0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
</script>
@endpush
