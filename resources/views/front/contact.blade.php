@include('front.IncludesFiles.head')
@include('front.IncludesFiles.menu')
@include('front.IncludesFiles.page-title', ['title' => 'Contact'])
<div role="main" class="main">
    <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
    <div id="googlemaps" class="google-map m-0"></div>

    <div class="container py-5">
        <div class="row">
            <div class="col pt-3">

                <h3 class="text-color-quaternary font-weight-bold text-capitalize mb-2">Contact Info</h3>
                <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet libero id nisi
                    euismod, sed porta est consectetur. Vestibulum auctor felis eget orci semper vestibulum.
                    Pellentesque ultricies nibh gravida, accumsan libero luctus, molestie nunc.</p>

                <div class="row text-center pb-3 pt-4">
                    <div class="col-lg-3 col-md-6 pb-4 pb-lg-0">
                        <img width="60" src="assets/img/demos/medical-2/icons/icon-location.svg" alt="" />
                        <h4 class="m-0 pt-4 font-weight-bold">Address</h4>
                        <p class="m-0">{{get_settings('general_settings', 'address')}}</p>
                    </div>
                    <div class="col-lg-3 col-md-6 pb-4 pb-lg-0">
                        <img width="60" src="assets/img/demos/medical-2/icons/icon-phone.svg" alt="" />
                        <h4 class="m-0 pt-4 font-weight-bold">Phone Number</h4>
                        <p class="m-0"><a href="tel:{{get_settings('general_settings', 'support_number')}}"
                                class="text-color-default text-color-hover-primary">{{get_settings('general_settings', 'support_number')}}</a>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 pb-4 pb-md-0">
                        <img width="60" src="assets/img/demos/medical-2/icons/icon-envelope.svg" alt="" />
                        <h4 class="m-0 pt-4 font-weight-bold">E-mail Address</h4>
                        <p class="m-0"><a href="mailto:{{get_settings('general_settings', 'support_email')}}"
                                class="text-default text-hover-primary">{{get_settings('general_settings', 'support_email')}}</a>
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <img width="60" src="assets/img/demos/medical-2/icons/icon-calendar.svg" alt="" />
                        <h4 class="m-0 pt-4 font-weight-bold">Working Days/Hours</h4>
                        <p class="m-0">Mon - Sun / 9:00AM - 8:00PM</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <hr class="my-5">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h3 class="text-color-quaternary font-weight-bold text-capitalize mb-2">Send a Message</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla volutpat ex finibus urna tincidunt,
                    auctor ullamcorper risus luctus.</p>

                <form class="contact-form custom-form-style-1 appear-animation" data-appear-animation="fadeIn"
                    data-appear-animation-delay="100" action="{{asset('assets/front/php/contact-form.php')}}" method="POST">
                    <div class="contact-form-success alert alert-success d-none mt-4">
                        <strong>Success!</strong> Your message has been sent to us.
                    </div>

                    <div class="contact-form-error alert alert-danger d-none mt-4">
                        <strong>Error!</strong> There was an error sending your message.
                        <span class="mail-error-message text-1 d-block"></span>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <input type="text" placeholder="Your Name" value=""
                                data-msg-required="Please enter your name." maxlength="100" class="form-control"
                                name="name" required>
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="email" placeholder="Your E-mail" value=""
                                data-msg-required="Please enter your email address."
                                data-msg-email="Please enter a valid email address." maxlength="100"
                                class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <input placeholder="Subject" type="text" value=""
                                data-msg-required="Please enter the subject." maxlength="100" class="form-control"
                                name="subject" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <textarea placeholder="Your Message..." maxlength="5000"
                                data-msg-required="Please enter your message." rows="10" class="form-control"
                                name="message" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <input type="submit" value="Send Message"
                                class="btn btn-primary px-4 text-3 py-3 text-center text-uppercase font-weight-semibold"
                                data-loading-text="Loading...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <img class="img-fluid"
                    src="{{ $aboutMediaFiles->isNotEmpty() ? asset('assets/media_files/' . $aboutMediaFiles->first()->mf_image) : 'https://placehold.co/960x670' }}"
                    alt="about Image">
            </div>
        </div>
    </div>
</div>

@include('front.IncludesFiles.footer')