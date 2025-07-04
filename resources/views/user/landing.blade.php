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
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light static-top sticky-top">
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
                    </ul>
                </div>

            </div>
        </nav>

        <!-- Masthead-->
        <header class="masthead">
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="text-center text-white">
                            <!-- Page heading -->
                            <h1 class="mb-4">Report Child Abuse Safely and Anonymously</h1>

                            <!-- Action buttons -->
                            <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                                <a href="/report" class="btn btn-light btn-lg px-4">Report Anonymously</a>
                                <a href="/login" class="btn btn-outline-light btn-lg px-4">Login to Track My Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Icons Grid-->
        <section class="features-icons bg-light text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-shield-lock m-auto text-primary"></i></div>
                            <h3>Safe & Confidential</h3>
                            <p class="lead mb-0">Reports are encrypted and protected under PDPA guidelines.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-clock-history m-auto text-primary"></i></div>
                            <h3>Real-Time Case Tracking</h3>
                            <p class="lead mb-0">Registered users can view status updates of submitted cases.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-people m-auto text-primary"></i></div>
                            <h3>Interagency Collaboration</h3>
                            <p class="lead mb-0">Social workers, law enforcement, and doctors work together efficiently.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image Showcases-->
        <section class="showcase">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('assets/images/landing/bg-showcase-1.jpg')"></div>
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h2>How SinDa Works</h2>
                        <p class="lead mb-0">SinDa allows the public to submit anonymous child abuse reports securely. Reports are automatically logged into the system and assigned to relevant social workers or authorities based on severity and location.</p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 text-white showcase-img" style="background-image: url('assets/images/landing/bg-showcase-2.jpg')"></div>
                    <div class="col-lg-6 my-auto showcase-text">
                        <h2>Supporting Agencies Together</h2>
                        <p class="lead mb-0">SinDa integrates law enforcement, healthcare professionals, and child welfare officers into one platform, allowing for real-time updates, secure collaboration, and data-protected follow-up actions on reported cases.</p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('assets/images/landing/bg-showcase-3.jpg')"></div>
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h2>Your Report Can Make a Difference</h2>
                        <p class="lead mb-0"> Every report submitted helps activate real-world intervention from trained social workers, law enforcement, and healthcare providers. Together, we can act quickly to protect vulnerable children across Malaysia.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Testimonials-->
        {{-- <section class="testimonials text-center bg-light">
            <div class="container">
                <h2 class="mb-5">What people are saying...</h2>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                            <img class="img-fluid rounded-circle mb-3" src="assets/" alt="..." />
                            <h5>Margaret E.</h5>
                            <p class="font-weight-light mb-0">"This is fantastic! Thanks so much guys!"</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                            <img class="img-fluid rounded-circle mb-3" src="assets/img/testimonials-2.jpg" alt="..." />
                            <h5>Fred S.</h5>
                            <p class="font-weight-light mb-0">"Bootstrap is amazing. I've been using it to create lots of super nice landing pages."</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                            <img class="img-fluid rounded-circle mb-3" src="assets/img/testimonials-3.jpg" alt="..." />
                            <h5>Sarah W.</h5>
                            <p class="font-weight-light mb-0">"Thanks so much for making these free resources available to us!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}

        <!-- Call to Action-->
        <section class="call-to-action text-white text-center bg-secondary" id="sdg">
            <div class="container position-relative py-5">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <h2 class="mb-4 fw-bold">Aligned with United Nations SDG 16</h2>
                        <p class="lead mb-4">
                            SinDa directly supports <strong>Sustainable Development Goal 16</strong>: <br>
                            <em>“End abuse, exploitation, trafficking and all forms of violence against children.”</em>
                        </p>
                        <p class="mb-0 small">
                            By enabling secure digital reporting and interagency collaboration, SinDa contributes to protecting vulnerable children and promoting justice, accountability, and institutional reform in Malaysia.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer-->
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
</html>