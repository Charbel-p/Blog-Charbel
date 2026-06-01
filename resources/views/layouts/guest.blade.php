<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Le blog de Charbel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <x-vite-assets />
    </head>
    <body class="font-sans antialiased bg-brand-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 px-4">
            <div class="mb-6 text-center">
                <a href="{{ route('posts.index') }}" class="inline-flex flex-col items-center gap-2">
                    <div class="w-14 h-14 rounded-xl bg-brand-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        C
                    </div>
                    <span class="text-brand-900 font-semibold text-lg">Le blog de Charbel</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white shadow-xl rounded-xl border border-brand-200 px-6 py-8 text-gray-900">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
