@extends('layout.landing')

@section('title', 'Landing')

@section('content')
    
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
                                <a href="{{ route('report') }}" class="btn btn-light btn-lg px-4">Report Anonymously</a>

                                @auth
                                    <!-- If logged in -->
                                    <a href="{{ route('reports.track') }}" class="btn btn-outline-light btn-lg px-4">
                                        Track My Report
                                    </a>
                                @else
                                    <!-- If not logged in -->
                                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                        Login to Track My Report
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Icons Grid-->
        <section id="#about" class="features-icons bg-light text-center">
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
        <section id="how-it-works" class="showcase">
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
@endsection

