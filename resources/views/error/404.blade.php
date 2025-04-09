@extends('IncludesFiles.Master')

@section('sidebar')
@endsection

@section('Header')
@endsection
{{-- <style>
    .nk-header {display: none;}
    .nk-wrap {min-height: auto !important;}
    .nk-wrap-nosidebar .nk-content {min-height: 92vh !important;}
</style> --}}


@section('container')
   <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle wide-xs mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <h1 class="nk-error-head">404</h1>
                            <h3 class="nk-error-title">Oops! Why you're here?</h3>
                            <p class="nk-error-text">We are very sorry for inconvenience. It looks like you're try to access a page that either has been deleted or never existed.</p>
                            <a href="{{ getBaseURL() }}admin/dashboard" class="btn btn-lg btn-primary mt-2">Back To Home</a>
                        </div>
                    </div><!-- .nk-block -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
@endsection
