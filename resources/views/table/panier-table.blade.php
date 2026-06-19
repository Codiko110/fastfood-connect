@extends('layouts.table')

@section('title', 'Panier - FlashFood')
@section('back', route('table.menu'))
@section('table_title', 'Table ' . $tableNumber)

@push('styles')
<style>
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    .primary-glow { box-shadow: 0 0 20px rgba(183, 26, 26, 0.3); }
    .sticky-summary { position: sticky; top: 5rem; }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-item { animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush

@section('content')
<div class="px-4 py-4 max-w-7xl mx-auto">
    @if(session('success'))
    <div class="mb-4 p-4 rounded-2xl bg-[#e8f5e9] text-[#2e7d32] text-sm font-semibold flex items-center gap-2">
        <x-icon name="check-circle" class="text-lg" filled />
        {{ session('success') }}
    </div>
    @endif

    <h1 class="text-xl font-extrabold text-on-surface mb-1">Commande pour la Table N°{{ $tableNumber }}</h1>
    <p class="text-xs text-on-surface-variant/60 mb-6">{{ $cart->items->count() }} article(s) dans votre panier</p>

    @if($cart->items->isEmpty())
    <div class="flex flex-col items-center justify-center py-20 text-center">
        <x-icon name="shopping-cart" class="text-6xl text-on-surface-variant/20 mb-4" :filled="0" />
        <p class="text-lg font-extrabold text-on-surface-variant/60 mb-1">Votre panier est vide</p>
        <p class="text-xs text-on-surface-variant/40 mb-6">Ajoutez des articles depuis le menu</p>
        <a href="{{ route('table.menu') }}"
            class="py-3 px-6 rounded-2xl font-extrabold text-sm bg-primary text-on-primary hover:bg-primary/90 transition-all">
            Voir le menu
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-3" id="cartItemsContainer">
            @foreach($cart->items as $item)
            <div class="cart-item bg-surface-container-lowest rounded-2xl card-shadow p-4 flex items-center gap-4" data-id="{{ $item->id }}" data-price="{{ $item->unit_price }}">
                <div class="w-16 h-16 rounded-xl bg-primary-fixed/20 flex items-center justify-center flex-shrink-0">
                    <x-icon name="cake" class="text-primary/30" />
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-extrabold text-sm text-on-surface truncate">{{ $item->product->name }}</h3>
                    <p class="text-sm font-bold text-primary mt-0.5">{{ price($item->unit_price) }}</p>
                </div>

                <form method="POST" action="{{ route('table.panier.update', $item) }}" class="flex items-center gap-2 bg-surface-container-low rounded-xl p-1">
                    @csrf
                    <button type="submit" name="quantity" value="{{ max(1, $item->quantity - 1) }}"
                        class="qty-minus w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high transition-colors text-on-surface-variant">
                        <x-icon name="minus" class="text-lg" />
                    </button>
                    <span class="qty-value w-6 text-center font-extrabold text-on-surface text-sm">{{ $item->quantity }}</span>
                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                        class="qty-plus w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-high transition-colors text-on-surface-variant">
                        <x-icon name="plus" class="text-lg" />
                    </button>
                </form>

                <div class="text-right min-w-[60px]">
                    <span class="line-total font-extrabold text-on-surface text-sm">{{ price($item->total_price) }}</span>
                </div>

                <form method="POST" action="{{ route('table.panier.remove', $item) }}">
                    @csrf
                    <button type="submit" class="delete-item p-2 rounded-xl hover:bg-error-container/50 transition-colors text-on-surface-variant hover:text-error">
                        <x-icon name="trash" class="text-xl" />
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        <div class="lg:col-span-1">
            <div class="sticky-summary bg-surface-container-lowest rounded-2xl card-shadow p-6">
                <h3 class="font-extrabold text-sm text-on-surface mb-5">Récapitulatif</h3>

                @php
                $subtotal = $cart->items->sum('total_price');
                $total = $subtotal;
                @endphp

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-on-surface-variant">Sous-total</span>
                        <span class="font-bold text-on-surface" id="subtotalDisplay">{{ price($subtotal) }}</span>
                    </div>
                    <div class="border-t border-outline-variant/20 pt-3 flex items-center justify-between">
                        <span class="font-extrabold text-on-surface">Total</span>
                        <span class="text-xl font-extrabold text-primary" id="totalDisplay">{{ price($total) }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('table.commander') }}" class="mt-6 space-y-3">
                    @csrf
                    <button type="submit"
                        class="w-full py-4 rounded-2xl font-extrabold text-base bg-primary text-on-primary hover:bg-primary/90 transition-all duration-300 hover:primary-glow active:scale-[0.98]">
                        Valider la commande
                    </button>
                    <a href="{{ route('table.menu') }}"
                        class="w-full py-3.5 rounded-2xl font-bold text-sm bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high transition-all duration-300 flex items-center justify-center gap-2">
                        <x-icon name="arrow-left" class="text-lg" />
                        Continuer mes achats
                    </a>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
