@extends('web/layout')

@section('title', 'Carrito')

@section('content')
    <section class="container py-5 my-5">
        <x-cart />
    </section>
@endsection

@section('js')
    <script type="text/javascript">
        var currentTotal = document.getElementById('subtotal').value;

        function eliminar(id) {
            $.ajax({
                url: '{{ route('web.remove_from_cart') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    document.querySelector('.cart-count').innerText = response.cartCount
                    document.getElementById('subtotal').innerText = 'Subtotal: $'+ parseFloat(response.subtotal).toFixed(2);
                    document.getElementById('row-' + id).remove()
                    toast(response.success);
                }
            });
        }

        function remove(id) {
            Swal.fire({
                    title: "Atención",
                    text: "Seguro quieres eliminar el producto del carrito?",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#ccc',
                    confirmButtonText: 'Confirmar!'
                })
                .then((result) => {
                    if (result.value) {
                        eliminar(id);
                    }
                });
        }

        function update(id, productQuantity, price) {
            quantity = document.getElementById('quantity-' + id);
            totalPricePerProduct = document.getElementById('priceQty-'+id).textContent.replace('$', '')
            
            if (parseInt(quantity.value) > productQuantity) {
                quantity.value = parseInt(totalPricePerProduct / price)
                toast('Solo hay '+productQuantity+' disponibles', 'danger')
                return;
            }

            if (parseInt(quantity.value) == 0) {
                quantity.value = parseInt(totalPricePerProduct / price)
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
                    toast(response.success);
                    document.querySelector('.cart-count').innerText = response.cartCount
                    document.getElementById('subtotal').innerText = 'Subtotal: $'+ parseFloat(response.subtotal).toFixed(2);
                    document.getElementById('priceQty-' + id).innerText = '$'+ (quantity.value * parseFloat(price)).toFixed(2)
                }
            });
        }
    </script>
@endsection
