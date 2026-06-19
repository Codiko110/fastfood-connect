@extends('layouts.table')

@section('title', 'Service - FlashFood')
@section('back', route('table.menu'))
@section('table_title', 'Table ' . $tableNumber)

@push('styles')
<style>
    .card-shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
    @keyframes ping {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(2); opacity: 0; }
    }
    .animate-ping-dot {
        animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideDown {
        from { transform: translateY(0); opacity: 1; }
        to { transform: translateY(100%); opacity: 0; }
    }
    .toast-show { animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .toast-hide { animation: slideDown 0.3s ease forwards; }
</style>
@endpush

@section('content')
<div class="px-4 py-4 max-w-2xl mx-auto">
    @if(session('success'))
    <div class="mb-4 p-4 rounded-2xl bg-[#e8f5e9] text-[#2e7d32] text-sm font-semibold flex items-center gap-2">
        <x-icon name="check-circle" class="text-lg" filled />
        {{ session('success') }}
    </div>
    @endif

    <h1 class="text-xl font-extrabold text-on-surface mb-6">Service en table</h1>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <form method="POST" action="{{ route('table.service.request') }}" class="contents service-form">
            @csrf
            <input type="hidden" name="type" value="serveur">
            <button type="submit"
                class="flex flex-col items-center justify-center gap-3 p-8 bg-surface-container-lowest rounded-2xl card-shadow hover:shadow-lg hover:border-primary/30 border-2 border-transparent transition-all duration-300 active:scale-[0.97]">
                <div class="w-16 h-16 rounded-2xl bg-primary-container/30 flex items-center justify-center">
                    <x-icon name="bell-alert" class="text-primary text-3xl" filled />
                </div>
                <span class="text-sm font-extrabold text-on-surface text-center">Besoin d'un serveur</span>
            </button>
        </form>

        <form method="POST" action="{{ route('table.service.request') }}" class="contents service-form">
            @csrf
            <input type="hidden" name="type" value="eau">
            <button type="submit"
                class="flex flex-col items-center justify-center gap-3 p-8 bg-surface-container-lowest rounded-2xl card-shadow hover:shadow-lg hover:border-primary/30 border-2 border-transparent transition-all duration-300 active:scale-[0.97]">
                <div class="w-16 h-16 rounded-2xl bg-secondary-container/30 flex items-center justify-center">
                    <x-icon name="beaker" class="text-secondary text-3xl" filled />
                </div>
                <span class="text-sm font-extrabold text-on-surface text-center">Demander de l'eau</span>
            </button>
        </form>

        <form method="POST" action="{{ route('table.service.bill') }}" class="contents">
            @csrf
            <button type="submit"
                class="flex flex-col items-center justify-center gap-3 p-8 bg-surface-container-lowest rounded-2xl card-shadow hover:shadow-lg hover:border-primary/30 border-2 border-transparent transition-all duration-300 active:scale-[0.97]">
                <div class="w-16 h-16 rounded-2xl bg-primary-container/30 flex items-center justify-center">
                    <x-icon name="document-text" class="text-primary text-3xl" filled />
                </div>
                <span class="text-sm font-extrabold text-on-surface text-center">Demander l'addition</span>
            </button>
        </form>

        <form method="POST" action="{{ route('table.service.request') }}" class="contents service-form">
            @csrf
            <input type="hidden" name="type" value="assistance">
            <button type="submit"
                class="flex flex-col items-center justify-center gap-3 p-8 bg-surface-container-lowest rounded-2xl card-shadow hover:shadow-lg hover:border-primary/30 border-2 border-transparent transition-all duration-300 active:scale-[0.97]">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-low flex items-center justify-center">
                    <x-icon name="question-mark-circle" class="text-on-surface-variant text-3xl" filled />
                </div>
                <span class="text-sm font-extrabold text-on-surface text-center">Assistance</span>
            </button>
        </form>
    </div>

    <div class="bg-surface-container-lowest/80 backdrop-blur-md rounded-2xl card-shadow p-5 border border-outline-variant/10">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                <div class="w-3 h-3 rounded-full bg-secondary animate-ping-dot absolute inset-0"></div>
            </div>
            <div>
                <p class="text-sm font-bold text-on-surface">Table occupée</p>
                <p class="text-xs text-on-surface-variant/60">Notre équipe est à votre disposition</p>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="fixed bottom-28 left-4 right-4 max-w-sm mx-auto z-[60] hidden">
    <div class="bg-on-surface text-surface px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3">
        <x-icon name="check-circle" class="text-secondary-container text-xl" filled />
        <div class="flex-1 min-w-0">
            <p id="toastTitle" class="text-sm font-bold text-surface">Demande envoyée</p>
            <p id="toastMessage" class="text-xs text-surface/70">Un serveur va bientôt arriver</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.service-form');
        const toast = document.getElementById('toast');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');

        const messages = {
            'serveur': { title: 'Serveur demandé', message: 'Un serveur va bientôt arriver à votre table' },
            'eau': { title: 'Eau demandée', message: 'De l\'eau fraîche arrive !' },
            'assistance': { title: 'Assistance demandée', message: 'Un membre de l\'équipe vient vous aider' },
        };

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const type = this.querySelector('input[name="type"]')?.value;
                if (type && messages[type]) {
                    toastTitle.textContent = messages[type].title;
                    toastMessage.textContent = messages[type].message;

                    if (navigator.vibrate) {
                        navigator.vibrate(200);
                    }

                    toast.classList.remove('hidden', 'toast-hide');
                    toast.classList.add('toast-show');
                    toast.style.display = 'block';

                    setTimeout(() => {
                        toast.classList.remove('toast-show');
                        toast.classList.add('toast-hide');
                        setTimeout(() => {
                            toast.classList.add('hidden');
                            toast.style.display = 'none';
                            toast.classList.remove('toast-hide');
                        }, 300);
                    }, 3000);
                }
            });
        });
    });
</script>
@endpush
