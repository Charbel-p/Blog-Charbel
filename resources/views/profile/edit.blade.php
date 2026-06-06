<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-brand-600 font-medium">Espace personnel</p>
            <h2 class="font-semibold text-xl text-brand-950 leading-tight">Mon compte</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-brand-100">
                <div class="p-6 sm:p-8 flex flex-col sm:flex-row gap-6 items-start">
                    <div class="shrink-0">
                        @if($user->hasProfilePhoto())
                            <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}"
                                 class="w-24 h-24 rounded-full object-cover border-4 border-brand-100 shadow-sm">
                        @else
                            <div class="w-24 h-24 rounded-full bg-brand-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-brand-100 shadow-sm">
                                {{ $user->profileInitial() }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-brand-950">{{ $user->name }}</h3>
                            @if($user->isAdmin())
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-accent-100 text-accent-800">Administrateur</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full bg-brand-100 text-brand-800">Visiteur</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mb-3">{{ $user->email }}</p>

                        @if($user->bio)
                            <p class="text-gray-700 text-sm leading-relaxed">{{ $user->bio }}</p>
                        @else
                            <p class="text-gray-400 text-sm italic">Aucune bio pour le moment.</p>
                        @endif

                        <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-500">
                            <span>{{ $user->comments_count }} commentaire(s)</span>
                            <span>{{ $user->reviews_count }} note(s)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-brand-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-brand-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm sm:rounded-xl border border-brand-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
