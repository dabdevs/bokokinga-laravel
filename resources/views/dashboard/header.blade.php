<header class="navbar navbar-expand-lg header container-fluid d-none">
    <h2 class="text-white">{{ config('app.name') }}</h2>

    <input type="search" class="d-none searchbar form-control icono-placeholder mx-auto" placeholder="Buscar">

    <i class='bx bx-bell d-none d-sm-block'></i>

    <a href="" class="nav-link ml-auto">
        Alain
        <img src="../public/img/user.png" alt="" class="">
    </a>

    <a class="d-md-none ml-auto p-2 sidebar-menu-toggler" onclick="toggleMenu()">
        <i class="bx bx-menu-alt-right" aria-hidden="true"></i>
    </a>

    @auth 
        <img src="../public/img/user.png" alt="" class="d-none d-sm-block">

        <div class="btn-group dropleft d-none">
            <a class="d-none d-md-block dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->firstname }}
                {{ Auth::user()->lasttname }}
            </a>
            <div class="dropdown-menu">
                <!-- Dropdown menu links -->
                <a class="dropdown-item" href="#">Cuenta</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="logout()">Logout</a>
            </div>
        </div>
    @endauth
</header>