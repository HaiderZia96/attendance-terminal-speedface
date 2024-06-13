@extends('manager.layouts.app')
@section('page_title')
    {{(!empty($page_title) && isset($page_title)) ? $page_title : ''}}
@endsection
@push('head-scripts')
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('manager/select2/dist/css/select2-bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('manager/cropper/cropper.min.css') }}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <style>
        .custom-file-button input[type=file] {
            margin-left: -2px !important;
        }
        img {
            max-width: 100%;
        }
        .cropper-point.point-se {
            height: 5px !important;
            width: 5px !important;
        }
    </style>
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
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="employee_code">Employee Code *</label>
                                    <input type="text" class="form-control @error('employee_code') is-invalid @enderror"
                                           name="employee_code"
                                           id="employee_code" placeholder="Employee Code" value="{{old('employee_code')}}">
                                    @error('employee_code')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           id="name" placeholder="Name" value="{{old('name')}}">
                                    @error('name')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="campus">Campus</label>
                                    <input type="text" class="form-control @error('campus') is-invalid @enderror"
                                           name="campus"
                                           id="campus" placeholder="Campus" value="{{old('campus')}}">
                                    @error('campus')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="designation">Designation *</label>
                                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                           name="designation"
                                           id="designation" placeholder="Designation" value="{{old('designation')}}">
                                    @error('designation')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="department">Department *</label>
                                    <input type="text" class="form-control @error('department') is-invalid @enderror"
                                           name="department"
                                           id="department" placeholder="Department" value="{{old('department')}}">
                                    @error('department')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="select2-options-status form-control @error('status') is-invalid @enderror" name="status">
                                        <option value="">Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">In-Active</option>
                                    </select>
                                    @error('status')
                                    <strong class="text-danger">{{ $message }}</strong>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6" id="image_input">
                                <div class="image-edit custom-file-button mb-3">
                                    <label class="form-label" for="image">Image *</label>
                                    <input type="file" id="image-cropper" accept=".png, .jpg, .jpeg" name="image"
                                           class="form-control wizard-required image-cropper @error('image') is-invalid @enderror">
                                    @error('image')
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
            {{-- Modal --}}
            <div class="modal fade bd-example-modal-lg imageCrop" id="model" tabindex="-1" role="dialog"
                 aria-labelledby="cropperModalLabel " aria-hidden="true" data-coreui-backdrop="static">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-1 m-0 px-3" style="background-color: #a51313">
                            <h5 class="modal-title fw-bold " id="cropperModal" style="color: white">Profile Image</h5>
                            <button type="button" class="close btn-close" id="reset-image" data-coreui-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 m-0">
                            <div class="img-container">
                                <div class="row pe-4">
                                    <div class="col-md-12">
                                        <img class="cropper-image" id="previewImage" src="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer p-2 m-0">
                            <button type="button" class="btn  btn-sm crop" id="cropImage" style="background-color: #a51313; color: white">Crop
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End: Modal --}}
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
                        //Select Status
                        $('.select2-options-status').select2({
                            theme: "bootstrap5",
                            placeholder: 'Select Status',
                        });

                    });


                </script>
                <script>

                    $(document).ready(function () {
                        // Image Cropper
                        $('#reset-image').on('click', function() {
                            $('.image-cropper').val('');
                        });
                        var $modal = $('.imageCrop');
                        var image = document.getElementById('previewImage');
                        var cropper;
                        $("body").on("change", ".image-cropper", function (e) {
                            e.preventDefault();
                            var files = e.target.files;
                            var done = function (url) {
                                image.src = url;
                                $modal.modal('show');
                            };
                            var reader;
                            var file;
                            var URL;
                            if (files && files.length > 0) {
                                file = files[0];
                                if (URL) {
                                    done(URL.createObjectURL(file));
                                } else if (FileReader) {
                                    reader = new FileReader();
                                    reader.onload = function (e) {
                                        done(reader.result);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        });
                        $modal.on('shown.coreui.modal', function () {
                            cropper = new Cropper(image, {
                                dragMode: 'move',
                                aspectRatio: 500 / 500,
                                viewMode: 1,
                                autoCropArea: 0.75,
                                restore: false,
                                guides: false,
                                center: false,
                                highlight: false,
                                cropBoxMovable: false,
                                cropBoxResizable: false,
                                toggleDragModeOnDblclick: false,
                            });
                        }).on('hidden.coreui.modal', function () {
                            cropper.destroy();
                            cropper = null;
                        });
                        $("body").on("click", "#cropImage", function () {
                            canvas = cropper.getCroppedCanvas({
                                width: 500,
                                height: 500,
                            });
                            canvas.toBlob(function (blob) {
                                // Crop & Convert it to image
                                const imageInput = document.getElementById("image_input")
                                var x = document.createElement("INPUT");
                                x.setAttribute("type", "file");
                                x.setAttribute("name", "cropper_image");
                                x.classList.add("d-none");
                                imageInput.appendChild(x);
                                const file = new File([blob], 'image.jpg', {type: 'image/jpeg'})
                                const dataTransfer = new DataTransfer()
                                dataTransfer.items.add(file)
                                x.files = dataTransfer.files
                                $modal.modal('hide');
                            });
                        });
                    });
                </script>

    @endpush
