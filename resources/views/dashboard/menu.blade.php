<div class="col-sm-2 m-0 p-0 sidebar sidebar-offcanvas" id="sidebar-menu" role="navigation">
    <nav class="nav-container m-0">
        <div class="row m-0 d-sm-none text-white">
            <center class="sidebar-profile"><img src="../public/img/user.png" alt="" class="mt-2 mx-center"></center>
            {{ Auth::user()->firstname }}
            {{ Auth::user()->lastname }}
        </div>
        <div class="publico m-0">
            <a href="./dashboard.php"><i class='bx bx-home'></i><span>Dashboard</span></a>
            <a href="./collections.php" id="categoria"><i class='bx bx-category'></i><span>Colecciones</span></a>
            <a href="configurations.php"><i class="bx bxs-cog"></i><span>Configuraciones</span></a>
        </div>
        <div class="configuracion">
            <a href="#" onclick="logout()"><i class='bx bx-log-out'></i><span>Salir</span></a>
        </div>
    </nav>
</div>