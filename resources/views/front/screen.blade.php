@extends('front.layouts.app')
@section('content')
    @push('head-scripts')
        <link href="{{asset('front/coreui/css/datatables.bootstrap.css')}}" rel="stylesheet">

        <style>
            .emp-history tbody tr td img{
                width: 100px;
            }
            .emp-history thead tr th{
                color: white;
                text-align: center!important;
            }
            .emp-history tbody tr td{

                text-align: center!important;
            }
        </style>
    @endpush
    {{-- Default Card Start --}}
    <div class="default-detail d-none" id="default-detail">
        <div class="card border-1">
            <div class="card-body text-center">
                <div class="row pt-2 g-0">
                    <div class="col-md-2">
                        <img src="{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}" class="img-thumbnail url_profile_active  p-2" id="url_df__profile_image" >
                    </div>
                    <div class="col-md-7">
                        <div class="active-detail">
                            <div class="d-flex">
                                <h5 class="card-title mt-1">Name:</h5>
                                <p class="card-value ms-4 mb-0 mt-2" id="df_name">User</p>
                            </div>
                            <div class="d-flex">
                                <h5 class="card-title mt-1">Code:</h5>
                                <p class="card-value ms-4 mb-0 mt-2" id="df_emp_code">000000</p>
                            </div>
                            <div class="d-flex">
                                <h5 class="card-title mt-1">Campus:</h5>
                                <p class="card-value ms-4 mb-0 mt-2" id="df_campus">User Campus</p>
                            </div>
                        </div></div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-center">
                            <div class="vr">
                            </div>
                            <div class="in_out ms-5">
                                <img src="{{asset('/front/coreui/assets/img/out-1.svg')}}" class=" in_out_img p-0"  style="width: 160px; margin-top: -30px" id="url_df_in_out">
                                <h5 class="text-center" style="font-size: 40px; font-weight: 900;margin-top: -20px "  id="df_attendance_date">0000-00-00</h5>
                                <h5 class="text-center" style="font-size: 40px; font-weight: 900;margin-top: -10px "  id="df_attendance_time">00:00:00</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Default Card End --}}
    {{-- Employee Card Start --}}
    <div class="emp-detail d-none" id="emp-detail">
        <div class="card border-1">
            <div class="card-body text-center">
                <div class="row pt-2 g-0">
                    <div class="col-md-2">
                        <img src="{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}" class="img-thumbnail url_profile_active  p-2" id="url_profile_image" >
                    </div>
                    <div class="col-md-7">
                        <div class="active-detail">
                        <div class="d-flex">
                            <h5 class="card-title mt-1">Name:</h5>
                            <p class="card-value ms-4 mb-0 mt-2" id="name">User</p>
                        </div>
                        <div class="d-flex">
                            <h5 class="card-title mt-1">Code:</h5>
                            <p class="card-value ms-4 mb-0 mt-2" id="emp_code">000000</p>
                        </div>
                        <div class="d-flex">
                            <h5 class="card-title mt-1">Design:</h5>
                            <p class="card-value ms-4 mb-0 mt-2" id="designation">User Designation</p>
                        </div>
                        <div class="d-flex">
                            <h5 class="card-title mt-1">Dept:</h5>
                            <p class="card-value ms-4 mb-0 mt-2" id="department">User Department</p>
                        </div>
                    </div></div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-center">
                            <div class="vr">
                            </div>
                            <div class="in_out ms-5">

                                <img src="{{asset('/front/coreui/assets/img/out-1.svg')}}" class=" in_out_img p-0"  style="width: 160px; margin-top: -30px" id="url_in_out">
                                <h5 class="text-center" style="font-size: 40px; font-weight: 900;margin-top: -20px "  id="attendance_date">0000-00-00</h5>
                                <h5 class="text-center" style="font-size: 40px; font-weight: 900;margin-top: -10px "  id="attendance_time">00:00:00</h5>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Employee Card End --}}
    {{-- Student Card Start --}}
    <div class="std-detail d-none" id="std-detail">
        <div class="card border-1">
            <div class="card-body text-center">
                <div class="row pt-2 g-0">


                    <div class="col-md-2">

                        <img src="{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}" class="img-thumbnail url_profile_active  p-2" id="url_std_profile_image" >
                    </div>
                    <div class="col-md-7">
                        <div class="active-detail">
                            <div class="d-flex">
                                <h5 class="card-title mt-1">Name:</h5>
                                <p class="card-value ms-4 mb-0 mt-2" id="std_name">User</p>
                            </div>
                            <div class="d-flex">
                                <h5 class="card-title mt-1">Code:</h5>
                                <p class="card-value ms-4 mb-0 mt-2" id="std_code">000000</p>
                            </div>
                            <div class="d-flex">
                                <h5 class="card-title mt-1">Campus:</h5>
                                <p class="card-value ms-4 mb-0 mt-2" id="std_campus">User Campus</p>
                            </div>

                        </div></div>
                    <div class="col-md-3">

                        <div class="d-flex justify-content-center">
                            <div class="vr">
                            </div>
                            <div class="in_out ms-5">

                                <img src="{{asset('/front/coreui/assets/img/out-1.svg')}}" class=" in_out_img p-0"  style="width: 160px; margin-top: -30px" id="url_std_in_out">
                                <h5 class="text-center" style="font-size: 40px; font-weight: 900;margin-top: -20px "  id="std_attendance_date">0000-00-00</h5>
                                <h5 class="text-center" style="font-size: 40px; font-weight: 900;margin-top: -10px "  id="std_attendance_time">00:00:00</h5>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Student Card End --}}
    {{-- History Table Tart --}}
    <div class="emp-history card my-3">
        <div class="table-responsive">
        <table id="empTable" class="table table-striped table-bordered mb-0" style="width:100%">
            <thead class="table-dark bg-dark">
            <tr>
                <th class="image-col">Image</th>
                <th class="emp-col">Code</th>
                <th class="name-col">Name</th>
                <th class="att-col">Attendance Time</th>
                <th class="in-out-col">In/Out</th>
            </tr>
            </thead>
            <tbody class="text-center">

            </tbody>

        </table>
        </div>
    </div>
    {{-- History Table End --}}
