@extends('layouts.client')

@section('title', 'Panier - FlashFood')

@section('back', route('client.menu'))

@section('content')
    <div class="px-4 pt-4 pb-24">
        {{-- Title --}}
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-extrabold text-on-surface">Votre Panier</h1>
            <span class="rounded-full bg-primary/10 px-3 py-1 text-sm font-semibold text-primary">{{ $cart->items->sum('quantity') }} article(s)</span>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif

        @if($cart->items->isEmpty())
            <div class="mt-10 text-center">
                <x-icon name="shopping-cart" class="text-5xl text-outline/50" />
                <p class="mt-3 text-sm text-on-surface-variant">Votre panier est vide.</p>
                <a href="{{ route('client.menu') }}" class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-primary px-6 py-3 font-semibold text-white">Voir le menu</a>
            </div>
        @else
            <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
                {{-- Cart Items --}}
                <div class="space-y-4">
                    @foreach($cart->items as $item)
                        <div class="flex gap-4 rounded-2xl bg-surface-container-lowest p-4 shadow-[0px_4px_20px_rgba(33,33,33,0.08)] transition-all hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
                            {{-- Image --}}
                            <div class="flex h-24 w-24 flex-shrink-0 items-center justify-center rounded-xl bg-primary-fixed">
                                <x-icon name="cake" class="text-3xl text-white/60" />
                            </div>
                            {{-- Details --}}
                            <div class="flex flex-1 flex-col justify-between">
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="font-bold text-on-surface">{{ $item->product->name }}</h3>
                                            @if($item->extras)
                                                <p class="text-xs text-on-surface-variant">{{ is_array($item->extras) ? implode(', ', $item->extras) : $item->extras }}</p>
                                            @endif
                                        </div>
                                        <form action="{{ route('client.panier.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-on-surface-variant hover:text-error transition-colors">
                                                <x-icon name="trash" class="text-xl" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <form action="{{ route('client.panier.update', $item) }}" method="POST" class="flex items-center gap-3">
                                        @csrf
                                        <button type="button" onclick="decrementItemQty(this)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-outline/30 text-on-surface transition-all hover:bg-primary hover:text-white">
                                            <x-icon name="minus" class="text-sm" />
                                        </button>
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="item-qty-input w-8 text-center font-semibold text-on-surface bg-transparent outline-none" readonly>
                                        <button type="button" onclick="incrementItemQty(this)" class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-white transition-all hover:bg-primary/90">
                                            <x-icon name="plus" class="text-sm" />
                                        </button>
                                    </form>
                                    <span class="font-bold text-primary">{{ price($item->total_price) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary Sidebar --}}
                <div class="lg:sticky lg:top-20 lg:self-start">
                    <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                        <h2 class="mb-4 text-lg font-bold text-on-surface">Résumé de la commande</h2>

                        {{-- Promo Code --}}
                        <form action="{{ route('client.promo.apply') }}" method="POST" class="mb-5 flex gap-2">
                            @csrf
                            <input type="text" name="promo_code" placeholder="Code promo" class="flex-1 rounded-xl border border-outline/30 bg-surface-container-low px-4 py-2.5 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary">
                            <button type="submit" class="rounded-xl bg-secondary-container px-4 py-2.5 text-sm font-semibold text-secondary transition-all hover:bg-secondary-container/80">Appliquer</button>
                        </form>

                        {{-- Lines --}}
                        @php
                            $subtotal = $cart->items->sum('total_price');
                            $delivery = 0.6;
                            $total = $subtotal + $delivery;
                        @endphp
                        <div class="space-y-3 border-b border-outline/10 pb-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Sous-total</span>
                                <span class="font-medium text-on-surface">{{ price($subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Livraison</span>
                                <span class="font-medium text-on-surface">{{ price($delivery) }}</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between text-lg">
                            <span class="font-bold text-on-surface">Total</span>
                            <span class="font-extrabold text-primary">{{ price($total) }}</span>
                        </div>

                        {{-- CTA --}}
                        <a href="{{ route('client.livraison') }}" class="primary-glow mt-6 w-full rounded-2xl bg-primary py-3.5 font-bold text-white shadow-[0px_4px_20px_rgba(183,26,26,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(183,26,26,0.35)] active:scale-[0.98] flex items-center justify-center gap-2">
                            <x-icon name="shopping-cart" />
                            Passer la commande
                        </a>

                        {{-- Trust Badges --}}
                        <div class="mt-5 flex items-center justify-center gap-4 text-on-surface-variant">
                            <div class="flex items-center gap-1.5 text-xs">
                                <x-icon name="lock-closed" class="text-sm" filled />
                                Paiement sécurisé
                            </div>
                            <div class="flex items-center gap-1.5 text-xs">
                                <x-icon name="check-badge" class="text-sm" filled />
                                Produits frais
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.item-qty-input').forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    function incrementItemQty(btn) {
        const input = btn.closest('form').querySelector('.item-qty-input');
        input.value = parseInt(input.value) + 1;
        input.closest('form').submit();
    }

    function decrementItemQty(btn) {
        const input = btn.closest('form').querySelector('.item-qty-input');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
            input.closest('form').submit();
        }
    }
</script>
@endpush
