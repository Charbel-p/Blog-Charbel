@props(['comment', 'depth' => 0])

<div class="border-b border-gray-100 py-3 last:border-0" style="margin-left: {{ $depth * 1.5 }}rem">
    <div class="flex items-center justify-between">
        <div>
            <strong class="text-sm">{{ $comment->user->name }}</strong>
            @if($comment->isReply() && $comment->parent)
                <span class="text-xs text-gray-400 ms-2">réponse à {{ $comment->parent->user->name }}</span>
            @endif
        </div>
        @if($comment->is_approved)
            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-800">Approuve</span>
        @else
            <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">En attente</span>
        @endif
    </div>
    <p class="text-sm text-gray-600 mt-1">{{ $comment->content }}</p>
</div>

@foreach ($comment->children as $reply)
    @include('admin.posts.partials.comment', ['comment' => $reply, 'depth' => $depth + 1])
@endforeach
