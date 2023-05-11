@extends('web/layout')

@section('title', 'Carrito')

@section('content')
    <section class="container py-5 my-5">
        {{ view('web.cart.includes.items') }}
    </section>
@endsection

@section('js')
    <script type="text/javascript">
        var currentTotal = document.getElementById('totalPrice').value;

        function eliminar(id) {
            $.ajax({
                url: '{{ route('web.remove_from_cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    $('section').html(response.html)
                    toast(response.success);
                    $('#cart-count').text(response.cartQuantity)
                }
            });
        }

        function remove(id) {
            Swal.fire({
                    title: "Atención",
                    text: "Seguro quieres eliminar el producto?",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirmar!'
                })
                .then((result) => {
                    if (result.value) {
                        eliminar(id);
                    }
                });
        }

        function update(id, productQuantity) {
            quantity = document.getElementById('quantity-' + id);
            
            if (quantity.value > productQuantity) {
                quantity.value = quantity.defaultValue;
                toast('Solo hay '+productQuantity+' disponibles', 'danger')
                return;
            }

            if (quantity.value == 0) {
                eliminar(id);
                return;
            }
            
            $.ajax({
                url: '{{ route('web.update_cart') }}',
                method: "PATCH",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    quantity: quantity.value
                },
                success: function(response) {
                    $('section').html(response.html)
                    toast(response.success);
                    document.querySelector('#cart-count').innerText = response.cartQuantity
                    document.getElementById('subtotal').innerText = 'Subtotal: $'+(response.subtotal).toFixed(2);
                }
            });
        }
    </script>
@endsection
