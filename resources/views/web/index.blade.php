@extends('web/layout')

@section('title', 'Inicio')

@section('content')
    @include('web.includes.banner')
    @include('web.includes.collections')
@endsection

@section('js')
    <script>
        $(document).ready(function($) {
        $("#owl-latest-products").owlCarousel({
            autoPlay: 3000, //Set AutoPlay to 3 seconds
            items : 4,
            itemsDesktop : [1199,3],
            itemsDesktopSmall : [979,3]
        });
        });
    </script>
@endsection
