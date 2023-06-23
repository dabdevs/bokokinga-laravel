
<!-- ***** Preloader Start ***** -->
<div id="preloader">
    <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- ***** Preloader End ***** -->

<!-- ***** Search modal ***** -->
<div class="modal" id="searchModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal header -->
      <div class="modal-header">
        <h4 class="modal-title">Buscar producto</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <form action="{{ route('web.search') }}">
                <label for="query">Buscar por nombre, descripción, etc.</label>
                <input type="text" name="query" class="form-control" id="query">
            </form>
      </div>
    </div>
  </div>
</div>

<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="/" class="logo">
                        <img height="70" src="{{ asset('front/img/logo.png') }}">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li><a href="#" id="search"><i class="fa fa-search"></i></a></li>
                        <li class="submenu">
                            <a href="javascript:;">Colecciones</a>
                            <ul>
                                @forelse ($collections as $collection)
                                    <li><a href="{{ route('web.collection.show', Str::slug($collection->name)) }}"><?= $collection->name ?></a></li>
                                @empty
                                @endforelse
                            </ul>
                        </li>
                        <li class="scroll-to-section">
                            <a href="{{ route('web.cart') }}">
                                <i class="fa fa-shopping-cart fa-2x"></i>
                                <p class="cart-count">{{ session('cartCount') ?? '' }}</p>
                            </a>
                        </li>
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ***** Header Area End ***** -->