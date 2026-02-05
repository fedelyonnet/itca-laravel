<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ITCA - Panel de Administración</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/backoffice.css', 'resources/js/backoffice.js'])
        
        @stack('head')
        
        <!-- SortableJS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- Alpine.js x-cloak CSS -->
        <style>
            [x-cloak] { display: none !important; }
            /* Fix dropdown menu visibility */
            [x-show="true"] { display: block !important; }
            [x-show="false"] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased dark">
        <div class="min-h-screen bg-gray-900 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-800 dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Confirmation Modal with Alpine.js -->
        <div id="confirmation-modal" x-data="{ open: false, title: '', message: '', onConfirm: null }" x-show="open" x-cloak>
            <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900" x-text="title"></h3>
                        <p class="mt-2 text-sm text-gray-500" x-text="message"></p>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                        <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button @click="if(onConfirm) onConfirm(); open = false" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
