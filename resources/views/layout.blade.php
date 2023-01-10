<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Language App</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;500&display=swap" rel="stylesheet">

        <!-- Bootstrap, without JS because only use for Tables, copied in-case version updates cause issues-->
        <link rel="stylesheet" href="{{asset('style/bootstrap.css')}}">

        <link rel="stylesheet" href="{{asset('style/style.css')}}">
        @auth
        <script>
        //Script will Only Run when Auth, hopefully!
    
        //Tutorial Local Storage Check, if user cleared the Tutorial Setting or first time entering..
        if (!localStorage.getItem('vocabAppTutorial')){
            localStorage.setItem('vocabAppTutorial', '0');
            localStorage.setItem('vocabAppTutorialFooterInfo', '0');
            footerTutorial.textContent = "Disable Tutorial";
        }
        </script>
        @endauth

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
            <div class="footerEntryButton">
                @auth
                <p id="footerTutorial" onclick="footerTutorialToggle()">Toggle Tutorial</p>
                @else
                <!-- Nothing -->
                @endauth
            </div>
            <div class="footerEntry">
                <p>Server-Side Web Development Module - Inspired by Wanikani, Special Thanks to Dimitri - 2022</p>
            </div>
        </div>
        
    </div>

    @auth
    <script>
    const footerTutorial = document.getElementById('footerTutorial');

    //Just to Adjust Tutorial Setting Properly Everyload...
    if (localStorage.getItem('vocabAppTutorial') == 1){
        footerTutorial.textContent = "Enable Tutorial";
    } else {
        footerTutorial.textContent = "Disable Tutorial";
    }

    //Turn off Tutorial
    function footerTutorialToggle(){
        //For the Big Modal
        if (localStorage.getItem('vocabAppTutorial') == 0){
            //If Tutorial is ON, we turn it OFF
            localStorage.setItem('vocabAppTutorial', '1');
            localStorage.setItem('vocabAppTutorialFooterInfo', '1');
            footerTutorial.textContent = "Enable Tutorial";
        } else {
            //If Tutorial is OFF, we turn it ON
            localStorage.setItem('vocabAppTutorial', '0');
            localStorage.setItem('vocabAppTutorialFooterInfo', '0');
            footerTutorial.textContent = "Disable Tutorial";
        }
    }

    </script>
    @endauth
        
    </body>
</html>
