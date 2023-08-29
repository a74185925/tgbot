<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <title>
            @hasSection ('title')
                @yield('title')
            @else
                Cabinet
            @endif
        </title>
    
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/dashboard.css" rel="stylesheet">

        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                font-size: 3.5rem;
                }
            }

            .b-example-divider {
                height: 3rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }
        </style>
    
    </head>
    <body>
        
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/cabinet">Cabinet</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-nav me-0 px-3 fs-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    this.closest('form').submit(); " role="button">
                            <i class="fas fa-sign-out-alt"></i>
            
                            {{ __('Log Out') }}
                        </a>
                    </div>
                </form>
            </div>
        </header>
        
        <div class="container-fluid">
            <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/cabinet">
                                <span data-feather="home" class="align-text-bottom"></span>
                                Кабинет
                            </a>
                        </li>
                        @if ($user->is_admin || $user->is_manager)
                        <li class="nav-item">
                            <a class="nav-link" href="/orders">
                                <span data-feather="file" class="align-text-bottom"></span>
                                Заказы
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/products">
                                <span data-feather="shopping-cart" class="align-text-bottom"></span>
                                Товары
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/castomers">
                                <span data-feather="users" class="align-text-bottom"></span>
                                Пользователи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="payments">
                                <span data-feather="dollar-sign" class="align-text-bottom"></span>
                                Платежи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/wokers">
                                <span data-feather="box" class="align-text-bottom"></span>
                                Сотрудники
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cities">
                                <span data-feather="box" class="align-text-bottom"></span>
                                Города
                            </a>
                        </li>                     
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="/loots">
                                <span data-feather="box" class="align-text-bottom"></span>
                                Посылки
                            </a>
                        </li>

                        
                    </ul>
                    
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted text-uppercase">
                        <span>Действия</span>
                        <a class="link-secondary" href="#">
                        <span data-feather="plus-circle" class="align-text-bottom"></span>
                        </a>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="/loot-add">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Добавить посылку
                            </a>
                        </li>
                        @if ($user->is_admin || $user->is_manager)
                        <li class="nav-item">
                            <a class="nav-link" href="/product-add">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Добавить товарную позицию
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="/woker-add">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Добавить сотрудника
                            </a>
                        </li> 
                        {{-- bot_messages --}}
                        <li class="nav-item">
                            <a class="nav-link" href="/bot-messages">
                                <span data-feather="file-text" class="align-text-bottom"></span>
                                Настройка сообщений
                            </a>
                        </li> 

                        @endif

                    </ul>
                </div>
            </nav>
        
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        @hasSection ('header')
                            @yield('header')
                        @else
                            Cabinet
                        @endif
                    </h1>
                    @yield('headbuttons')
                </div>

                <div class="main_content">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
            
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- CONTENT -->
                    @yield('content')
                    <!-- /CONTENT -->
                </div>
            </main>
            </div>
        </div>
        
        <script src="/js/jquery-3.6.0.min.js"></script>
        <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
        <script src="/js/dashboard.js"></script>
        @yield('js')
        <script>@yield('jscode')</script>
</body>
</html>