<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Countdown Timers</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-base flex flex-col min-h-screen">
<header class="bg-white p-4 shadow-zinc-300 shadow-sm flex justify-between items-center gap-4">
    <h1 class="text-headline text-4xl font-semibold">Countdown Timers</h1>
    <nav>
        <ul class="flex gap-2 items-center">
            @auth
                <li>
                    <a class="text-secondary hover:text-headline transition-colors hover:bg-base py-3 px-2"
                       href="{{route('timers.index')}}">Home</a>
                </li>
                <li>
                    <form action="{{route('auth.logout')}}" method="POST">
                        @csrf
                        <button class="text-secondary hover:text-headline transition-colors hover:bg-base py-3 px-2 cursor-pointer">Logout</button>
                    </form>
                </li>
            @endauth
            @guest
                <li>
                    <a class="text-secondary hover:text-headline transition-colors hover:bg-base py-3 px-2"
                       href="{{route('auth.login.view')}}">Login</a>
                </li>
            @endguest
        </ul>
    </nav>
</header>
<main class="p-4 flex-1 flex flex-col">
    {{$slot}}
</main>
</body>
</html>
