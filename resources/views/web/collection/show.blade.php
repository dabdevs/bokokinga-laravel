@extends('web/layout')

@section('title', $collection->name)

@section('content')
    @if($collection->banner != null)
    <div class="page-heading" id="top" style="background-image: url('{{ $collection->banner }}')">
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
    @endif

    <section class="section products">
        <div class="container">
            <div class="row">
                @if($collection->banner == null)
                <div class="col-lg-12 py-4">
                    <div class="section-heading">
                        <h2>{{ $collection->name }}</h2>
                        <span>{{ $collection->description }}</span>
                    </div>
                </div>
                @endif

                @if($collection->products->count() > 0)
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h3>{{ $collection->products->count() }} resultado(s)</h2>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                @forelse ($collection->products as $product)
                    <div class="col-sm-6 col-lg-3">
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
                <div class="col-sm-12">
                    <p class="p-5 text-center mx-auto">Collección vacía.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