@endsection
@push('footer-scripts')
    <script src="{{asset('front/js/datatables.js')}}"></script>
    <script src="{{asset('front/js/datatables.bootstrap.js')}}"></script>
    <script>

        $(document).ready(function () {


            function ajaxCall(){
                $.ajax({
                    type: "GET",
                    "url":"{{route('screenUuid', $uuid)}}",
                    success: function (response) {
                        $r =  response['data']
                        console.log($r);
                        switchScreen($r);

                        if ( $r['active_profile_1']['role_status'] === 'E'){
                            refreshscreen($r);
                        }
                        else if ( $r['active_profile_1']['role_status'] === 'S'){

                            refreshscreenstd($r);
                        }
                        else {
                            refreshscreendf($r);
                        }

                        // Datatable
                        $('#empTable').DataTable({
                            data: response['data']['profiles_history'],
                            punch :  response['data'],
                            sDom: 't',
                            bDestroy: true,
                            columns: [

                                { data: 'image'},
                                { data: 'attendance_employee_code' },
                                { data: 'name' },
                                { data: 'punch_time' },
                                { data: null}
                            ],
                            columnDefs:[
                                {
                                    targets: 0,
                                    orderable: false,
                                    searchable: false,
                                    width: '100px',
                                    render: function ( data, type, row, meta ) {
                                        var dummyImage = "{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}";

                                        if(data !== null){
                                            if (data || data.length !== 0){

                                                let active_profile_history_img = "{{ asset(':url') }}";
                                                active_profile_history_img = active_profile_history_img.replace(':url', data);

                                                return '<img src="' + active_profile_history_img + '" height="120" width="120" class="img-thumbnail-dt"/>';
                                            } else{
                                                return '<img src="' + dummyImage + '" height="120" width="120" class="img-thumbnail-dt"/>';
                                            }
                                        }
                                        return '<img src="' + dummyImage + '" height="120" width="120" class="img-thumbnail-dt"/>';

                                    }
                                },
                                {
                                    targets: 3, // Replace 0 with the index of the column containing dates
                                    "render": function ( data, type, row, meta ) {
                                        var dateData = new Date(data);
                                        var date = dateData.toLocaleDateString();
                                        var time = dateData.toLocaleTimeString();

                                        return date
                                            + '<br>' + time;
                                    }
                                },
                                {
                                    targets: -1,
                                    orderable: false,
                                    searchable: false,
                                    width: '100px',
                                    visible:false,
                                    render: function ( data, type, row, meta ) {
                                        var outImage ='{{asset('/front/coreui/assets/img/out-1.svg')}}';
                                        var inImage ='{{asset('/front/coreui/assets/img/in-1.svg')}}';
                                        if (data.in_out == 0){
                                            return '<img src="' + outImage + '" height="120" width="120" alt=""/>';
                                        }
                                        else if(data.in_out == 1){
                                            return '<img src="' + inImage + '" height="120" width="120" alt=""/>';
                                        }
                                        else {
                                            return 'Image Not Found.';
                                        }
                                    }
                                }
                            ]
                        });

                    },
                    error: function (response) {
                        alert(response.responseText);
                    }
                });
            }

            function refreshscreen(profile) {

                // set active_profile_1(profile)
                setActiveProfile1(profile);
                // // push_to_history(profile) // at top
                // pushToHistory(profile);
                // // set_last_profile(profile);
                // setLastProfile(profile);
            };

            function refreshscreenstd(profile) {

                // set active_profile_1 Std(profile)
                setStdActiveProfile1(profile);

            };
            function refreshscreendf(profile) {

                // set active_profile_1 Std(profile)
                setDfActiveProfile1(profile);

            };

            // ajaxCall();

             window.setInterval(function(){
            //      $.get("http://attendance_terminal_zkteco_speedface.test/attendance-test", function(data, status){
            //
            //      });
            //      $.get("http://attendance_terminal_zkteco_speedface.test/attendance-erp-test", function(data, status){
            //
            //      });
            //      $.get("http://attendance_terminal_zkteco_speedface.test/employee-test", function(data, status){
            //
            //      });
            //
            //     // call your function here
                 ajaxCall();
            //
            }, 3000);

        });

        function setActiveProfile1(profile){

            // Set Employee Details
            $('#name').text(profile['active_profile_1']['name'])
            $('#emp_code').text(profile['active_profile_1']['attendance_employee_code'])
            $('#designation').text(profile['active_profile_1']['designation'])
            $('#department').text(profile['active_profile_1']['department'])

            // Set Active Profile
            let dummyImage = "{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}";
            $("#url_profile_image").attr("src",dummyImage);
            if (profile['active_profile_1']['image'] || profile['active_profile_1']['image'].length !== 0){
                let active_profile_1_img = "{{ asset(':img') }}";
                active_profile_1_img = active_profile_1_img.replace(':img', profile['active_profile_1']['image']);
                $("#url_profile_image").attr("src", active_profile_1_img);


            }

            // Set IN/OUT STATUS
            var outImage ='{{asset('/front/coreui/assets/img/out-1.svg')}}';
            var inImage ='{{asset('/front/coreui/assets/img/in-1.svg')}}';
            if (profile['active_profile_1']['in_out'] == 0){
                $("#url_in_out").attr("src",outImage);
            }else if(profile['active_profile_1']['in_out'] == 1){
                $("#url_in_out").attr("src",inImage);
            }

            // Set Punch Status
            var dateDataActiveProfile = new Date(profile['active_profile_1']['punch_time']);
            var dateActiveProfile = dateDataActiveProfile.toLocaleDateString();
            var timeActiveProfile = dateDataActiveProfile.toLocaleTimeString();

            $('#attendance_date').text(dateActiveProfile);
            $('#attendance_time').text(timeActiveProfile);

            // Set Card Border Color
            borderColor(profile);
        }

        {{--function pushToHistory(profile){--}}

        {{--    row = $("<tr></tr>");--}}
        {{--    let URL = "{{ asset(':img') }}";--}}
        {{--    URL = URL.replace(':img', profile['active_profile_1']['image']);--}}
        {{--    col1 = $("<td>"+ "<img src=" +  URL + ">" +"</td>");--}}
        {{--    col2 = $("<td>"+ profile['active_profile_1']['attendance_employee_code'] +"</td>");--}}
        {{--    col3 = $("<td>"+ profile['active_profile_1']['attendance_time'] +"</td>");--}}
        {{--    col4 = $("<td>"+  profile['active_profile_1']['in_out'] +"</td>");--}}
        {{--    row.append(col1 ,col2,col3,col4).prependTo("#empTable");--}}
        {{--}--}}

        {{--function setLastProfile(profile){--}}
        {{--    let URL = "{{ asset(':img') }}";--}}
        {{--    URL = URL.replace(':img', profile['active_profile_1']['image']);--}}
        {{--    $lastProfile = [--}}

        {{--        $url_profile_image = URL,--}}
        {{--        $emp_code = profile['active_profile_1']['attendance_employee_code'],--}}
        {{--        $attendance_time = profile['active_profile_1']['attendance_time'],--}}
        {{--        $url_in_out =profile['active_profile_1']['in_out'],--}}
        {{--    ]--}}

        {{--    return $lastProfile;--}}
        {{--}--}}

        // Student Card Active Profile

        function setStdActiveProfile1(profile){

            // Set Student Details
            $('#std_name').text(profile['active_profile_1']['name'])
            $('#std_code').text(profile['active_profile_1']['attendance_employee_code'])
            $('#std_campus').text(profile['active_profile_1']['campus'])


            // Set Active Profile
            let dummyImage = "{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}";
            $("#url_std_profile_image").attr("src",dummyImage);
            if (profile['active_profile_1']['image'] || profile['active_profile_1']['image'].length !== 0){
                let active_profile_1_img = "{{ asset(':img') }}";
                active_profile_1_img = active_profile_1_img.replace(':img', profile['active_profile_1']['image']);
                $("#url_std_profile_image").attr("src",active_profile_1_img);
            }

            // Set IN/OUT STATUS
            var outImage ='{{asset('/front/coreui/assets/img/out-1.svg')}}';
            var inImage ='{{asset('/front/coreui/assets/img/in-1.svg')}}';
            if (profile['active_profile_1']['in_out'] == 0){
                $("#url_std_in_out").attr("src",outImage);
            }else if(profile['active_profile_1']['in_out'] == 1){
                $("#url_std_in_out").attr("src",inImage);

            }

            // Set Punch Status
            var dateDataActiveProfile = new Date(profile['active_profile_1']['punch_time']);
            var dateActiveProfile = dateDataActiveProfile.toLocaleDateString();
            var timeActiveProfile = dateDataActiveProfile.toLocaleTimeString();

            $('#std_attendance_date').text(dateActiveProfile);
            $('#std_attendance_time').text(timeActiveProfile);

            // Set Card Border Color
            borderColor(profile);
        }

        function setDfActiveProfile1(profile){

            // Set Default Details
            $('#df_name').text(profile['active_profile_1']['name'])
            $('#df_emp_code').text(profile['active_profile_1']['attendance_employee_code'])
            $('#df_campus').text(profile['active_profile_1']['campus'])


            // Set Active Profile
            let dummyImage = "{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}";
            $("#url_df_profile_image").attr("src",dummyImage);
            if (!profile['active_profile_1']['image'] || profile['active_profile_1']['image'].length === 0){
                $("#url_df_profile_image").attr("src",profile['active_profile_1']['image']);
            }

            // Set IN/OUT STATUS
            var outImage ='{{asset('/front/coreui/assets/img/out-1.svg')}}';
            var inImage ='{{asset('/front/coreui/assets/img/in-1.svg')}}';
            if (profile['active_profile_1']['in_out'] == 0){
                $("#url_df_in_out").attr("src",outImage);
            }else if(profile['active_profile_1']['in_out'] == 1){
                $("#url_df_in_out").attr("src",inImage);

            }

            // Set Punch Status
            var dateDataActiveProfile = new Date(profile['active_profile_1']['punch_time']);
            var dateActiveProfile = dateDataActiveProfile.toLocaleDateString();
            var timeActiveProfile = dateDataActiveProfile.toLocaleTimeString();

            $('#df_attendance_date').text(dateActiveProfile);
            $('#df_attendance_time').text(timeActiveProfile);

            // Set Card Border Color
            borderColor(profile);
        }



        function borderColor(profile){
            if (profile['active_profile_1']['in_out'] == 0){
                $("#emp-detail").css("border-bottom", "#d15e5f solid 7px");
                $("#std-detail").css("border-bottom", "#d15e5f solid 7px");

            }else if(profile['active_profile_1']['in_out'] == 1){
                $("#emp-detail").css("border-bottom", "#669d43 solid 7px ");
                $("#std-detail").css("border-bottom", "#669d43 solid 7px ");
            }
            else{
                $("#emp-detail").css("border-bottom", "#d15e5f solid 7px");
                $("#std-detail").css("border-bottom", "#d15e5f solid 7px");
            }
        }

        function switchScreen(profile){

            showDefaultDetail();
            if((profile['active_profile_1']['role_status'] === 'E')){
                showEmpDetail();
            }
            if((profile['active_profile_1']['role_status'] === 'S')){
                showStdDetail();
            }
        }


        // Screen tabs Switching
        function showEmpDetail(){
            hideDefaultDetail();
            hideStdDetail();
            $("#emp-detail").removeClass('d-none');
        }
        function hideEmpDetail(){
            $("#emp-detail").addClass('d-none');
        }

        function showStdDetail(){
            hideEmpDetail();
            hideDefaultDetail();
            $("#std-detail").removeClass('d-none');
        }
        function hideStdDetail(){
            $("#std-detail").addClass('d-none');
        }

        function showDefaultDetail(){
            hideStdDetail();
            hideEmpDetail()
            $("#default-detail").removeClass('d-none')
        }
        function hideDefaultDetail(){
            $("#default-detail").addClass('d-none');
        }

    //     On image Loading Error
        $(".img-thumbnail").on("error", function(){
            let dummyImage = "{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}";
            $(this).attr('src', dummyImage);
        });
        $(".img-thumbnail-dt").on("error", function(){
            let dummyImage = "{{asset('/front/coreui/assets/img/avatars/dummy-user.png')}}";
            $(this).attr('src', dummyImage);
        });

    </script>


@endpush
