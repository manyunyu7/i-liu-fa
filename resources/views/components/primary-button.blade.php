<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-duo-green border-b-4 border-duo-green-dark rounded-duo font-bold text-sm text-white uppercase tracking-wide hover:bg-duo-green-dark active:border-b-0 active:mt-1 active:mb-[-4px] transition-all duration-100 focus:outline-none focus:ring-2 focus:ring-duo-green focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
