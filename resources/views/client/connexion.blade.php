@extends('layouts.client')

@section('title', 'Connexion - FlashFood')

@section('back', route('client.accueil'))

@section('content')
    <div class="flex min-h-[calc(100vh-10rem)]">
        {{-- Left: Login Form --}}
        <div class="flex w-full flex-col justify-center px-6 py-10 lg:w-1/2 lg:px-12">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-8 text-center lg:text-left">
                    <h1 class="text-3xl font-extrabold text-on-surface">FlashFood</h1>
                    <p class="mt-2 text-on-surface-variant">Connectez-vous à votre compte</p>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
                @endif

                <form action="{{ route('auth.login') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-on-surface">Email</label>
                        <div class="flex items-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-4 py-3 transition-all focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                            <x-icon name="envelope" class="text-on-surface-variant" />
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-on-surface">Mot de passe</label>
                        <div class="flex items-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-4 py-3 transition-all focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                            <x-icon name="lock-closed" class="text-on-surface-variant" />
                            <input type="password" name="password" id="password" placeholder="Votre mot de passe" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                            <button type="button" @click="show = !show; document.getElementById('password').type = show ? 'text' : 'password'" class="text-on-surface-variant hover:text-on-surface" x-data="{ show: false }">
                                <x-icon name="eye-slash" class="text-xl" x-show="!show" />
                                <x-icon name="eye" class="text-xl" x-show="show" />
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="primary-glow w-full rounded-2xl bg-primary py-3.5 font-bold text-white shadow-[0px_4px_20px_rgba(183,26,26,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(183,26,26,0.35)] active:scale-[0.98]">
                        Se connecter
                    </button>
                </form>

                {{-- Divider --}}
                <div class="my-6 flex items-center gap-4">
                    <span class="h-px flex-1 bg-outline/20"></span>
                    <span class="text-xs text-on-surface-variant">OU</span>
                    <span class="h-px flex-1 bg-outline/20"></span>
                </div>

                {{-- Google Login --}}
                <button class="flex w-full items-center justify-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-6 py-3.5 text-sm font-semibold text-on-surface transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Continuer avec Google
                </button>

                <p class="mt-8 text-center text-sm text-on-surface-variant">
                    Pas encore de compte ?
                    <a href="{{ route('client.inscription') }}" class="font-semibold text-primary hover:underline">Créer un compte</a>
                </p>
            </div>
        </div>

        {{-- Right: Illustration (hidden on mobile) --}}
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center bg-gradient-to-br from-primary/5 to-secondary-container/10 p-12">
            <div class="text-center">
                <div class="mx-auto mb-6 flex h-48 w-48 items-center justify-center rounded-full bg-gradient-to-br from-primary/10 to-secondary-container/20">
                    <x-icon name="building-storefront" class="text-7xl text-primary" />
                </div>
                <h2 class="text-2xl font-bold text-on-surface">Bienvenue!</h2>
                <p class="mt-2 text-on-surface-variant">Connectez-vous pour commander</p>
            </div>
        </div>
    </div>
@endsection


