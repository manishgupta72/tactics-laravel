<section class="section section-funnel border-0 m-0 p-0">
    <div class="owl-carousel-wrapper" style="height: 991px;">
        <div class="owl-carousel dots-inside dots-horizontal-center custom-dots-style-1 show-dots-hover show-dots-xs nav-style-1 nav-inside nav-inside-plus nav-dark nav-lg nav-font-size-lg show-nav-hover mb-0"
            data-plugin-options="{'responsive': {'0': {'items': 1, 'dots': true, 'nav': false}, '479': {'items': 1, 'dots': true}, '768': {'items': 1, 'dots': true}, '979': {'items': 1}, '1199': {'items': 1}}, 'loop': false, 'autoHeight': false, 'margin': 0, 'dots': true, 'dotsVerticalOffset': '-250px', 'nav': false, 'animateIn': 'fadeIn', 'animateOut': 'fadeOut', 'mouseDrag': false, 'touchDrag': false, 'pullDrag': false, 'autoplay': true, 'autoplayTimeout': 7000, 'autoplayHoverPause': true, 'rewind': true}">

            @if($sliders->isNotEmpty())
                @foreach($sliders as $slider)
                <!-- Dynamic Carousel Slide -->
                <div class="position-relative overflow-hidden pb-5" 
                    data-dynamic-height="['991px','991px','991px','650px','650px']" style="height: 991px;">
                    <div class="background-image-wrapper position-absolute top-0 left-0 right-0 bottom-0"
                        data-appear-animation="kenBurnsToLeft" data-appear-animation-duration="30s"
                        data-plugin-options="{'minWindowWidth': 0}" data-carousel-onchange-show
                        style="background-image: url('{{ asset("assets/media_files/" . $slider->mf_image) }}'); background-size: cover; background-position: center;">
                    </div>
                    <div class="container position-relative z-index-3 pb-5 h-100">
                        <div class="row align-items-center pb-5 h-100">
                            <div class="col-md-10 col-lg-6 text-center text-md-end pb-5 ms-auto">
                                <h1 class="text-color-dark font-weight-extra-bold text-10 line-height-2 mb-3 appear-animation"
                                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500"
                                    data-plugin-options="{'minWindowWidth': 0}">
                                    {{ $slider->mf_title }}
                                </h1>
                                <a target="_blank" href="{{ $slider->mf_url ?? '#' }}"
                                    class="btn btn-primary btn-modern font-weight-semibold text-3 btn-py-3 px-5 appear-animation"
                                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="1000"
                                    data-plugin-options="{'minWindowWidth': 0}">GET STARTED</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center">
                    <p>No sliders available</p>
                </div>
            @endif

        </div>
    </div>
    <div class="section-funnel-layer-bottom d-none d-xl-block z-index-1">
        <div class="section-funnel-layer bg-light"></div>
        <div class="section-funnel-layer bg-light"></div>
    </div>
</section>
