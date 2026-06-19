@extends('layouts.client')

@section('title', 'Suivi de commande - FlashFood')

@section('back', route('client.commandes'))

@section('content')
    <div class="px-4 pt-4 pb-24" x-data="orderTracker({{ $order->id }}, '{{ $order->status }}', 6000, '{{ $statusLabels[$order->status] ?? $order->status }}', @json($initialStatuses))"
         x-init="$store.clientOrders.save({ id: {{ $order->id }}, order_number: '{{ $order->order_number }}', status: '{{ $order->status }}', status_label: '{{ $statusLabels[$order->status] ?? $order->status }}', total: '{{ price($order->total) }}', items_count: {{ $order->items->count() }}, customer_name: '{{ addslashes($order->customer_name ?? '') }}', type: '{{ $order->type }}', created_at: '{{ $order->created_at->diffForHumans() }}' })">

        {{-- Order Status Header --}}
        <div class="mb-6 rounded-2xl bg-gradient-to-br from-primary to-primary/80 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-white/80">Commande #{{ $order->order_number }}</p>
                    <h1 class="text-2xl font-extrabold" x-text="statusLabel">{{ $statusLabels[$order->status] ?? $order->status }}</h1>
                </div>
                <span class="rounded-full bg-white/20 px-4 py-1.5 text-sm font-semibold backdrop-blur-sm" x-text="statusLabel">{{ $order->status }}</span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
            {{-- Main Content --}}
            <div>
                {{-- Horizontal Stepper --}}
                @php
                    $deliverySteps = ['Recue', 'Confirmée', 'Préparation', 'Livraison', 'Livrée'];
                @endphp
                <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <div class="flex items-center justify-between">
                        <template x-for="(step, index) in {{ json_encode($deliverySteps) }}" :key="index">
                            <div class="flex flex-col items-center gap-2">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold"
                                     :class="index < statuses.length ? 'bg-primary text-white' : 'border-2 border-outline/30 bg-surface-container-lowest text-on-surface-variant'"
                                     :class="index === statuses.length - 1 ? 'ring-4 ring-primary/20' : ''">
                                    <x-icon name="check" class="text-sm" x-show="index < statuses.length - 1" />
                                    <x-icon name="clock" class="text-sm" x-show="index === statuses.length - 1" />
                                    <span x-show="index >= statuses.length" x-text="index + 1" class="text-sm font-bold"></span>
                                </div>
                                <span class="text-xs font-medium" :class="index < statuses.length ? 'text-primary' : 'text-on-surface-variant'" x-text="step"></span>
                            </div>
                            <div x-show="index < {{ count($deliverySteps) - 1 }}" class="h-0.5 flex-1" :class="index < statuses.length - 1 ? 'bg-primary' : 'bg-outline/20'"></div>
                        </template>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-4 rounded-2xl bg-surface-container-lowest p-5 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-on-surface">Progression</span>
                        <span class="text-sm font-bold text-primary" x-text="Math.round((statuses.length / {{ count($deliverySteps) }}) * 100) + '%'">{{ round(($order->statuses->count() / count($deliverySteps)) * 100) }}%</span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-surface-container-low">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-primary/70 transition-all duration-1000" :style="'width: ' + Math.round((statuses.length / {{ count($deliverySteps) }}) * 100) + '%'" style="width: {{ round(($order->statuses->count() / count($deliverySteps)) * 100) }}%"></div>
                    </div>
                </div>

                {{-- Map --}}
                <div class="mt-4 flex aspect-[21/9] items-center justify-center rounded-2xl bg-surface-container-low shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <div class="text-center">
                        <x-icon name="map" class="text-5xl text-outline/50" />
                        <p class="mt-2 text-sm text-on-surface-variant">Suivi en temps réel</p>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:sticky lg:top-20 lg:self-start space-y-4">
                {{-- Delivery Partner --}}
                <div class="rounded-2xl bg-surface-container-lowest p-5 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <h3 class="mb-3 font-bold text-on-surface">Livreur</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-fixed text-white">
                            <x-icon name="user" class="text-2xl" />
                        </div>
                        <div>
                            <p class="font-semibold text-on-surface">Amadou Diallo</p>
                            <p class="text-xs text-on-surface-variant">À environ 10 min</p>
                        </div>
                    </div>
                    <button class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border border-outline/30 py-2.5 text-sm font-semibold text-on-surface transition-all hover:bg-primary hover:text-white">
                        <x-icon name="chat-bubble-left-ellipsis" />
                        Contacter
                    </button>
                </div>

                {{-- Order Summary Mini --}}
                <div class="rounded-2xl bg-surface-container-lowest p-5 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <h3 class="mb-3 font-bold text-on-surface">Récapitulatif</h3>
                    <div class="space-y-2 text-sm">
                        @foreach($order->items as $item)
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">{{ $item->quantity }}x {{ $item->product->name }}</span>
                                <span class="font-medium text-on-surface">{{ price($item->total_price) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3 border-t border-outline/10 pt-3">
                        <div class="flex justify-between font-bold">
                            <span class="text-on-surface">Total</span>
                            <span class="text-primary">{{ price($order->total) }}</span>
                        </div>
                        <div class="flex justify-between text-xs mt-1">
                            <span class="text-on-surface-variant">Adresse</span>
                            <span class="text-on-surface-variant">{{ $order->customer_address }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
