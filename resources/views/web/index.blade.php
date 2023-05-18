@extends('web/layout')

@section('title', 'Inicio')

@section('content')
    @include('web.includes.banner')
    @include('web.includes.collections')
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.latest-products').slick({
                dots: true,
                infinite: true,
                slidesToShow: 1,
                centerMode: true,
                variableWidth: true,
                autoplay: true,
                autoplaySpeed: 2000,
            });
        });
    </script>
@endsection
