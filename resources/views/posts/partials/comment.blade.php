@props(['comment', 'post'])

<div id="comment-{{ $comment->id }}" class="{{ $comment->isReply() ? 'ms-8 sm:ms-10 mt-3' : 'mb-3' }}">
    <div class="bg-white rounded-lg border border-brand-100 p-4 {{ $comment->isReply() ? 'border-l-4 border-l-brand-300' : '' }}">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-white text-sm font-semibold shrink-0">
                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
            </div>
            <div>
                <strong class="text-sm text-brand-900">{{ $comment->user->name }}</strong>
                @if($comment->isReply() && $comment->parent)
                    <span class="text-xs text-gray-400 block sm:inline sm:ms-2">
                        en réponse à {{ $comment->parent->user->name }}
                    </span>
                @endif
            </div>
        </div>

        <p id="comment-content-{{ $comment->id }}" class="text-gray-600 text-sm">{{ $comment->content }}</p>

        <div class="mt-3 flex flex-wrap items-center gap-3">
            @auth
                <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 text-xs font-medium transition {{ $comment->isLikedBy(auth()->user()) ? 'text-brand-700' : 'text-gray-500 hover:text-brand-600' }}"
                            title="{{ $comment->isLikedBy(auth()->user()) ? 'Retirer mon like' : 'Aimer ce commentaire' }}">
                        <svg class="w-4 h-4" fill="{{ $comment->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <span>{{ $comment->likes_count }}</span>
                    </button>
                </form>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs text-gray-400" title="Likes">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                    <span>{{ $comment->likes_count }}</span>
                </span>
            @endauth

            @auth
                <button type="button"
                        class="text-xs font-medium text-brand-600 hover:text-brand-800 reply-toggle"
                        data-target="reply-form-{{ $comment->id }}">
                    Répondre
                </button>

                @if($comment->isOwnedBy(auth()->user()))
                    <button type="button"
                            class="text-xs font-medium text-gray-600 hover:text-gray-800 edit-toggle"
                            data-target="edit-form-{{ $comment->id }}">
                        Modifier
                    </button>

                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline"
                          onsubmit="return confirm('Supprimer ce commentaire ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">
                            Supprimer
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        @auth
            @if($comment->isOwnedBy(auth()->user()))
                <div id="edit-form-{{ $comment->id }}" class="edit-form hidden mt-3 pt-3 border-t border-brand-50">
                    <form action="{{ route('comments.update', $comment) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="edit_comment_id" value="{{ $comment->id }}">
                        <textarea name="content" rows="3" required
                                  class="block w-full input-brand text-sm">{{ old('edit_comment_id') == $comment->id ? old('content') : $comment->content }}</textarea>
                        @if(old('edit_comment_id') == $comment->id)
                            <x-input-error :messages="$errors->get('content')" class="mt-1" />
                        @endif
                        <div class="flex gap-2">
                            <x-primary-button class="text-xs">Enregistrer</x-primary-button>
                            <button type="button"
                                    class="text-xs text-gray-500 hover:text-gray-700 edit-cancel"
                                    data-target="edit-form-{{ $comment->id }}"
                                    data-content-target="comment-content-{{ $comment->id }}"
                                    data-original="{{ e($comment->content) }}">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div id="reply-form-{{ $comment->id }}" class="reply-form hidden mt-3 pt-3 border-t border-brand-50">
                <form action="{{ route('comments.store', $post) }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content" rows="3" required
                              class="block w-full input-brand text-sm"
                              placeholder="Ecrivez votre réponse...">{{ old('parent_id') == $comment->id ? old('content') : '' }}</textarea>
                    @if(old('parent_id') == $comment->id)
                        <x-input-error :messages="$errors->get('content')" class="mt-1" />
                    @endif
                    <div class="flex gap-2">
                        <x-primary-button class="text-xs">Publier la réponse</x-primary-button>
                        <button type="button"
                                class="text-xs text-gray-500 hover:text-gray-700 reply-cancel"
                                data-target="reply-form-{{ $comment->id }}">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        @endauth
    </div>

    @foreach ($comment->children as $reply)
        @include('posts.partials.comment', ['comment' => $reply, 'post' => $post])
    @endforeach
</div>
