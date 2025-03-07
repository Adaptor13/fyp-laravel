@section('title', 'Sign In')
@include('layout.head')

@include('layout.css')

<body>
    <div class="app-wrapper d-block">
        <div class="">
            <!-- Body main section starts -->
            <main class="w-100">
                <!-- sign in start -->
                <div class="container-fluid">
                    <div class="row" id="form-validation">
                        <div class="col-lg-7 col-xl-8 d-none d-lg-block p-0">
                            <div class="image-contentbox">
                                <img src="{{ asset('assets/images/login/01.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="col-lg-5 col-xl-4 p-0 bg-white">
                            <div class="form-container">
                                <form class="app-form needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-5 text-center text-lg-start">
                                                <h2 class="text-primary f-w-600">Welcome To SentraDx! </h2>
                                                <p>Sign in with your data that you entered during your registration.</p>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control"
                                                    placeholder="Enter Your Email" id="email">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <a href="" class="link-primary float-end">Forgot Password ?</a>
                                                <input type="password" class="form-control"
                                                    placeholder="Enter Your Password" id="password">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="checkDefault">
                                                <label class="form-check-label text-secondary" for="checkDefault">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <a href="" role="button" class="btn btn-primary w-100">Sign
                                                    In</a>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center text-lg-start">
                                                Don't Have Your Account yet? <a href="{{ route('sign_up') }}"
                                                    class="link-primary text-decoration-underline"> Sign up</a>
                                            </div>
                                        </div>
                                        <div class="app-divider-v justify-content-center">
                                            <p>Or sign in with</p>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center">
                                                <button type="button" class="btn btn-facebook icon-btn b-r-22 m-1"><i
                                                        class="ti ti-brand-facebook text-white"></i></button>
                                                <button type="button" class="btn btn-gmail icon-btn b-r-22 m-1"><i
                                                        class="ti ti-brand-google text-white"></i></button>
                                                <button type="button" class="btn btn-github icon-btn b-r-22 m-1"><i
                                                        class="ti ti-brand-github text-white"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- sign in end -->
            </main>
            <!-- Body main section ends -->
        </div>
    </div>


</body>
@section('script')

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/formvalidation.js') }}"></script>

@endsection
