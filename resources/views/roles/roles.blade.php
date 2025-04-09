@extends('IncludesFiles.Master')

@section('container')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $title }}</h4>

                            <form id="FormFrm">
                                <div class="row form-group p-4">

                                    <div class="col-lg-4 pb-0">
                                        <select class="form-control mb-3 select2" name="Status" id="Status">
                                            <option>Select Status</option>
                                            @if (check_valid_array($status))
                                                @foreach ($status as $key => $item)
                                                    <option value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-lg-4 pb-0">
                                        @if (HasPermission('A1') == 'true')
                                            <a class="btn btn-primary w-50" href="{{ route('page.RollsController') }}">+ Add
                                                Role</a>
                                        @endif
                                        <a class="mb-1 mt-1 me-1 btn btn-dark reload_data"><i class="fas fa-sync"></i>
                                            Refresh</a>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="row p-4">

                                {{-- <table class="table table-bordered table-striped mb-0" id="ell-table"> --}}
                                    <table id="ell-table" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Role Name</th>
                                            <th>Permission</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>
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
        var view_permisssion = '{{ $view_permisssion }}';
    </script>
@endpush
