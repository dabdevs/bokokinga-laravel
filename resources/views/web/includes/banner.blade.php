
<!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="left-content">
                        <div class="thumb">
                            <div class="inner-content">
                                <h4>{{ config('app.name') }}</h4>
                                <span>Uñas, decoración, etc</span>
                                <div class="main-border-button">
                                    <a href="#">Shop Now!</a>
                                </div>
                            </div>
                            <img src="{{ asset('front/img/left-banner-image.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-12">
                    <div class="right-content">
                        <div class="row">
                            @php
                                $count = 0;
                            @endphp
                            @forelse ($collections as $collection)
                                @if($collection->image != null && $count < 4)
                                    <div class="col-sm-6">
                                        <div class="right-first-image">
                                            <div class="thumb">
                                                <div class="inner-content">
                                                    <h4><?= $collection->name ?></h4>
                                                    <span><?= $collection->description ?></span>
                                                </div>
                                                <div class="hover-content">
                                                    <div class="inner">
                                                        <h4><?= $collection->name ?></h4>
                                                        <p><?= $collection->description ?></p>
                                                        <div class="main-border-button">
                                                            <a href="{{ route('web.collection.show', $collection->slug) }}">Ver más</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <img height="283px" src="{{ $collection->image == null ? asset('front/img/baner-right-image-01') : $collection->image }}">
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $count++;
                                    @endphp
                                @endif
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- ***** Main Banner Area End ***** -->