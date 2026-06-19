@props(['product', 'route' => 'client.detail', 'addToCartRoute' => 'client.panier.ajouter', 'showCategory' => false, 'showDescription' => false, 'aspect' => 'square', 'icon' => 'shopping-cart'])

@php $aspectClass = $aspect === 'square' ? 'aspect-square' : 'aspect-[4/3]'; @endphp

<div class="group rounded-2xl bg-surface-container-lowest p-3 shadow-[0px_4px_20px_rgba(33,33,33,0.08)] transition-all hover:-translate-y-1 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
    <a href="{{ route($route, $product) }}">
        <div class="relative mb-3 flex {{ $aspectClass }} items-center justify-center overflow-hidden rounded-xl bg-primary-fixed">
            <img src="{{ productImageUrl($product) }}" alt="{{ $product->name }}" class="absolute inset-0 h-full w-full object-cover">
            @if($showCategory && $product->category)
                <span class="absolute left-2 top-2 rounded-full bg-secondary-container px-3 py-1 text-xs font-semibold text-secondary">{{ $product->category->name }}</span>
            @endif
        </div>
        <h3 class="font-bold text-on-surface">{{ $product->name }}</h3>
        @if($showDescription)
            <p class="mt-1 line-clamp-2 text-sm text-on-surface-variant">{{ Str::limit($product->description, 80) }}</p>
        @endif
    </a>
    <div class="mt-{{ $showDescription ? '3' : '1' }} flex items-center justify-between">
        <span class="text-lg font-bold text-primary">{{ price($product->price) }}</span>
        <span class="flex items-center gap-1 text-xs text-on-surface-variant">
            <x-icon name="clock" class="text-sm" />
            {{ $product->preparation_time }} min
        </span>
    </div>
    <form action="{{ route($addToCartRoute, $product) }}" method="POST">
        @csrf
        <button class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-primary py-2.5 text-sm font-semibold text-white transition-all hover:bg-primary/90 active:scale-95">
            @if($icon)<x-icon name="{{ $icon }}" class="text-lg" />@endif
            Ajouter
        </button>
    </form>
</div>
