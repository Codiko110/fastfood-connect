@extends('layouts.client')

@section('title', 'Inscription - FlashFood')

@section('back', route('client.accueil'))

@section('content')
    <div class="flex min-h-[calc(100vh-10rem)]">
        {{-- Left: Bento food grid (hidden on mobile) --}}
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center bg-surface-container-low p-8">
            <div class="grid grid-cols-2 gap-4 max-w-md">
                <div class="col-span-2 flex aspect-video items-center justify-center rounded-2xl bg-gradient-to-br from-primary/10 to-primary/20">
                    <x-icon name="cake" class="text-5xl text-primary" />
                </div>
                <div class="flex aspect-square items-center justify-center rounded-2xl bg-gradient-to-br from-secondary-container/20 to-secondary-container/30">
                    <x-icon name="cake" class="text-4xl text-secondary" />
                </div>
                <div class="flex aspect-square items-center justify-center rounded-2xl bg-gradient-to-br from-primary/5 to-primary/15">
                    <x-icon name="cake" class="text-4xl text-primary" />
                </div>
                <div class="col-span-2 flex aspect-video items-center justify-center rounded-2xl bg-gradient-to-br from-secondary-container/10 to-secondary-container/20">
                    <x-icon name="sparkles" class="text-5xl text-secondary" />
                </div>
            </div>
        </div>

        {{-- Right: Registration Form --}}
        <div class="flex w-full flex-col justify-center px-6 py-10 lg:w-1/2 lg:px-12">
            <div class="mx-auto w-full max-w-lg">
                <h1 class="mb-1 text-3xl font-extrabold text-on-surface">Créer un Compte</h1>
                <p class="mb-8 text-on-surface-variant">Rejoignez FlashFood</p>

                @if(session('success'))
                    <div class="mb-4 rounded-2xl bg-green-100 px-5 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
                @endif

                <form action="{{ route('auth.register') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-on-surface">Nom complet</label>
                            <div class="flex items-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-4 py-3 transition-all focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                                <x-icon name="user" class="text-on-surface-variant text-xl" />
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Jean Dupont" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-on-surface">Téléphone</label>
                            <div class="flex items-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-4 py-3 transition-all focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                                <x-icon name="phone" class="text-on-surface-variant text-xl" />
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="06 12 34 56 78" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

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

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-on-surface">Mot de passe</label>
                            <div class="flex items-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-4 py-3 transition-all focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                                <x-icon name="lock-closed" class="text-on-surface-variant text-xl" />
                                <input type="password" name="password" placeholder="••••••••" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-on-surface">Confirmer</label>
                            <div class="flex items-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-4 py-3 transition-all focus-within:border-primary focus-within:shadow-[0_0_0_3px_rgba(183,26,26,0.12)]">
                                <x-icon name="lock-closed" class="text-on-surface-variant text-xl" />
                                <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full bg-transparent text-sm text-on-surface outline-none placeholder:text-on-surface-variant/60">
                            </div>
                        </div>
                    </div>

                    {{-- Terms --}}
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="terms" class="mt-0.5 h-4 w-4 rounded border-outline/30 text-primary focus:ring-primary">
                        <span class="text-sm text-on-surface-variant">
                            J'accepte les <a href="#" class="font-medium text-primary hover:underline">conditions d'utilisation</a> et la <a href="#" class="font-medium text-primary hover:underline">politique de confidentialité</a>
                        </span>
                    </label>
                    @error('terms')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="group relative w-full overflow-hidden rounded-2xl bg-primary py-3.5 font-bold text-white shadow-[0px_4px_20px_rgba(183,26,26,0.25)] transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(183,26,26,0.35)] active:scale-[0.98]">
                        <span class="relative z-10">Créer mon compte</span>
                        <span class="absolute inset-0 -translate-x-full bg-white/10 transition-transform duration-300 group-hover:translate-x-0"></span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="my-6 flex items-center gap-4">
                    <span class="h-px flex-1 bg-outline/20"></span>
                    <span class="text-xs text-on-surface-variant">OU</span>
                    <span class="h-px flex-1 bg-outline/20"></span>
                </div>

                {{-- Social Buttons --}}
                <div class="grid gap-3 sm:grid-cols-2">
                    <button class="flex items-center justify-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-6 py-3.5 text-sm font-semibold text-on-surface transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        Google
                    </button>
                    <button class="flex items-center justify-center gap-3 rounded-2xl border border-outline/30 bg-surface-container-lowest px-6 py-3.5 text-sm font-semibold text-on-surface transition-all hover:-translate-y-0.5 hover:shadow-[0px_8px_30px_rgba(33,33,33,0.12)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        Facebook
                    </button>
                </div>

                <p class="mt-8 text-center text-sm text-on-surface-variant">
                    Déjà un compte ?
                    <a href="{{ route('client.connexion') }}" class="font-semibold text-primary hover:underline">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
@endsection
