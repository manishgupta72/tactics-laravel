<footer id="footer">
    <div class="container">
        <div class="footer-ribbon">
            <span>Get in Touch</span>
        </div>
        <div class="row py-5 my-4">
            <div class="col-md-6 mb-4 mb-lg-0">
                <a href="index.html" class="logo pe-0 pe-lg-3">
                    <img alt="Porto Website Template" src="img/logo-footer.png" class="opacity-7 bottom-4" height="32">
                </a>
                <p class="mt-2 mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec eu pulvinar magna.
                    Phasellus semper scelerisque purus, et semper nisl lacinia sit amet. Praesent venenatis turpis vitae
                    purus semper...</p>
                <p class="mb-0"><a href="#" class="btn-flat btn-xs text-color-light"><strong class="text-2">VIEW
                            MORE</strong><i class="fas fa-angle-right p-relative top-1 ps-2"></i></a></p>
            </div>
            <div class="col-md-6">
                <h5 class="text-3 mb-3">CONTACT US</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list list-icons list-icons-lg">
                            <li class="mb-1"><i class="far fa-dot-circle text-color-primary"></i>
                                <p class="m-0">{{get_settings('general_settings','address')}}</p>
                            </li>
                            <li class="mb-1"><i class="fab fa-whatsapp text-color-primary"></i>
                                <p class="m-0"><a href="#">  {{get_settings('general_settings','support_whatsapp_number')}}</a></p>
                            </li>
                            <li class="mb-1"><i class="icon-phone icons text-5 me-2"></i>
                                <p class="m-0"><a href="#">  {{get_settings('general_settings','support_number')}}</a></p>
                            </li>
                            <li class="mb-1"><i class="far fa-envelope text-color-primary"></i>
                                <p class="m-0"><a href="mailto:mail@example.com">{{get_settings('general_settings','support_email')}}</a></p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list list-icons list-icons-sm">
                            <li><i class="fas fa-angle-right"></i><a href="page-faq.html"
                                    class="link-hover-style-1 ms-1"> FAQ's</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="sitemap.html"
                                    class="link-hover-style-1 ms-1"> Sitemap</a></li>
                            <li><i class="fas fa-angle-right"></i><a href="contact-us.html"
                                    class="link-hover-style-1 ms-1"> Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright footer-copyright-style-2">
        <div class="container py-2">
            <div class="row py-4">
                <div class="col d-flex align-items-center justify-content-center">
                    <!-- <p>© Copyright 2024. All Rights Reserved</p> -->
                    <p>{{get_settings('general_settings','copyright');}}</p>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>




