@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('manager/datatable/datatables.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="card mt-3">
        <div class="card-body">
            {{-- Start: Page Content --}}
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0">{{(!empty($p_title) && isset($p_title)) ? $p_title : ''}}</h4>
                    <div class="small text-medium-emphasis">{{(!empty($p_summary) && isset($p_summary)) ? $p_summary : ''}}</div>
                </div>
                <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                    @can('manager_attendance_employee-sync')
                        <a href="{{(!empty($emp_url) && isset($emp_url)) ? $emp_url : ''}}" class="btn btn-sm btn-success">{{(!empty($emp_url_text) && isset($emp_url_text)) ? $emp_url_text : ''}}</a>
                    @endcan
                    @can('manager_attendance_employee-create')
                        <a href="{{(!empty($url) && isset($url)) ? $url : ''}}" class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    @endcan
                    @can('manager_attendance_employee-activity-log')
                        <a href="{{(!empty($trash) && isset($trash)) ? $trash : ''}}" class="btn btn-sm btn-danger">{{(!empty($trash_text) && isset($trash_text)) ? $trash_text : ''}}</a>
                    @endcan
                </div>
            </div>
            <hr>
            {{-- Datatatble : Start --}}
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="col-12 mb-4">
                            <fieldset class="reset-this redo-fieldset">
                                <legend class="reset-this redo-legend">Filters</legend>
                                <div class="row gy-2 gx-3 align-items-center    ">
                                    <div class="col-3">
                                        <label class="mb-1">Status</label>
                                        <select class="select2-options-status form-control" id="status">
                                            <option value="">All</option>
                                            <option value="1">Active</option>
                                            <option value="0">In-Active</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="mb-1">Role Status</label>
                                        <select class="select2-options-role-status form-control" id="role_status">
                                            <option value="">All</option>
                                            <option value="E">Employee</option>
                                            <option value="S">Student</option>
                                        </select>
                                    </div>
                                    <div class="col-12 ">
                                        <div class="float-end mt-2">
                                            <button type="button" class="btn btn-sm btn-primary px-3" onclick="selectRange()">
                                                Apply Filter
                                            </button>
                                            <a href="{{route('manager.employee.index')}}"
                                               class="btn btn-sm btn-danger text-white px-3">
                                                Reset
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="indextable" class="table table-bordered table-striped table-hover table-responsive w-100 pt-1">
                            <thead class="table-dark">
                            <th>#</th>
                            <th>Code</th>
                            <th>Registration No.</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>Campus</th>
                            <th>Role Status</th>
                            <th>IN Day_1</th>
                            <th>IN Day_2</th>
                            <th>IN Day_3</th>
                            <th>IN Day_4</th>
                            <th>IN Day_5</th>
                            <th>IN Day_6</th>
                            <th>IN Day_7</th>
                            <th>OUT Day_1</th>
                            <th>OUT Day_2</th>
                            <th>OUT Day_3</th>
                            <th>OUT Day_4</th>
                            <th>OUT Day_5</th>
                            <th>OUT Day_6</th>
                            <th>OUT Day_7</th>
                            <th>Status</th>
                            <th>Actions</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Datatatble : End --}}
            {{-- Page Description : Start --}}
            @if(!empty($p_description) && isset($p_description))
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 mb-sm-2 mb-0">
                            <p>{{(!empty($p_description) && isset($p_description)) ? $p_description : ''}}</p>
                        </div>
                    </div>
                </div>
            @endif
            {{-- Page Description : End --}}
            {{-- Delete Confirmation Model : Start --}}
            <div class="del-model-wrapper">
                <div class="modal fade" id="del-model" tabindex="-1" aria-labelledby="del-model" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close shadow-none" data-coreui-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="font-weight-bold mb-2"> Are you sure you wanna delete this ?</p>
                                <p class="text-muted "> This item will be deleted immediately. You can't undo this action.</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" id="del-form">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    <button type="button" class="btn btn-light" data-coreui-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Delete Confirmation Model : End --}}

            {{-- Refresh Key Confirmation Model : Start --}}
            <div class="key-model-wrapper">
                <div class="modal fade" id="emp-model" tabindex="-1" aria-labelledby="emp-model" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close shadow-none" data-coreui-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="font-weight-bold mb-2"> Are you sure you wanna save this ?</p>
                                <p class="text-muted "> This record will be added in Get-Employee immediately. You can't undo this action.</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" id="emp-form">
                                    @csrf
                                    {{method_field('PUT')}}
                                    <button type="button" class="btn btn-light" data-coreui-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-secondary">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End: Page Content --}}
        </div>
    </div>
