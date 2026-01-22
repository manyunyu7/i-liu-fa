@props(['interactive' => false])

<div {{ $attributes->merge([
    'class' => 'bg-white border-2 border-duo-gray-100 rounded-duo p-5 shadow-duo ' .
               ($interactive ? 'cursor-pointer transition-all duration-200 hover:shadow-duo-md hover:-translate-y-1' : '')
]) }}>
    {{ $slot }}
</div>
