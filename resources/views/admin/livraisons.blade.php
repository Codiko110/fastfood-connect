@extends('layouts.admin')

@section('title', 'Gestion des Livraisons')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-on-surface">Gestion des Livraisons</h1>
            <p class="text-on-surface-variant mt-1">Suivez les livreurs et les livraisons en temps réel.</p>
        </div>
        <div class="flex items-center gap-2 mt-4 sm:mt-0 px-4 py-2 rounded-xl bg-green-50 border border-green-200">
            <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-sm font-semibold text-green-700">{{ $activeDeliveries }} Livraison(s) active(s)</span>
        </div>
    </div>

    <x-flash-messages />

    {{-- Search --}}
    <div class="mb-6">
        <form action="{{ route('admin.deliveries') }}" method="GET">
            <div class="relative max-w-md">
                <x-icon name="magnifying-glass" class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" />
                <input type="text" name="search" placeholder="Rechercher une livraison..." value="{{ request('search') }}" class="w-full pl-12 pr-4 py-3 bg-surface-container-lowest rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                @if(request('search'))
                    <a href="{{ route('admin.deliveries') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                        <x-icon name="x-mark" class="text-lg" />
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left & Middle --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Delivery List --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                <h3 class="text-lg font-bold text-on-surface mb-4">Livraisons</h3>
                @if($deliveries->count() > 0)
                    <div class="space-y-3">
                        @foreach($deliveries as $delivery)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low transition-all hover:bg-surface-container-high">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <x-icon name="document-text" class="text-primary" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">#{{ $delivery->order->order_number ?? 'N/A' }}</p>
                                        <p class="text-xs text-on-surface-variant">
                                            @if($delivery->delivery_person_name)
                                                {{ $delivery->delivery_person_name }}
                                            @else
                                                Non assigné
                                            @endif
                                            · {{ ucfirst($delivery->status) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php $statusBadge = ['pending' => 'bg-blue-50 text-blue-700', 'assigned' => 'bg-[#ff9800]/10 text-[#cc7a00]', 'in_transit' => 'bg-yellow-50 text-yellow-700', 'delivered' => 'bg-green-50 text-green-700', 'failed' => 'bg-red-50 text-red-600']; @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge[$delivery->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($delivery->status) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-on-surface-variant text-center py-8">Aucune livraison</p>
                @endif

                @if($deliveries->hasPages())
                    <div class="mt-4 pt-4 border-t border-outline-variant/10">
                        {{ $deliveries->links() }}
                    </div>
                @endif
            </div>

            {{-- Pending Deliveries --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                <h3 class="text-lg font-bold text-on-surface mb-4">En attente d'assignation ({{ $pendingDeliveries }})</h3>
                @php $pendingDels = $deliveries->where('status', 'pending'); @endphp
                @if($pendingDels->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingDels as $delivery)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low transition-all hover:bg-surface-container-high">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <x-icon name="document-text" class="text-primary" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">#{{ $delivery->order->order_number ?? 'N/A' }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $delivery->order->customer_name ?? 'Client' }} · {{ $delivery->order->customer_address ?? '' }}</p>
                                    </div>
                                </div>
                                <form action="{{ route('admin.deliveries.assign', $delivery) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="text" name="delivery_person_name" placeholder="Nom livreur" required class="w-32 px-3 py-2 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-sm font-semibold transition-all hover:shadow-lg hover:scale-105">Assigner</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-on-surface-variant text-center py-4">Aucune livraison en attente</p>
                @endif
            </div>
        </div>

        {{-- Right: Status Update --}}
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
            <h3 class="text-lg font-bold text-on-surface mb-4">Mettre à jour le statut</h3>
            @if($deliveries->count() > 0)
                <div class="space-y-3">
                    @foreach($deliveries as $delivery)
                        <div class="p-4 rounded-xl bg-surface-container-low">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-sm font-semibold text-on-surface">#{{ $delivery->order->order_number ?? 'N/A' }}</span>
                                <span class="text-xs text-on-surface-variant">{{ $delivery->delivery_person_name ?? 'Non assigné' }}</span>
                            </div>
                            <form action="{{ route('admin.deliveries.status', $delivery) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="status" class="flex-1 px-3 py-2 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                                    <option value="pending" {{ $delivery->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="assigned" {{ $delivery->status === 'assigned' ? 'selected' : '' }}>Assignée</option>
                                    <option value="in_transit" {{ $delivery->status === 'in_transit' ? 'selected' : '' }}>En cours</option>
                                    <option value="delivered" {{ $delivery->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
                                    <option value="failed" {{ $delivery->status === 'failed' ? 'selected' : '' }}>Échouée</option>
                                </select>
                                <button type="submit" class="px-3 py-2 rounded-xl bg-primary text-on-primary text-sm font-semibold transition-all hover:shadow-lg">OK</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-on-surface-variant text-center py-8">Aucune livraison</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
