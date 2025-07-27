<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @vite(['resources/css/app.css','resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rsvp/4.8.5/rsvp.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/qz-tray/sha256.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.0/qz-tray.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <title>{{ config('app.name', 'Chana Frozen') }} {{ isset($title) ? '| '.$title : '' }} </title>

        @livewireStyles
    </head>
    <body>
        <div class="min-h-screen flex bg-gray-100">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <main class="flex-1 p-8">
                {{ $slot ?? '' }}
            </main>
        </div>

    @stack('scripts')

    <script>
        window.addEventListener('swal', event => {
            const msg = event.detail[0];

            Swal.fire({
                title: msg.title,
                text: msg.text,
                icon: msg.icon,
                timer: 2000,
                showConfirmButton: false,
            });
        });
    </script>

    @livewireScripts

    </body>
</html>
