@props(['active' => false, 'icon' => ''])

<a {{ $attributes->merge([
    'class' => 'flex items-center space-x-3 px-4 py-3 rounded-duo font-bold transition-all duration-200 ' .
               ($active
                   ? 'bg-duo-green-light/20 text-duo-green border-2 border-duo-green'
                   : 'text-duo-gray-300 hover:bg-duo-gray-50 border-2 border-transparent')
]) }}>
    @if($icon)
        <span class="text-xl">{{ $icon }}</span>
    @endif
    <span>{{ $slot }}</span>
</a>
