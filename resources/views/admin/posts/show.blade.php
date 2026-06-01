<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-brand-600 hover:text-brand-800">Retour admin</a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('posts.show', $post->slug) }}" class="text-sm text-brand-600 hover:text-brand-800">Voir sur le site</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($post->hasCoverImage())
                    <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="w-full h-56 object-cover">
                @endif
                <div class="p-6 sm:p-8">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-brand-100 text-brand-800">{{ $post->category->name }}</span>
                    <span class="text-sm text-gray-500">Par {{ $post->user->name }}</span>
                </div>
                <div class="prose max-w-none text-gray-700">{!! nl2br(e($post->content)) !!}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Commentaires ({{ $post->comments->count() }})</h3>
                @forelse($post->comments as $comment)
                    <div class="border-b border-gray-100 py-3 last:border-0">
                        <div class="flex items-center justify-between">
                            <strong class="text-sm">{{ $comment->user->name }}</strong>
                            @if($comment->is_approved)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-800">Approuve</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $comment->content }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Aucun commentaire.</p>
                @endforelse
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Avis / notes ({{ $post->reviews->count() }})</h3>
                @forelse($post->reviews as $review)
                    <div class="border-b border-gray-100 py-3 last:border-0">
                        <strong class="text-sm">{{ $review->user->name }}</strong>
                        <span class="text-yellow-500 text-sm ms-2">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        @if($review->opinion)
                            <p class="text-sm text-gray-600 mt-1">{{ $review->opinion }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Aucun avis.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
