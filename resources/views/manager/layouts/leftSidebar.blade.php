<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <h5 class="mb-0">{{!empty(session('currentModule.0')) ? ucwords(session('currentModule.0')) : ''}}</h5>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/dashboard') ? 'active' : '' }}" href="{{route('manager.dashboard')}}">
                <i class="nav-icon cil-speedometer"></i> Dashboard
            </a>
        </li>
        @can('manager_attendance_config-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/config') ? 'active' : '' }}" href="{{route('manager.config.index')}}">
                <i class="nav-icon cil-speedometer"></i> Config
            </a>
        </li>
        @endcan
        @can('manager_attendance_attendance-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/attendance') ? 'active' : '' }}" href="{{route('manager.attendance.index')}}">
                <i class="nav-icon cil-speedometer"></i> Attendance
            </a>
        </li>
        @endcan
        @can('manager_attendance_employee-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/employee') ? 'active' : '' }}" href="{{route('manager.employee.index')}}">
                <i class="nav-icon cil-speedometer"></i> Employee
            </a>
        </li>
        @endcan
        @can('manager_attendance_get-employee-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/get-employees') ? 'active' : '' }}" href="{{route('manager.get-employees.index')}}">
                <i class="nav-icon cil-speedometer"></i> Get Employee
            </a>
        </li>
        @endcan
        @can('manager_attendance_get-employee-history-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/get-employee-histories') ? 'active' : '' }}" href="{{route('manager.get-employee-histories.index')}}">
                <i class="nav-icon cil-speedometer"></i> Get Employee Log
            </a>
        </li>
        @endcan
        @can('manager_attendance_set-attendance-histories-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/set-attendance-histories') ? 'active' : '' }}" href="{{route('manager.set-attendance-histories.index')}}">
                <i class="nav-icon cil-speedometer"></i> Set Attendance Log
            </a>
        </li>
        @endcan
        @can('manager_attendance_screen-list')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('manager/screen') ? 'active' : '' }}" href="{{route('manager.screen.index')}}">
                <i class="nav-icon cil-speedometer"></i> Screen
            </a>
        </li>
        @endcan
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link {{ request()->is('manager/screen-ip') ? 'active' : '' }}" href="{{route('manager.screen-ip.index')}}">--}}
{{--                <i class="nav-icon cil-speedometer"></i> Screen IP--}}
{{--            </a>--}}
{{--        </li>--}}
    </ul>
    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div>
