@extends('layouts.admin')

@section('title', 'Gestion des Tables')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto pb-24">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-3xl font-bold text-on-surface">Gestion des Tables</h1>
        <div class="flex items-center gap-4 mt-4 sm:mt-0">
            @php
                $freeCount = $tables->where('status', 'free')->count();
                $occupiedCount = $tables->where('status', 'occupied')->count();
                $orderingCount = $tables->where('status', 'ordering')->count();
            @endphp
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                <span class="text-sm text-on-surface-variant">{{ $freeCount }} Libres</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                <span class="text-sm text-on-surface-variant">{{ $occupiedCount }} Occupées</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-[#ff9800]"></span>
                <span class="text-sm text-on-surface-variant">{{ $orderingCount }} En commande</span>
            </div>
        </div>
    </div>

    <x-flash-messages />

    {{-- Tables Grid --}}
    @if($tables->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($tables as $table)
                @php $statusColor = ['free' => 'bg-green-500', 'occupied' => 'bg-red-500', 'ordering' => 'bg-[#ff9800]']; @endphp
                <div class="table-card bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden card-shadow transition-all duration-300 hover:-translate-y-1" data-table="{{ $table->id }}">
                    <div class="h-1 {{ $statusColor[$table->status] ?? 'bg-gray-300' }}"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-on-surface">Table n°{{ $table->table_number }}</h3>
                            <div class="flex items-center gap-1 text-on-surface-variant">
                                <x-icon name="users" class="text-lg" />
                                <span class="text-sm font-semibold">{{ $table->capacity }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            @php $statusLabel = ['free' => 'Libre', 'occupied' => 'Occupée', 'ordering' => 'En commande']; @endphp
                            @php $statusBadge = ['free' => 'bg-green-50 text-green-600', 'occupied' => 'bg-red-50 text-red-600', 'ordering' => 'bg-[#ff9800]/10 text-[#cc7a00]']; @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge[$table->status] ?? 'bg-gray-100 text-gray-600' }}">{{ $statusLabel[$table->status] ?? $table->status }}</span>
                        </div>
                        <div class="mt-4 space-y-2">
                            <form action="{{ route('admin.tables.status', $table) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <select name="status" class="flex-1 px-3 py-2 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                                    <option value="free" {{ $table->status === 'free' ? 'selected' : '' }}>Libre</option>
                                    <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>Occupée</option>
                                    <option value="ordering" {{ $table->status === 'ordering' ? 'selected' : '' }}>En commande</option>
                                </select>
                                <button type="submit" class="px-3 py-2 rounded-xl bg-primary text-on-primary text-sm font-semibold transition-all hover:shadow-lg">OK</button>
                            </form>
                            <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" onsubmit="return confirm('Supprimer cette table ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 rounded-xl bg-red-50 text-red-600 text-sm font-semibold transition-all hover:bg-red-100">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-surface-container-lowest rounded-2xl border border-outline-variant/10">
            <x-icon name="rectangle-stack" class="text-5xl text-on-surface-variant/40 mb-4" />
            <p class="text-on-surface-variant">Aucune table pour le moment</p>
        </div>
    @endif

    {{-- Add Table Form --}}
    <div class="mt-8 bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
        <h3 class="text-lg font-bold text-on-surface mb-4">Ajouter une table</h3>
        <form action="{{ route('admin.tables.store') }}" method="POST" class="flex flex-wrap items-end gap-4">
            @csrf
            <div class="flex-1 min-w-[200px]">
                <label for="table_number" class="block text-sm font-semibold text-on-surface mb-2">Numéro de table</label>
                <input type="number" id="table_number" name="table_number" min="1" value="{{ old('table_number') }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                @error('table_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1 min-w-[200px]">
                <label for="capacity" class="block text-sm font-semibold text-on-surface mb-2">Capacité</label>
                <input type="number" id="capacity" name="capacity" min="1" value="{{ old('capacity', 4) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                @error('capacity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-on-primary font-bold transition-all hover:shadow-lg hover:scale-105 primary-glow inline-flex items-center gap-2">
                <x-icon name="plus" class="text-lg" />
                Ajouter
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.table-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName === 'SELECT' || e.target.tagName === 'BUTTON' || e.target.tagName === 'OPTION') return;
            document.querySelectorAll('.table-card').forEach(c => {
                c.classList.remove('ring-2', 'ring-primary');
            });
            this.classList.add('ring-2', 'ring-primary');
        });
    });

    document.querySelectorAll('.card-shadow').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 12px 40px rgba(0,0,0,0.1)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
</script>
@endpush
