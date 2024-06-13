@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')

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
                    <label class="form-label" for="key">Key</label>
                    <input type="text" disabled class="form-control @error('key') is-invalid @enderror" name="key" id="key" placeholder="Name" value="{{(isset($data) ? $data->key : old('key'))}}">
                    @error('key')
                    <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="value">Value</label>
                    <input type="text" disabled class="form-control @error('value') is-invalid @enderror" name="value" id="value" placeholder="Value" value="{{(isset($data) ? $data->value : old('value'))}}">
                    @error('value')
                    <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
{{--                <div class="col-12">--}}
{{--                    <div class="mb-3">--}}
{{--                        <label class="form-label" for="make_attempt">Make Attempt</label>--}}
{{--                        <select class="select2-options-make_attempt form-control @error('make_attempt') is-invalid @enderror" name="make_attempt" disabled>--}}
{{--                            <option value="">Please Select</option>--}}
{{--                            <option value="1" {{(isset($data->make_attempt)) && $data->make_attempt == 1  ? 'selected' : ''}}>Ready to Attempt</option>--}}
{{--                            <option value="0" {{(isset($data->make_attempt)) && $data->make_attempt == 0  ? 'selected' : ''}}>Not Ready to Attempt</option>--}}
{{--                        </select>--}}
{{--                        @error('make_attempt')--}}
{{--                        <strong class="text-danger">{{ $message }}</strong>--}}
{{--                        @enderror--}}
{{--                    </div>--}}
{{--                </div>--}}
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
    {{-- Toastr : Script : Start --}}
    @if(Session::has('messages'))
        <script>
            noti({!! json_encode((Session::get('messages'))) !!});
        </script>
    @endif
    {{-- Toastr : Script : End --}}
@endpush
