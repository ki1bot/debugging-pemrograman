<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        @php
            $pageScripts = [];
            $pageStyles = [];
            $manifestPath = public_path('build/manifest.json');
            $pageComponent = data_get($page ?? [], 'component');

            if (
                is_string($pageComponent)
                && $pageComponent !== ''
                && is_file($manifestPath)
            ) {
                $manifest = json_decode(
                    file_get_contents($manifestPath),
                    true,
                );

                $manifestKey = "resources/js/Pages/{$pageComponent}.tsx";

                if (
                    is_array($manifest)
                    && isset($manifest[$manifestKey])
                    && is_array($manifest[$manifestKey])
                ) {
                    $seen = [];

                    $collectChunk = function (string $key) use (
                        &$collectChunk,
                        &$pageScripts,
                        &$pageStyles,
                        &$seen,
                        $manifest,
                    ): void {
                        if (
                            isset($seen[$key])
                            || !isset($manifest[$key])
                            || !is_array($manifest[$key])
                        ) {
                            return;
                        }

                        $seen[$key] = true;
                        $chunk = $manifest[$key];

                        foreach ($chunk['imports'] ?? [] as $import) {
                            if (is_string($import)) {
                                $collectChunk($import);
                            }
                        }

                        $file = $chunk['file'] ?? null;

                        if (is_string($file) && $file !== '') {
                            $pageScripts[] = $file;
                        }

                        foreach ($chunk['css'] ?? [] as $style) {
                            if (is_string($style) && $style !== '') {
                                $pageStyles[] = $style;
                            }
                        }
                    };

                    $collectChunk($manifestKey);
                }
            }

            $pageScripts = array_values(array_unique($pageScripts));
            $pageStyles = array_values(array_unique($pageStyles));
        @endphp

        @foreach ($pageStyles as $pageStyle)
            <link
                rel="stylesheet"
                href="{{ asset('build/'.$pageStyle) }}"
            >
        @endforeach

        @foreach ($pageScripts as $pageScript)
            <link
                rel="modulepreload"
                href="{{ asset('build/'.$pageScript) }}"
            >
        @endforeach

        <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
        >
        <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
        >

        <link
            rel="icon"
            type="image/png"
            href="/assets/logoKibot.png?v=2"
        >
        <link
            rel="shortcut icon"
            type="image/png"
            href="/assets/logoKibot.png?v=2"
        >
        <link
            rel="apple-touch-icon"
            href="/assets/logoKibot.png?v=2"
        >

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
