@extends('web/layout')

@section('content')
    <section class="container py-5 my-5">
        {{ view('web.cart.includes.products') }}
    </section>
@endsection


@section('js')
    <script type="text/javascript">

        function eliminar(id) {
            $.ajax({
                url: '{{ route('web.remove_from_cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id
                },
                success: function (response) {
                    $('section').html(response.html)
                    toast(response.success);
                    $('#cart-count').text(response.cartQuantity)
                }
            });
        }

        function remove(id) {
            if(confirm("Seguro quieres borrar el producto?")) {
                eliminar(id);
            }
        }

        function update(id) {
            quantity = document.getElementById('quantity-'+id).value;

            if (quantity == 0) {
                eliminar(id);
                return;
            }

            $.ajax({
                url: '{{ route('web.update_cart') }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id, 
                    quantity: quantity
                },
                success: function (response) {
                    $('section').html(response.html)
                    toast(response.success);
                    $('#cart-count').text(response.cartQuantity)
                }
            });
        }
    
    </script>
@endsection

