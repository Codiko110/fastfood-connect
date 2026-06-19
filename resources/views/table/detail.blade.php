@extends('layouts.table')

@section('title', $product->name . ' - FlashFood')
@section('back', route('table.menu'))
@section('table_title', $product->name)

@push('styles')
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    .primary-glow { box-shadow: 0 0 20px rgba(183, 26, 26, 0.3); }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes check { 0% { transform: scale(0); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
    .animate-spin { animation: spin 0.6s linear infinite; }
    .animate-check { animation: check 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .sticky-panel { position: sticky; top: 5rem; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="relative aspect-4/3 md:aspect-21/9 bg-surface-container-high overflow-hidden group">
        @if($product->image)
            <img src="{{ productImageUrl($product) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
            <div class="absolute inset-0 flex items-center justify-center text-primary/20">
                <x-icon name="cake" class="text-7xl" :filled="0" />
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        @if($product->is_featured)
        <div class="absolute top-4 left-4 bg-secondary-container text-on-secondary-container text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">Produit vedette</div>
        @endif
        <div class="absolute inset-0 bg-primary/[0.06] opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
    </div>

    <div class="px-4 py-5">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-primary">{{ $product->category->name }}</span>
                    <h1 class="text-2xl font-extrabold text-on-surface mt-1">{{ $product->name }}</h1>
                    <p class="text-3xl font-extrabold text-primary mt-2">{{ price($product->price) }}</p>
                    <p class="text-sm text-on-surface-variant/80 mt-3 leading-relaxed">{{ $product->description }}</p>
                    <div class="flex items-center gap-4 mt-4">
                        <span class="flex items-center gap-1.5 text-xs text-on-surface-variant/60 font-semibold">
                            <x-icon name="clock" class="text-sm" />
                            {{ $product->preparation_time ?? '10 min' }}
                        </span>
                        @if($product->is_available)
                        <span class="flex items-center gap-1.5 text-xs text-[#2e7d32] font-semibold">
                            <x-icon name="check-circle" class="text-sm" filled />
                            Disponible
                        </span>
                        @else
                        <span class="flex items-center gap-1.5 text-xs text-error font-semibold">
                            <x-icon name="no-symbol" class="text-sm" filled />
                            Indisponible
                        </span>
                        @endif
                    </div>
                </div>

                @if($product->extras->isNotEmpty())
                <div class="bg-surface-container-lowest rounded-2xl card-shadow p-5">
                    <h3 class="font-extrabold text-sm text-on-surface mb-4">Personnalisez votre plat</h3>
                    <div class="space-y-3">
                        @foreach($product->extras as $extra)
                        <label class="flex items-center justify-between p-3 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="extras[]" value="{{ $extra->id }}" data-price="{{ $extra->price }}"
                                    class="extra-checkbox w-5 h-5 rounded-lg border-2 border-outline-variant/50 text-primary focus:ring-primary/30 cursor-pointer">
                                <span class="text-sm font-semibold text-on-surface">{{ $extra->name }}</span>
                            </div>
                            <span class="text-sm font-bold text-on-surface-variant">+{{ price($extra->price) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="sticky-panel bg-surface-container-lowest rounded-2xl card-shadow p-6">
                    @if($product->is_available)
                    <form method="POST" action="{{ route('table.panier.ajouter', $product) }}" id="addToCartForm">
                        @csrf
                        <h3 class="font-extrabold text-sm text-on-surface mb-5">Votre commande</h3>

                        <div class="flex items-center justify-between mb-6">
                            <span class="text-sm font-semibold text-on-surface-variant">Quantité</span>
                            <div class="flex items-center gap-3 bg-surface-container-low rounded-xl p-1">
                                <button type="button" id="qtyMinus" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-surface-container-high transition-colors text-on-surface-variant">
                                    <x-icon name="minus" class="text-lg" />
                                </button>
                                <input type="hidden" name="quantity" id="qtyInput" value="1">
                                <span id="qtyDisplay" class="w-8 text-center font-extrabold text-on-surface text-lg">1</span>
                                <button type="button" id="qtyPlus" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-surface-container-high transition-colors text-on-surface-variant">
                                    <x-icon name="plus" class="text-lg" />
                                </button>
                            </div>
                        </div>

                        <div class="border-t border-outline-variant/20 pt-4 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-on-surface-variant">Total estimé</span>
                                <span id="totalPrice" class="text-2xl font-extrabold text-primary">{{ price($product->price) }}</span>
                            </div>
                        </div>

                        <button type="submit" id="addToCartBtn"
                            class="w-full py-4 rounded-2xl font-extrabold text-base bg-primary text-on-primary hover:bg-primary/90 transition-all duration-300 hover:primary-glow active:scale-[0.98] flex items-center justify-center gap-2">
                            <x-icon name="shopping-cart" id="btnIcon" />
                            <span id="btnText">Ajouter au panier</span>
                        </button>
                    </form>
                    @else
                    <div class="text-center py-8">
                        <x-icon name="no-symbol" class="text-4xl text-error/50 mb-3" :filled="0" />
                        <p class="text-sm font-bold text-on-surface-variant/60">Ce produit est actuellement indisponible</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @if($recommended->isNotEmpty())
        <div class="mt-10 mb-6">
            <h3 class="font-extrabold text-sm text-on-surface mb-4">Souvent commandé ensemble</h3>
            <div class="flex gap-3 overflow-x-auto scrollbar-hide -mx-4 px-4 pb-2">
                @foreach($recommended as $rec)
                <a href="{{ route('table.detail', $rec) }}"
                    class="flex-shrink-0 w-36 bg-surface-container-lowest rounded-2xl card-shadow p-4 transition-all hover:shadow-md hover:border-primary/30 border border-transparent">
                    <div class="w-full aspect-square bg-primary-fixed/20 rounded-xl flex items-center justify-center mb-3">
                        <x-icon name="building-storefront" class="text-2xl text-primary/30" />
                    </div>
                    <p class="text-xs font-bold text-on-surface truncate">{{ $rec->name }}</p>
                    <p class="text-xs font-extrabold text-primary mt-1">{{ price($rec->price) }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const basePrice = {{ $product->price }};
        const qtyDisplay = document.getElementById('qtyDisplay');
        const qtyInput = document.getElementById('qtyInput');
        const qtyMinus = document.getElementById('qtyMinus');
        const qtyPlus = document.getElementById('qtyPlus');
        const totalPrice = document.getElementById('totalPrice');
        const addBtn = document.getElementById('addToCartBtn');
        const btnIcon = document.getElementById('btnIcon');
        const btnText = document.getElementById('btnText');
        const extras = document.querySelectorAll('.extra-checkbox');

        let quantity = 1;

        function formatAr(amount) {
            const ar = Math.round(amount * 5000);
            return ar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
        }

        function updateTotal() {
            let extrasTotal = 0;
            extras.forEach(cb => {
                if (cb.checked) extrasTotal += parseFloat(cb.dataset.price);
            });
            const total = (basePrice + extrasTotal) * quantity;
            totalPrice.textContent = formatAr(total);
        }

        qtyPlus.addEventListener('click', function() {
            quantity++;
            qtyDisplay.textContent = quantity;
            qtyInput.value = quantity;
            updateTotal();
        });

        qtyMinus.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                qtyDisplay.textContent = quantity;
                qtyInput.value = quantity;
                updateTotal();
            }
        });

        extras.forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    });
</script>
@endpush
