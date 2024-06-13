@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
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
                    {{--                    @can('manager_user-management_config-list')--}}
                    <a href="{{(!empty($url) && isset($url)) ? $url : ''}}" class="btn btn-sm btn-primary">{{(!empty($url_text) && isset($url_text)) ? $url_text : ''}}</a>
                    {{--                    @endcan--}}
                </div>
            </div>
            <hr>
            {{-- Start: Form --}}
            <form method="{{$method}}" action="{{$action}}" enctype="{{$enctype}}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="ip">IP Address *</label>
                    <input  type="text" class="form-control @error('ip') is-invalid @enderror"
                           name="ip"
                           id="ip" placeholder="IP Address" value="{{(!empty($data->ip) && isset($data->ip)) ? $data->ip : old('ip')}}">
                    @error('ip')
                    <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="type">Type *</label>
                    <select class="select2-options-screen-type form-control @error('type') is-invalid @enderror" name="type">
                        <option value="2" {{(isset($data->type)) && $data->type == 2  ? 'selected' : ''}}>Auto</option>
                        <option value="1" {{(isset($data->type)) && $data->type == 1  ? 'selected' : ''}}>In</option>
                        <option value="0" {{(isset($data->type)) && $data->type == 0  ? 'selected' : ''}}>Out</option>
                    </select>
                    @error('type')
                    <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
{{--                <div class="mb-3">--}}
{{--                    <label class="form-label" for="screen_id">Screen *</label>--}}
{{--                    <select  class="select2-options-screen-id form-control @error('screen_id') is-invalid @enderror" name="screen_id"></select>--}}
{{--                    @error('screen_id')--}}
{{--                    <strong class="text-danger">{{ $message }}</strong>--}}
{{--                    @enderror--}}
{{--                </div>--}}
                                <button type="submit" class="btn btn-sm btn-success">Submit</button>
            </form>
            {{-- End: Form --}}
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
            {{-- End: Page Content --}}
        </div>
    </div>
@endsection
@push('footer-scripts')
    <script src="{{ asset('manager/select2/dist/js/select2.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //Select Type
            $('.select2-options-screen-type').select2({
                theme: "bootstrap5",
                placeholder: 'Select Type',
            });

            {{--let screen=[{--}}
            {{--    id: "{{$data['screen_id']}}",--}}
            {{--    text: "{{$data['screen_name']}}",--}}
            {{--}];--}}
            {{--$(".select2-options-screen-id").select2({--}}
            {{--    data: screen,--}}
            {{--    theme: "bootstrap5",--}}
            {{--    placeholder: 'Select Screen',--}}
            {{--});--}}
            {{--//Select Screen--}}
            {{--$('.select2-options-screen-id').select2({--}}
            {{--    theme: "bootstrap5",--}}
            {{--    placeholder: 'Select Screen',--}}
            {{--    allowClear: true,--}}
            {{--    ajax: {--}}
            {{--        url: '{{route('manager.get.screen-select')}}',--}}
            {{--        dataType: 'json',--}}
            {{--        delay: 250,--}}
            {{--        type: 'GET',--}}
            {{--        data: function (params){--}}
            {{--            var query = {--}}
            {{--                q: params.term,--}}
            {{--                type: 'public',--}}
            {{--                _token: '{{csrf_token()}}'--}}
            {{--            }--}}
            {{--            return query;--}}
            {{--        },--}}
            {{--        processResults: function (data) {--}}
            {{--            return {--}}
            {{--                results:  $.map(data, function (item) {--}}
            {{--                    return {--}}
            {{--                        id: item.id,--}}
            {{--                        text: item.name--}}
            {{--                    }--}}
            {{--                })--}}
            {{--            };--}}
            {{--        },--}}
            {{--        cache: true--}}
            {{--    }--}}
            {{--}).trigger('change.select2')--}}
            {{--$(document).on('select2:open', () => {--}}
            {{--    document.querySelector('.select2-search__field').focus();--}}
            {{--});--}}
        })
    </script>
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush
