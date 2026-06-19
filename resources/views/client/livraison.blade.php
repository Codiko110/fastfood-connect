@extends('layouts.client')

@section('title', 'Livraison - FlashFood')

@section('back', route('client.panier'))

@section('content')
<div class="px-4 pt-4 pb-24">
    {{-- Progress Stepper --}}
    <div class="mb-8 flex items-center justify-center gap-2 text-sm">
        <div class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white font-bold text-xs">1</span>
            <span class="font-semibold text-primary">Livraison</span>
        </div>
        <span class="h-px w-10 bg-primary"></span>
        <div class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-outline/30 bg-surface-container-lowest text-on-surface-variant font-bold text-xs">2</span>
            <span class="text-on-surface-variant">Paiement</span>
        </div>
        <span class="h-px w-10 bg-outline/20"></span>
        <div class="flex items-center gap-2">
            <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-outline/30 bg-surface-container-lowest text-on-surface-variant font-bold text-xs">3</span>
            <span class="text-on-surface-variant">Confirmation</span>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        {{-- Main Content --}}
        <div>
            {{-- Contact Form --}}
            <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <h2 class="mb-5 text-lg font-bold text-on-surface">Adresse de livraison</h2>

                @if(session('success'))
                    <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
                @endif

                <form action="{{ route('client.paiement') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="latitude" x-model="lat">
                    <input type="hidden" name="longitude" x-model="lng">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-on-surface">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Jean Dupont" class="w-full rounded-2xl border border-outline/30 bg-surface-container-low px-4 py-3 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary focus:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                        @error('name')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-on-surface">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="06 12 34 56 78" class="w-full rounded-2xl border border-outline/30 bg-surface-container-low px-4 py-3 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary focus:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                        @error('phone')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-on-surface">Adresse</label>
                        <div class="flex gap-2">
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="123 Rue de la Paix" class="flex-1 rounded-2xl border border-outline/30 bg-surface-container-low px-4 py-3 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary focus:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                            <button type="button" class="rounded-2xl border border-outline/30 bg-surface-container-low px-3 py-3 text-on-surface-variant transition-colors" title="Me localiser">
                                <x-icon name="viewfinder-circle" />
                            </button>
                            <button type="button" class="rounded-2xl border border-outline/30 bg-surface-container-low px-3 py-3 text-on-surface-variant transition-colors" title="Choisir sur la carte">
                                <x-icon name="map" />
                            </button>
                        </div>
                        @error('address')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full rounded-2xl bg-primary py-3.5 font-bold text-white shadow-[0px_4px_20px_rgba(183,26,26,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(183,26,26,0.35)] active:scale-[0.98] flex items-center justify-center gap-2">
                        Continuer vers le paiement
                        <x-icon name="arrow-right" />
                    </button>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:sticky lg:top-20 lg:self-start space-y-4">
            {{-- Delivery Stats --}}
            <div class="rounded-2xl bg-surface-container-lowest p-5 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <h3 class="mb-3 font-bold text-on-surface">Livraison</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-2">
                            <x-icon name="arrows-right-left" class="text-sm" />
                            Distance
                        </span>
                        <span class="font-medium text-on-surface">2.3 km</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-2">
                            <x-icon name="clock" class="text-sm" />
                            Temps estimé
                        </span>
                        <span class="font-medium text-on-surface">25-35 min</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-2">
                            <x-icon name="truck" class="text-sm" />
                            Frais de livraison
                        </span>
                        <span class="font-medium text-primary">{{ price(0.6) }}</span>
                    </div>
                </div>
            </div>

            {{-- Order Summary Mini --}}
            <div class="rounded-2xl bg-surface-container-lowest p-5 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                <h3 class="mb-3 font-bold text-on-surface">Votre commande</h3>
                <div class="space-y-2 text-sm">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">{{ $item->quantity }}x {{ $item->product->name }}</span>
                            <span class="font-medium text-on-surface">{{ price($item->total_price) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 border-t border-outline/10 pt-3">
                    @php
                        $subtotal = $cart->items->sum('total_price');
                        $delivery = 0.6;
                        $total = $subtotal + $delivery;
                    @endphp
                    <div class="flex justify-between font-bold">
                        <span class="text-on-surface">Total</span>
                        <span class="text-primary">{{ price($total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('input[name="search"]')?.addEventListener('keyup', function() {
        clearTimeout(this._timeout);
        this._timeout = setTimeout(() => this.form.submit(), 500);
    });
</script>
@endpush
