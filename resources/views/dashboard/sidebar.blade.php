<div class="col-sm-2 m-0 d-none d-md-block p-0 sidebar sidebar-offcanvas" id="sidebar-menu" role="navigation">
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
            <a href="{{  route('admin.index') }}"><i class='bx bx-home'></i><span>Dashboard</span></a>
            <a href="{{  route('collections.index') }}" id="categoria"><i class='bx bx-category'></i><span>Colecciones</span></a>
            <a href="{{  route('products.index') }}" id="products"><i class='bx bx-box'></i><span>Productos</span></a>
            <a href="{{  route('configurations.index') }}"><i class="bx bxs-cog"></i><span>Configuraciones</span></a>
        </div>
        <div class="configuracion">
            <form id="logoutForm" action="{{ route('admin.logout') }}" method="POST" class="mr-auto">
                @csrf
                <a href="#" onclick="$(this).parent().submit()"><i class='bx bx-log-out'></i><span>Salir</span></a>
            </form>
        </div>
    </nav>
</div>