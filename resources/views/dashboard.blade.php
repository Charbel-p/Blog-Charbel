<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-brand-600 font-medium">Espace personnel</p>
            <h2 class="font-semibold text-xl text-brand-950 leading-tight">
                @if(auth()->user()->isAdmin())
                    Dashboard
                @else
                    Mes commentaires
                @endif
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-brand-100">
                <div class="p-6 sm:p-8">
                    <p class="text-brand-950 text-lg font-medium mb-1">Bonjour, {{ auth()->user()->name }} 👋</p>

                    @if(auth()->user()->isAdmin())
                        <p class="text-sm text-gray-600 mb-8">Tu es le proprietaire du blog. Que veux-tu faire ?</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <a href="{{ route('admin.posts.index') }}"
                               class="group block p-5 rounded-xl border border-brand-100 hover:border-brand-300 hover:shadow-md transition">
                                <div class="w-10 h-10 rounded-lg bg-accent-100 flex items-center justify-center mb-3 group-hover:bg-accent-400/30 transition">
                                    <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-brand-900">Mes articles</h3>
                                <p class="text-sm text-gray-500 mt-1">Gerer et modifier</p>
                            </a>

                            <a href="{{ route('admin.posts.create') }}"
                               class="group block p-5 rounded-xl border border-brand-100 hover:border-brand-300 hover:shadow-md transition">
                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mb-3 group-hover:bg-green-200 transition">
                                    <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-brand-900">Nouvel article</h3>
                                <p class="text-sm text-gray-500 mt-1">Ecrire et publier</p>
                            </a>

                            <a href="{{ route('admin.comments.index') }}"
                               class="group block p-5 rounded-xl border border-brand-100 hover:border-brand-300 hover:shadow-md transition">
                                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center mb-3 group-hover:bg-orange-200 transition">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-brand-900">Commentaires</h3>
                                <p class="text-sm text-gray-500 mt-1">Moderer les avis</p>
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-6">
                            Voici les commentaires que tu as publiés sur le blog de Charbel.
                        </p>

                        @forelse($myComments as $comment)
                            <div class="mb-4 p-4 rounded-lg border border-brand-100 bg-brand-50/30 hover:border-brand-200 transition">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                    <a href="{{ route('posts.show', $comment->post->slug) }}"
                                       class="font-semibold text-brand-800 hover:text-brand-600">
                                        {{ $comment->post->title }}
                                    </a>
                                    <span class="text-xs text-gray-400">
                                        {{ $comment->created_at->format('d/m/Y à H:i') }}
                                    </span>
                                </div>
                                <p class="text-gray-700 text-sm">{{ $comment->content }}</p>
                                <a href="{{ route('posts.show', $comment->post->slug) }}"
                                   class="inline-block mt-2 text-xs link-brand">
                                    Voir l'article &rarr;
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-8 rounded-lg border border-dashed border-brand-200">
                                <p class="text-gray-500 mb-4">Tu n'as pas encore laissé de commentaire.</p>
                                <a href="{{ route('posts.index') }}" class="btn-brand inline-flex">
                                    Découvrir les articles
                                </a>
                            </div>
                        @endforelse

                        @if($myComments->hasPages())
                            <div class="mt-6">{{ $myComments->links() }}</div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
