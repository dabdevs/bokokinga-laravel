<!-- ***** Footer Start ***** -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="first-item">
                    <div class="logo d-none">
                        <img src="{{ asset('front/img/white-logo.png') }}" alt="hexashop ecommerce templatemo">
                    </div>
                    <h4>Contacto</h4>
                    <ul>
                        <li><a href="#">Dirección, Buenos Aires</a></li>
                        <li><a href="#">bokokinga@gmail.com</a></li>
                        <li><a href="#">010-020-0340</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3">
                <h4>Colleciones</h4>
                <ul>
                    @foreach ($collections as $collection)
                        <li><a href="{{ route('web.collection.show', [$collection->id, Str::slug($collection->name)]) }}">{{ $collection->name }}</a></li>
                    @endforeach 
                </ul>
            </div>
            <div class="col-lg-3">
                <h4>Links</h4>
                <ul>
                    <li><a href="/">Inicio</a></li>
                    <li><a href="#">Nosotros</a></li>
                    <li><a href="#">Contactos</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h4>Información útil</h4>
                <ul>
                    <li><a href="#">Políticas</a></li>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Shipping</a></li>
                </ul>
            </div>
            <div class="col-lg-12">
                <div class="under-footer">
                    <p>Copyright © 2023 Bokokinga Co., Ltd. Todos derechos reservados.</p>
                    <ul>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#"><i class="fa fa-behance"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="{{ asset('front/js/jquery-2.1.0.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('front/js/popper.js') }}"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>

<!-- Plugins -->
<script src="{{ asset('front/js/owl-carousel.js') }}"></script>
<script src="{{ asset('front/js/accordions.js') }}"></script>
<script src="{{ asset('front/js/datepicker.js') }}"></script>
<script src="{{ asset('front/js/scrollreveal.min.js') }}"></script>
<script src="{{ asset('front/js/waypoints.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('front/js/imgfix.min.js') }}"></script>
<script src="{{ asset('front/js/lightbox.js') }}"></script>
<script src="{{ asset('front/js/isotope.js') }}"></script>
<script src="{{ asset('front/js/toastify-js.js') }}"></script>
<script src="{{ asset('dashboard/js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('front/js/splide.min.js') }}"></script>
<script src="{{ asset('front/slick/slick.min.js') }}"></script>

<!-- Global Init -->
<script src="{{ asset('front/js/custom.js') }}"></script>

<script>
    const search = document.getElementById('search')
    search.addEventListener("click", function(){
        $('#searchModal').modal('show')
        $('#query').focus()
    })

    function toast(message, type='success') {
        if (type == 'success') {
            $color = '#198754';
        }
        else if (type == 'danger') {
            $color = '#FF5252';
        }
        else if (type == 'warning') {
            $color = '#FFC107';
        }
        else if (type == 'info') {
            $color = '#2196F3';
        }
        
        Toastify({
            text: message,
            style: {
                background: $color
            }
        }).showToast();
    }
</script>