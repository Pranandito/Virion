<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-800 antialiased bg-[#F4F7F3]">
    <div class="flex flex-col sm:justify-center items-center">
        <div class="lg:w-2/5 w-11/12 lg:my-28 my-4 px-12 lg:px-28 py-12 bg-[#FFFFF0] overflow-hidden rounded-3xl">
            <a href="/">
                <x-application-logo class="fill-current" class="mb-5" />
            </a>
            {{ $slot }}
        </div>
    </div>
</body>

</html>