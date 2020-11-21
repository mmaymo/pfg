<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PFG Uned</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"
          rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
            <img src="images/logo_uned.gif" alt="Logo UNED">
        </div>

        <div class="mt-8  overflow-hidden shadow sm:rounded-lg">
            <div class="grid grid-cols-1">
                <div class="p-6">
                    <div class="flex items-center">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                             class="w-8 h-8 text-green-700">
                            <path
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <div class="ml-4 text-lg leading-7 font-semibold"><p class=" text-gray-900">
                                Bienvenido!</p></div>
                    </div>

                    <div class="ml-12 grid-cols-1">
                        <div class="mt-2 text-gray-900 ">
                            Esta es una propuesta para ofrecer cursos de la UNED con contenidos
                            interactivos. Para entrar tienes que acceder con el email de la
                            universidad. Si todavía no has creado una contraseña puedes hacerlo a
                            través del proceso "He olvidado mi contraseña"
                        </div>
                        <div class="flex items-center justify-center m-4">
                            @if (Route::has('login'))
                                <button
                                    class="bg-green-700 hover:bg-green-300 text-white font-bold py-2 px-4 rounded">
                                    @auth
                                        <a href="{{ url('/dashboard') }}" class="">Cursos</a>
                                    @else
                                        <a href="{{ route('login') }}" class="">Entra</a>
                                    @endif
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
            <div class="text-center text-sm text-green-700 sm:text-left">
                <div class="flex items-center">
                    <a href="http://portal.uned.es/" class="ml-1 font-bold hover:text-green-300">
                        Campus Uned
                    </a>
                </div>
            </div>

            <div class="ml-4 text-center text-sm text-red-900 sm:text-right sm:ml-0">
                <div class="flex items-center">

                    <svg fill="none" stroke="currentColor" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                         class="ml-4 -mt-px w-5 h-5 text-red-400">
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>

                    <a href="https://github.com/mmaymo" class="ml-1 font-bold hover:text-red-300">
                        built by mmaymo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script>
    import Button from "../js/Jetstream/Button";

    export default {
        components: {Button}
    }
</script>
