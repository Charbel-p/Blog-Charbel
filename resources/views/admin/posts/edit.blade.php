<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier l'article</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-brand-600 hover:text-brand-800">&larr; Retour</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" value="Titre" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                                      :value="old('title', $post->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="category_id" value="Categorie" />
                        <select name="category_id" id="category_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="excerpt" value="Resume" />
                        <textarea id="excerpt" name="excerpt" rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="cover_image" value="Image de couverture" />
                        @if($post->coverImageUrl())
                            <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}"
                                 class="mt-2 mb-3 w-full max-h-48 object-cover rounded-lg border border-brand-100">
                        @endif
                        <input type="file" id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp"
                               class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                        <p class="mt-1 text-xs text-gray-500">Laisser vide pour conserver l'image actuelle</p>
                        <x-input-error :messages="$errors->get('cover_image')" class="mt-1" />
                    </div>

                    <div>
                        <x-input-label for="content" value="Contenu" />
                        <textarea id="content" name="content" rows="10" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('content', $post->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-1" />
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1"
                               @checked(old('is_published', $post->is_published))
                               class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500">
                        <label for="is_published" class="ms-2 text-sm text-gray-700">Publier</label>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Mettre a jour</x-primary-button>
                        <a href="{{ route('admin.posts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
