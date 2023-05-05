@extends('web/layout')

@section('content')
    <div class="page-heading" id="top" style="background-image: url('{{ env('S3_BASE_URL'). "/" .$collection->image }}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="inner-content">
                        <h2>{{ $collection->name }}</h2>
                        <span>{{ $collection->description }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section" id="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>{{ $collection->name }}</h2>
                        <span>{{ $collection->description }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @forelse ($collection->products as $product)
                    <div class="col-lg-4">
                        <x-product
                            :id="$product->id"
                            :slug="$product->slug"
                            :image="$product->primaryImage->path"
                            :name="$product->name"
                            :price="$product->price"
                            :description="$product->description"
                        />
                    </div>
                @empty 
                    <p class="p-5 text-center mx-auto">Collección vacía.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
