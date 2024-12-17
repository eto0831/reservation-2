<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <button class="hamburger-menu" popovertarget="Modal" popovertargetaction="show">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </button>
                <div id="Modal" popover="auto">
                    <div class="inner-modal">
                        <div class="button__wrapper">
                            <button class="Close" popovertarget="Modal" popovertargetaction="hidden">×</button>
                        </div>
                        <nav>
                            <ul class="header-nav">
                                @if (Auth::check())
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/">Home</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/mypage">Mypage</a>
                                </li>
                                @if (Auth::user()->hasRole('admin'))
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/admin/dashboard">Admin</a>
                                </li>
                                @endif
                                @if (Auth::user()->hasRole('owner'))
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/owner/dashboard">Owner</a>
                                </li>
                                @endif
                                <li class="header-nav__item">
                                    <form class="form" action="/logout" method="post">
                                        @csrf
                                        <button class="header-nav__button">Logout</button>
                                    </form>
                                </li>
                                @else
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/">Home</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/register">Registration</a>
                                </li>
                                <li class="header-nav__item">
                                    <a class="header-nav__link" href="/login">Login</a>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                <h1 class="header__logo">Rese</h1>
            </div>
        </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>