@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endpush
@section('content')
    <div class="card mt-3">
        <div class="card-body">
            {{-- Start: Page Content --}}
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0">{{(!empty($p_title) && isset($p_title)) ? $p_title : ''}}</h4>
                    <div
                        class="small text-medium-emphasis">{{(!empty($p_summary) && isset($p_summary)) ? $p_summary : ''}}</div>
                </div>
                <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with buttons">
                    {{--                    @can('manager_event_category-list')--}}
                    <a href="{{(!empty($url) && isset($url)) ? $url : ''}}"
                       class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    {{--                    @endcan--}}
                </div>
            </div>
            <hr>
            {{-- Start: Form --}}
            <div>
                <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                {{--                            <div class="mb-3">--}}
                                {{--                                <label class="form-label" for="employee_code">Employee Code *</label>--}}
                                {{--                                <select class="select2-options-employee-id form-control @error('employee_code') is-invalid @enderror" name="employee_code"></select>--}}
                                {{--                                @error('employee_code')--}}
                                {{--                                <strong class="text-danger">{{ $message }}</strong>--}}
                                {{--                                @enderror--}}
                                {{--                            </div>--}}
                                <div class="mb-3">
                                    <label class="form-label" for="employee_code">Employee Code *</label>
                                    <input  type="text" class="form-control @error('employee_code') is-invalid @enderror"
                                           name="employee_code"
                                           id="employee_code" placeholder="Employee Code" value="{{(!empty($data->employee_code) && isset($data->employee_code)) ? $data->employee_code : old('employee_code')}}">
                                    @error('employee_code')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="punch_time">Punch Time *</label>
                                    <input  type="datetime-local" class="form-control @error('punch_time') is-invalid @enderror"
                                           name="punch_time"
                                           id="punch_time" placeholder="Punch Time"  value="{{(!empty($data->punch_time) && isset($data->punch_time)) ? $data->punch_time : old('2024-01-15T08:00:00')}}" step="1">
                                    @error('punch_time')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="upload_time">Upload Time *</label>
                                    <input  type="datetime-local" class="form-control @error('upload_time') is-invalid @enderror"
                                           name="upload_time"
                                           id="upload_time" placeholder="Upload Time" value="{{(!empty($data->upload_time) && isset($data->upload_time)) ? $data->upload_time : old('2024-01-15T08:00:00')}}" step="1">
                                    @error('upload_time')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="area_alias">Area Alias</label>
                                    <input  type="text" class="form-control @error('area_alias') is-invalid @enderror"
                                           name="area_alias"
                                           id="area_alias" placeholder="Area Alias" value="{{(!empty($data->area_alias) && isset($data->area_alias)) ? $data->area_alias : old('area_alias')}}">
                                    @error('area_alias')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="terminal_sn">Terminal Serial No.</label>
                                    <input  type="text" class="form-control @error('terminal_sn') is-invalid @enderror"
                                           name="terminal_sn"
                                           id="terminal_sn" placeholder="Terminal Serial No." value="{{(!empty($data->terminal_sn) && isset($data->terminal_sn)) ? $data->terminal_sn : old('terminal_sn')}}">
                                    @error('terminal_sn')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="terminal_alias">Terminal Alias</label>
                                    <input  type="text" class="form-control @error('terminal_alias') is-invalid @enderror"
                                           name="terminal_alias"
                                           id="terminal_alias" placeholder="Terminal Alias." value="{{(!empty($data->terminal_alias) && isset($data->terminal_alias)) ? $data->terminal_alias : old('terminal_alias')}}">
                                    @error('terminal_alias')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="in_out">In/Out *</label>
                                    <select class="select2-options-in-out form-control @error('in_out') is-invalid @enderror" name="in_out">
                                        <option value="">Select In/Out</option>
                                        <option value="1" {{(isset($data->in_out)) && $data->in_out == 1  ? 'selected' : ''}}>In</option>
                                        <option value="0" {{(isset($data->in_out)) && $data->in_out == 0  ? 'selected' : ''}}>Out</option>
                                    </select>
                                    @error('in_out')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                </form>
            </div>
            {{-- End: Form --}}

            {{-- End: Modal --}}
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
            @endsection
            @push('footer-scripts')
                <script src="{{ asset('manager/select2/dist/js/select2.js') }}"></script>
                <script src="{{ asset('manager/cropper/cropper.js') }}"></script>
                <script>
                    $(document).ready(function() {
                        let employee=[{
                            id: "{{$data['employee_id']}}",
                            text: "{{$data['employee_name']}}",
                        }];
                        $(".select2-options-employee-id").select2({
                            data: employee,
                            theme: "bootstrap5",
                            placeholder: 'Select Employee',
                        });
                        //Select User
                        $('.select2-options-employee-id').select2({
                            theme: "bootstrap5",
                            placeholder: 'Select Employee',
                            allowClear:true,
                            ajax: {
                                url: '{{route('manager.get.attendance-employee-select')}}',
                                dataType: 'json',
                                delay: 250,
                                type: 'GET',
                                data: function (params){
                                    var query = {
                                        q: params.term,
                                        type: 'public',
                                        _token: '{{csrf_token()}}'
                                    }
                                    return query;
                                },
                                processResults: function (data) {
                                    return {
                                        results:  $.map(data, function (item) {
                                            return {
                                                id: item.id,
                                                text: item.name
                                            }
                                        })
                                    };
                                },
                                cache: true
                            }
                        }).trigger('change.select2');

                        //Select Punch State
                        $('.select2-options-in-out').select2({
                            theme: "bootstrap5",
                            placeholder: 'Select In/Out',
                        });

                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });
                    })

                </script>
                <script>
                    $('#ip_address').mask('099.099.099.099');
                </script>

    @endpush
