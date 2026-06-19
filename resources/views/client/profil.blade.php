@extends('layouts.client')

@section('title', 'Mon Profil - FlashFood')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-surface-container-lowest rounded-2xl p-6 card-shadow text-center mb-6">
        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <x-icon name="user" class="text-4xl text-primary" />
        </div>
        <h1 class="text-xl font-bold">{{ auth()->user()->name }}</h1>
        <p class="text-on-surface-variant text-sm">{{ auth()->user()->email }}</p>
        @if(auth()->user()->phone)
        <p class="text-on-surface-variant text-sm">{{ auth()->user()->phone }}</p>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-surface-container-lowest rounded-2xl p-5 card-shadow text-center">
            <p class="text-2xl font-bold text-primary">{{ $ordersCount }}</p>
            <p class="text-sm text-on-surface-variant">Commandes</p>
        </div>
        <div class="bg-surface-container-lowest rounded-2xl p-5 card-shadow text-center">
            <p class="text-2xl font-bold text-secondary-container">{{ session('cart_count', 0) }}</p>
            <p class="text-sm text-on-surface-variant">Articles au panier</p>
        </div>
    </div>

    <div class="space-y-3">
        <a href="{{ route('client.commandes') }}" class="flex items-center justify-between bg-surface-container-lowest rounded-xl p-4 card-shadow">
            <div class="flex items-center gap-3">
                <x-icon name="document-text" class="text-primary" />
                <span class="font-medium">Mes commandes</span>
            </div>
            <x-icon name="chevron-right" class="text-on-surface-variant" />
        </a>
        <a href="{{ route('client.menu') }}" class="flex items-center justify-between bg-surface-container-lowest rounded-xl p-4 card-shadow">
            <div class="flex items-center gap-3">
                <x-icon name="book-open" class="text-primary" />
                <span class="font-medium">Voir le menu</span>
            </div>
            <x-icon name="chevron-right" class="text-on-surface-variant" />
        </a>
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-between bg-surface-container-lowest rounded-xl p-4 card-shadow hover:bg-error-container/20 transition-colors">
                <div class="flex items-center gap-3">
                    <x-icon name="arrow-left-end-on-rectangle" class="text-error" />
                    <span class="font-medium text-error">Déconnexion</span>
                </div>
                <x-icon name="chevron-right" class="text-error" />
            </button>
        </form>
    </div>
</div>
@endsection
