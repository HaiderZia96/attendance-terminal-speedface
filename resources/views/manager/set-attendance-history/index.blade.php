@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('manager/datatable/datatables.min.css') }}" rel="stylesheet" />
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
                                        @can('manager_attendance_set-attendance-histories-delete-all')
                        <a class="me-1" href="javascript:void(0)"><span type="button" class="btn btn-sm btn-danger" data-url='{{(!empty($delete_all) && isset($delete_all)) ? $delete_all : ''}}'  data-coreui-toggle="modal" data-coreui-target="#del-all-model">{{$delete_all_text}}</span></a>
                    @endcan
                    {{--                    @can('admin_user-management_module-activity-log-trash')--}}
                    {{--                    <a href="{{(!empty($trash) && isset($trash)) ? $trash : ''}}" class="btn btn-sm btn-danger">{{(!empty($trash_text) && isset($trash_text)) ? $trash_text : ''}}</a>--}}
                    {{--                    @endcan--}}
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
                                            <option value="1">Success</option>
                                            <option value="0">Failed</option>
                                            <option value="2">Unknown</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="mb-1">Date</label>
                                        <div class="input-daterange input-group">
                                            <input type="date" class="form-control" id="start_date" autocomplete="off">
                                            <div class="input-group-prepend px-1">
                                                <span class="input-group-text">to</span>
                                            </div>
                                            <input type="date" class="form-control" id="end_date" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-12 ">
                                        <div class="float-end mt-2">
                                            <button type="button" class="btn btn-sm btn-primary px-3" onclick="selectRange()">
                                                Apply Filter
                                            </button>
                                            <a href="{{route('manager.set-attendance-histories.index')}}"
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
                            <th>Employee Code</th>
                            <th>Log</th>
                            <th>Status</th>
                            <th>Created At</th>
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
            {{-- Delete Confirmation Model : Start --}}
            <div class="del-model-wrapper">
                <div class="modal fade" id="del-all-model" tabindex="-1" aria-labelledby="del-all-model" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close shadow-none" data-coreui-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="font-weight-bold mb-2"> Are you sure you wanna delete this all?</p>
                                <p class="text-muted "> This item will be deleted immediately. You can't undo this action.</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" id="del-all-form">
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
                    "url":"{{route('manager.get.set-attendance-histories')}}",
                    "data":function (d){
                        d.status = document.getElementById('status').value;
                        // d.status = status,
                        d.start_date = document.getElementById('start_date').value;
                        d.end_date = document.getElementById('end_date').value;
                    }
                },
                columns: [
                    { data: 'id'},
                    { data: 'employee_code' },
                    { data: 'log' },
                    {data: 'status_code'},
                    {data: 'created_at'},
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
                        targets: 3,
                        orderable: false,
                        className:'perm_col',
                        // defaultContent: '<span class="badge bg-info text-dark">group</span>'
                        render: function ( data, type, row, meta ) {
                            var output="";
                            if(data == 1){
                                output +=  '<span class="badge bg-success">Success</span>' ;
                            }
                            else if(data == 0){
                                output +=  '<span class="badge bg-danger">Failed</span>' ;
                            }
                            else if(data == 2){
                                output +=  '<span class="badge bg-danger">Unknown</span>' ;
                            }
                            else {
                                output +=  '' ;
                            }
                            return output;
                        }
                    },
                    {
                        targets: -1,
                        searchable: false,
                        orderable: false,
                        render: function ( data, type, row, meta ) {
                            let URL = "{{ route('manager.set-attendance-histories.destroy', ':id') }}";
                            URL = URL.replace(':id', row.id);

                            return '<div class="d-flex">' +

                                @can('manager_attendance_set-attendance-histories-delete-all')
                                    '<a class="me-1" href="javascript:void(0)"><span type="button" class="badge bg-danger" data-url="'+URL+'" data-coreui-toggle="modal" data-coreui-target="#del-model">Delete</span></a>'+
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
        $("#del-all-model").on('show.coreui.modal', function (event) {
            var triggerLink = $(event.relatedTarget);
            var url = triggerLink.data("url");
            $("#del-all-form").attr('action', url);
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
