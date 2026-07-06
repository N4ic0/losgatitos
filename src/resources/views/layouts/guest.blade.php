<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Motel Los Gatitos') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-white antialiased bg-black">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-b from-black via-dark to-black">
        <div class="mb-6">
            <a href="/" class="flex items-center space-x-3">
                <img src="/img/logo.png" alt="Logo" class="h-12 w-auto">
                <span class="text-white font-bold text-xl">Los Gatitos</span>
            </a>
        </div>
        <div class="w-full sm:max-w-md px-8 py-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
