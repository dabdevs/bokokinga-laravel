<div role="button" class="item w-100">
    <div class="thumb product-card">
        <div class="hover-content">
            <ul>
                <li onclick="quickView({{ $id }})"><a style="cursor: pointer" href="#"><i class="fa fa-eye"></i></a></li>
                <li class="d-none"><a href="single-product.html"><i class="fa fa-star"></i></a></li>
                <li><a style="cursor: pointer" onclick="addToCart('{{ $id }}')"><i class="fa fa-shopping-cart"></i></a></li>
            </ul>
        </div>
        <img data-url="{{ route('web.product.show', Str::lower($slug)) }}" class="w-100" src="{{ $image }}" alt="">
    </div>
    <div class="down-content">
        <h4>
            <a class="text-dark" href="{{ route('web.product.show', Str::lower($slug)) }}">{{ $name }}</a>
        </h4>
        <span>${{ number_format($price, 2, '.', ',') }}</span>
        <ul class="stars d-none">
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
        </ul>
    </div>
</div>