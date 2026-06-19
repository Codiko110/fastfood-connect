@extends('layouts.table')

@section('title', 'Historique - FlashFood')
@section('back', route('table.menu'))
@section('table_title', 'Table ' . $tableNumber)

@push('styles')
<style>
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endpush

@section('content')
<div class="px-4 py-4 max-w-4xl mx-auto">

    @if(session('success'))
    <div class="mb-4 p-4 rounded-2xl bg-[#e8f5e9] text-[#2e7d32] text-sm font-semibold flex items-center gap-2">
        <x-icon name="check-circle" class="text-lg" filled />
        {{ session('success') }}
    </div>
    @endif

    <h1 class="text-xl font-extrabold text-on-surface mb-1">Historique des Commandes</h1>
    <p class="text-xs text-on-surface-variant/60 mb-6">Retrouvez toutes vos commandes passées</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-3">
            @forelse($orders as $index => $order)
            @php
            $statusColors = [
                'pending' => 'bg-[#fef7e0] text-[#785900]',
                'confirmed' => 'bg-[#e8f5e9] text-[#2e7d32]',
                'preparing' => 'bg-[#fef7e0] text-[#785900]',
                'ready' => 'bg-[#e8f5e9] text-[#2e7d32]',
                'delivered' => 'bg-[#e8f5e9] text-[#2e7d32]',
                'cancelled' => 'bg-[#ffdad6] text-[#ba1a1a]',
            ];
            $statusBadge = $statusColors[$order->status] ?? 'bg-surface-container-low text-on-surface-variant';
            $statusLabel = match($order->status) {
                'pending' => 'En attente',
                'confirmed' => 'Confirmée',
                'preparing' => 'En préparation',
                'ready' => 'Prête',
                'delivered' => 'Servie',
                'cancelled' => 'Annulée',
                default => $order->status,
            };
            @endphp
            <div class="bg-surface-container-lowest rounded-2xl card-shadow p-5 animate-in" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="text-sm font-extrabold text-on-surface">#{{ $order->order_number }}</span>
                        <span class="text-xs text-on-surface-variant/60 ml-2">{{ $order->created_at->format('H:i') }}</span>
                    </div>
                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $statusBadge }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="space-y-1.5 mb-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-on-surface-variant/80">{{ $item->product->name }} x{{ $item->quantity }}</span>
                        <span class="font-semibold text-on-surface">{{ price($item->total_price) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="border-t border-outline-variant/20 pt-3 flex items-center justify-between">
                    <span class="font-extrabold text-on-surface">Total</span>
                    <span class="font-extrabold text-primary">{{ price($order->total) }}</span>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <a href="{{ route('table.suivis', $order) }}"
                        class="flex-1 py-2.5 rounded-xl font-bold text-xs bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high transition-all text-center">
                        Voir détails
                    </a>
                    <a href="{{ route('table.menu') }}"
                        class="flex-1 py-2.5 rounded-xl font-bold text-xs bg-primary text-on-primary hover:bg-primary/90 transition-all text-center flex items-center justify-center gap-1">
                        <x-icon name="arrow-uturn-left" class="text-sm" filled />
                        Commander à nouveau
                    </a>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <x-icon name="document-text" class="text-5xl text-on-surface-variant/30 mb-4" :filled="0" />
                <p class="text-sm font-bold text-on-surface-variant/60">Aucune commande pour le moment</p>
                <p class="text-xs text-on-surface-variant/40 mt-1">Passez votre première commande depuis le menu</p>
                <a href="{{ route('table.menu') }}"
                    class="mt-4 py-2.5 px-5 rounded-xl font-bold text-xs bg-primary text-on-primary hover:bg-primary/90 transition-all">
                    Voir le menu
                </a>
            </div>
            @endforelse
        </div>

        <div class="lg:col-span-1 space-y-4">
            @if($orders->isNotEmpty())
            <div class="bg-surface-container-lowest rounded-2xl card-shadow p-6 animate-in" style="animation-delay: 0.3s">
                <h3 class="font-extrabold text-sm text-on-surface mb-4">Total Session</h3>
                <p class="text-3xl font-extrabold text-primary">{{ price($totalSession) }}</p>
                <p class="text-xs text-on-surface-variant/60 mt-1">{{ $orders->count() }} commande(s) passée(s)</p>
                <div class="mt-5 space-y-2">
                    <form method="POST" action="{{ route('table.service.bill') }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-3 rounded-xl font-bold text-sm bg-primary text-on-primary hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                            <x-icon name="document-text" class="text-lg" />
                            Demander l'addition
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <div class="bg-surface-container-lowest rounded-2xl card-shadow p-6 animate-in" style="animation-delay: 0.4s">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary-container/50 flex items-center justify-center">
                        <x-icon name="chat-bubble-left-ellipsis" class="text-secondary text-lg" filled />
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-on-surface">Une suggestion ?</h3>
                        <p class="text-xs text-on-surface-variant/60">Aidez-nous à nous améliorer</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('table.feedback') }}" class="mt-4 space-y-3">
                    @csrf
                    <div class="flex gap-1" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" data-value="{{ $i }}" class="star-btn text-2xl text-outline/40 hover:text-secondary transition-colors">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0">
                    <input type="hidden" name="product_id" value="">
                    <textarea name="comment" rows="2" placeholder="Votre message..."
                        class="w-full bg-surface-container-low rounded-xl p-3 text-xs font-semibold text-on-surface placeholder:text-on-surface-variant/40 border-2 border-outline-variant/20 focus:outline-none focus:border-primary/50 resize-none"></textarea>
                    <button type="submit"
                        class="w-full py-2.5 rounded-xl font-bold text-xs bg-surface-container-low text-on-surface-variant hover:bg-surface-container-high transition-all">
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                ratingInput.value = value;
                stars.forEach((s, i) => {
                    if (i < value) {
                        s.classList.remove('text-outline/40');
                        s.classList.add('text-secondary');
                    } else {
                        s.classList.remove('text-secondary');
                        s.classList.add('text-outline/40');
                    }
                });
            });
        });

        const items = document.querySelectorAll('.animate-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        items.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            observer.observe(item);
        });
    });
</script>
@endpush
