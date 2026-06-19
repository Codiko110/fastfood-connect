@extends('layouts.admin')

@section('title', 'Statistiques')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto pb-24" x-data="statsBoard()">
    {{-- Live update banner --}}
    <div x-show="$store.admin.stats" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2"
         class="mb-6 px-5 py-4 rounded-xl bg-secondary-container border border-secondary/20 text-on-secondary-container text-sm font-semibold flex items-center gap-3 cursor-pointer hover:bg-secondary-container/80 transition-all"
         @click="$store.admin.stats = null">
        <x-icon name="megaphone" class="text-lg" filled />
        <span>Données mises à jour en temps réel</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Statistiques</h1>
    </div>

    <x-flash-messages />

    {{-- KPI Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-on-surface-variant mb-1">Revenus Totaux</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ price($totalRevenue) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-500 to-green-400 flex items-center justify-center shadow-md shadow-green-500/20">
                    <x-icon name="arrow-trending-up" class="text-white" filled />
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-outline-variant/10 flex gap-4 text-xs">
                <span class="text-on-surface-variant">CA commandes: <strong class="text-green-600">{{ price($orderRevenue) }}</strong></span>
                <span class="text-on-surface-variant">Dépenses: <strong class="text-red-500">{{ price($manualExpenses) }}</strong></span>
            </div>
        </div>

        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-on-surface-variant mb-1">Panier Moyen</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ price($averageBasket) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center shadow-md shadow-primary/20">
                    <x-icon name="shopping-cart" class="text-white" filled />
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">Moyenne par commande</span>
            </div>
        </div>

        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-on-surface-variant mb-1">Préparation Moy.</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ round($averagePrep) }} <span class="text-lg font-medium">min</span></p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#ff9800] to-[#ff9800]/70 flex items-center justify-center shadow-md shadow-[#ff9800]/20">
                    <x-icon name="clock" class="text-white" filled />
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">Temps de préparation moyen</span>
            </div>
        </div>

        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-on-surface-variant mb-1">Revenu Manuel</p>
                    <p class="text-3xl font-extrabold text-on-surface tracking-tight">{{ price($manualRevenue) }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#2196f3] to-[#2196f3]/70 flex items-center justify-center shadow-md shadow-[#2196f3]/20">
                    <x-icon name="building-library" class="text-white" filled />
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-outline-variant/10">
                <span class="text-xs text-on-surface-variant">Ajouts manuels (hors commandes)</span>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Monthly Revenue Chart --}}
        <div class="lg:col-span-2 rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <h3 class="text-lg font-bold text-on-surface mb-6">Revenus Mensuels</h3>
            @php $maxMonthly = max($monthlyRevenue) ?: 1; @endphp
            <div class="flex items-end justify-between gap-2 h-52">
                @foreach($monthlyRevenue as $month => $amount)
                    @php $height = max(round(($amount / $maxMonthly) * 100), 4); @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group">
                        <span class="text-xs font-semibold text-on-surface-variant opacity-0 group-hover:opacity-100 transition-opacity">{{ price($amount) }}</span>
                        <div class="w-full flex justify-center">
                            <div class="w-8 bg-primary rounded-t-lg transition-all duration-500 group-hover:bg-primary/70" style="height: {{ $height }}%"></div>
                        </div>
                        <span class="text-xs text-on-surface-variant">{{ $month }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Category Breakdown --}}
        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <h3 class="text-lg font-bold text-on-surface mb-6">Répartition par Catégorie</h3>
            @php
                $totalCatProducts = $categoryBreakdown->sum('products_count') ?: 1;
                $colors = ['#b7131a', '#fdc003', '#db322f', '#785900', '#2196f3', '#4caf50', '#ff9800', '#9c27b0'];
            @endphp
            @if($categoryBreakdown->count() > 0)
                @php $dashOffset = 0; @endphp
                <div class="flex flex-col items-center">
                    <div class="relative w-44 h-44">
                        <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e8e8e8" stroke-width="3"></circle>
                            @foreach($categoryBreakdown as $i => $cat)
                                @php
                                    $pct = ($cat->products_count / $totalCatProducts) * 100;
                                    $dashLen = max($pct, 1);
                                    $dashOffset -= ($loop->first ? 0 : $dashLen);
                                @endphp
                                <circle cx="18" cy="18" r="15.5" fill="none" stroke="{{ $colors[$i % count($colors)] }}" stroke-width="3" stroke-dasharray="{{ $dashLen }} {{ 100 - $dashLen }}" stroke-linecap="round" stroke-dashoffset="{{ $dashOffset }}"></circle>
                            @endforeach
                        </svg>
                    </div>
                    <div class="w-full mt-6 space-y-2">
                        @foreach($categoryBreakdown as $i => $cat)
                            @php $pct = $totalCatProducts > 0 ? round(($cat->products_count / $totalCatProducts) * 100) : 0; @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full" style="background: {{ $colors[$i % count($colors)] }}"></span>
                                    <span class="text-sm text-on-surface">{{ $cat->name }}</span>
                                </div>
                                <span class="text-sm font-semibold text-on-surface">{{ $pct }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-on-surface-variant text-center py-8">Aucune catégorie</p>
            @endif
        </div>
    </div>

    {{-- Revenue Management --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Add Revenue Form --}}
        <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-on-surface">Gestion des Revenus</h3>
                <div class="text-right">
                    <p class="text-xs text-on-surface-variant">Solde disponible</p>
                    <p class="text-sm font-extrabold text-green-600">{{ price($totalRevenue) }}</p>
                </div>
            </div>
            <form action="{{ route('admin.statistics.revenue') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-on-surface mb-1.5">Type</label>
                    <div class="flex gap-2">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" value="income" checked class="hidden peer">
                            <div class="text-center py-2.5 rounded-xl border-2 border-outline-variant/20 bg-surface-container-low peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-700 transition-all text-sm font-semibold text-on-surface-variant">
                                <x-icon name="plus" class="text-lg align-text-bottom" filled /> Revenu
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="type" value="expense" class="hidden peer">
                            <div class="text-center py-2.5 rounded-xl border-2 border-outline-variant/20 bg-surface-container-low peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-700 transition-all text-sm font-semibold text-on-surface-variant">
                                <x-icon name="minus" class="text-lg align-text-bottom" filled /> Dépense
                            </div>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="label" class="block text-sm font-semibold text-on-surface mb-1.5">Libellé</label>
                    <input type="text" id="label" name="label" required placeholder="Ex: Achat fournisseur" class="w-full px-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                </div>
                <div>
                    <label for="amount_ar" class="block text-sm font-semibold text-on-surface mb-1.5">Montant <span class="text-on-surface-variant font-medium">(Ariary)</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-bold text-sm">Ar</span>
                        <input type="number" id="amount_ar" name="amount_ar" required step="100" min="0" placeholder="0" class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-semibold text-on-surface mb-1.5">Notes (optionnel)</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Détails..." class="w-full px-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all resize-none"></textarea>
                </div>
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-on-primary font-bold hover:bg-primary/90 transition-all">Enregistrer</button>
            </form>
        </div>

        {{-- Revenue Logs --}}
        <div class="lg:col-span-2 rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-on-surface">Historique des Opérations</h3>
                <div class="flex gap-3 text-xs">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Revenus</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Dépenses</span>
                </div>
            </div>
            @if($revenueLogs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-outline-variant/10">
                                <th class="text-left px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Date</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Type</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Libellé</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenueLogs as $log)
                                <tr class="border-b border-outline-variant/5 hover:bg-surface-container-low/50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-on-surface">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $log->type === 'income' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                            <x-icon name="{{ $log->type === 'income' ? 'add' : 'remove' }}" class="text-xs" />
                                            {{ $log->type === 'income' ? 'Revenu' : 'Dépense' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-on-surface">{{ $log->label }}</p>
                                        @if($log->notes)
                                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $log->notes }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-sm font-bold {{ $log->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                                            {{ $log->type === 'income' ? '+' : '-' }}{{ price($log->amount) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <x-icon name="document-text" class="text-4xl text-on-surface-variant/20" filled />
                    <p class="text-sm text-on-surface-variant mt-2">Aucune opération manuelle</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Top Products --}}
    <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/10 p-6">
        <h3 class="text-lg font-bold text-on-surface mb-6">Top 5 Produits</h3>
        @if($topProducts->count() > 0)
            @php $maxCount = $topProducts->max('order_items_count') ?: 1; @endphp
            <div class="space-y-5">
                @foreach($topProducts as $i => $product)
                    @php $width = round(($product->order_items_count / $maxCount) * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full {{ $i === 0 ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface' }} text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                                <span class="text-sm font-semibold text-on-surface">{{ $product->name }}</span>
                            </div>
                            <span class="text-sm font-semibold text-on-surface">{{ $product->order_items_count }} vente(s)</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-surface-container-high overflow-hidden">
                            <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-on-surface-variant text-center py-8">Aucune donnée de vente</p>
        @endif
    </div>
</div>
@endsection
