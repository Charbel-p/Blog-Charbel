<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Administration des articles
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('posts.index') }}"
                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Voir le site
                </a>
                <a href="{{ route('admin.comments.index') }}"
                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Commentaires
                </a>
                <a href="{{ route('admin.posts.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-brand-600 text-white text-sm font-medium rounded-md hover:bg-brand-700">
                    Nouvel article
                </a>
            </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($posts as $post)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $post->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $post->category->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $post->user->name }}</td>
                                    <td class="px-6 py-4">
                                        @if($post->is_published)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Publie</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Brouillon</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm space-x-2">
                                        <a href="{{ route('admin.posts.show', $post) }}" class="text-brand-600 hover:text-brand-900">Voir</a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="text-brand-600 hover:text-brand-900">Modifier</a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer cet article ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucun article pour le moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($posts->hasPages())
                <div class="mt-6">{{ $posts->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
