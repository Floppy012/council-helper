<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        @vite(['resources/css/tailwind.css', 'resources/css/app.sass', 'resources/js/app.ts'])
    </head>
    <body class="relative bg-dark-600 text-gray-200 z-0">
        {{ $slot }}
    </body>
</html>
