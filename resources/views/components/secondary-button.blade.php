<button {{ $attributes->merge([
    'type' => 'button',
    'class' => '
        bg-white
        border
        border-gray-300
        dark:bg-gray-800
        dark:border-gray-500
        dark:focus:ring-offset-gray-800
        dark:hover:bg-gray-700
        dark:text-gray-300
        disabled:opacity-25
        duration-150
        ease-in-out
        focus:outline-hidden
        focus:ring-2
        focus:ring-indigo-500
        focus:ring-offset-2
        font-semibold
        hover:bg-gray-50
        inline-flex
        items-center
        px-4
        py-2
        rounded-md
        shadow-xs
        text-gray-700
        text-xs
        tracking-widest
        transition
        uppercase
    ']) }}>
    {{ $slot }}
</button>
