<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Le blog de Charbel' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <x-vite-assets />
    </head>
    <body class="font-sans antialiased text-gray-900">
        <nav x-data="{ open: false }" class="bg-white/90 backdrop-blur border-b border-brand-100 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ route('posts.index') }}" class="flex items-center gap-3 group">
                            <div class="w-9 h-9 rounded-lg bg-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-sm group-hover:bg-brand-700 transition">
                                C
                            </div>
                            <div class="hidden sm:block">
                                <span class="font-bold text-brand-900 leading-tight block">Le blog de Charbel</span>
                                <span class="text-xs text-brand-500">Pensees, avis & partages</span>
                            </div>
                        </a>
                        <div class="hidden sm:flex sm:items-center sm:gap-6">
                            <a href="{{ route('posts.index') }}"
                               class="text-sm font-medium {{ request()->routeIs('posts.*') ? 'nav-active' : 'text-gray-600 hover:text-brand-700' }}">
                                Accueil
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="text-sm font-medium {{ request()->routeIs('dashboard') || request()->routeIs('admin.*') ? 'nav-active' : 'text-gray-600 hover:text-brand-700' }}">
                                    @if(auth()->user()->isAdmin())
                                        Dashboard
                                    @else
                                        Mes commentaires
                                    @endif
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="text-sm font-medium {{ request()->routeIs('profile.*') ? 'nav-active' : 'text-gray-600 hover:text-brand-700' }}">
                                    Mon compte
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:gap-4">
                        @auth
                            <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-gray-600 hover:text-brand-700">Mon compte</a>
                            <span class="text-sm text-gray-500">Bonjour, <span class="text-brand-700 font-medium">{{ auth()->user()->name }}</span></span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Deconnexion</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-brand-700">Connexion</a>
                            <a href="{{ route('register') }}" class="btn-brand">Inscription</a>
                        @endauth
                    </div>

                    <div class="flex items-center sm:hidden">
                        <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-brand-700 hover:bg-brand-50">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-brand-100 bg-white">
                <div class="px-4 py-3 space-y-2">
                    <a href="{{ route('posts.index') }}" class="block text-sm font-medium text-gray-700">Accueil</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-sm font-medium text-gray-700">
                            @if(auth()->user()->isAdmin())
                                Dashboard
                            @else
                                Mes commentaires
                            @endif
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block text-sm font-medium text-gray-700">Mon compte</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block text-sm font-medium text-red-600">Deconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-sm font-medium text-gray-700">Connexion</a>
                        <a href="{{ route('register') }}" class="block text-sm font-medium text-brand-700">Inscription</a>
                    @endauth
                </div>
            </div>
        </nav>

        @isset($header)
            <header class="hero-gradient relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-accent-400 blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-brand-300 blur-3xl"></div>
                </div>
                <div class="relative max-w-7xl mx-auto py-14 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg bg-brand-50 border border-brand-200 px-4 py-3 text-sm text-brand-800">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>

        <footer class="border-t border-brand-100 bg-brand-950 text-brand-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="font-semibold text-white">Le blog de Charbel</p>
                        <p class="text-sm text-brand-300 mt-1">Un espace pour partager, commenter et noter.</p>
                    </div>
                    <p class="text-sm text-brand-400">&copy; {{ date('Y') }} &mdash; Propulse par Laravel</p>
                </div>
            </div>
        </footer>
        @stack('scripts')
    </body>
</html>
