<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>@yield('title') | SinDA</title>
        <link rel="icon" href="{{('../assets/images/logo/sinda01.png')}}" type="image/x-icon">
        <link rel="shortcut icon" href="{{('../assets/images/logo/sinda01.png')}}" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" /> 
        <link href="{{ asset('assets/css/userstyles.css') }}" rel="stylesheet" rel="stylesheet">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('landing') }}">
                    <img src="{{ asset('assets/images/logo/sinda.png') }}" alt="SinDa" class="img-fluid" style="max-height: 40px;">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collapsible menu -->
                <div class="collapse navbar-collapse justify-content-center" id="navbarResponsive">
                    <ul class="navbar-nav w-100 text-center text-lg-start justify-content-lg-end">

                        @if (!request()->routeIs('report') && !request()->routeIs('profile.edit') && !request()->routeIs('reports.track'))

                            <li class="nav-item border-bottom me-3">
                                <a class="nav-link py-2" href="#about">About</a>
                            </li>
                            <li class="nav-item border-bottom me-3">
                                <a class="nav-link py-2" href="#how-it-works">How It Works</a>
                            </li>
                            <li class="nav-item border-bottom me-3">
                                <a class="nav-link py-2" href="{{ route('contact.show') }}">Contact</a>
                            </li>
                        @endif

                        <li class="nav-item border-bottom me-3">
                            <a class="nav-link py-2" href="{{ route('report') }}">Report Abuse</a>
                        </li>

                        @auth
                            <li class="nav-item border-bottom me-3">
                                <a class="nav-link py-2" href="{{ route('reports.track') }}">
                                    My Reports
                                </a>
                            </li>
                        @endauth

                        <li class="nav-item border-bottom me-3">
                            @auth
                                <a class="nav-link py-2 fw-bold" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            @else
                                <a class="nav-link py-2 fw-bold" href="{{ route('sign_in') }}">Login</a>
                            @endauth
                        </li>

                        {{-- User email -> Profile, or Guest --}}
                        <li class="nav-item">
                            @auth
                                <a class="nav-link text-success" href="{{ route('profile.edit') }}">
                                    ({{ Auth::user()->email }})
                                </a>
                            @else
                                <span class="nav-link text-muted">(Guest)</span>
                            @endauth
                        </li>

                        {{-- Hidden logout form --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </ul>
                </div>


            </div>
        </nav>

        <!-- Main Content -->
        @yield('content')

        <footer class="footer bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 h-100 text-center text-lg-start my-auto">
                        <ul class="list-inline mb-2">
                            <li class="list-inline-item"><a href="{{ route('contact.show') }}">Contact</a></li>
                        </ul>
                        <p class="text-muted small mb-4 mb-lg-0">&copy; SinDa 2025. All Rights Reserved.</p>
                    </div>
                  
                </div>
            </div>
        </footer>

        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery (needed for mask plugin) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <!-- jQuery Mask Plugin -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <!-- Your site scripts -->
        {{-- <script src="js/scripts.js"></script> --}}

        <script>
            $(document).ready(function(){
                $('#phone').mask('000-0000000');
            });
        </script>

        @yield('scripts')

    </body>

<html>