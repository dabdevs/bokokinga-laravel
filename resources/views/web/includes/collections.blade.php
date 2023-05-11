@forelse ($collections as $collection)
    @if ($collection->latestProducts->count() >= 3)
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
                {{-- <div class="row">
                    <div class="col-lg-12">
                        <div class="men-item-carousel">
                            <div class="owl-men-item owl-carousel owl-loaded owl-drag">
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
                </div> --}}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="men-item-carousel">
                            <div class="owl-men-item owl-carousel owl-loaded owl-drag">
                                <div class="owl-stage-outer">
                                    <div class="owl-stage" style="width: 3800px; transform: translate3d(-1140px, 0px, 0px); transition: all 0s ease 0s;">
                                        @foreach ($collection->latestProducts as $product)
                                            <div class="owl-item" style="width: 350px; margin-right: 30px;">
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
                                <div class="owl-nav">
                                    <button type="button" role="presentation" class="owl-prev">
                                        <span aria-label="Previous">‹</span>
                                    </button>
                                    <button type="button" role="presentation"
                                        class="owl-next">
                                        <span aria-label="Next">›</span>
                                    </button>
                                </div>
                                <div class="owl-dots">
                                    <button role="button" class="owl-dot active">
                                        <span></span>
                                    </button>
                                    <button role="button" class="owl-dot"><span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@empty
@endforelse
