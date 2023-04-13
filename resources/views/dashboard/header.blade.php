<header class="navbar navbar-expand-lg header container-fluid">
    <h2 class="text-white">{{ config('app.name') }}</h2>

    <input type="search" class="searchbar form-control icono-placeholder mx-auto" placeholder="Buscar">

    <img src="../public/img/user.png" alt="" class="d-none d-sm-block">
    <i class='bx bx-bell d-none d-sm-block'></i>


    <a class="d-md-none ml-auto p-2 sidebar-menu-toggler" onclick="toggleMenu()">
        <i class="bx bx-menu-alt-right" aria-hidden="true"></i>
    </a>


    @if (Auth::user()) 
        <div class="btn-group dropleft">
            <a class="btn btn-danger d-none d-md-block dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                {{ Auth::user()->firstname }}
                {{ Auth::user()->lastname }}
            </a>
            <div class="dropdown-menu">
                <!-- Dropdown menu links -->
                <a class="dropdown-item" href="#">Cuenta</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="logout()">Logout</a>
            </div>
        </div>
    @endif
</header>