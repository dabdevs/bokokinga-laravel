<div class="col-sm-2 m-0 p-0 sidebar sidebar-offcanvas" id="sidebar-menu" role="navigation">
    <nav class="nav-container m-0">
        @auth
            <div class="row m-0 text-white">
                <center class="sidebar-profile">
                    <img height="100" src="{{ asset('dashboard/img/user.png') }}" alt="" class="mt-2 mx-center">
                </center>
                {{ Auth::user()->firstname }}
                {{ Auth::user()->lastname }}
            </div>
        @endauth
        <div class="publico m-0">
            <a href="./dashboard.php"><i class='bx bx-home'></i><span>Dashboard</span></a>
            <a href="./collections.php" id="categoria"><i class='bx bx-category'></i><span>Colecciones</span></a>
            <a href="configurations.php"><i class="bx bxs-cog"></i><span>Configuraciones</span></a>
        </div>
        <div class="configuracion">
            <form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" class="mr-auto">
                @csrf
                <a href="#" onclick="$(this).parent().submit()"><i class='bx bx-log-out'></i><span>Salir</span></a>
            </form>
        </div>
    </nav>
</div>