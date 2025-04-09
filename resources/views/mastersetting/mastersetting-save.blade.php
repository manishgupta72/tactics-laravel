@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">

        <!-- Collapse -->
        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Settings</h4>
                        {{-- <p class="card-title-desc">Extend the default collapse behavior to create an accordion.</p>
                        --}}


                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item border rounded">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        General Setting
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <h3>General Setting</h3>
                                        <form id="GeneralSettingFrm">
                                            <div class="row form-group pb-3">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="formGroupExampleInput">System
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="System Name"
                                                            name="system_name"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['system_name']) ? $general_settings['system_name'] : '' }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label" for="formGroupExampleInput">Upload
                                                            Logo</label>
                                                        <input type="file" class="filestyle" name="system_logo"
                                                            data-buttonname="btn-secondary">
                                                        @if ($system_logo != '')
                                                            <img class="img-fluid ps-1" src="{{ $system_logo }}"
                                                                style="height:100%; width:200px">
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label"
                                                            for="formGroupExampleInput">Support Email ID</label>
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="Support Email ID"
                                                            name="support_email"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['support_email']) ? $general_settings['support_email'] : '' }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label" for="formGroupExampleInput">Text
                                                            Display</label>
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="Text Display"
                                                            name="display_text"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['display_text']) ? $general_settings['display_text'] : '' }}">
                                                    </div>
                                                    <!-- X (Twitter) Link Field -->
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="x_link">X (Twitter)
                                                            Link</label>
                                                        <input type="text" class="form-control" id="x_link"
                                                            name="x_link" placeholder="Enter X (Twitter) link"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['x_link']) ? $general_settings['x_link'] : '' }}">
                                                    </div>

                                                    <!-- Address Field -->
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="address">Address</label>
                                                        <textarea class="form-control" id="address" name="address"
                                                            placeholder="Enter address"
                                                            rows="3">{{ check_valid_array($general_settings) && !empty($general_settings['address']) ? $general_settings['address'] : '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label"
                                                            for="formGroupExampleInput">Application Title</label>
                                                        <input type="text" class="form-control"
                                                            id="formGroupExampleInput" placeholder="Application Title"
                                                            name="aplication_title"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['aplication_title']) ? $general_settings['aplication_title'] : '' }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label" for="formGroupExampleInput">Login
                                                            Screen Logo</label>

                                                        <input type="file" class="filestyle" name="login_screen_logo"
                                                            data-buttonname="btn-secondary">
                                                        @if ($login_screen_logo != '')
                                                            <img class="img-fluid ps-1" src="{{ $login_screen_logo }}"
                                                                style="height:100%; width:200px">
                                                        @endif
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label"
                                                            for="formGroupExampleInput">Support Number</label>
                                                        <input type="number" class="form-control"
                                                            id="formGroupExampleInput" placeholder="Support Number"
                                                            name="support_number"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['support_number']) ? $general_settings['support_number'] : '' }}">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-form-label"
                                                            for="formGroupExampleInput">Support WhatsApp Number</label>
                                                        <input type="number" class="form-control"
                                                            id="formGroupExampleInput"
                                                            placeholder="Support WhatsApp Number"
                                                            name="support_whatsapp_number"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['support_whatsapp_number']) ? $general_settings['support_whatsapp_number'] : '' }}">
                                                    </div>
                                                    <!-- Copyright Field -->
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="copyright">Copyright</label>
                                                        <input type="text" class="form-control" id="copyright"
                                                            name="copyright" placeholder="Enter copyright"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['copyright']) ? $general_settings['copyright'] : '' }}">
                                                    </div>

                                                    <!-- Instagram Link Field -->
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="insta_link">Instagram
                                                            Link</label>
                                                        <input type="text" class="form-control" id="insta_link"
                                                            name="insta_link" placeholder="Enter Instagram link"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['insta_link']) ? $general_settings['insta_link'] : '' }}">
                                                    </div>

                                                    <!-- Facebook Link Field -->
                                                    <div class="form-group">
                                                        <label class="col-form-label" for="facebook_link">Facebook
                                                            Link</label>
                                                        <input type="text" class="form-control" id="facebook_link"
                                                            name="facebook_link" placeholder="Enter Facebook link"
                                                            value="{{ check_valid_array($general_settings) && !empty($general_settings['facebook_link']) ? $general_settings['facebook_link'] : '' }}">
                                                    </div>




                                                </div>
                                            </div>
                                            <input type="hidden" name="Action" value="1" />
                                            <input type="hidden" name="id" value="1" />
                                            <button type="button"
                                                class="mb-1 mt-1 me-1 btn btn-primary GeneralSettingFrmBtn">Update
                                                Settings</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border rounded mt-2">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed fw-semibold" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                        aria-controls="collapseTwo">
                                        Master Data
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <form id="MasterFrm">
                                            <div class="row form-group pb-3">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="col-form-label"
                                                            for="formGroupExampleInput">Name</label>
                                                        <input type="text" class="form-control" name="master_data_name"
                                                            id="master_data_name" placeholder="Name">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-form-label"
                                                            for="formGroupExampleInput">Description</label>
                                                        <textarea class="form-control" rows="3"
                                                            name="master_data_description" id="master_data_description"
                                                            placeholder="Description"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="col-form-label" for="formGroupExampleInput">Select
                                                        Master</label>
                                                    <select class="form-control mb-3" name="mid" id="mid">
                                                        <option value="">Select Master</option>
                                                        @if (check_valid_array($master_data))
                                                            @foreach ($master_data as $key => $item)
                                                                <option value="{{ $key }}">
                                                                    {{ $item }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <input type="hidden" name="master_data_id" value="0"
                                                        id="master_data_id">
                                                    <button type="button"
                                                        class="mb-1 mt-1 me-1 btn btn-primary create_master_data_page_data">Submit</button>
                                                    <button type="button"
                                                        class="mb-1 mt-1 me-1 btn btn-primary reset_master_data">Reset</button>
                                                    <button type="button"
                                                        class="mb-1 mt-1 me-1 btn btn-primary add_master_data"
                                                        style="display: none">Add</button>
                                                </div>
                                                <hr class="my-5">
                                                <div class="col-lg-12">
                                                    <div class="table_bg">
                                                        <table class="table table-bordered table-striped mb-0"
                                                            id="ell-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Description</th>
                                                                    <th>Master</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/mastersetting/setting.js') }}"></script>
@endsection

@push('footer_scripts')
    <script>
        var save_route = '{{ $save_route }}';
        var save_master_route = '{{ $save_master_route }}';
        var lst_master_route = '{{ $lst_master_route }}';
        var edt_master_route = '{{ $edt_master_route }}';
        var del_master_route = '{{ $del_master_route }}';
        var password_route = '{{ $password_route }}';
    </script>
@endpush