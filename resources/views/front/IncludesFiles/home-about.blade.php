<section class="more-about lazyload mb-0" style="background-image: url('{{ 
        $aboutMediaFiles->isNotEmpty() && $aboutMediaFiles->firstWhere('mf_type', 'HomeAbout')
    ? asset('assets/media_files/' . $aboutMediaFiles->firstWhere('mf_type', 'HomeAbout')->mf_image)
    : ($aboutMediaFiles->isNotEmpty() ? asset('assets/media_files/' . $aboutMediaFiles->first()->mf_image)
        : 'https://placehold.co/960x670') 
    }}')">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-6 p-relative overflow-hidden col-cuttin-more-about"></div>
            <div class="col-xs-12 col-lg-6 p-relative py-5 bg-color-light z-index-1 ps-lg-5 ps-xl-0">
                <p class="text-uppercase mb-0 appear-animation" data-appear-animation="fadeInUpShorter"
                    data-appear-animation-delay="100">Who We Are</p>
                @if($aboutMediaFiles->isNotEmpty())
                    <h3 class="text-color-quaternary font-weight-bold text-capitalize mb-2 appear-animation"
                        data-appear-animation="fadeInUpShorter" data-appear-animation-delay="200">
                        {{ $aboutMediaFiles->firstWhere('mf_type', 'HomeAbout')->mf_title ?? $aboutMediaFiles->first()->mf_title }}
                    </h3>
                    <!-- <p class="font-weight-semibold appear-animation" data-appear-animation="fadeInUpShorter"
                                    data-appear-animation-delay="300">
                                    {!! $aboutMediaFiles->first()->mf_url !!}
                                </p> -->
                @else
                    <p>No content available.</p>
                @endif
                <p class="font-weight-semibold appear-animation" data-appear-animation="fadeInUpShorter"
                    data-appear-animation-delay="300">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ut
                    tellus ante. Nam suscipit urna risus, fermentum commodo ipsum porta id.</p>
                <p class="mb-4 appear-animation" data-appear-animation="fadeInUpShorter"
                    data-appear-animation-delay="400">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed
                    imperdiet libero id nisi euismod, sed porta est consectetur. Vestibulum auctor felis eget orci
                    semper vestibulum. Pellentesque ultricies nibh gravida, accumsan libero luctus, molestie nunc.</p>
                <div class="row counters mb-4 flex-nowrap flex-sm-wrap">
                    <div class="col-xs-4 col-sm-4 col-lg-4 mb-0 d-flex">
                        <div class="counter counter-primary appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="500">
                            <strong class="number-counter text-10" data-to="35" data-append="+">0</strong>
                            <label
                                class="number-counter-text text-4 text-color-primary font-weight-semibold negative-ls-1">Business
                                Year</label>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-lg-4 mb-0 d-flex">
                        <div class="counter counter-primary appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="750">
                            <strong class="number-counter text-10" data-to="50" data-append="+">0</strong>
                            <label
                                class="number-counter-text text-4 text-color-primary font-weight-semibold negative-ls-1">Specialist
                                Doctors</label>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-lg-4 mb-0 d-flex justify-content-center">
                        <div class="counter counter-primary appear-animation" data-appear-animation="fadeInRightShorter"
                            data-appear-animation-delay="1000">
                            <strong class="number-counter text-10" data-to="200" data-append="+">0</strong>
                            <label
                                class="number-counter-text text-4 text-color-primary font-weight-semibold negative-ls-1">Modern
                                Rooms</label>
                        </div>
                    </div>
                </div>
                <p class="mb-4 appear-animation" data-appear-animation="fadeInUpShorter"
                    data-appear-animation-delay="100">Pellentesque ultricies nibh gravida, accumsan libero luctus,
                    molestie nunc. In nibh ipsum, blandit id faucibus ac.</p>

            </div>
        </div>
    </div>
</section>