@endsection
@push('footer-scripts')
    <script type="text/javascript" src="{{ asset('manager/datatable/datatables.min.js')}}"></script>
    <script src="{{ asset('manager/select2/dist/js/select2.js') }}"></script>

    <script>
        $(document).ready(function () {
            //Select Status
            $('.select2-options-status').select2({
                theme: "bootstrap5",
                placeholder: 'All',
                allowClear: true
            });

            //Select Role Status
            $('.select2-options-role-status').select2({
                theme: "bootstrap5",
                placeholder: 'All',
                allowClear: true
            });
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            //Datatable
            $('#indextable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                order: [[ 0, "desc" ]],
                ajax: {
                    "type":"GET",
                    "url":"{{route('manager.get.employee')}}",
                    "data":function (d){
                        d.status = document.getElementById('status').value;
                        d.role_status = document.getElementById('role_status').value;
                    }
                },
                columns: [
                    { data: 'id'},
                    { data: 'employee_code' },
                    { data: 'student_reg_no' },
                    { data: null},
                    { data: 'name' },
                    { data: 'designation' },
                    { data: 'department' },
                    { data: 'campus' },
                    { data: 'role_status' },
                    { data: 'st_in_day1' },
                    { data: 'st_in_day2' },
                    { data: 'st_in_day3' },
                    { data: 'st_in_day4' },
                    { data: 'st_in_day5' },
                    { data: 'st_in_day6' },
                    { data: 'st_in_day7' },
                    { data: 'st_out_day1' },
                    { data: 'st_out_day2' },
                    { data: 'st_out_day3' },
                    { data: 'st_out_day4' },
                    { data: 'st_out_day5' },
                    { data: 'st_out_day6' },
                    { data: 'st_out_day7' },
                    { data: 'status' },
                    { data: null},
                ],
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        width: '100px',
                        render: function ( data, type, row, meta ) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        render: function (data,type,row,meta) {
                            var profileImage = "{{ asset(':img') }}";
                            var std_emp_profile_pic = profileImage.replace(':img', data.image);
                            return '<img src="' + std_emp_profile_pic + '" height="80" width="80" alt="No Image Uploaded"/>';
                        },
                        searchable:false,
                        orderable:false,
                        targets: 3
                    },
                    {
                        targets: 23,
                        orderable: false,
                        className:'perm_col',
                        // defaultContent: '<span class="badge bg-info text-dark">group</span>'
                        render: function ( data, type, row, meta ) {
                            var output="";
                            if(data == 1){
                                output +=  '<span class="badge bg-success">Active</span>' ;
                            }
                            else{
                                output +=  '<span class="badge bg-danger">In-Active</span>' ;
                            }
                            return output;
                        }
                    },
                    {
                        targets: -1,
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row, meta ) {
                            let URL = "{{ route('manager.employee.show', ':id') }}";
                            URL = URL.replace(':id', row.id);
                            let ACTIVITY = "{{ route('manager.get.employee-activity', ':id') }}";
                            ACTIVITY = ACTIVITY.replace(':id', row.id);
                            let ERP = "{{ route('manager.employee-erp', [':id']) }}";
                            ERP = ERP.replace(':id', row.id);
                            return '<div class="d-flex">' +
                                        @can('manager_attendance_employee-show')
                                            '<a class="me-1" href="'+URL+'"><span class="badge bg-success text-dark">Show</span>' +
                                        @endcan
                                        @can('manager_attendance_employee-edit')
                                            '<a class="me-1" href="'+URL+'/edit"><span class="badge bg-info text-dark">Edit</span></a>' +
                                        @endcan
                                        @can('manager_attendance_employee-activity-log')
                                            '<a class="me-1" href="'+ACTIVITY+'"><span class="badge bg-warning text-dark">Activity</span></a>' +
                                        @endcan
                                        @can('manager_attendance_employee-delete')
                                            '<a class="me-1" href="javascript:void(0)"><span type="button" class="badge bg-danger" data-url="'+URL+'" data-coreui-toggle="modal" data-coreui-target="#del-model">Delete</span></a>'+
                                        @endcan
                                            @can('manager_attendance_employee-sync-to-erp')
                                            '<a class="me-1" href="javascript:void(0)"><span type="button" class="badge bg-secondary" data-url="'+ERP+'" data-coreui-toggle="modal" data-coreui-target="#emp-model">Sync with ERP</span></a>'+
                                  @endcan
                                   '</div>'
                        }
                    }
                ]
            });
        });
        function selectRange(){
            $('.dataTable').DataTable().ajax.reload()
        }
    </script>
    {{-- Delete Confirmation Model : Script : Start --}}
    <script>
        $("#del-model").on('show.coreui.modal', function (event) {
            var triggerLink = $(event.relatedTarget);
            var url = triggerLink.data("url");
            $("#del-form").attr('action', url);
        })
        $("#emp-model").on('show.coreui.modal', function (event) {
            var triggerLink = $(event.relatedTarget);
            var url = triggerLink.data("url");
            $("#emp-form").attr('action', url);
        })
    </script>
    {{-- Delete Confirmation Model : Script : Start --}}
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush
