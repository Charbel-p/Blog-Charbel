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

        <p class="text-gray-600 text-sm">{{ $comment->content }}</p>

        @auth
            <button type="button"
                    class="mt-3 text-xs font-medium text-brand-600 hover:text-brand-800 reply-toggle"
                    data-target="reply-form-{{ $comment->id }}">
                Répondre
            </button>

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
