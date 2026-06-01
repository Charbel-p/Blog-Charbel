<x-public-layout>
    <x-slot name="title">Le blog de Charbel</x-slot>

    <x-slot name="header">
        <div class="max-w-3xl">
            <p class="text-accent-400 text-sm font-semibold uppercase tracking-wider mb-2">Bienvenue</p>
            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight">Le blog de Charbel</h1>
            <p class="mt-4 text-brand-100 text-lg leading-relaxed">
                Mes articles, vos commentaires et vos notes. Un coin personnel pour echanger et partager.
            </p>
        </div>
    </x-slot>

    @forelse ($posts as $post)
        <article class="card-blog mb-6 overflow-hidden">
            @if($post->hasCoverImage())
                <a href="{{ route('posts.show', $post->slug) }}" class="block">
                    <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}"
                         class="w-full h-48 sm:h-56 object-cover">
                </a>
            @else
                <div class="w-full h-48 sm:h-56 bg-gradient-to-br from-brand-700 to-brand-400 flex items-center justify-center">
                    <span class="text-white/80 text-4xl font-bold">C</span>
                </div>
            @endif
            <div class="p-6 sm:p-8">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="badge-brand">{{ $post->category->name }}</span>
                    @if($post->published_at)
                        <span class="text-xs text-gray-400">{{ $post->published_at->format('d/m/Y') }}</span>
                    @endif
                </div>

                <h2 class="text-xl sm:text-2xl font-bold text-brand-950 mb-2">
                    <a href="{{ route('posts.show', $post->slug) }}" class="hover:text-brand-600 transition-colors">
                        {{ $post->title }}
                    </a>
                </h2>

                <p class="text-gray-600 leading-relaxed mb-4">
                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 180) }}
                </p>

                <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-brand-50">
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-medium text-brand-800">{{ number_format((float) $post->reviews_avg_rating, 1) }}/5</span>
                            <span class="text-gray-400">({{ $post->reviews_count }})</span>
                        </span>
                        <span>{{ $post->comments_count }} commentaire(s)</span>
                    </div>

                    <a href="{{ route('posts.show', $post->slug) }}" class="link-brand text-sm font-semibold">
                        Lire la suite &rarr;
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div class="text-center py-16 bg-white rounded-xl border border-brand-100">
            <div class="w-16 h-16 mx-auto rounded-full bg-brand-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-brand-900">Aucun article publie</h3>
            <p class="mt-2 text-gray-500">Revenez bientot ou connectez-vous pour en creer un.</p>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.posts.create') }}" class="btn-brand mt-6">Creer mon premier article</a>
                @endif
            @endauth
        </div>
    @endforelse

    @if($posts->hasPages())
        <div class="mt-8">{{ $posts->links() }}</div>
    @endif
</x-public-layout>
