<div class="item">
    <div class="thumb">
        <div class="hover-content">
            <ul>
                <li><a href="{{ route('web.product.show', [$id, $slug]) }}"><i class="fa fa-eye"></i></a></li>
                <li class="d-none"><a href="single-product.html"><i class="fa fa-star"></i></a></li>
                <li><a style="cursor: pointer" onclick="addToCart('{{ $id }}')"><i class="fa fa-shopping-cart"></i></a></li>
            </ul>
        </div>
        <img class="w-100" src="{{ env('S3_BASE_URL'). "/" .$image }}" alt="">
    </div>
    <div class="down-content">
        <h4>{{ $name }}</h4>
        <span>{{ number_format($price, 2, '.', ','); }}</span>
        <ul class="stars d-none">
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
        </ul>
    </div>
</div>