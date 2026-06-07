@props(['user'])

<a href="{{ route('users.show', $user) }}"
   {{ $attributes->merge(['class' => 'text-brand-800 hover:text-brand-600 hover:underline font-medium']) }}
   title="Voir le profil de {{ $user->name }}">
    {{ $user->name }}
</a>
