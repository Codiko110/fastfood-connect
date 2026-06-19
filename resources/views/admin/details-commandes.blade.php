@extends('layouts.admin')

@section('title', 'Détails de la Commande')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto pb-24" x-data="orderTracker({{ $order->id }}, '{{ $order->status }}', 6000, '{{ $statusLabels[$order->status] ?? $order->status }}', @json($initialStatuses))">

    {{-- Back link --}}
    <a href="{{ route('admin.orders') }}" class="inline-flex items-center gap-1.5 text-on-surface-variant hover:text-primary transition-colors mb-6">
        <x-icon name="arrow-left" class="text-lg" />
        <span class="text-sm font-semibold">Retour aux commandes</span>
    </a>

    <x-flash-messages />

    {{-- Order Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="flex items-center gap-4">
            <h1 class="text-3xl font-bold text-on-surface">#{{ $order->order_number }}</h1>
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-500"
                  :class="currentStatus === 'ready' ? 'bg-secondary-container text-secondary animate-pulse' : (currentStatus === 'preparing' ? 'bg-[#ff9800]/10 text-[#cc7a00]' : (currentStatus === 'pending' ? 'bg-blue-50 text-blue-700' : (currentStatus === 'delivered' ? 'bg-gray-100 text-gray-600' : (currentStatus === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-700'))))">
                <span class="w-2 h-2 rounded-full bg-[#cc7a00] animate-pulse" x-show="currentStatus === 'preparing'"></span>
                <span x-text="statusLabel">{{ $statusLabels[$order->status] ?? $order->status }}</span>
            </span>
        </div>
        <p class="text-on-surface-variant mt-2 sm:mt-0">Passée le {{ $order->created_at->format('d/m/Y à H\hi') }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer Info --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                <h3 class="text-lg font-bold text-on-surface mb-4">Informations Client</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <x-icon name="user" class="text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-on-surface-variant">Nom</p>
                            <p class="text-base font-semibold text-on-surface">{{ $order->customer_name ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <x-icon name="phone" class="text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-on-surface-variant">Téléphone</p>
                            <p class="text-base font-semibold text-on-surface">{{ $order->customer_phone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    @if($order->customer_address || $order->table)
                        <div class="flex items-start gap-3 sm:col-span-2">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <x-icon name="map-pin" class="text-primary" />
                            </div>
                            <div>
                                <p class="text-sm text-on-surface-variant">Adresse / Table</p>
                                <p class="text-base font-semibold text-on-surface">{{ $order->customer_address ?? ($order->table ? 'Table ' . $order->table->table_number : 'Non renseigné') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($order->type === 'delivery' && $order->latitude && $order->longitude)
                        <div class="sm:col-span-2 mt-2">
                            <div id="route-map" class="w-full h-[250px] rounded-xl overflow-hidden border border-outline/10"></div>
                            <p class="text-xs text-on-surface-variant mt-1.5">
                                <x-icon name="truck" class="text-xs align-text-bottom" />
                                Itinéraire restaurant → client
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                <h3 class="text-lg font-bold text-on-surface mb-4">Articles commandés</h3>
                @if($order->items->count() > 0)
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-surface-container-low">
                                <div class="w-16 h-16 rounded-xl bg-surface-container-high flex items-center justify-center flex-shrink-0">
                                    <x-icon name="cake" class="text-3xl text-on-surface-variant" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-on-surface">{{ $item->product->name ?? 'Produit' }}</p>
                                    @if($item->extras && count($item->extras) > 0)
                                        <p class="text-xs text-on-surface-variant">{{ implode(', ', $item->extras) }}</p>
                                    @endif
                                </div>
                                <span class="text-sm text-on-surface-variant font-semibold">x{{ $item->quantity }}</span>
                                <span class="text-sm font-bold text-on-surface">{{ price($item->total_price) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-on-surface-variant text-center py-4">Aucun article</p>
                @endif

                {{-- Total Summary --}}
                <div class="mt-6 pt-4 border-t border-outline-variant/10 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Sous-total</span>
                        <span class="text-on-surface">{{ price($order->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">Frais de livraison</span>
                        <span class="text-on-surface">{{ price($order->delivery_fee) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold pt-2 border-t border-outline-variant/10">
                        <span class="text-on-surface">Total</span>
                        <span class="text-primary">{{ price($order->total) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- Timeline Card --}}
            <!-- <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                <h3 class="text-lg font-bold text-on-surface mb-6">Suivi de commande</h3>
                <div class="relative" x-show="statuses.length > 0">
                    <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-outline-variant/30"></div>
                    <div class="space-y-8">
                        <template x-for="(status, index) in statuses" :key="index">
                            <div class="relative flex items-start gap-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10"
                                     :class="index === statuses.length - 1 ? 'bg-[#ff9800] ring-4 ring-[#ff9800]/20' : 'bg-green-500'">
                                    <x-icon name="ellipsis-horizontal" class="text-white text-sm" x-show="index === statuses.length - 1" />
                                    <x-icon name="check" class="text-white text-sm" x-show="index !== statuses.length - 1" />
                                </div>
                                <div class="flex-1 pt-0.5">
                                    <p class="text-sm font-bold text-on-surface" x-text="status.label"></p>
                                    <p class="text-xs text-on-surface-variant" x-text="status.time ? status.time : 'En cours...'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div x-show="statuses.length === 0">
                    <p class="text-sm text-on-surface-variant text-center py-4">Aucun suivi disponible</p>
                </div>
            </div> -->

            {{-- Notes --}}
            @if($order->notes)
                <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                    <h3 class="text-lg font-bold text-on-surface mb-3">Notes</h3>
                    <p class="text-sm text-on-surface-variant">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Fixed Bottom Action Bar --}}
<div class="fixed bottom-0 left-0 right-0 md:left-72 bg-surface-container-lowest border-t border-outline-variant/10 px-6 py-4 z-40 shadow-lg">
    <div class="max-w-[1400px] mx-auto flex items-center justify-end gap-3">
        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="confirmed">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/30 text-on-surface font-semibold transition-all hover:bg-surface-container-high">
                <x-icon name="check" class="text-lg" />
                Confirmer
            </button>
        </form>
        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="preparing">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#ff9800] text-white font-semibold transition-all hover:bg-[#e68900] hover:shadow-lg">
                <x-icon name="fire" class="text-lg" />
                Préparer
            </button>
        </form>
        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="ready">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold transition-all hover:shadow-lg hover:scale-105 primary-glow">
                <x-icon name="check-circle" class="text-lg" />
                Prête
            </button>
        </form>
        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="delivered">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-600 text-white font-bold transition-all hover:shadow-lg hover:scale-105">
                <x-icon name="truck" class="text-lg" />
                Livrée
            </button>
        </form>
        <form action="{{ route('admin.orders.status', $order) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-500 text-white font-semibold transition-all hover:bg-red-600 hover:shadow-lg">
                <x-icon name="x-circle" class="text-lg" />
                Annuler
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.card-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 30px rgba(0,0,0,0.08)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });

    @if($order->type === 'delivery' && $order->latitude && $order->longitude)
    import('leaflet').then(L => {
        const restLat = -18.8792;
        const restLng = 47.5079;
        const clientLat = {{ $order->latitude }};
        const clientLng = {{ $order->longitude }};

        const map = L.map('route-map').setView([(restLat + clientLat) / 2, (restLng + clientLng) / 2], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const restaurantIcon = L.divIcon({
            html: '<div style="background:#b7131a;color:#fff;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,0.3)">🍔</div>',
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        const clientIcon = L.divIcon({
            html: '<div style="background:#22c55e;color:#fff;border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,0.3)">📍</div>',
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 18]
        });

        L.marker([restLat, restLng], { icon: restaurantIcon }).addTo(map).bindPopup('Restaurant');
        L.marker([clientLat, clientLng], { icon: clientIcon }).addTo(map).bindPopup('Client');

        // OSRM route
        fetch(`https://router.project-osrm.org/route/v1/driving/${restLng},${restLat};${clientLng},${clientLat}?overview=full&geometries=geojson`)
            .then(r => r.json())
            .then(data => {
                if (data.routes && data.routes[0]) {
                    const route = data.routes[0];
                    L.geoJSON(route.geometry, {
                        style: { color: '#b7131a', weight: 5, opacity: 0.8 }
                    }).addTo(map);

                    const dist = (route.distance / 1000).toFixed(1);
                    const time = Math.round(route.duration / 60);
                    map.bindPopup(`<b>${dist} km</b> — ${time} min`).openPopup();
                }
            })
            .catch(() => {});

        setTimeout(() => map.invalidateSize(), 500);
    });
    @endif
</script>
@endpush
