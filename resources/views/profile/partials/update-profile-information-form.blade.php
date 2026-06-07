<section>
    <header>
        <h2 class="text-lg font-medium text-brand-900">
            Informations du compte
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Modifiez votre photo, votre bio, votre nom et votre adresse e-mail.
        </p>
    </header>

    @if($user->hasProfilePhoto())
        <form id="delete-photo-form" method="post" action="{{ route('profile.photo.destroy') }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="photo_profil" value="Photo de profil" />
            <div class="mt-2 flex items-center gap-4">
                <div id="photo-preview" class="shrink-0">
                    @if($user->hasProfilePhoto())
                        <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}"
                             class="w-16 h-16 rounded-full object-cover border border-brand-100">
                    @else
                        <div class="w-16 h-16 rounded-full bg-brand-600 flex items-center justify-center text-white text-lg font-semibold">
                            {{ $user->profileInitial() }}
                        </div>
                    @endif
                </div>
                <input type="file" id="photo_profil" name="photo_profil" accept="image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>
            <p class="mt-1 text-xs text-gray-500">JPG, PNG ou WebP — 2 Mo max.</p>
            <x-input-error class="mt-2" :messages="$errors->get('photo_profil')" />

            @if($user->hasProfilePhoto())
                <button type="submit"
                        form="delete-photo-form"
                        onclick="return confirm('Supprimer votre photo de profil ?')"
                        class="mt-3 text-sm font-medium text-red-600 hover:text-red-800">
                    Supprimer ma photo actuelle
                </button>
            @endif
        </div>

        <div>
            <x-input-label for="name" value="Nom" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Votre adresse e-mail n'est pas vérifiée.

                        <button form="send-verification" class="underline text-sm text-brand-600 hover:text-brand-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Renvoyer l'e-mail de vérification
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Un nouveau lien de vérification a été envoyé.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="bio" value="Bio (optionnel)" />
            <textarea id="bio" name="bio" rows="4" maxlength="500"
                      class="mt-1 block w-full input-brand"
                      placeholder="Quelques mots sur vous...">{{ old('bio', $user->bio) }}</textarea>
            <p class="mt-1 text-xs text-gray-500">500 caractères maximum.</p>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Enregistrer</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-600"
                >Profil mis à jour.</p>
            @elseif (session('status') === 'profile-photo-removed')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-green-600"
                >Photo de profil supprimée.</p>
            @endif
        </div>
    </form>

    <script>
        document.getElementById('photo_profil')?.addEventListener('change', function (event) {
            const file = event.target.files?.[0];
            const preview = document.getElementById('photo-preview');

            if (! file || ! preview) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Apercu" class="w-16 h-16 rounded-full object-cover border border-brand-100">';
            };
            reader.readAsDataURL(file);
        });
    </script>
</section>
