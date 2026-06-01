@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    {{-- Secours si le build Vite manque sur le serveur --}}
    <style>body{font-family:system-ui,sans-serif;margin:0;padding:1rem}</style>
@endif
