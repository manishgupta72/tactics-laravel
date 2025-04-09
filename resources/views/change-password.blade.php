@extends('IncludesFiles.Master')

@section('container')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $title }}</h4>
                            <form id="ChangePasswordFrm">
                                <div class="row form-group pb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Username / Email
                                                ID</label>
                                            <input type="text" class="form-control" name="email" disabled=""
                                                placeholder="Username / Email ID"
                                                value="{{ isset(Session::get('UserData')['Email']) ? Session::get('UserData')['Email'] : '' }}">

                                            <input type="hidden" class="form-control" name="email" readonly=""
                                                value="{{ isset(Session::get('UserData')['Email']) ? Session::get('UserData')['Email'] : '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Old Plassword</label>
                                            <input type="password" class="form-control" name="old_password"
                                                placeholder="Old Plassword">
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">New Password</label>
                                            <input type="password" class="form-control" name="password"
                                                placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Confirm New
                                                Password</label>
                                            <input type="password" class="form-control" name="password_confirmation"
                                                placeholder="Confirm New Password">
                                        </div>
                                    </div>
                                </div>
                                <button type="button"
                                    class="mb-1 mt-1 me-1 btn btn-primary create_change_password_page_data">Change
                                    Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('file_script')
    <script type="text/javascript" src="{{ static_asset('assets/Frontend/change-password.js') }}"></script>
@endsection

@push('footer_scripts')
    <script>
        var password_route          = '{{ $password_route }}';
    </script>
@endpush
