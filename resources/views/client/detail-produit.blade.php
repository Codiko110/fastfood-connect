@extends('layouts.client')

@section('title', $product->name . ' - FlashFood')

@section('back', route('client.menu'))

@section('content')
    <div class="px-4 pt-4 pb-24">
        @if(session('success'))
            <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif

        {{-- Image Gallery --}}
        <div class="mb-6 overflow-hidden rounded-2xl bg-primary-fixed">
            <div class="flex aspect-[16/9] items-center justify-center">
                <x-icon name="cake" class="text-7xl text-white/60" />
            </div>
        </div>

        {{-- Product Info --}}
        <div class="mb-6">
            @if($product->category)
                <span class="inline-block rounded-full bg-secondary-container px-4 py-1 text-xs font-semibold text-secondary">{{ $product->category->name }}</span>
            @endif
            <h1 class="mt-2 text-2xl font-extrabold text-on-surface">{{ $product->name }}</h1>

            {{-- Rating --}}
            <div class="mt-1 flex items-center gap-2">
                <div class="flex">
                    @php $rating = round($product->reviews->avg('rating') ?? 0); @endphp
                    @for($s = 1; $s <= 5; $s++)
                        <x-icon name="star" class="text-lg text-secondary-container" :filled="$s <= $rating" />
                    @endfor
                </div>
                <span class="text-sm text-on-surface-variant">({{ $product->reviews->count() }} avis)</span>
            </div>

            {{-- Price --}}
            <div class="mt-3 flex items-baseline gap-3">
                <span class="text-3xl font-extrabold text-primary">{{ price($product->price) }}</span>
                @if($product->original_price)
                    <span class="text-lg text-on-surface-variant line-through">{{ price($product->original_price) }}</span>
                @endif
            </div>

            {{-- Description --}}
            <p class="mt-4 text-sm leading-relaxed text-on-surface-variant">
                {{ $product->description }}
            </p>
        </div>

        {{-- Customization Extras --}}
        @if($product->extras->isNotEmpty())
            <div class="mb-6">
                <h2 class="mb-3 text-lg font-bold text-on-surface">Personnalisez votre plat</h2>
                <div class="space-y-3">
                    @foreach($product->extras as $extra)
                        <label class="flex cursor-pointer items-center justify-between rounded-2xl border border-outline/20 bg-surface-container-lowest px-4 py-3 transition-all hover:border-primary/40">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="extras[]" value="{{ $extra->id }}" form="addToCartForm" class="h-4 w-4 rounded border-outline/30 text-primary focus:ring-primary">
                                <span class="text-sm font-medium text-on-surface">{{ $extra->name }}</span>
                            </div>
                            <span class="text-sm font-semibold text-primary">+{{ price($extra->price) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Quantity Selector & Total --}}
        <div class="mb-6 flex items-center justify-between rounded-2xl bg-surface-container-lowest p-4 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
            <div class="flex items-center gap-4">
                <button type="button" onclick="decrementQty()" class="flex h-10 w-10 items-center justify-center rounded-xl border border-outline/30 text-on-surface transition-all hover:bg-primary hover:text-white active:scale-95">
                    <x-icon name="minus" />
                </button>
                <span id="qtyDisplay" class="min-w-[2rem] text-center text-lg font-bold text-on-surface">1</span>
                <button type="button" onclick="incrementQty()" class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white transition-all hover:bg-primary/90 active:scale-95">
                    <x-icon name="plus" />
                </button>
            </div>
            <span id="totalPriceDisplay" class="text-xl font-extrabold text-primary">{{ price($product->price) }}</span>
        </div>

        {{-- Add to Cart --}}
        <form id="addToCartForm" action="{{ route('client.panier.ajouter', $product) }}" method="POST">
            @csrf
            <input type="hidden" name="quantity" id="qtyInput" value="1">
            <button type="submit" class="primary-glow w-full rounded-2xl bg-primary py-4 font-bold text-white shadow-[0px_4px_20px_rgba(183,26,26,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(183,26,26,0.35)] active:scale-[0.98] flex items-center justify-center gap-3">
                <x-icon name="shopping-cart" />
                Ajouter au panier
            </button>
        </form>

        {{-- Recommended Products --}}
        @if($recommended->isNotEmpty())
            <div class="mt-10">
                <h2 class="mb-4 text-xl font-bold text-on-surface">Vous aimerez aussi</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($recommended as $rec)
                        <a href="{{ route('client.detail', $rec) }}" class="group rounded-2xl bg-surface-container-lowest p-3 shadow-[0px_4px_20px_rgba(33,33,33,0.08)] transition-all hover:-translate-y-1 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
                            <div class="mb-3 flex aspect-[4/3] items-center justify-center overflow-hidden rounded-xl bg-primary-fixed">
                                <x-icon name="cake" class="text-4xl text-white/60" />
                            </div>
                            <h3 class="font-bold text-on-surface">{{ $rec->name }}</h3>
                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-lg font-bold text-primary">{{ price($rec->price) }}</span>
                                <span class="flex items-center gap-1 text-xs text-on-surface-variant">
                                    <x-icon name="clock" class="text-sm" />
                                    {{ $rec->preparation_time }} min
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    const basePrice = {{ $product->price }};
    const qtyDisplay = document.getElementById('qtyDisplay');
    const qtyInput = document.getElementById('qtyInput');
    const totalDisplay = document.getElementById('totalPriceDisplay');
    let qty = 1;

    function formatAr(amount) {
        const ar = Math.round(amount * 5000);
        return ar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' Ar';
    }

    function updateTotal() {
        qtyDisplay.textContent = qty;
        qtyInput.value = qty;
        totalDisplay.textContent = formatAr(qty * basePrice);
    }

    function incrementQty() {
        qty++;
        updateTotal();
    }

    function decrementQty() {
        if (qty > 1) {
            qty--;
            updateTotal();
        }
    }
</script>
@endpush
