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
                                <form class="login-form" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-5 text-center text-lg-start">
                                                <h2 class="text-primary f-w-600">Welcome Back to SinDa!</h2>
                                                <p>Sign in with your credentials below.</p>
                                                    @if(session('success'))
                                                        <div id="session-alert" class="alert alert-success">
                                                            {{ session('success') }}
                                                        </div>
                                                    @endif
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror"name="email" id="email" placeholder="Enter Your Email"value="{{ old('email') }}" required>
                                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <a href="" class="link-primary float-end">Forgot Password?</a>
                                                <input type="password" class="form-control @error('password') is-invalid @enderror"name="password" id="password" placeholder="Enter Your Password" required>
                                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                                <label class="form-check-label text-secondary" for="remember">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary w-100">Sign In</button>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="text-center text-lg-start">
                                                Don’t Have an Account?
                                                <a href="{{ route('sign_up') }}" class="link-primary text-decoration-underline">Sign Up</a>
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

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertBox = document.getElementById('session-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.transition = 'opacity 0.5s ease-out';
                alertBox.style.opacity = '0';
                setTimeout(() => {
                    alertBox.remove();
                }, 500);
            }, 3000); // 3 seconds before it fades
        }
    });
</script>


@endsection
