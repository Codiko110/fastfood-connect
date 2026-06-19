@extends('layouts.table')

@section('title', 'Commande confirmée - FlashFood')
@section('back', route('table.menu'))
@section('table_title', 'Table ' . $tableNumber)

@push('styles')
<style>
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    .primary-glow { box-shadow: 0 0 20px rgba(183, 26, 26, 0.3); }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    .animate-float { animation: float 3s ease-in-out infinite; }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-in { animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .confetti-piece {
        position: fixed;
        width: 10px;
        height: 10px;
        border-radius: 2px;
        pointer-events: none;
        z-index: 9999;
    }
</style>
@endpush

@section('content')
<div class="min-h-[calc(100dvh-10rem)] flex flex-col items-center justify-center px-6 py-12 text-center">
    <div class="relative mb-8 animate-float">
        <div class="w-28 h-28 rounded-full bg-surface-container-lowest card-shadow flex items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-primary flex items-center justify-center shadow-xl primary-glow">
                <x-icon name="check" class="text-on-primary text-4xl animate-check" filled />
            </div>
        </div>
    </div>

    <div class="animate-in" style="animation-delay: 0.2s">
        <h1 class="text-2xl font-extrabold text-on-surface mb-2">Votre commande a été envoyée à la cuisine</h1>
        <p class="text-sm text-on-surface-variant/70 max-w-xs mx-auto leading-relaxed">
            Merci pour votre commande ! Notre équipe s'occupe de la préparer avec soin.
        </p>
    </div>

    <div class="animate-in w-full max-w-sm bg-surface-container-lowest rounded-2xl card-shadow p-6 mt-8" style="animation-delay: 0.4s">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">Numéro de commande</span>
                <span class="text-sm font-extrabold text-primary">#{{ $order->order_number }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">Table</span>
                <span class="text-sm font-extrabold text-on-surface">N°{{ $tableNumber }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">Heure de commande</span>
                <span class="text-sm font-extrabold text-on-surface">{{ $order->created_at->format('H:i') }}</span>
            </div>
            <div class="border-t border-outline-variant/20 pt-4 flex items-center justify-between">
                <span class="text-xs font-semibold text-on-surface-variant">Temps estimé</span>
                <span class="text-sm font-extrabold text-secondary-container">15-20 min</span>
            </div>
        </div>
    </div>

    <div class="animate-in mt-8 w-full max-w-sm" style="animation-delay: 0.6s">
        <a href="{{ route('table.suivis', $order) }}"
            class="w-full py-4 rounded-2xl font-extrabold text-base bg-primary text-on-primary hover:bg-primary/90 transition-all duration-300 hover:primary-glow active:scale-[0.98] flex items-center justify-center gap-2">
            <x-icon name="clock" filled />
            Suivre la commande
        </a>
        <a href="{{ route('table.menu') }}"
            class="w-full py-3.5 rounded-2xl font-bold text-sm text-on-surface-variant hover:text-on-surface transition-all duration-300 flex items-center justify-center gap-2 mt-3">
            <x-icon name="plus" class="text-lg" />
            Ajouter d'autres articles
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const brandColors = ['#b7131a', '#db322f', '#785900', '#fdc003', '#1a1c1c', '#ba1a1a'];
        const container = document.body;

        for (let i = 0; i < 50; i++) {
            const piece = document.createElement('div');
            piece.className = 'confetti-piece';
            const color = brandColors[Math.floor(Math.random() * brandColors.length)];
            const size = Math.random() * 8 + 4;
            const left = Math.random() * 100;
            const delay = Math.random() * 2;
            const duration = Math.random() * 2 + 2;
            const rotation = Math.random() * 360;

            piece.style.cssText = `
                left: ${left}%;
                top: -10px;
                width: ${size}px;
                height: ${size * (Math.random() * 0.5 + 0.5)}px;
                background: ${color};
                border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
                animation: confettiFall ${duration}s ease-in ${delay}s forwards;
                transform: rotate(${rotation}deg);
                opacity: 0;
            `;

            container.appendChild(piece);
        }

        const style = document.createElement('style');
        style.textContent = `
            @keyframes confettiFall {
                0% { transform: translateY(0) rotate(0deg); opacity: 1; }
                100% { transform: translateY(100vh) rotate(${Math.random() * 720}deg); opacity: 0; }
            }
            @keyframes check { 0% { transform: scale(0); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
            .animate-check { animation: check 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        `;
        document.head.appendChild(style);

        setTimeout(() => {
            document.querySelectorAll('.confetti-piece').forEach(el => el.remove());
        }, 5000);
    });
</script>
@endpush
