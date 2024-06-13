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
            </div>
            <hr>
            <div class="text-center">
                <div class="row">
                    <div class="col-lg-12 my-6">
                        <img width="500px" src="{{asset('common/assets/images/logo.png')}}" alt=""/>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
    </div>
    <!-- /.row-->
@endsection
@push('footer-scripts')

@endpush
