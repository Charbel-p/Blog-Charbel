<x-public-layout>
    <x-slot name="title">{{ $post->title }} — Le blog de Charbel</x-slot>

    <div class="mb-6">
        <a href="{{ route('posts.index') }}" class="link-brand text-sm inline-flex items-center gap-1">
            &larr; Retour aux articles
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <article class="lg:col-span-2">
            <div class="card-blog overflow-hidden">
                @if($post->hasCoverImage())
                    <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}"
                         class="w-full h-56 sm:h-72 object-cover">
                @else
                    <div class="w-full h-56 sm:h-72 bg-gradient-to-br from-brand-800 via-brand-600 to-brand-400 flex items-center justify-center">
                        <span class="text-white/70 text-6xl font-bold">C</span>
                    </div>
                @endif
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="badge-brand">{{ $post->category->name }}</span>
                        @if($post->published_at)
                            <span class="text-xs text-gray-400">{{ $post->published_at->format('d/m/Y') }}</span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold text-brand-950 mb-4">{{ $post->title }}</h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b border-brand-50">
                        <span>Par <x-user-link :user="$post->user" /></span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="font-medium text-brand-800">{{ number_format((float) $post->reviews_avg_rating, 1) }}/5</span>
                        </span>
                    </div>

                    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>

            <section class="mt-8" id="comments">
                <h2 class="text-lg font-semibold text-brand-900 mb-4">
                    Commentaires ({{ $commentsCount }})
                </h2>

                @forelse ($post->comments as $comment)
                    @include('posts.partials.comment', ['comment' => $comment, 'post' => $post])
                @empty
                    <p class="text-gray-500 text-sm bg-white rounded-lg border border-brand-100 p-4">
                        Aucun commentaire pour le moment. Soyez le premier à réagir !
                    </p>
                @endforelse
            </section>
        </article>

        <aside class="space-y-6">
            @auth
                <div class="card-blog p-6">
                    <h2 class="text-lg font-semibold text-brand-900 mb-1">Donner votre avis</h2>
                    <p class="text-xs text-gray-500 mb-4">Note de 1 a 5 etoiles</p>
                    <form action="{{ route('reviews.store', $post) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="rating" value="Note (1 a 5)" />
                            <x-text-input id="rating" name="rating" type="number" min="1" max="5"
                                          class="mt-1 block w-full" :value="old('rating', 5)" required />
                            <x-input-error :messages="$errors->get('rating')" class="mt-1" />
                        </div>
                        <x-primary-button class="w-full justify-center">Envoyer ma note</x-primary-button>
                    </form>
                </div>

                <div class="card-blog p-6">
                    <h2 class="text-lg font-semibold text-brand-900 mb-4">Laisser un commentaire</h2>
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <textarea name="content" rows="4" required class="block w-full input-brand"
                                      placeholder="Ecrivez votre commentaire...">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-1" />
                        </div>
                        <x-primary-button class="w-full justify-center">Publier le commentaire</x-primary-button>
                    </form>
                </div>
            @else
                <div class="rounded-xl border border-brand-200 bg-brand-50 p-6 text-center">
                    <p class="text-sm text-brand-800 mb-4">
                        Connectez-vous pour commenter et noter cet article.
                    </p>
                    <a href="{{ route('login') }}" class="btn-brand">Se connecter</a>
                </div>
            @endauth
        </aside>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.reply-toggle').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.getElementById(button.dataset.target);
                    form?.classList.toggle('hidden');
                    if (form && !form.classList.contains('hidden')) {
                        form.querySelector('textarea')?.focus();
                    }
                });
            });

            document.querySelectorAll('.reply-cancel').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.getElementById(button.dataset.target);
                    if (form) {
                        form.classList.add('hidden');
                        form.querySelector('textarea').value = '';
                    }
                });
            });

            document.querySelectorAll('.edit-toggle').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.getElementById(button.dataset.target);
                    form?.classList.toggle('hidden');
                    if (form && !form.classList.contains('hidden')) {
                        form.querySelector('textarea')?.focus();
                    }
                });
            });

            document.querySelectorAll('.edit-cancel').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.getElementById(button.dataset.target);
                    const content = document.getElementById(button.dataset.contentTarget);
                    if (form) {
                        form.classList.add('hidden');
                        if (content && button.dataset.original) {
                            form.querySelector('textarea').value = button.dataset.original;
                        }
                    }
                });
            });

            @if(old('parent_id'))
                document.getElementById('reply-form-{{ old('parent_id') }}')?.classList.remove('hidden');
            @endif

            @if(old('edit_comment_id'))
                document.getElementById('edit-form-{{ old('edit_comment_id') }}')?.classList.remove('hidden');
            @elseif(request('edit'))
                document.getElementById('edit-form-{{ request('edit') }}')?.classList.remove('hidden');
            @endif
        </script>
    @endpush
</x-public-layout>
