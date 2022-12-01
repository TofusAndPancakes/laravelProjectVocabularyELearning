<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Language App</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;500&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{asset('style/style.css')}}">
    </head>
    <body>
    
    <div class="navbarSection">
        <div class="navbarMargin">
            <div class="navbarLogo">
                <div class="navbarMenuEntry">
                    @auth
                    <a href={{route('menu')}}>
                        <p>Home</p>
                    </a>
                    @else
                    <a href={{route('index')}}>
                        <p>Home</p>
                    </a>
                    @endauth
                </div>
            </div>
            <div class="navbarMenu">
                @auth
                @if (auth()->user()->admin == 1)
                <!-- Admin Interface -->
                <div class="navbarMenuEntry">
                    <a href={{route('admin.user')}}>
                        <p>Users</p>
                    </a>
                </div>
                <div class="navbarMenuEntry">
                    <a href={{route('admin.level')}}>
                        <p>Levels</p>
                    </a>
                </div>
                @endif
                <!-- Logout -->
                <div class="navbarMenuEntry">
                    <form class="inline" method="POST" action={{route('logout')}}>
                        @csrf
                        <button type="submit">
                            Logout
                        </button>
                    </form>
                </div>
                @else
                <!-- Login -->
                <div class="navbarMenuEntry">
                    <a href={{route('login')}}>
                        <p>Login</p>
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </div>

    @yield('content')

    <div class="footerSection">
        <div class="footerMargin">
            <p>Server-Side Web Development Module - Inspired by Wanikani, Special Thanks to Dimitri - 2022</p>
        </div>
    </div>
        
    </body>
</html>
