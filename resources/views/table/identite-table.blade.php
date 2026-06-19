<!DOCTYPE html>
<html lang="fr" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisissez votre table - FlashFood</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-mesh {
            background-image: radial-gradient(circle at 20% 30%, rgba(183, 26, 26, 0.08) 0%, transparent 50%),
                              radial-gradient(circle at 80% 70%, rgba(253, 192, 3, 0.08) 0%, transparent 50%);
        }
        .card-shadow {
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        }
        .primary-glow {
            box-shadow: 0 0 20px rgba(183, 26, 26, 0.3);
        }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen bg-mesh overflow-x-hidden" style="min-height: max(884px, 100dvh);">
    <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-secondary/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/[0.03] rounded-full blur-3xl"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">
        <div class="flex flex-col items-center gap-6 mb-12 animate-fade-in">
            <div class="w-24 h-24 bg-primary rounded-full flex items-center justify-center shadow-xl primary-glow">
                <x-icon name="building-storefront" class="text-on-primary text-5xl" filled />
            </div>
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Bienvenue chez</h1>
                <p class="text-4xl font-extrabold text-primary mt-1 tracking-tight">FlashFood</p>
                <p class="text-on-surface-variant mt-3 text-sm max-w-xs mx-auto leading-relaxed">
                    Sélectionnez votre table pour commencer à commander directement depuis votre table.
                </p>
            </div>
        </div>

        <div class="w-full max-w-2xl mx-auto mb-10">
            @if(session('error'))
            <div class="mb-4 p-4 rounded-2xl bg-[#ffdad6] text-[#ba1a1a] text-sm font-semibold flex items-center gap-2">
                <x-icon name="exclamation-circle" class="text-lg" filled />
                {{ session('error') }}
            </div>
            @endif
            @if(session('success'))
            <div class="mb-4 p-4 rounded-2xl bg-[#e8f5e9] text-[#2e7d32] text-sm font-semibold flex items-center gap-2">
                <x-icon name="check-circle" class="text-lg" filled />
                {{ session('success') }}
            </div>
            @endif
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-bold text-on-surface uppercase tracking-widest">Choisissez votre table</h2>
                <span class="text-xs text-on-surface-variant" id="selectedLabel">Aucune table sélectionnée</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                @forelse($tables as $table)
                <button type="button" data-table="{{ $table->id }}" data-number="{{ $table->table_number }}"
                    class="table-btn relative flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-outline-variant/30 bg-surface-container-lowest card-shadow transition-all duration-300 hover:border-primary/50 hover:bg-primary/[0.04] active:scale-95">
                    <x-icon name="rectangle-stack" class="text-outline text-2xl mb-1" :filled="0" />
                    <span class="text-sm font-bold text-on-surface">T{{ $table->table_number }}</span>
                    @if($table->capacity)
                    <span class="text-[10px] text-on-surface-variant/50 mt-0.5">{{ $table->capacity }} pers.</span>
                    @endif
                </button>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-center">
                    <x-icon name="rectangle-stack" class="text-5xl text-on-surface-variant/20 mb-3" :filled="0" />
                    <p class="text-sm font-bold text-on-surface-variant/60">Aucune table libre pour le moment</p>
                    <p class="text-xs text-on-surface-variant/40 mt-1">Veuillez patienter ou contactez un serveur</p>
                </div>
                @endforelse
            </div>
        </div>

        <form id="tableForm" method="POST" action="{{ route('table.select') }}" class="w-full max-w-md">
            @csrf
            <input type="hidden" name="table_id" id="selectedTableInput" value="">
            <button type="submit" id="submitBtn" disabled
                class="w-full py-4 rounded-2xl font-extrabold text-base transition-all duration-500 bg-outline/30 text-white/50 cursor-not-allowed">
                <span class="flex items-center justify-center gap-2">
                    <x-icon name="rectangle-stack" />
                    Commencer la commande
                </span>
            </button>
        </form>

        <p class="text-xs text-on-surface-variant/60 mt-4 text-center">
            En continuant, vous acceptez de passer une commande pour la table sélectionnée.
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.table-btn');
            const submitBtn = document.getElementById('submitBtn');
            const selectedInput = document.getElementById('selectedTableInput');
            const selectedLabel = document.getElementById('selectedLabel');
            let selected = null;

            buttons.forEach((btn, index) => {
                btn.style.opacity = '0';
                btn.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    btn.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
                    btn.style.opacity = '1';
                    btn.style.transform = 'translateY(0)';
                }, 80 + index * 50);

                btn.addEventListener('click', function() {
                    const tableId = this.dataset.table;
                    const tableNumber = this.dataset.number;
                    if (selected === tableId) {
                        selected = null;
                        this.classList.remove('border-primary', 'bg-primary-fixed', 'shadow-lg', 'primary-glow');
                        this.classList.add('border-outline-variant/30', 'bg-surface-container-lowest');
                        submitBtn.disabled = true;
                        submitBtn.classList.remove('bg-primary', 'text-on-primary', 'cursor-pointer', 'hover:bg-primary/90', 'primary-glow');
                        submitBtn.classList.add('bg-outline/30', 'text-white/50', 'cursor-not-allowed');
                        selectedInput.value = '';
                        selectedLabel.textContent = 'Aucune table sélectionnée';
                    } else {
                        buttons.forEach(b => {
                            b.classList.remove('border-primary', 'bg-primary-fixed', 'shadow-lg', 'primary-glow');
                            b.classList.add('border-outline-variant/30', 'bg-surface-container-lowest');
                        });
                        selected = tableId;
                        this.classList.remove('border-outline-variant/30', 'bg-surface-container-lowest');
                        this.classList.add('border-primary', 'bg-primary-fixed', 'shadow-lg', 'primary-glow');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('bg-outline/30', 'text-white/50', 'cursor-not-allowed');
                        submitBtn.classList.add('bg-primary', 'text-on-primary', 'cursor-pointer', 'hover:bg-primary/90', 'primary-glow');
                        selectedInput.value = tableId;
                        selectedLabel.textContent = 'Table T' + tableNumber + ' sélectionnée';
                    }
                });
            });
        });
    </script>
</body>
</html>
