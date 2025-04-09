@extends('IncludesFiles.Master')

@section('container')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        @if ($EID == 'EID' && isset($EID))
                        <form id="AdminFrms">
                            <div class="card-body">
                                <div class="row form-group pb-3">
                                    <!-- Admin Name -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Admin Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Admin Name"
                                                value="{{ isset($EID) && isset($detail->name) ? $detail->name : '' }}">
                                        </div>
                                    </div>

                                    <!-- Email ID -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Email ID</label>
                                            <input type="text" class="form-control" name="email" placeholder="Email ID"
                                                value="{{ isset($EID) && isset($detail->email) ? $detail->email : '' }}">
                                        </div>
                                    </div>



                                    <!-- Password -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Password</label>
                                            <input type="password" class="form-control" name="password"
                                                placeholder="Password">
                                        </div>
                                    </div>

                                    <!-- ADMIN_TYPE -->
                                    <!-- <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="admin_type">Admin Type</label>
                                            <select class="form-control select2" name="admin_type">
                                                <option value="0">Select Admin Type</option>
                                                @foreach (config('constant.ADMIN_TYPE') as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ isset($detail->admin_type) && $detail->admin_type == $key ? 'SELECTED' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->

                                    <!-- Select Role -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Select
                                                Role</label>
                                            <select class="form-control select2" name="UserType">
                                                <option value="0">Select Role</option>
                                                @if (!$role_list->isEmpty())
                                                @foreach ($role_list as $item)
                                                <option value="{{ $item->RollId }}"
                                                    {{ isset($EID) && isset($detail->UserType) && $detail->UserType == $item->RollId ? 'SELECTED' : '' }}>
                                                    {{ $item->RollName }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group mt-4">
                                            <label class="col-form-label" for="switch1">Status</label>
                                            <div class="d-flex align-items-center">
                                                <input class="form-check form-switch" name="status" type="checkbox"
                                                    id="switch1" switch="none"
                                                    {{ isset($EID) && isset($detail->status) && $detail->status == 1 ? 'Checked' : '' }}>
                                                <label class="form-label" for="switch1" data-on-label="On"
                                                    data-off-label="Off"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden ID -->
                                    <input type="hidden" name="id"
                                        value="{{ isset($EID) && isset($detail->id) ? $detail->id : 0 }}">

                                    <!-- Update Button -->
                                    <div class="col-sm-12 text-center">
                                        <button type="button"
                                            class="mb-1 mt-1 me-1 btn btn-primary create_admin_page_data">Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @else
                        <form id="AdminFrms">
                            <div class="card-body">
                                <div class="row form-group pb-3">
                                    <!-- Admin Name -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Admin Name</label>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="Admin Name">
                                        </div>
                                    </div>

                                    <!-- Email ID -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Email ID</label>
                                            <input type="text" class="form-control" name="email" placeholder="Email ID">
                                        </div>
                                    </div>



                                    <!-- Password -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Password</label>
                                            <input type="password" class="form-control" name="password"
                                                placeholder="Password">
                                        </div>
                                    </div>

                                    <!-- ADMIN_TYPE -->
                                    <!-- <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="admin_type">Admin Type</label>
                                            <select class="form-control select2" name="admin_type">
                                                <option value="0">Select Admin Type</option>
                                                @foreach (config('constant.ADMIN_TYPE') as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ isset($detail->admin_type) && $detail->admin_type == $key ? 'SELECTED' : '' }}>
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> -->

                                    <!-- Select Role -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group">
                                            <label class="col-form-label" for="formGroupExampleInput">Select
                                                Role</label>
                                            <select class="form-control select2" name="UserType">
                                                <option value="0">Select Role</option>
                                                @if (!$role_list->isEmpty())
                                                @foreach ($role_list as $item)
                                                <option value="{{ $item->RollId }}">{{ $item->RollName }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-lg-4 pb-0">
                                        <div class="form-group mt-4">
                                            <label class="col-form-label" for="switch1">Status</label>
                                            <div class="d-flex align-items-center">
                                                <input class="form-check form-switch" name="status" type="checkbox"
                                                    id="switch1" switch="none">
                                                <label class="form-label" for="switch1" data-on-label="On"
                                                    data-off-label="Off"></label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="col-sm-12 text-center">
                                        <button type="button"
                                            class="mb-1 mt-1 me-1 btn btn-primary create_admin_page_data">Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('file_script')
<script type="text/javascript" src="{{ static_asset('assets/Frontend/admin/admin.js') }}"></script>
@endsection

@push('footer_scripts')
<script>
var list_route = '{{ $list_route }}';
var save_route = '{{ $save_route }}';
var edit_route = '{{ $edit_route }}';
var del_route = '{{ $del_route }}';
</script>
@endpush