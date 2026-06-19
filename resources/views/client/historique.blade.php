@extends('layouts.client')

@section('title', 'Historique - FlashFood')

@section('back', route('client.accueil'))

@section('content')
    <div class="px-4 pt-4 pb-24" x-data="{
        orders: $store.clientOrders.getAll(),
        liveUpdate: false,

        init() {
            if (window.Echo) {
                window.Echo.channel('orders')
                    .listen('.order.updated', (e) => {
                        if (e.order) {
                            this.orders = [e.order, ...this.orders.filter(o => o.id !== e.order.id)]
                            this.liveUpdate = true
                            setTimeout(() => this.liveUpdate = false, 4000)
                        }
                    })
            }
        },
        filter: 'Tout',
        search: '',

        get filtered() {
            let list = this.orders
            if (this.filter === 'En cours') {
                list = list.filter(o => !['delivered', 'cancelled'].includes(o.status))
            } else if (this.filter === 'Livrées') {
                list = list.filter(o => o.status === 'delivered')
            } else if (this.filter === 'Annulées') {
                list = list.filter(o => o.status === 'cancelled')
            }
            if (this.search) {
                list = list.filter(o => o.order_number?.toLowerCase().includes(this.search.toLowerCase()))
            }
            return list
        },

        get statusLabel() {
            return { pending: 'En attente', confirmed: 'Confirmée', preparing: 'En préparation', ready: 'Prête', delivered: 'Livrée', cancelled: 'Annulée' }
        },

        statusClass(status) {
            if (status === 'cancelled') return 'bg-error/10 text-error'
            if (status !== 'delivered') return 'bg-secondary-container/20 text-secondary'
            return 'bg-primary/10 text-primary'
        },

        cancelClass(status) {
            return ['cancelled'].includes(status) ? 'opacity-75 grayscale' : ''
        },

        setFilter(f) { this.filter = f },
    }">
        {{-- Title --}}
        <h1 class="mb-5 text-2xl font-extrabold text-on-surface">Mes Commandes</h1>

        @if(session('success'))
            <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif

        {{-- Search --}}
        <div class="mb-4 flex items-center gap-3 rounded-2xl bg-surface-container-lowest px-5 py-3 shadow-[0px_4px_20px_rgba(33,33,33,0.08)]">
            <x-icon name="magnifying-glass" class="text-on-surface-variant" />
            <input type="search" x-model="search" placeholder="Rechercher une commande..." class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
            <button x-show="search" @click="search = ''" class="text-on-surface-variant hover:text-primary">
                <x-icon name="x-mark" class="text-lg" />
            </button>
        </div>

        {{-- Filter Pills --}}
        <div class="mb-6 flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
            <template x-for="f in ['Tout', 'En cours', 'Livrées', 'Annulées']" :key="f">
                <button @click="setFilter(f)"
                    class="flex-shrink-0 rounded-full border border-outline/30 px-6 py-2.5 text-sm font-medium transition-all hover:-translate-y-0.5"
                    :class="filter === f ? 'border-primary bg-primary text-white' : 'bg-surface-container-lowest text-on-surface hover:border-primary hover:bg-primary hover:text-white'"
                    x-text="f">
                </button>
            </template>
        </div>

        {{-- Orders Grid --}}
        <template x-if="filtered.length === 0">
            <div class="mt-10 text-center">
                <x-icon name="document-text" class="text-5xl text-outline/50" />
                <p class="mt-3 text-sm text-on-surface-variant">Aucune commande pour le moment.</p>
                <a href="{{ route('client.menu') }}" class="mt-4 inline-flex items-center gap-2 rounded-2xl bg-primary px-6 py-3 font-semibold text-white">Passer une commande</a>
            </div>
        </template>

        <template x-if="filtered.length > 0">
            <div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <template x-for="order in filtered" :key="order.id">
                        <div class="rounded-2xl bg-surface-container-lowest p-5 shadow-[0px_4px_20px_rgba(33,33,33,0.08)] transition-all hover:-translate-y-1 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]"
                             :class="cancelClass(order.status)">
                            {{-- Header --}}
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-xs text-on-surface-variant" x-text="'Commande #' + order.order_number"></p>
                                    <p class="text-sm font-semibold text-on-surface" x-text="order.created_at"></p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="statusClass(order.status)" x-text="statusLabel[order.status] || order.status_label"></span>
                            </div>

                            {{-- Info --}}
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-on-surface-variant" x-text="order.items_count + ' article(s)'"></span>
                                <span class="text-on-surface-variant" x-text="order.type === 'delivery' ? 'Livraison' : (order.type === 'table' ? 'Sur place' : 'À emporter')"></span>
                            </div>

                            {{-- Total --}}
                            <div class="mt-3 flex items-center justify-between border-t border-outline/10 pt-3">
                                <span class="font-bold text-on-surface">Total</span>
                                <span class="font-extrabold text-primary" x-text="order.total"></span>
                            </div>

                            {{-- Actions --}}
                            <div class="mt-3 flex gap-2">
                                <a :href="'/commandes/' + order.id + '/suivis'" class="flex-1 rounded-xl bg-primary py-2.5 text-sm font-semibold text-white text-center transition-all hover:bg-primary/90 active:scale-95"
                                   x-show="!['delivered', 'cancelled'].includes(order.status)">
                                    Suivre
                                </a>
                                <a :href="'/commandes/' + order.id" class="flex-1 rounded-xl border border-outline/30 py-2.5 text-sm font-semibold text-on-surface text-center transition-all hover:bg-surface-container-low">
                                    Détails
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
@endsection
