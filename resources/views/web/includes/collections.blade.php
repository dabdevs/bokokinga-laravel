@forelse ($collections as $collection)
    @if ($collection->latestProducts->count() >= 3)
        <section class="section products">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-heading">
                            <h2>Últimos en {{ $collection->name }}</h2>
                            <span>{{ $collection->description }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="latest-products">
                            @foreach ($collection->latestProducts as $product)
                                <div class="item p-2">
                                    <x-product
                                        :id="$product->id"
                                        :slug="$product->slug"
                                        :image="$product->primaryImage->path"
                                        :name="$product->name"
                                        :price="$product->price"
                                        :description="$product->description"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@empty
@endforelse
