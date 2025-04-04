<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- metadata --}}
        <meta name="author" content="{{ setting("metadata_author") }}">
        <meta name="description" content="{{ setting("metadata_description") }}">
        <meta name="keywords" content="{{ setting("metadata_keywords") }}">

        <meta property="og:title" content="{{ setting("metadata_title") }}">
        <meta property="og:description" content="{{ setting("metadata_description") }}">
        <meta property="og:image" content="{{ setting("metadata_image") }}">
        <meta property="og:url" content="{{ route("main") }}">
        <meta property="og:type" content="website">

        <link rel="icon" href="{{ setting("app_favicon_path") }}">

        @inertiaHead

        <!-- Styles -->
        @vite('resources/css/app.css')
        @vite('resources/css/identity.css')

        <style>
        :root {
            --primary-light: {{ setting("color_primary") }};
            --secondary-light: {{ setting("color_secondary") }};
            --tertiary-light: {{ setting("color_tertiary") }};
            --primary-dark: {{ setting("color_primary_dark") }};
            --secondary-dark: {{ setting("color_secondary_dark") }};
            --tertiary-dark: {{ setting("color_tertiary_dark") }};
        }
        </style>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="flex down">
        @inertia
    </body>
</html>
