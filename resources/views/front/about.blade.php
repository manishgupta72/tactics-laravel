@include('front.IncludesFiles.head')
@include('front.IncludesFiles.menu')
@include('front.IncludesFiles.page-title', ['title' => 'About'])
<div role="main" class="main">
    <section>
        <div class="container my-5">
            <div class="row">
                <h3 class="text-color-primary font-weight-bold text-capitalize mb-2">Welcome to Tactics Management
                    Services Pvt. Ltd.</h3>
                <div class="col-lg-7 pb-4 pb-lg-0">
                    <p>Headquartered in Mumbai, Tactics Management Services Pvt. Ltd. is a premier HR outsourcing
                        company specializing in staffing solutions, payroll management, and compliance services. With a
                        strong commitment to excellence,
                        we aim to be a trusted partner for organizations across industries, helping them meet their
                        human resource needs efficiently and effectively.</p>
                    <h4 class="text-color-primary font-weight-bold text-capitalize mb-2">Who We Are</h4>
                    <p>
                        Tactics Management Services was founded with the goal of providing organizations with
                        comprehensive HR outsourcing solutions that simplify complex processes and enhance workforce
                        efficiency. With years of expertise, a dedicated team, and a client-first approach, we are
                        well-equipped to handle diverse HR requirements, from staffing to payroll management and beyond.
                        Our services are designed to reduce operational overhead, ensure compliance with statutory
                        regulations, and allow organizations to focus on their core business objectives.
                    </p>
                </div>
                <div class="col-lg-5 pb-4 pb-lg-0">
                    <div class="card border-0 border-radius-0  appear-animation animated fadeInUpShorter appear-animation-visible"
                        data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500"
                        style="animation-delay: 500ms;">
                        <div class="card-body p-3 z-index-1">
                            <img class="card-img-top border-radius-0" src="{{ 
                            $aboutMediaFiles->isNotEmpty() && $aboutMediaFiles->firstWhere('mf_type', 'About1')
    ? asset('assets/media_files/' . $aboutMediaFiles->firstWhere('mf_type', 'About1')->mf_image)
    : ($aboutMediaFiles->isNotEmpty() ? asset('assets/media_files/' . $aboutMediaFiles->first()->mf_image)
        : 'https://placehold.co/960x670') 
                        }}" alt="Card Image">
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row ">
                    <div class="col-lg-6 py-5 text-center">
                        <h3 class="text-color-primary font-weight-bold text-capitalize mt-2 mb-2">Our Vision</h3>
                        <p class="pb-3">To be a dedicated business partner to our clients, providing efficient and
                            customized services while building and fostering strong corporate relationships.</p>
                    </div>
                    <div class="col-lg-6 divider-left-border text-center py-5">
                        <h3 class="text-color-primary font-weight-bold text-capitalize mt-2 mb-2">Our Mission</h3>
                        <p class="pb-3">To deliver quality manpower promptly, tailored to the specific needs of our
                            clients, thereby strengthening their talent base to meet future challenges.</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-6 py-5">
                        <h4 class="text-color-quaternary font-weight-bold text-capitalize mb-2">Our Core Values</h4>
                        <div class="process process-vertical pt-4">
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="200">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">01</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Client-Centric Approach</h4>
                                    <p class="mb-0">We prioritize understanding our clients’ needs and delivering
                                        solutions that exceed expectations.</p>
                                </div>
                            </div>
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="400">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">02</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Integrity</h4>
                                    <p class="mb-0">Transparency and ethical practices are at the heart of everything we
                                        do.</p>
                                </div>
                            </div>
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="600">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">03</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Innovation</h4>
                                    <p class="mb-0">We continuously strive to improve and adapt our services to meet
                                        changing business landscapes.</p>
                                </div>
                            </div>
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="800">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">04</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Excellence</h4>
                                    <p class="mb-0">Quality is non-negotiable, and we ensure it in every service we
                                        provide.</p>
                                </div>
                            </div>
                            <div class="process-step appear-animation" data-appear-animation="fadeInUpShorter"
                                data-appear-animation-delay="1000">
                                <div class="process-step-circle">
                                    <strong class="process-step-circle-content text-4">05</strong>
                                </div>
                                <div class="process-step-content">
                                    <h4 class="mb-1 text-4 font-weight-bold">Partnership</h4>
                                    <p class="mb-0">We aim to build strong, long-lasting relationships with our clients
                                        and candidates.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 divider-left-border text-center py-5">
                        <div class="card border-0 border-radius-0  appear-animation animated fadeInUpShorter appear-animation-visible"
                            data-appear-animation="fadeInUpShorter" data-appear-animation-delay="500"
                            style="animation-delay: 500ms;">
                            <div class="card-body p-3 z-index-1">
                                <img class="card-img-top border-radius-0" src="{{ 
                            $aboutMediaFiles->isNotEmpty() && $aboutMediaFiles->firstWhere('mf_type', 'About2')
    ? asset('assets/media_files/' . $aboutMediaFiles->firstWhere('mf_type', 'About2')->mf_image)
    : ($aboutMediaFiles->isNotEmpty() ? asset('assets/media_files/' . $aboutMediaFiles->first()->mf_image)
        : 'https://placehold.co/960x670') 
                        }}" alt="Card Image">
                            </div>
                        </div>
                    </div>
                </div>




            </div>
        </div>
    </section>
</div>

@include('front.IncludesFiles.footer')
