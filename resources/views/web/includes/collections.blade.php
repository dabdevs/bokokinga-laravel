@forelse ($collections as $collection)
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
                            @foreach (App\Models\Product::where('collection_id', $collection->id)->orderBy('id', 'desc')->limit(10)->get() as $product)
                                {{ view('web.includes.product_card', ['product' => $product]) }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@empty
    
@endforelse
