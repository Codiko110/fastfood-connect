@extends('layouts.admin')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Gestion des Commandes</h1>
            <p class="text-on-surface-variant mt-1">Suivez et gérez toutes les commandes en temps réel.</p>
        </div>
    </div>

    <x-flash-messages />

    {{-- Search --}}
    <div class="mb-6">
        <form action="{{ route('admin.orders') }}" method="GET">
            <div class="relative max-w-md">
                <x-icon name="magnifying-glass" class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" />
                <input type="text" name="search" placeholder="Rechercher une commande..." value="{{ request('search') }}" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                @if(request('search'))
                    <a href="{{ route('admin.orders') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                        <x-icon name="x-mark" class="text-lg" />
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Status Filter Chips --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-primary text-on-primary" data-filter="all">
            Toutes <span class="ml-1.5 opacity-80">{{ $orders->total() }}</span>
        </button>
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80" data-filter="pending">
            Nouvelles
        </button>
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80" data-filter="confirmed">
            Confirmées
        </button>
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80" data-filter="preparing">
            En préparation
        </button>
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80" data-filter="ready">
            Prêtes
        </button>
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80" data-filter="delivered">
            Livrées
        </button>
        <button class="filter-chip px-4 py-2 rounded-full text-sm font-semibold transition-all bg-surface-container-high text-on-surface hover:bg-surface-container-high/80" data-filter="cancelled">
            Annulées
        </button>
    </div>

    {{-- Orders Table --}}
    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden card-shadow">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/10">
                        <th class="text-left px-6 py-4 text-sm font-semibold text-on-surface-variant"># N° Commande</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-on-surface-variant">Client</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-on-surface-variant">Type</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-on-surface-variant">Montant</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-on-surface-variant">Statut</th>
                        <th class="text-right px-6 py-4 text-sm font-semibold text-on-surface-variant">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b border-outline-variant/10 transition-all hover:bg-surface-container-low/50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.details', $order) }}" class="font-semibold text-primary hover:underline">#{{ $order->order_number }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $order->type === 'table' ? 'bg-secondary/10' : 'bg-primary/10' }} flex items-center justify-center">
                                        <x-icon name="{{ $order->type === 'table' ? 'table_restaurant' : 'person' }}" class="{{ $order->type === 'table' ? 'text-secondary' : 'text-primary' }} text-lg" />
                                    </div>
                                    <div>
                                        @if($order->type === 'table' && $order->table)
                                            <p class="text-sm font-semibold text-on-surface">Table T{{ $order->table->table_number }}</p>
                                            <p class="text-xs text-on-surface-variant">{{ $order->customer_name }}</p>
                                        @else
                                            <p class="text-sm font-semibold text-on-surface">{{ $order->customer_name ?? 'Anonyme' }}</p>
                                            @if($order->customer_phone)
                                                <p class="text-xs text-on-surface-variant">{{ $order->customer_phone }}</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    @php $typeIcon = ['table' => 'table_restaurant', 'takeaway' => 'takeout_dining', 'delivery' => 'delivery_dining']; @endphp
                                    <x-icon name="{{ $typeIcon[$order->type] ?? 'receipt' }}" class="text-sm text-on-surface-variant" />
                                    <span class="text-sm text-on-surface capitalize">{{ str_replace('_', ' ', $order->type) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-on-surface">{{ price($order->total) }}</td>
                            <td class="px-6 py-4">
                                @php $statusColors = ['pending' => 'bg-blue-50 text-blue-700', 'confirmed' => 'bg-green-50 text-green-700', 'preparing' => 'bg-[#ff9800]/10 text-[#cc7a00]', 'ready' => 'bg-yellow-50 text-yellow-700', 'delivered' => 'bg-gray-100 text-gray-600', 'cancelled' => 'bg-red-50 text-red-600']; @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    @if($order->status === 'preparing')<span class="w-1.5 h-1.5 rounded-full bg-[#cc7a00] animate-pulse"></span>@endif
                                    @php $statusLabels = ['pending' => 'Nouvelle', 'confirmed' => 'Confirmée', 'preparing' => 'En préparation', 'ready' => 'Prête', 'delivered' => 'Livrée', 'cancelled' => 'Annulée']; @endphp
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.orders.details', $order) }}" class="p-2 rounded-lg hover:bg-surface-container-low transition-all" title="Voir détails">
                                        <x-icon name="eye" class="text-on-surface-variant text-lg" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-on-surface-variant">Aucune commande trouvée</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/10 bg-surface-container-low/30">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            document.querySelectorAll('.filter-chip').forEach(c => {
                c.classList.remove('bg-primary', 'text-on-primary');
                c.classList.add('bg-surface-container-high', 'text-on-surface');
            });
            this.classList.remove('bg-surface-container-high', 'text-on-surface');
            this.classList.add('bg-primary', 'text-on-primary');
        });
    });

    document.querySelectorAll('.card-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 8px 30px rgba(0,0,0,0.08)';
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
