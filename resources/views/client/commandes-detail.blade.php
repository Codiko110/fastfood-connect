@extends('layouts.client')

@section('title', 'Détails de la commande - FlashFood')
@section('back', route('client.commandes'))

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6" x-data="orderTracker({{ $order->id }}, '{{ $order->status }}', 6000, '{{ $statusLabels[$order->status] ?? $order->status }}', @json($initialStatuses))">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-on-surface">Détails de la commande</h1>
            <p class="text-on-surface-variant">{{ $order->order_number }}</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-semibold"
              :class="{
                  'bg-blue-50 text-blue-700': currentStatus === 'pending',
                  'bg-green-50 text-green-700': currentStatus === 'confirmed',
                  'bg-orange-50 text-orange-700': currentStatus === 'preparing',
                  'bg-yellow-50 text-yellow-700': currentStatus === 'ready',
                  'bg-gray-100 text-gray-700': currentStatus === 'delivered' || currentStatus === 'cancelled'
              }">
            <span x-text="statusLabel">{{ $order->status }}</span>
        </span>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-xl text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-surface-container-lowest rounded-2xl p-5 card-shadow">
                <h2 class="font-semibold mb-3">Articles commandés</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-3 pb-3 border-b border-outline-variant/10 last:border-0">
                        <div class="w-14 h-14 rounded-xl bg-primary-fixed/20 flex items-center justify-center flex-shrink-0">
                            <x-icon name="cake" class="text-primary" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm">{{ $item->product->name }}</p>
                            @if($item->extras)
                            <p class="text-xs text-on-surface-variant">{{ is_array($item->extras) ? implode(', ', $item->extras) : $item->extras }}</p>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-semibold text-sm">{{ price($item->total_price) }}</p>
                            <p class="text-xs text-on-surface-variant">x{{ $item->quantity }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-surface-container-lowest rounded-2xl p-5 card-shadow">
                <h2 class="font-semibold mb-3">Suivi de la commande</h2>
                <div class="space-y-3">
                    @forelse($order->statuses as $status)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                            <x-icon name="check" class="text-white text-sm" />
                        </div>
                        <div>
                            <p class="font-medium text-sm">
                                @switch($status->status)
                                    @case('pending') Commande reçue @break
                                    @case('confirmed') Confirmée @break
                                    @case('preparing') En préparation @break
                                    @case('ready') Prête @break
                                    @case('delivered') Livrée @break
                                    @case('cancelled') Annulée @break
                                    @default {{ $status->status }}
                                @endswitch
                            </p>
                            <p class="text-xs text-on-surface-variant">{{ $status->created_at->format('d/m/Y à H:i') }}</p>
                            @if($status->notes)
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $status->notes }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-on-surface-variant">Aucun suivi disponible</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @if($order->customer_address)
            <div class="bg-surface-container-lowest rounded-2xl p-5 card-shadow">
                <h3 class="font-semibold text-sm mb-2">Adresse de livraison</h3>
                <p class="text-sm text-on-surface-variant">{{ $order->customer_address }}</p>
                @if($order->customer_phone)
                <p class="text-sm text-on-surface-variant mt-1">{{ $order->customer_phone }}</p>
                @endif
            </div>
            @endif

            <div class="bg-surface-container-lowest rounded-2xl p-5 card-shadow">
                <h3 class="font-semibold text-sm mb-3">Récapitulatif</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-on-surface-variant">Sous-total</span><span>{{ price($order->subtotal) }}</span></div>
                    <div class="flex justify-between"><span class="text-on-surface-variant">Livraison</span><span>{{ price($order->delivery_fee) }}</span></div>
                    <div class="border-t border-outline-variant/10 pt-2 flex justify-between font-bold"><span>Total</span><span class="text-primary">{{ price($order->total) }}</span></div>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('client.commandes.suivis', $order) }}" class="flex-1 bg-primary text-on-primary text-center py-3 rounded-xl font-semibold text-sm primary-glow">Suivre</a>
                <form action="{{ route('client.commandes.reorder', $order) }}" method="POST" class="flex-1">
                    @csrf
                    <button class="w-full border-2 border-primary text-primary py-3 rounded-xl font-semibold text-sm">Commander à nouveau</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
