@extends('layout.master')
@section('title', 'Dashboard')
@section('css')

<!-- apexcharts css -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}">

<!-- slick css -->
<link rel="stylesheet" href="{{asset('assets/vendor/slick/slick.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/slick/slick-theme.css')}}">

<!-- glight css -->
<link rel="stylesheet" href="{{asset('assets/vendor/glightbox/glightbox.min.css')}}">

<!-- Data Table css -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/datatable/jquery.dataTables.min.css')}}">

<!-- vector map css -->
<link rel="stylesheet" href="{{asset('assets/vendor/vector-map/jquery-jvectormap.css')}}">

@endsection
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-lg-3">
            <div class="card equal-card">
                <div class="card-body card-image">
                    <div class="premiere-card">
                        <div class="">
                            <h6 class="mb-0 f-w-400 text-dark"> Current Subscription Plan</h6>
                            <h3 class="text-secondary text-nowrap f-s-24 f-w-600">Premiere <a href=""
                                    class="text-primary">Pro</a></h3>
                        </div>
                        <div class="premiere-image">
                            <img src="{{asset('assets/images/dashboard/analytics/02.png')}}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card analytice-front-card equal-card ">
                <div class="card-body p-0">
                    <div class="analytice-card p-4">
                        <span class="bg-primary h-35 w-35 d-flex-center b-r-50 position-absolute">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <div class="flex-grow-1 ms-5">
                            <h5 class="header-heading text-primary">Users</h5>
                            <p class="text-secondary mb-0">Last Update at 2 Dec </p>
                        </div>
                        <div>
                            <h4 class="header-heading">+89k</h4>
                        </div>
                    </div>
                    <div>
                        <div id="cardChart1"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card analytice-front-card equal-card ">
                <div class="card-body p-0">
                    <div class="analytice-card p-4">
                        <span class="bg-success h-35 w-35 d-flex-center position-absolute b-r-50">
                            <i class="fa-solid fa-line-chart fa-fw"></i>
                        </span>
                        <div class="flex-grow-1 ms-5">
                            <h5 class="header-heading text-success">Earnings</h5>
                            <p class="text-secondary mb-0">Last Update at 2 Dec </p>
                        </div>
                        <div>
                            <h4 class="header-heading">+$90</h4>
                        </div>
                    </div>
                    <div>
                        <div id="cardChart2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-primary equal-card collection-card">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between p-4">
                        <div>
                            <p class="header-heading f-s-16">Monthly Collection</p>
                        </div>
                        <div>
                            <span class="badge text-bg-success b-r-5">12.08%</span>
                        </div>
                    </div>
                    <div class="collection-card-content">
                        <div class="ms-4">
                            <p class="f-w-300 f-s-12 mb-0">Total This Month</p>
                            <h3 class="header-heading">$82,980</h3>
                        </div>
                        <div class="collection-chart">
                            <div id="collectionChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 visitors">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>Source Visitors Report</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="app-scroll table-responsive">
                        <table class="table table-bottom-border visitors-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="check-box">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmark outline-dark ms-2 "></span>
                                        </label>
                                    </th>
                                    <th class="col" scope="col">Visitor Name</th>
                                    <th class="col" scope="col">ID</th>

                                    <th scope="col">User</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th scope="col">Rate</th>
                                    <th class="col" scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            John Smith</p>
                                    </td>
                                    <td class="text-primary f-w-500">#012</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light">
                                                <img src="{{asset('assets/images/avtar/08.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-dark b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/16.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Maya Horton">
                                                <img src="{{asset('assets/images/avtar/10.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">09:00 AM</span></td>
                                    <td><span class="badge bg-light-danger">11:00 AM</span></td>
                                    <td class="f-w-600">30.2%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            Jane Doe</p>
                                    </td>
                                    <td class="text-primary f-w-500">#867</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light">
                                                <img src="{{asset('assets/images/avtar/08.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-dark b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/12.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">10:30 AM</span></td>
                                    <td><span class="badge bg-light-danger">11:30 AM</span></td>
                                    <td class="f-w-600">86.3%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            Mark Johnson</p>
                                    </td>
                                    <td class="text-primary f-w-500">#674</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-primary b-2-light">
                                                <img src="{{asset('assets/images/avtar/2.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-success b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/08.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Maya Horton">
                                                <img src="{{asset('assets/images/avtar/10.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">01:00 PM</span></td>
                                    <td><span class="badge bg-light-danger">02:00 PM</span></td>
                                    <td class="f-w-600">10%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            Emily Davis</p>
                                    </td>
                                    <td class="text-primary f-w-500">#364</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light">
                                                <img src="{{asset('assets/images/avtar/12.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-dark b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/07.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-danger b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Maya Horton">
                                                <img src="{{asset('assets/images/avtar/5.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-warning"
                                                data-bs-toggle="tooltip" data-bs-title="Maya Horton">
                                                <img src="{{asset('assets/images/avtar/14.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">09:00 AM</span></td>
                                    <td><span class="badge bg-light-danger">11:00 AM</span></td>
                                    <td class="f-w-600">55.4%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            Michael Brown</p>
                                    </td>
                                    <td class="text-primary f-w-500">#453</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-dark b-2-light">
                                                <img src="{{asset('assets/images/avtar/08.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-primary b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/16.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Maya Horton">
                                                <img src="{{asset('assets/images/avtar/10.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">09:45 AM</span></td>
                                    <td><span class="badge bg-light-danger">10:00 AM</span></td>
                                    <td class="f-w-600">99.2%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            James Anderson</p>
                                    </td>
                                    <td class="text-primary f-w-500">#769</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-light b-2-light">
                                                <img src="{{asset('assets/images/avtar/08.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-warning b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/16.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">08:00 AM</span></td>
                                    <td><span class="badge bg-light-danger">09:00 AM</span></td>
                                    <td class="f-w-600">10.2%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="check-box">
                                            <input type="checkbox">
                                            <span class="checkmark outline-dark ms-2"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <p class="f-w-500 text-start mb-0">
                                            Sarah Wilson</p>
                                    </td>
                                    <td class="text-primary f-w-500">#048</td>

                                    <td>
                                        <ul class="avatar-group justify-content-center">
                                            <li
                                                class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-success b-2-light">
                                                <img src="{{asset('assets/images/avtar/09.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-danger b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Lennon Briggs">
                                                <img src="{{asset('assets/images/avtar/11.png')}}" alt="" class="img-fluid">
                                            </li>
                                            <li class="h-30 w-30 d-flex-center b-r-50 overflow-hidden text-bg-secondary b-2-light"
                                                data-bs-toggle="tooltip" data-bs-title="Maya Horton">
                                                <img src="{{asset('assets/images/avtar/10.png')}}" alt="" class="img-fluid">
                                            </li>
                                        </ul>
                                    </td>
                                    <td><span class="badge bg-light-success">11:00 AM</span></td>
                                    <td><span class="badge bg-light-danger">12:00 PM</span></td>
                                    <td class="f-w-600">20.2%</td>
                                    <td>
                                        <button type="button" class="btn btn-light-success icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-edit"></i></button>
                                        <button type="button" class="btn btn-light-danger icon-btn w-30 h-30 b-r-6"><i
                                                class="ti ti-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer visitors-table-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-secondary mb-0">Showing 1 to 6 of 24 order entries</p>
                        <ul class="pagination app-pagination">
                            <li class="page-item bg-light-secondary disabled">
                                <a class="page-link b-r-left">Previous</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link bg-primary" href="#">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            <div class="card equal-card">
                <div class="card-header">
                    <h5>Countries Report</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-shrink-0">
                            <h3 class="f-w-600 text-success mb-0">68.09%</h3>
                            <p class="text-secondary mb-0">United States<i
                                    class="fa-solid fa-arrow-circle-up fa-fw ms-1 text-success"></i></p>
                        </div>
                        <div>
                            <div id="sharesChart"></div>
                        </div>
                    </div>

                    <ul class="browser-source-list">
                        <li class="position-relative">
                            <span class="rounded-circle overflow-hidden position-absolute">
                                <i class="flag-icon flag-icon-usa flag-icon-squared b-r-50 f-s-32"></i>
                            </span>
                            <div class="ms-5">
                                <h6 class="header-heading">USA</h6>
                                <div class="progress h-10">
                                    <div class="progress-bar progress-bar-striped bg-primary h-20" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                </div>
                            </div>
                        </li>
                        <li class="position-relative">
                            <span class="rounded-circle overflow-hidden position-absolute">
                                <i class="flag-icon flag-icon-aus flag-icon-squared b-r-50 f-s-32"></i>
                            </span>
                            <div class="ms-5">
                                <h6 class="header-heading">Australia</h6>
                                <div class="progress h-10">
                                    <div class="progress-bar progress-bar-striped bg-success h-20" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
                                </div>
                            </div>
                        </li>
                        <li class="position-relative">
                            <span class="rounded-circle overflow-hidden position-absolute">
                                <i class="flag-icon flag-icon-can flag-icon-squared b-r-50 f-s-32"></i>
                            </span>
                            <div class="ms-5">
                                <h6 class="header-heading">Canada </h6>
                                <div class="progress h-10">
                                    <div class="progress-bar progress-bar-striped bg-danger h-20" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 58%;"></div>
                                </div>
                            </div>
                        </li>
                        <li class="position-relative">
                            <span class="rounded-circle overflow-hidden position-absolute mt-1">
                                <i class="flag-icon flag-icon-nzl flag-icon-squared b-r-50 f-s-32"></i>
                            </span>
                            <div class="ms-5">
                                <h6 class="header-heading">New Zealand</h6>
                                <div class="progress h-10">
                                    <div class="progress-bar progress-bar-striped bg-info h-20" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                </div>
                            </div>
                        </li>
                        <li class="position-relative">
                            <span class="rounded-circle overflow-hidden position-absolute mt-1">
                                <i class="flag-icon flag-icon-ita flag-icon-squared b-r-50 f-s-32"></i>
                            </span>
                            <div class="ms-5">
                                <h6 class="header-heading">Italy</h6>
                                <div class="progress h-10">
                                    <div class="progress-bar progress-bar-striped bg-warning h-20" role="progressbar"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 40%;"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-7 col-lg-4 target-chart">
            <div class="card equal-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="m-0">Target Report</h5>
                            <p class="text-secondary">Last 2H Update</p>
                        </div>
                        <div>
                            <div class="btn-group dropdown-icon-none">
                                <button class="btn btn-light-secondary icon-btn b-r-4 dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                    <i class="ti ti-dots f-s-20"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"> Weekly</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"> Monthly</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"> Yearly</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="targetChart" class="targetChart"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>Browser Update</h5>
                </div>
                <div class="card-body">
                    <ul class="browser-update-list">
                        <li>
                            <div class="position-relative">
                                <span class="h-30 w-30 d-flex-center position-absolute">
                                    <img src="{{asset('assets/images/dashboard/analytics/chrome.png')}}" alt="logo"
                                        class="img-fluid">
                                </span>
                                <div class="ms-5">
                                    <h6 class="header-heading mb-0">Chrome</h6>
                                    <p class="text-secondary mb-0">10.3.0</p>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 f-s-16 f-w-500 text-success"><i class="ti ti-chevrons-up"></i> 8,098</p>
                            </div>
                        </li>
                        <li>
                            <div class="position-relative">
                                <span class="h-30 w-30 d-flex-center position-absolute">
                                    <img src="{{asset('assets/images/dashboard/analytics/opera.png')}}" alt="logo"
                                        class="img-fluid">
                                </span>
                                <div class="ms-5">
                                    <h6 class="header-heading mb-0">Opera</h6>
                                    <p class="text-secondary mb-0">11.3.2</p>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 f-s-16 f-w-500 text-danger"><i class="ti ti-chevrons-down"></i> 5,932</p>
                            </div>
                        </li>
                        <li>
                            <div class="position-relative">
                                <span class="h-30 w-30 d-flex-center position-absolute">
                                    <img src="{{asset('assets/images/dashboard/analytics/microsoft.png')}}" alt="logo"
                                        class="img-fluid">
                                </span>
                                <div class="ms-5">
                                    <h6 class="header-heading mb-0">Edge</h6>
                                    <p class="text-secondary mb-0">8.3.0</p>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 f-s-16 f-w-500 text-danger"><i class="ti ti-chevrons-down"></i> 2,905</p>
                            </div>
                        </li>
                        <li>
                            <div class="position-relative">
                                <span class="h-30 w-30 d-flex-center position-absolute">
                                    <img src="{{asset('assets/images/dashboard/analytics/firefox.png')}}" alt="logo"
                                        class="img-fluid">
                                </span>
                                <div class="ms-5">
                                    <h6 class="header-heading mb-0">Firefox</h6>
                                    <p class="text-secondary mb-0">6.2.0</p>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 f-s-16 f-w-500 text-success"><i class="ti ti-chevrons-up"></i> 8,905</p>
                            </div>
                        </li>
                        <li>
                            <div class="position-relative">
                                <span class="h-30 w-30 d-flex-center position-absolute">
                                    <img src="{{asset('assets/images/dashboard/analytics/medium.png')}}" alt="logo"
                                        class="img-fluid">
                                </span>
                                <div class="ms-5">
                                    <h6 class="header-heading mb-0"> Others</h6>
                                    <p class="text-secondary mb-0">10.9.0</p>
                                </div>
                            </div>
                            <div>
                                <p class="mb-0 f-s-16 f-w-500 text-success"><i class="ti ti-chevrons-up"></i> 6,849</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <div class="transactions-list d-flex justify-content-between align-items-center">
                        <h5>Transactions</h5>
                        <p class="mb-0 text-secondary ellipsis-text">290 Transactions</p>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle transactions-list-table mb-0">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="position-relative">
                                            <div
                                                class="h-40 w-40 d-flex-center b-r-50 overflow-hidden text-bg-primary position-absolute">
                                                <img src="{{asset('assets/images/avtar/1.png')}}" alt="" class="img-fluid">
                                            </div>
                                            <div class="ms-5">
                                                <h6 class="header-heading mb-0 ellipsis-text">Starbucks</h6>
                                                <p class="text-secondary mb-0">Wallet</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end text-secondary f-w-500">$500.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="position-relative">
                                            <div
                                                class="h-40 w-40 d-flex-center b-r-50 overflow-hidden text-bg-secondary position-absolute">
                                                <img src="{{asset('assets/images/avtar/2.png')}}" alt="" class="img-fluid">
                                            </div>
                                            <div class="ms-5">
                                                <h6 class="header-heading mb-0 ellipsis-text">Food Order</h6>
                                                <p class="text-secondary mb-0">Online</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end text-secondary f-w-500">$480.89</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="position-relative">
                                            <div
                                                class="h-40 w-40 d-flex-center b-r-50 overflow-hidden text-bg-success position-absolute">
                                                <img src="{{asset('assets/images/avtar/3.png')}}" alt="" class="img-fluid">
                                            </div>
                                            <div class="ms-5">
                                                <h6 class="header-heading mb-0 ellipsis-text">Refund</h6>
                                                <p class="text-secondary mb-0">Transfer</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end text-secondary f-w-500">$687.57</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="position-relative">
                                            <div
                                                class="h-40 w-40 d-flex-center b-r-50 overflow-hidden text-bg-danger position-absolute">
                                                <img src="{{asset('assets/images/avtar/6.png')}}" alt="" class="img-fluid">
                                            </div>
                                            <div class="ms-5">
                                                <h6 class="header-heading mb-0 ellipsis-text">Send Money</h6>
                                                <p class="text-secondary mb-0">Visa</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end text-secondary f-w-500">$900.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="position-relative">
                                            <div
                                                class="h-40 w-40 d-flex-center b-r-50 overflow-hidden text-bg-info position-absolute">
                                                <img src="{{asset('assets/images/avtar/4.png')}}" alt="" class="img-fluid">
                                            </div>
                                            <div class="ms-5">
                                                <h6 class="header-heading mb-0 ellipsis-text">Order Phone</h6>
                                                <p class="text-secondary mb-0">Credit Card</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end text-secondary f-w-500">$1,094.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal -->
{{-- <div class="modal" tabindex="-1" id="welcomeCard" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content welcome-image">

            <div class="modal-body">
                <div>
                    <div class="text-center">
                        <div class="text-end">
                            <i class="ti ti-x fs-5 text-secondary" data-bs-dismiss="modal"></i>
                        </div>
                        <h2 class="text-primary f-w-600">Welcome <i class="ti ti-heart-handshake text-warning"></i></h2>
                        <p class="text-light f-w-500 f-s-16 mx-sm-5">
                            Start Multipurpose, clean modern responsive bootstrap 5 admin template</p>
                        <img src="{{asset('assets/images/modals/welcome.png')}}" class="img-fluid h-140 mt-3 mb-3" alt="">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="">
                                    <div class="mb-2">
                                        <p class="text-secondary f-w-400"><i
                                                class="ti ti-bookmarks f-s-15 text-primary me-2"></i>Build
                                            your next project faster with AdminX</p>
                                    </div>

                                    <div class="mb-2">
                                        <p class="text-secondary f-w-400 ms-2 mb-0"><i
                                                class="ti ti-presentation-analytics f-s-15 me-2 text-success"></i>Start
                                            Your Project With
                                            Flexible Admin</p>
                                    </div>

                                    <div class="mb-2">
                                        <p class="text-secondary f-w-400 ms-2 mb-0"> <i
                                                class="ti ti-users f-s-15 me-2 text-danger"></i>
                                            Enjoy dev-friendly features </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 mt-2">
                            <button type="button" class="btn btn-primary text-white w-200 btn-lg"
                                data-bs-dismiss="modal">Let's
                                Started <i class="ti ti-chevrons-right"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@section('script')

<!-- slick-file -->
<script src="{{asset('assets/vendor/slick/slick.min.js')}}"></script>

<!-- vector map plugin js -->
<script src="{{asset('assets/vendor/vector-map/jquery-jvectormap-2.0.5.min.js')}}"></script>
<script src="{{asset('assets/vendor/vector-map/jquery-jvectormap-world-mill.js')}}"></script>

<!--cleave js  -->
<script src="{{asset('assets/vendor/cleavejs/cleave.min.js')}}"></script>

<!-- Glight js -->
<script src="{{asset('assets/vendor/glightbox/glightbox.min.js')}}"></script>

<!-- data table-->
<script src="{{asset('assets/vendor/datatable/jquery.dataTables.min.js')}}"></script>

<!-- apexcharts js-->
<script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

<!-- Ecommerce js-->
<script src="{{asset('assets/js/analytics_dashboard.js')}}"></script>

@endsection