@include('front.IncludesFiles.Common-Js')
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- define here all logic related to the fetch the jobs -->
<script>
    let nextPageUrl = "{{ route('fetch.jobs') }}"; // Initialize the next page URL
    let isLoading = false; // Flag to prevent multiple simultaneous requests

    function loadJobs() {
        if (!nextPageUrl || isLoading) return; // If no next page or already loading, return

        isLoading = true; // Set the loading flag to true
        $('#loading-spinner').show(); // Show a loading spinner

        $.get(nextPageUrl, function (response) {
            const jobs = response.jobs;
            // console.log(jobs);
            nextPageUrl = response.next_page_url; // Update the next page URL

            jobs.forEach(job => {
                $('#jobs-container').append(`
                    <div class="card card-border card-border-bottom card-border-hover bg-color-grey box-shadow-6 box-shadow-hover anim-hover-translate-top-10px transition-3ms mb-5">
                        <div class="card-body">
                            <h4 class="card-title mb-1 text-4 font-weight-bold">${job.job_title}</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="text-color-primary font-weight-black">Job Language :</span><br>
                                    ${job.job_language || 'N/A'}
                                </div>
                                <div class="col-md-3">
                                    <span class="text-color-primary font-weight-black">Job Location :</span><br>
                                    ${job.job_location || 'N/A'}
                                </div>
                                <div class="col-md-3">
                                    <span class="text-color-primary font-weight-black">No. Of Openings :</span><br>
                                    ${job.job_opening || 'N/A'}
                                </div>
                                <div class="col-md-3">
                                    <span class="text-color-primary font-weight-black">Expected Salary :</span><br>
                                    ${job.job_salary || 'N/A'}
                                </div>
                                <div class="col-md-9">
                                    <span class="text-color-primary font-weight-black">Experience Required:</span><br>
                                    ${job.job_experience || 'N/A'}
                                </div>
                                <div class="col-md-3" style="display: flex;align-content: center;justify-content: center;flex-wrap: nowrap;flex-direction: column;">
                                    <a href="#" class="btn btn-modern btn-primary rounded-0 text-3 mt-3">Apply Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            $('#loading-spinner').hide(); // Hide the loading spinner

            if (!nextPageUrl) {
                $('#jobs-container').append('<p class="text-center">No more jobs to load.</p>');
            }

            isLoading = false; // Reset the loading flag
        }).fail(function () {
            // In case of an error
            $('#loading-spinner').hide();
            console.error('Failed to fetch jobs.');
            isLoading = false; // Reset the loading flag
        });
    }

    // Infinite Scroll Event Listener
    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadJobs(); // Call the loadJobs function when scrolling near the bottom
        }
    });

    // Initial Load
    loadJobs();
</script>


<script>
$('a.nav-link').on('click', function (event) {
    console.log('Link clicked:', $(this).attr('href')); // Debug log
    event.preventDefault(); // Prevent any default actions
    const url = $(this).attr('href'); // Get the link's href
    console.log('Navigating to:', url); // Debug log
    window.location.href = url; // Force navigation
});

</script>

<!-- define here all logic related to the otp and toast -->
<!-- //send and verify otp java script here  -->
<script>
    // Show a toast notification
    function showToast(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type, // 'success', 'error', 'info', 'warning'
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    $(document).ready(function () {
        let otpTimer; // Variable to hold the timer

        // Handle Send OTP
        $('#sendOtpBtn').on('click', function () {
            const mobile = $('input[name="mobile"]').val();

            if (!/^\d{10}$/.test(mobile)) {
                showToast('Please enter a valid 10-digit mobile number', 'error');
                return;
            }

            $.post("{{ route('send.otp') }}", { mobile: mobile, _token: '{{ csrf_token() }}' })
                .done(function (response) {
                    showToast(response.message, 'success');
                    $('#otpSection').removeClass('d-none'); // Show OTP field
                    $('#loginSection').removeClass('d-none'); // Show Login button

                    // Start timer and disable button
                    startOtpTimer();
                })
                .fail(function (xhr) {
                    showToast(xhr.responseJSON.message, 'error');
                });
        });

        // Handle Login
        $('#loginBtn').on('click', function () {
            const mobile = $('input[name="mobile"]').val();
            const otp = $('input[name="otp"]').val();

            if (!otp) {
                showToast('Please enter the OTP.', 'error');
                return;
            }

            $.post("{{ route('verify.otp') }}", { mobile: mobile, otp: otp, _token: '{{ csrf_token() }}' })
                .done(function (response) {
                    showToast(response.message, 'success');
                    window.location.href = "{{ route('user.dashboard') }}"; // Redirect to dashboard
                })
                .fail(function (xhr) {
                    showToast(xhr.responseJSON.message, 'error');
                });
        });

        // Function to start the timer
        function startOtpTimer() {
    let timerDuration = 150; // Timer duration in seconds (2 minutes and 30 seconds)
    const sendOtpBtn = $('#sendOtpBtn');
    sendOtpBtn.prop('disabled', true); // Disable the button

    otpTimer = setInterval(function () {
        if (timerDuration <= 0) {
            clearInterval(otpTimer); // Clear the timer
            sendOtpBtn.text('Send OTP').prop('disabled', false); // Enable the button
        } else {
            // Format the remaining time as MM:SS
            const minutes = Math.floor(timerDuration / 60);
            const seconds = timerDuration % 60;
            sendOtpBtn.text(`Resend OTP (${minutes}:${seconds < 10 ? '0' + seconds : seconds})`); // Update button text
            timerDuration--;
        }
    }, 1000);
}

    });
</script>

<!-- define here all logic related  toast -->
<script>
    $(document).ready(function () {
        // Show success toast if a success message exists in the session
        @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        @endif
        @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
        @endif
    });
</script>
</body>

</html>