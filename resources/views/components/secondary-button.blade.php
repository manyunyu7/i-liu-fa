<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-duo-gray-100 border-b-4 border-b-duo-gray-200 rounded-duo font-bold text-sm text-duo-gray-400 uppercase tracking-wide hover:bg-duo-gray-50 active:border-b-2 active:mt-1 active:mb-[-2px] transition-all duration-100 focus:outline-none focus:ring-2 focus:ring-duo-gray-200 focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
