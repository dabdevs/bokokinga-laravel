<div class="item">
    <div class="thumb">
        <div class="hover-content">
            <ul>
                <li><a href="single-product.html"><i class="fa fa-eye"></i></a></li>
                <li><a href="single-product.html"><i class="fa fa-star"></i></a></li>
                <li><a href="single-product.html"><i class="fa fa-shopping-cart"></i></a></li>
            </ul>
        </div>
        <img src="{{ env('S3_BASE_URL'). "/" .$product->image }}" alt="">
    </div>
    <div class="down-content">
        <h4>{{ $product->name }}</h4>
        <span>{{ $product->price }}</span>
        <ul class="stars d-none">
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
            <li><i class="fa fa-star"></i></li>
        </ul>
    </div>
</div>