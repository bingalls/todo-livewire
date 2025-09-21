<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        active:bg-gray-900
        bg-green-600
        border
        border-transparent
        dark:active:bg-gray-300
        dark:bg-gray-200
        dark:focus:bg-white
        dark:focus:ring-offset-gray-800
        dark:hover:bg-white
        dark:text-gray-800
        ease-in-out duration-150
        focus:bg-gray-700
        focus:outline-hidden
        focus:ring-2
        focus:ring-indigo-500
        focus:ring-offset-2
        font-semibold
        hover:bg-gray-700
        inline-flex
        items-center
        px-4
        py-2
        rounded-md
        text-white
        text-xs
        tracking-widest
        transition
        uppercase
    ',
    ]) }}>
    {{ $slot }}
</button>
