<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Moderation des commentaires</h2>
            <a href="{{ route('admin.posts.index') }}"
               class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Retour aux articles
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Article</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auteur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commentaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($comments as $comment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $comment->post->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $comment->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                        @if($comment->isReply())
                                            <span class="inline-flex px-2 py-0.5 text-xs rounded bg-brand-50 text-brand-700 mb-1">Réponse</span>
                                            @if($comment->parent)
                                                <span class="text-xs text-gray-400 block mb-1">à {{ $comment->parent->user->name }}</span>
                                            @endif
                                        @endif
                                        {{ $comment->content }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($comment->is_approved)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Approuve</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">En attente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm space-x-2">
                                        @unless($comment->is_approved)
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approuver</button>
                                            </form>
                                        @endunless
                                        <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer ce commentaire ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucun commentaire.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($comments->hasPages())
                <div class="mt-6">{{ $comments->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
