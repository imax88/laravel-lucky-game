<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Game Application')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&family=Playfair+Display:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            dark: '#1D3557',
                            accent: '#2A9D8F',
                        },
                        secondary: {
                            accent: '#F4A261',
                        },
                        highlight: '#E76F51',
                        light: {
                            bg: '#F8F8F8',
                        },
                        text: {
                            dark: '#333333',
                            light: '#666666',
                        },
                    },
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        playfair: ['Playfair Display', 'serif'],
                        roboto: ['Roboto', 'sans-serif'],
                    },
                    boxShadow: {
                        custom: '0 4px 12px rgba(0, 0, 0, 0.08)',
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-light-bg font-roboto">
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>
</body>
</html>
