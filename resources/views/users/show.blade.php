<x-public-layout>
    <x-slot name="title">Profil de {{ $user->name }} — Le blog de Charbel</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('posts.index') }}"
               class="link-brand text-sm inline-flex items-center gap-1">
                &larr; Retour
            </a>
        </div>

        <div class="card-blog overflow-hidden">
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row gap-6 items-start">
                <x-user-avatar :user="$user" size="lg" class="border-4 border-brand-100 shadow-sm" />

                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-2xl font-bold text-brand-950">{{ $user->name }}</h1>
                        @if($user->isAdmin())
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-accent-100 text-accent-800">Administrateur</span>
                        @endif
                    </div>

                    @if($user->cree_le)
                        <p class="text-sm text-gray-500 mb-4">
                            Membre depuis le {{ $user->cree_le->format('d/m/Y') }}
                        </p>
                    @endif

                    @if($user->bio)
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $user->bio }}</p>
                    @else
                        <p class="text-gray-400 text-sm italic">Cet utilisateur n'a pas encore renseigne de bio.</p>
                    @endif

                    <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-500">
                        <span>{{ $user->comments_count }} commentaire(s)</span>
                        <span>{{ $user->reviews_count }} note(s)</span>
                    </div>

                    @auth
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex mt-4 text-sm font-medium text-brand-600 hover:text-brand-800">
                                Modifier mon profil &rarr;
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        @if($recentComments->isNotEmpty())
            <section class="mt-8">
                <h2 class="text-lg font-semibold text-brand-900 mb-4">Derniers commentaires</h2>
                <div class="space-y-3">
                    @foreach($recentComments as $comment)
                        <div class="card-blog p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                <a href="{{ route('posts.show', $comment->post) }}#comment-{{ $comment->id }}"
                                   class="font-medium text-brand-800 hover:text-brand-600 text-sm">
                                    {{ $comment->post->title }}
                                </a>
                                @if($comment->cree_le)
                                    <span class="text-xs text-gray-400">{{ $comment->cree_le->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm">{{ \Illuminate\Support\Str::limit($comment->content, 200) }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-public-layout>
