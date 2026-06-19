<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - FlashFood</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-on-surface antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <x-icon name="building-storefront" class="text-on-primary text-3xl" filled />
            </div>
            <h1 class="text-2xl font-bold text-on-surface">FlashFood</h1>
            <p class="text-on-surface-variant mt-1">Administration</p>
        </div>

        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/10 p-8 card-shadow">
            <h2 class="text-lg font-bold text-on-surface mb-6">Connexion</h2>

            @if($errors->any())
                <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-on-surface mb-2">Email</label>
                    <div class="relative">
                        <x-icon name="envelope" class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" />
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@flashfood.fr" class="w-full pl-12 pr-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-on-surface mb-2">Mot de passe</label>
                    <div class="relative">
                        <x-icon name="lock-closed" class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant" />
                        <input type="password" id="password" name="password" placeholder="••••••••" class="w-full pl-12 pr-4 py-3 bg-surface-container-low rounded-xl border border-outline-variant/20 text-on-surface placeholder-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-primary text-on-primary font-bold text-base transition-all hover:shadow-lg hover:scale-[1.02] primary-glow inline-flex items-center justify-center gap-2">
                    <x-icon name="arrow-right-end-on-rectangle" class="text-lg" />
                    Se connecter
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-on-surface-variant mt-6">FlashFood Admin Panel</p>
    </div>
</body>
</html>
