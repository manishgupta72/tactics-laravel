@include('front.IncludesFiles.head')
@include('front.IncludesFiles.menu')
@include('front.IncludesFiles.page-title', ['title' => 'Payroll Outsourcing'])
<div role="main" class="main">
    <section>
        <div class="container my-5">
            <div class="row">
                <div class="col-lg-3 border-all py-4 position-relative">
                    <aside class="sidebar" id="sidebar" data-plugin-sticky
                        data-plugin-options="{'minWidth': 991, 'containerSelector': '.container', 'padding': {'top': 110}}">

                        <h3 class="text-color-primary text-capitalize font-weight-bold text-5 m-0 mb-3">Our Services
                        </h3>
                        <ul class="nav nav-list flex-column mb-0 p-relative right-9">
                            <li class="nav-item"><a
                                    class="nav-link font-weight-bold text-dark text-3 border-0 my-1 p-relative"
                                    href="#">Contract Staffing</a></li>
                            <li class="nav-item"><a
                                    class="nav-link font-weight-bold text-dark text-3 border-0 my-1 p-relative"
                                    href="#">Permanent Staffing</a></li>
                            <li class="nav-item"><a
                                    class="nav-link font-weight-bold text-dark text-3 border-0 my-1 p-relative"
                                    href="#">Payroll Outsourcing</a></li>
                        </ul>
                        <hr>
                        <h3 class="text-color-quaternary font-weight-bold text-capitalize text-5 mt-2 mb-2">Contact Info
                        </h3>
                        <p class="pb-3">Lorem ipsum dolor sit amet, consectetur adipiscing.</p>
                        <div class="feature-box feature-box-style-2 mb-4">
                            <div class="feature-box-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="feature-box-info">
                                <h5 class="m-0 font-weight-bold">Direct Phone Number</h5>
                                <p class="m-0"><a href="tel:+{{get_settings('general_settings', 'support_number')}}">{{get_settings('general_settings', 'support_number')}}</a></p>
                            </div>
                        </div>
                        <div class="feature-box feature-box-style-2 mb-4">
                            <div class="feature-box-icon">
                                <i class="far fa-envelope"></i>
                            </div>
                            <div class="feature-box-info">
                                <h5 class="m-0 font-weight-bold">E-mail Address</h5>
                                <p class="m-0"><a href="mailto:{{get_settings('general_settings', 'support_email')}}">{{get_settings('general_settings', 'support_email')}}</a></p>
                            </div>
                        </div>
                        <div class="feature-box feature-box-style-2 mb-4">
                            <div class="feature-box-icon">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="feature-box-info">
                                <h5 class="m-0 font-weight-bold">Working Days/Hours</h5>
                                <p class="m-0">Mon - Sun / 9:00AM - 8:00PM</p>
                            </div>
                        </div>
                    </aside>
                </div>


                <div class="col-lg-9">
                    <div class="row">
                        <h3 class="text-color-quaternary font-weight-bold text-capitalize mb-2">Contract Staffing</h3>
                        <div class="col-lg-7 pb-4 pb-lg-0">
                            <p>Contract staffing offers organizations a flexible workforce to address project-specific
                                needs, seasonal workloads, or specialized skill requirements.
                                At Tactics Management Services, we ensure seamless execution of the following processes:
                            </p>
                        </div>
                        <div class="col-lg-5 pb-4 pb-lg-0">
                            <div class="card border-0 border-radius-0 box-shadow-1 appear-animation animated fadeInUpShorter appear-animation-visible"
                                data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500"
                                style="animation-delay: 500ms;">
                                <div class="card-body p-3 z-index-1">
                                    <img class="card-img-top border-radius-0"
                                        src="{{ $aboutMediaFiles->isNotEmpty() ? asset('assets/media_files/' . $aboutMediaFiles->first()->mf_image) : 'https://placehold.co/372x274' }}" alt="Card Image">
                                </div>
                            </div>
                        </div>
                        <h4 class="text-color-quaternary font-weight-bold text-capitalize mb-2">Processes For Contract
                            Staffing</h4>
                        <div class="process process-vertical pt-4">
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">01</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Employee Transfer/Sourcing</h4>
                                    <p class="mb-0">We manage the seamless transfer and sourcing of employees to meet
                                        your project-specific requirements.</p>
                                </div>
                            </div>
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">02</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Selection & Finalization</h4>
                                    <p class="mb-0">Our team handles the entire selection process, ensuring the best
                                        candidates are finalized for your needs.</p>
                                </div>
                            </div>
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="600">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">03</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Joining Formalities</h4>
                                    <p class="mb-0">We take care of all joining formalities, facilitating a smooth
                                        onboarding process for contract staff.</p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
    </section>
</div>

@include('front.IncludesFiles.footer')