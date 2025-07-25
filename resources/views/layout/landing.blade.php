<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Landing |SinDa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" /> 
        <link href="{{ asset('assets/css/userstyles.css') }}" rel="stylesheet" rel="stylesheet">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="/">SinDa</a>

                <!-- Hamburger toggle button for mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collapsible menu -->
                <div class="collapse navbar-collapse justify-content-center" id="navbarResponsive">
                    <ul class="navbar-nav w-100 text-center text-lg-start justify-content-lg-end">
                        <li class="nav-item border-bottom">
                            <a class="nav-link py-2" href="#about">About</a>
                        </li>
                        <li class="nav-item border-bottom">
                            <a class="nav-link py-2" href="#how-it-works">How It Works</a>
                        </li>
                        <li class="nav-item border-bottom">
                            <a class="nav-link py-2" href="#report">Report Abuse</a>
                        </li>
                        <li class="nav-item border-bottom">
                            <a class="nav-link py-2 text-primary fw-bold" href="/sign_in">Login</a>
                        </li>
                            <li class="nav-item">
                            <!-- Uncomment one of the below lines based on your need -->
                            
                            <!-- For guest (default) -->
                            <span class="nav-link text-muted">(Guest)</span>
                            
                            <!-- For static logged-in email simulation -->
                            <!-- <a class="nav-link fw-semibold text-primary text-decoration-underline" href="/profile">justin@example.com</a> -->
                        </li>
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
                            <li class="list-inline-item"><a href="#!">About</a></li>
                            <li class="list-inline-item">⋅</li>
                            <li class="list-inline-item"><a href="#!">Contact</a></li>
                            <li class="list-inline-item">⋅</li>
                            <li class="list-inline-item"><a href="#!">Terms of Use</a></li>
                            <li class="list-inline-item">⋅</li>
                            <li class="list-inline-item"><a href="#!">Privacy Policy</a></li>
                        </ul>
                        <p class="text-muted small mb-4 mb-lg-0">&copy; Your Website 2023. All Rights Reserved.</p>
                    </div>
                    <div class="col-lg-6 h-100 text-center text-lg-end my-auto">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item me-4">
                                <a href="#!"><i class="bi-facebook fs-3"></i></a>
                            </li>
                            <li class="list-inline-item me-4">
                                <a href="#!"><i class="bi-twitter fs-3"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#!"><i class="bi-instagram fs-3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/scripts.js"></script>
    </body>

<html>