@props(['light' => false, 'animate' => false])
<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'logo'.($light ? ' logo-light' : '')]) }} aria-label="Kasawo Yaffe home">
    <x-logo-mark :size="44" :animate="$animate" />
    <span class="logo-word">Kasawo <em>Yaffe</em></span>
</a>
