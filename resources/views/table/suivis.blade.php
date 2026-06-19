@extends('layouts.table')

@section('title', 'Suivi commande - FlashFood')
@section('back', route('table.menu'))
@section('table_title', 'Table ' . $tableNumber)

@push('styles')
<style>
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    .primary-glow { box-shadow: 0 0 20px rgba(183, 26, 26, 0.3); }
    @keyframes pulse-ring {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.8); opacity: 0; }
    }
    .animate-pulse-ring {
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .timeline-line {
        position: absolute;
        left: 1.25rem;
        top: 2.5rem;
        bottom: 0;
        width: 2px;
    }
</style>
@endpush

@section('content')
<div class="px-4 py-4 max-w-3xl mx-auto" x-data="orderTracker({{ $order->id }}, '{{ $order->status }}', 5000, '{{ $statusLabels[$order->status] ?? $order->status }}', @json($initialStatuses))">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-lg font-extrabold text-on-surface">#{{ $order->order_number }}</h1>
            <p class="text-xs text-on-surface-variant/60 font-semibold">Suivi en direct</p>
        </div>
        <span class="text-xs font-bold px-3 py-1.5 rounded-full"
              :class="currentStatus === 'ready' ? 'bg-secondary-container text-secondary animate-pulse' : (currentStatus === 'preparing' ? 'bg-[#ff9800]/10 text-[#cc7a00]' : 'bg-surface-container-high text-on-surface-variant')"
              x-text="statusLabel">{{ $order->status }}</span>
    </div>

    <!-- <div class="bg-surface-container-lowest rounded-2xl card-shadow p-6 mb-6">
        <div class="relative">
            <template x-for="(status, index) in statuses" :key="index">
                <div class="flex items-start gap-4 pb-8 last:pb-0 relative">
                    <div class="timeline-line" x-show="index < statuses.length - 1" :style="'background: ' + (isCompleted(index) ? '#b7131a' : 'var(--color-outline-variant)') + '; opacity: 0.3;'"></div>
                    <div class="relative flex-shrink-0 z-10">
                        <div x-show="isCompleted(index)" class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-md">
                            <x-icon name="check" class="text-on-primary text-lg" filled />
                        </div>
                        <div x-show="isActive(index)" class="relative">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-md animate-pulse-ring"></div>
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-md relative z-10">
                                <x-icon name="clock" class="text-on-primary text-lg" filled x-show="['pending', 'schedule'].includes(status.status)" />
                                <x-icon name="check-circle" class="text-on-primary text-lg" filled x-show="['confirmed', 'check_circle', 'delivered'].includes(status.status)" />
                                <x-icon name="fire" class="text-on-primary text-lg" filled x-show="['preparing', 'cooking'].includes(status.status)" />
                                <x-icon name="briefcase" class="text-on-primary text-lg" filled x-show="['ready', 'checkroom'].includes(status.status)" />
                                <x-icon name="x-circle" class="text-on-primary text-lg" filled x-show="['cancelled', 'cancel'].includes(status.status)" />
                            </div>
                        </div>
                        <div x-show="!isCompleted(index) && !isActive(index)" class="w-10 h-10 rounded-full bg-outline/20 flex items-center justify-center">
                            <x-icon name="circle-stack" class="text-outline text-lg" />
                        </div>
                    </div>
                    <div class="flex-1 min-w-0 pt-1.5">
                        <p class="text-sm font-bold" :class="isCompleted(index) || isActive(index) ? 'text-on-surface' : 'text-on-surface-variant/40'" x-text="status.label"></p>
                        <p class="text-xs font-semibold mt-0.5" :class="isCompleted(index) ? 'text-primary' : 'text-on-surface-variant/40'" x-text="status.time"></p>
                    </div>
                </div>
            </template>
            <div x-show="statuses.length === 0" class="text-center py-8">
                <p class="text-sm text-on-surface-variant/60">Aucun statut disponible</p>
            </div>
        </div>
    </div> -->

    <div class="bg-surface-container-lowest rounded-2xl card-shadow p-5 mb-6">
        <h3 class="font-extrabold text-sm text-on-surface mb-4">Articles commandés</h3>
        <div class="space-y-3">
            @forelse($order->items as $item)
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-primary-fixed/20 flex items-center justify-center flex-shrink-0">
                    <x-icon name="cake" class="text-primary/30" />
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold text-on-surface">{{ $item->product->name }}</p>
                        <span class="text-xs font-extrabold text-primary">x{{ $item->quantity }}</span>
                    </div>
                    @if(!empty($item->extras))
                    <p class="text-[10px] text-on-surface-variant/60 font-medium mt-0.5">{{ is_array($item->extras) ? implode(', ', $item->extras) : $item->extras }}</p>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-on-surface-variant/60 text-center py-4">Aucun article dans cette commande</p>
            @endforelse
        </div>
    </div>

    <a href="{{ route('table.service') }}"
        class="w-full py-3.5 rounded-2xl font-bold text-sm bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container-high border-2 border-outline-variant/20 card-shadow transition-all duration-300 flex items-center justify-center gap-2">
        <x-icon name="user-group" class="text-lg" />
        Besoin d'aide ? Appeler un serveur
    </a>
</div>
@endsection
