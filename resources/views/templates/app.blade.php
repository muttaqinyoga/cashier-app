<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Kasir Kafe - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    @yield('datatable')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container" style="padding-right: 5rem; padding-left: 3rem;">
            <a class="navbar-brand" href="#">Mtq Kafe</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ url('admin/home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link categories" href="{{ url('admin/categories') }}">Category List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link foods" href="{{ url('admin/foods') }}">Food List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link orders" href="{{ url('admin/orders') }}">Orders</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Akun
                        </a>
                        <ul class="dropdown-menu col-md-4" aria-labelledby="navbarDropdownMenuLink">
                            <li class="dropdown-item">{{ Auth::user()->username }}</li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" id="logoutBtn">Sign out</a>
                                <form action="{{ url('auth/logout') }}" id="formLogout" method="post" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')


    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script>
        const csrf = document.getElementsByName("csrf-token")[0].getAttribute("content");
        const logoutBtn = document.querySelector("#logoutBtn");
        const formLogout = document.querySelector("#formLogout");

        logoutBtn.addEventListener("click", function() {
            formLogout.submit();
        });
    </script>
    @yield('custom-script')
</body>

</html>