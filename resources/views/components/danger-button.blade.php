<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        active:bg-red-700
        bg-red-600
        border
        border-transparent
        dark:focus:ring-offset-gray-800
        duration-150
        ease-in-out
        focus:outline-hidden
        focus:ring-2
        focus:ring-offset-2
        focus:ring-red-500
        font-semibold
        hover:bg-red-500
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
    ']) }}>
    {{ $slot }}
</button>
