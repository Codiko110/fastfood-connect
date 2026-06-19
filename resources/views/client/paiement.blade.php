@extends('layouts.client')

@section('title', 'Paiement - FlashFood')

@section('back', route('client.livraison'))

@section('content')
    <div class="px-4 pt-4 pb-24">
        {{-- Progress Stepper --}}
        <div class="mb-8 flex items-center justify-center gap-2 text-sm">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white font-bold text-xs">
                    <x-icon name="check" class="text-sm" />
                </span>
                <span class="text-on-surface-variant">Livraison</span>
            </div>
            <span class="h-px w-10 bg-primary"></span>
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-white font-bold text-xs">2</span>
                <span class="font-semibold text-primary">Paiement</span>
            </div>
            <span class="h-px w-10 bg-outline/20"></span>
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-outline/30 bg-surface-container-lowest text-on-surface-variant font-bold text-xs">3</span>
                <span class="text-on-surface-variant">Confirmation</span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
            {{-- Payment Content --}}
            <div>
                <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <h2 class="mb-5 text-lg font-bold text-on-surface">Mode de paiement</h2>

                    @if(session('success'))
                        <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
                    @endif

                    <form id="paymentForm" action="{{ route('client.commander') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" value="{{ session('delivery_info.latitude') }}">
                        <input type="hidden" name="longitude" value="{{ session('delivery_info.longitude') }}">

                        {{-- Payment Methods --}}
                        <div class="space-y-3">
                            <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-primary bg-primary/5 p-4 transition-all">
                                <input type="radio" name="payment_method" value="card" checked class="h-4 w-4 text-primary focus:ring-primary">
                                <x-icon name="credit-card" class="text-primary" />
                                <div>
                                    <span class="font-semibold text-on-surface">Carte bancaire</span>
                                    <p class="text-xs text-on-surface-variant">Visa, Mastercard, CB</p>
                                </div>
                            </label>
                            <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-outline/20 bg-surface-container-lowest p-4 transition-all hover:border-primary/40">
                                <input type="radio" name="payment_method" value="cash" class="h-4 w-4 text-primary focus:ring-primary">
                                <x-icon name="currency-dollar" class="text-on-surface-variant" />
                                <div>
                                    <span class="font-semibold text-on-surface">Espèces</span>
                                    <p class="text-xs text-on-surface-variant">Paiement à la livraison</p>
                                </div>
                            </label>
                            <label class="flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-outline/20 bg-surface-container-lowest p-4 transition-all hover:border-primary/40">
                                <input type="radio" name="payment_method" value="mobile" class="h-4 w-4 text-primary focus:ring-primary">
                                <x-icon name="device-phone-mobile" class="text-on-surface-variant" />
                                <div>
                                    <span class="font-semibold text-on-surface">Mobile Money</span>
                                    <p class="text-xs text-on-surface-variant">Orange Money, MTN, etc.</p>
                                </div>
                            </label>
                        </div>

                        {{-- Card Form (conditional) --}}
                        <div class="mt-6 space-y-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-on-surface">Numéro de carte</label>
                                <input type="text" name="card_number" placeholder="1234 5678 9012 3456" class="w-full rounded-2xl border border-outline/30 bg-surface-container-low px-4 py-3 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary focus:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-on-surface">Date d'expiration</label>
                                    <input type="text" name="expiry" placeholder="MM/AA" class="w-full rounded-2xl border border-outline/30 bg-surface-container-low px-4 py-3 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary focus:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-on-surface">CVV</label>
                                    <input type="text" name="cvv" placeholder="123" class="w-full rounded-2xl border border-outline/30 bg-surface-container-low px-4 py-3 text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60 focus:border-primary focus:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Sidebar --}}
            <div class="lg:sticky lg:top-20 lg:self-start space-y-4">
                <div class="rounded-2xl bg-surface-container-lowest p-6 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
                    <h3 class="mb-4 font-bold text-on-surface">Résumé</h3>
                    @php
                        $subtotal = $cart->items->sum('total_price');
                        $delivery = 0.6;
                        $total = $subtotal + $delivery;
                    @endphp
                    <div class="space-y-3 text-sm border-b border-outline/10 pb-4">
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

                    <button type="submit" form="paymentForm" class="primary-glow mt-6 w-full rounded-2xl bg-primary py-3.5 font-bold text-white shadow-[0px_4px_20px_rgba(183,26,26,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(183,26,26,0.35)] active:scale-[0.98] flex items-center justify-center gap-2">
                        <x-icon name="lock-closed" />
                        Payer {{ price($total) }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="payment_method"]').forEach(r => {
                r.closest('label').className = 'flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-outline/20 bg-surface-container-lowest p-4 transition-all hover:border-primary/40';
            });
            this.closest('label').className = 'flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-primary bg-primary/5 p-4 transition-all';
        });
    });
</script>
@endpush
