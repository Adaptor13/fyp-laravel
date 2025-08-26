<!-- Menu Navigation starts -->
<nav class="dark-sidebar">
    <div class="app-logo">
        <a class="logo d-inline-block" href="#">
            <img src="{{ asset('../assets/images/logo/sinda.png') }}" alt="#" class="dark-logo">
            <img src="{{ asset('../assets/images/logo/sinda.png') }}" alt="#" class="light-logo">
        </a>

        <span class="bg-light-light toggle-semi-nav">
            <i class="ti ti-chevrons-right f-s-20"></i>
        </span>
    </div>

    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">

            <!-- Dashboard -->
            <li class="menu-title">
                <span>Dashboard</span>
            </li>

            <!-- Cases -->
            <li>
                <a data-bs-toggle="collapse" href="#caseManagement" aria-expanded="false">
                    <i class="ti ti-briefcase"></i>
                    Cases
                </a>
                <ul class="collapse" id="caseManagement">
                    <li><a href="{{ route('cases.index') }}"><i class="ti ti-list"></i> View Cases</a></li>
                    <li><a href=""><i class="ti ti-history"></i> Case History</a></li>
                    <li><a href=""><i class="ti ti-user-check"></i> Assign Cases</a></li>
                </ul>
            </li>

            <!-- Users -->
            <li>
                <a data-bs-toggle="collapse" href="#userManagement" aria-expanded="false">
                    <i class="ti ti-users"></i>
                    Users
                </a>
                <ul class="collapse" id="userManagement">
                    <li><a href="{{ route('users.admins') }}"><i class="ti ti-shield"></i> Admins</a></li>
                    <li><a href="{{ route('users.public') }}"><i class="ti ti-user"></i> Public Users</a></li>
                    <li><a href="{{ route('users.social') }}"><i class="ti ti-id-badge"></i> Social Workers</a></li>
                    <li><a href="{{ route('users.law') }}"><i class="ti ti-target"></i> Law Enforcement</a></li>
                    <li><a href="{{ route('users.cwo') }}"><i class="ti ti-building"></i> Child Welfare Officers</a></li>
                    <li><a href="{{ route('users.health') }}"><i class="ti ti-stethoscope"></i> Healthcare Professionals</a></li>
                </ul>
            </li>

            <!-- Roles & Permissions -->
            <li>
                <a data-bs-toggle="collapse" href="#roleManagement" aria-expanded="false">
                    <i class="ti ti-lock"></i>
                    Roles & Permissions
                </a>
                <ul class="collapse" id="roleManagement">
                    <li><a href="#"><i class="ti ti-settings"></i> Manage Roles</a></li>
                    <li><a href="#"><i class="ti ti-key"></i> Assign Permissions</a></li>
                </ul>
            </li>

            <!-- Reports & Analytics -->
            <li>
                <a data-bs-toggle="collapse" href="#reportsAnalytics" aria-expanded="false">
                    <i class="ti ti-report-analytics"></i>
                    Reports & Analytics
                </a>
                <ul class="collapse" id="reportsAnalytics">
                    <li><a href="#"><i class="ti ti-chart-line"></i> Statistics</a></li>
                    <li><a href="#"><i class="ti ti-file-export"></i> Export Reports</a></li>
                </ul>
            </li>

            <!-- Communication -->
            <li>
                <a data-bs-toggle="collapse" href="#communication" aria-expanded="false">
                    <i class="ti ti-message-dots"></i>
                    Communication
                </a>
                <ul class="collapse" id="communication">
                    <li><a href="#"><i class="ti ti-mail"></i> Contact Queries</a></li>
                    <li><a href="#"><i class="ti ti-messages"></i> Secure Messaging</a></li>
                </ul>
            </li>

            <!-- Audit & Logs -->
            <li>
                <a data-bs-toggle="collapse" href="#auditLogs" aria-expanded="false">
                    <i class="ti ti-clipboard-list"></i>
                    Audit & Logs
                </a>
                <ul class="collapse" id="auditLogs">
                    <li><a href="#"><i class="ti ti-activity"></i> Activity Logs</a></li>
                    <li><a href="#"><i class="ti ti-server"></i> System Performance</a></li>
                </ul>
            </li>

        </ul>
    </div>


    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>

</nav>
<!-- Menu Navigation ends -->
