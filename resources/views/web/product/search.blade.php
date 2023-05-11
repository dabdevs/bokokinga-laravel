@extends('web/layout')

@section('title', 'Búsqueda')

@section('content')
    <section class="section py-5" id="products">
        <div class="container">
            <div class="row">
                @if($products->count() > 0)
                <div class="col-lg-12">
                    <div class="section-heading">
                        <h2>{{ $products->count() }} resultado(s)</h2>
                        <p>Búsqueda: {{ request('query') }}</p>
                    </div>
                </div>
                @endif 

                @foreach ($products as $product)
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
                @endforeach
            </div>

            @if ($products->hasPages())
            <div class="pagination mx-auto">
                <ul>
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <li class="disabled"><span><i class="fa fa-chevron-left"></i></span></li>
                    @else
                        <li><a href="{{ $products->previousPageUrl() }}" rel="prev"><i class="fa fa-chevron-left"></i></a></li>
                    @endif
                    
                    
                    
                    {{ "Página " . $products->currentPage() . "  de  " . $products->lastPage() }}
                
                    
                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <li><a href="{{ $products->nextPageUrl() }}" rel="next"><i class="fa fa-chevron-right"></i></a></li>
                    @else
                        <li class="disabled"><i class="fa fa-chevron-right"></i></li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </section>
@endsection
