@forelse ($collections as $collection)
    @if($loop->index <= 2 and $collection->latestProducts->count() >= 3)
        <section class="section" id="men">
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
                        <div class="men-item-carousel">
                            <div class="owl-men-item owl-carousel">
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
    @endif
@empty
    
@endforelse
