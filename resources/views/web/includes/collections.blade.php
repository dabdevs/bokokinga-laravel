@forelse ($collections as $collection)
    <section class="section" id="product">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2>Últimos en {{ $collection->name }}</h2>
                        <span>{{ $collection->description }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-item-carousel">
                        <div class="owl-product-item owl-carousel">
                            @foreach ($collection->latestProducts as $product)
                                <x-product
                                    :id="$product->id"
                                    :slug="$product->slug"
                                    :image="$product->primaryImage->path"
                                    :name="$product->name"
                                    :price="$product->price"
                                    :description="$product->description"
                                />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@empty
    
@endforelse
