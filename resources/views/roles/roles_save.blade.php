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
                                <form id="RoleFrms">
                                    <div class="card-body">
                                        <div class="row form-group pb-3">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="formGroupExampleInput">Roll
                                                        Name</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput"
                                                        placeholder="Roll Name" name="RollName"
                                                        value="{{ isset($EID) && isset($roll_detail->RollName) ? $roll_detail->RollName : '' }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mt-4">
                                                    {{-- <label class="col-form-label" for="formGroupExampleInput">Status</label>
                                                    <div class="switch switch-success ps-3">
                                                        <input type="checkbox" name="Status" data-plugin-ios-switch=""
                                                            style="display: none;"
                                                            {{ isset($EID) && isset($roll_detail->Status) && $roll_detail->Status == 1 ? 'Checked' : '' }}>
                                                    </div> --}}
                                                    <label class="col-form-label" for="switch1">Status</label>
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-check form-switch" name="Status" type="checkbox"
                                                            id="switch1"  switch="none" {{ isset($EID) && isset($roll_detail->Status) && $roll_detail->Status == 1 ? 'Checked' : '' }}>
                                                        <label class="form-label" for="switch1" data-on-label="On"
                                                            data-off-label="Off"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group mt-4">
                                                    <label class="col-form-label" for="formGroupExampleInput">Roles </label>
                                                    <div class="switch switch-success ps-3">
                                                        <select class="form-control dual_select" name="RollAssignID[]"
                                                            multiple>
                                                            @if (!$menu_list->isEmpty())
                                                                @foreach ($menu_list as $item)
                                                                    <option value="{{ $item->SubMenuID }}"
                                                                        {{ in_array($item->SubMenuID, explode(',', $roll_detail->RollAssignID)) ? 'selected' : '' }}>
                                                                        {{ $item->MenuTitle }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="RollId"
                                                value="{{ isset($EID) && isset($roll_detail->RollId) ? $roll_detail->RollId : 0 }}">
                                            <div class="col-sm-12 text-center">
                                                <button type="button"
                                                    class="mb-1 mt-1 me-1 btn btn-primary create_role_page_data">Update</button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            @else
                                <form id="RoleFrms">
                                    <div class="card-body">
                                        <div class="row form-group pb-3">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="formGroupExampleInput">Roll
                                                        Name</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput"
                                                        placeholder="Roll Name" name="RollName">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group mt-4">

                                                    {{-- <label class="col-form-label" for="formGroupExampleInput">Status</label> --}}
                                                    {{--  <div class="switch switch-success ps-3">
                                                        <input type="checkbox" name="Status" data-plugin-ios-switch=""
                                                            checked="checked" style="display: none;">
                                                    </div> --}}
                                                    <label class="col-form-label" for="switch1">Status</label>
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-check form-switch" name="Status" type="checkbox"
                                                            id="switch1" checked switch="none">
                                                        <label class="form-label" for="switch1" data-on-label="On"
                                                            data-off-label="Off"></label>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="form-group mt-4">
                                                    <label class="col-form-label" for="formGroupExampleInput">Roles </label>
                                                    <div class="switch switch-success ps-3">
                                                        <select class="form-control dual_select" name="RollAssignID[]"
                                                            multiple>
                                                            @if (!$menu_list->isEmpty())
                                                                @foreach ($menu_list as $item)
                                                                    <option value="{{ $item->SubMenuID }}">
                                                                        {{ $item->MenuTitle }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 text-center">
                                                <button type="button"
                                                    class="mb-1 mt-1 me-1 btn btn-primary create_role_page_data">Save</button>
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
    <script type="text/javascript" src="{{ static_asset('assets/Frontend/role/role.js') }}"></script>
@endsection

@push('footer_scripts')
    <script>
        var list_route = '{{ $list_route }}';
        var save_route = '{{ $save_route }}';
        var edit_route = '{{ $edit_route }}';
        var del_route = '{{ $del_route }}';
    </script>
@endpush
