@section('title', 'Sign Up')
@include('layout.head')

@include('layout.css')

<body>
    <div class="app-wrapper d-block">
        <div class="">
            <!-- Body main section starts -->
            <main class="w-100">
                <!-- sign up start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-7 col-xl-8 d-none d-lg-block p-0">
                            <div class="image-contentbox">
                                <img src="{{ asset('assets/images/login/05.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                        <div class="col-lg-5 col-xl-4 p-0 bg-white">
                            <div class="form-container">
                                <form class="register-form" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-5 text-center text-lg-start">
                                                <h2 class="text-primary f-w-600">Create Account</h2>
                                                <p>Get Started For Free Today!</p>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" name="name" id="username" placeholder="Enter Your Username" value="{{ old('name') }}" required>
                                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter Your Email" value="{{ old('email') }}" required>
                                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Your Password" required>
                                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="password1" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" name="password_confirmation" id="password1" placeholder="Confirm Your Password" required>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="checkDefault" required>
                                                    <label class="form-check-label text-secondary" for="checkDefault">
                                                        Accept Terms & Conditions
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="text-center text-lg-start">
                                                Already Have An Account? <a href="{{ route('sign_in') }}" class="link-primary text-decoration-underline">
                                                    Sign in</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- sign up end -->
            </main>
            <!-- Body main section ends -->
        </div>
    </div>

</body>
@section('script')
    <!--js-->
    <script src="{{ asset('assets/js/coming_soon.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
@endsection
