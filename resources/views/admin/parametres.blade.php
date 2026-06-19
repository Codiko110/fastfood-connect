@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
<div class="p-6 max-w-[1400px] mx-auto pb-24">
    <h1 class="text-3xl font-bold text-on-surface mb-6">Paramètres</h1>

    <x-flash-messages />

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left Tabs --}}
        <div class="lg:col-span-3">
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-3 card-shadow sticky top-24">
                <button class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-on-primary font-semibold text-sm transition-all text-left" data-tab="general">
                    <x-icon name="information-circle" class="text-lg" />
                    Info générales
                </button>
                <button class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-semibold text-sm transition-all hover:bg-surface-container-low text-left mt-1" data-tab="delivery">
                    <x-icon name="truck" class="text-lg" />
                    Livraison
                </button>
                <button class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface font-semibold text-sm transition-all hover:bg-surface-container-low text-left mt-1" data-tab="notifications">
                    <x-icon name="bell" class="text-lg" />
                    Notifications
                </button>
            </div>
        </div>

        {{-- Right Content --}}
        <div class="lg:col-span-9">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                {{-- General Tab --}}
                <div id="tab-general" class="tab-content bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Informations Générales</h3>

                    <div class="space-y-5">
                        <div>
                            <label for="restaurant_name" class="block text-sm font-semibold text-on-surface mb-2">Nom du restaurant</label>
                            <input type="text" id="restaurant_name" name="restaurant_name" value="{{ old('restaurant_name', config('app.name', 'FlashFood')) }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-on-surface mb-2">Téléphone</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+33 1 23 45 67 89" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="opening_time" class="block text-sm font-semibold text-on-surface mb-2">Heure d'ouverture</label>
                                <input type="time" id="opening_time" name="opening_time" value="{{ old('opening_time', '09:00') }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            </div>
                            <div>
                                <label for="closing_time" class="block text-sm font-semibold text-on-surface mb-2">Heure de fermeture</label>
                                <input type="time" id="closing_time" name="closing_time" value="{{ old('closing_time', '23:00') }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delivery Tab --}}
                <div id="tab-delivery" class="tab-content hidden bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Paramètres de Livraison</h3>

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="delivery_fee" class="block text-sm font-semibold text-on-surface mb-2">Frais de livraison (€)</label>
                                <input type="number" id="delivery_fee" name="delivery_fee" step="0.50" value="{{ old('delivery_fee', '3.50') }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            </div>
                            <div>
                                <label for="min_order" class="block text-sm font-semibold text-on-surface mb-2">Commande minimum (€)</label>
                                <input type="number" id="min_order" name="min_order" step="0.50" value="{{ old('min_order', '10.00') }}" class="w-full px-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="delivery_radius" class="block text-sm font-semibold text-on-surface mb-2">Rayon de livraison (km)</label>
                            <div class="flex items-center gap-4">
                                <input type="range" id="delivery_radius" name="delivery_radius" min="1" max="20" value="{{ old('delivery_radius', '10') }}" class="flex-1 accent-primary h-2 rounded-full appearance-none bg-surface-container-high cursor-pointer">
                                <span id="delivery-radius-value" class="text-sm font-bold text-primary min-w-[4rem] text-right">{{ old('delivery_radius', '10') }} km</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notifications Tab --}}
                <div id="tab-notifications" class="tab-content hidden bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-6 card-shadow">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Notifications</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low">
                            <div>
                                <p class="text-sm font-semibold text-on-surface">Nouvelles commandes</p>
                                <p class="text-xs text-on-surface-variant">Recevoir une notification pour chaque nouvelle commande</p>
                            </div>
                            <label class="toggle-switch relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notif_new_orders" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-outline-variant/40 rounded-full peer peer-checked:bg-primary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:bg-white after:rounded-full after:shadow-sm after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl bg-surface-container-low">
                            <div>
                                <p class="text-sm font-semibold text-on-surface">Commandes prêtes</p>
                                <p class="text-xs text-on-surface-variant">Notification quand une commande est prête à servir</p>
                            </div>
                            <label class="toggle-switch relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notif_ready_orders" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-outline-variant/40 rounded-full peer peer-checked:bg-primary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:bg-white after:rounded-full after:shadow-sm after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="submit" class="save-btn px-8 py-3.5 rounded-xl bg-primary text-on-primary font-bold transition-all hover:shadow-lg hover:scale-105 primary-glow inline-flex items-center gap-2">
                        <x-icon name="document-check" class="text-lg" />
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('bg-primary', 'text-on-primary');
                b.classList.add('text-on-surface');
            });
            this.classList.add('bg-primary', 'text-on-primary');
            this.classList.remove('text-on-surface');

            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.add('hidden'));
            document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
        });
    });

    document.querySelectorAll('.toggle-switch input[type="checkbox"]').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const track = this.nextElementSibling;
            if (this.checked) {
                track.classList.remove('bg-outline-variant/40');
                track.classList.add('bg-primary');
            } else {
                track.classList.remove('bg-primary');
                track.classList.add('bg-outline-variant/40');
            }
        });
    });

    const radiusSlider = document.getElementById('delivery_radius');
    const radiusValue = document.getElementById('delivery-radius-value');
    if (radiusSlider && radiusValue) {
        radiusSlider.addEventListener('input', function() {
            radiusValue.textContent = this.value + ' km';
        });
    }
</script>
@endpush
