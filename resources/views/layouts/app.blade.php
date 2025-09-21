<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow-sm">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
        <script src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.1/dist/livewire-sortable.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                "use strict";
                Livewire.on('swal:confirm', (event) => {
                    swal.fire({
                        title: event[0].title,
                        text: event[0].text,
                        icon: event[0].type,
                        showCancelButton: true,
                        confirmButtonColor: 'rgb(239 68 6)',
                        confirmButtonText: 'OK, delete!'
                    })
                    .then((willDelete) => {
                        if (willDelete.isConfirmed) {
                            // prevent 404 on page navigation
                            setTimeout(() => { window.location.reload(); }, 1000);
                            Livewire.dispatch(event[0].method, { id: event[0].id });
                        }
                    });
                })
            });
        </script>
        @stack('js')
    </body>
</html>
