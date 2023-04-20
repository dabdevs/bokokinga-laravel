@extends('web/layout')

@section('content')
    <section class="section py-5" id="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>{{ $results->count() }} resultados</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                @forelse ($results as $product)
                    <div class="col-lg-4">
                        <x-product
                            :image="$product->image"
                            :name="$product->name"
                            :price="$product->price"
                            :description="$product->description"
                        />
                    </div>
                @empty 
                @endforelse
            </div>
            <div class="row">
                <div class="pagination mx-auto">
                    <ul>
                        <li>
                            <a href="#">1</a>
                        </li>
                        <li class="active">
                            <a href="#">2</a>
                        </li>
                        <li>
                            <a href="#">3</a>
                        </li>
                        <li>
                            <a href="#">4</a>
                        </li>
                        <li>
                            <a href="#">&gt;</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
