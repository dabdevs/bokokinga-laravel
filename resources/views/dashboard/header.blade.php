<header class="navbar navbar-expand-lg header container-fluid d-md-none">
    <h2 class="text-white">{{ config('app.name') }}</h2>
    
    @auth
        <i class='bx bx-bell d-none d-sm-block'></i>
        <a class="ml-auto p-2" onclick="toggleMenu()">
            <i class="bx bx-menu-alt-right" aria-hidden="true"></i>
        </a>
    @endauth
</header>