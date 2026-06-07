<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Utilisateurs inscrits</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.posts.index') }}"
                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Articles
                </a>
                <a href="{{ route('admin.comments.index') }}"
                   class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Commentaires
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-4 text-sm text-gray-600">
                {{ $users->total() }} utilisateur(s) enregistre(s) sur la plateforme.
            </p>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-mail</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscrit le</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Activite</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <div class="flex items-center gap-3">
                                            @if($user->profilePhotoUrl())
                                                <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}"
                                                     class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-brand-600 flex items-center justify-center text-white text-xs font-semibold">
                                                    {{ $user->profileInitial() }}
                                                </div>
                                            @endif
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($user->isAdmin())
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-accent-100 text-accent-800">Administrateur</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-brand-100 text-brand-800">Visiteur</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        @if($user->cree_le)
                                            {{ $user->cree_le->format('d/m/Y à H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $user->comments_count }} commentaire(s),
                                        {{ $user->reviews_count }} note(s)
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Aucun utilisateur.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($users->hasPages())
                <div class="mt-6">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
