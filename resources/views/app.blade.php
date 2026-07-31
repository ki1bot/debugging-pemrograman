<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="image/png" href="/assets/logoKibot.png?v=2">
        <link rel="shortcut icon" type="image/png" href="/assets/logoKibot.png?v=2">
        <link rel="apple-touch-icon" href="/assets/logoKibot.png?v=2">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
        >
        <meta
            name="description"
            content="Debugging Pemrograman adalah platform latihan untuk membaca kode, menemukan bug, menguji perbaikan melalui sandbox, dan memahami penyebab masalah."
        >

        <title inertia>Rifqi | Debugging Pemrograman</title>

        @routes
        @viteReactRefresh
        @vite('resources/js/app.tsx')
        @inertiaHead
    </head>

    <body>
        @inertia
    </body>
</html>
