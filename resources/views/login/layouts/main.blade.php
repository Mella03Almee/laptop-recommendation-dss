<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Pemilihan Laptop Berbasis Weighted Product" />
    <meta name="author" content="Mella" />
    <title>{{ $title }}</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS Styles --}}
    <link href="/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            min-height: 100vh;
            color: #334155;
        }

        #layoutAuthentication {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        #layoutAuthentication_content {
            flex: 1 0 auto;
        }

        /* Card Login Modern */
        .card-login {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            background: #FFFFFF;
        }

        .btn-login {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 50rem;
            transition: all 0.2s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }
    </style>
</head>

<body>
    <div id="layoutAuthentication">
        {{-- Area Konten Form Login --}}
        <div id="layoutAuthentication_content" class="d-flex align-items-center justify-content-center py-5">
            @yield('container')
        </div>

        {{-- Footer Otentikasi --}}
        @include('login.partials.footer')
    </div>

    {{-- JS Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
    <script src="/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
    @livewireScripts
</body>

</html>