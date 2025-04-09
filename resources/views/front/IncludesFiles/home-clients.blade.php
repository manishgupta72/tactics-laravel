<section class="insurance-providers pt-5 pb-lg-5">
    <div class="container my-lg-3 py-lg-2">
        <div class="row">
            <div class="col">
                <p class="text-uppercase mb-0 d-block text-center text-uppercase appear-animation"
                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="300">MAJOR BRANDS</p>
                <h3 class="text-color-quaternary mb-2 d-block text-center font-weight-bold text-capitalize appear-animation"
                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="400">Our Clients</h3>
                <p class="mb-5 d-block text-center appear-animation" data-appear-animation="fadeInUpShorter"
                    data-appear-animation-delay="500">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet libero id nisi euismod.
                </p>
            </div>
        </div>

        <div class="owl-carousel owl-theme" data-plugin-options="{'items': 4, 'autoplay': true, 'autoplayTimeout': 3000}">
            @if($clientMediaFiles->isNotEmpty())
                @foreach($clientMediaFiles as $client)
                    <div class="item">
                        <img class="img-fluid p-1" src="{{ asset("assets/media_files/" .$client->mf_image) }}" alt="{{ $client->mf_title }}" title="{{ $client->mf_title }}">
                    </div>
                @endforeach
            @else
                <div class="item">
                    <p class="text-center">No clients available.</p>
                </div>
            @endif
        </div>
    </div>
</section>
