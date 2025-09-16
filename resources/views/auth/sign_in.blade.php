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

                                        @if (session('status'))
                                        <div class="alert alert-success mb-3" role="alert">
                                            {{ session('status') }}
                                        </div>
                                        @endif



                                        @if(session('success'))
                                        <div id="session-alert" class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                        @endif

                                        @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                        @endif
                                                                                
                                        
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <a href="{{ route('landing') }}" class="link-primary">
                                                    ← Back to Home
                                                </a>
                                            </div>

                                            <div class="mb-5 text-center text-lg-start">
                                                <h2 class="text-primary f-w-600">Welcome Back to SinDa!</h2>
                                                <p>Sign in with your credentials below.</p>
                                                    
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
                                                <a href="#" class="link-primary float-end" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
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

            <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 id="forgotPasswordLabel" class="modal-title text-white">Reset your password</h5>
                            <button type="button" class="btn-close m-0 fs-5" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="modal-body">
                            <p class="mb-3">Enter your account email. We'll send a password reset link.</p>

                            <label for="resetEmail" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email', 'passwordReset') is-invalid @enderror" id="resetEmail" name="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                            @error('email', 'passwordReset') 
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Send reset link</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

{{-- <script src="{{ asset('assets/js/formvalidation.js') }}"></script> --}}
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const hasPwErr = {!! json_encode($errors->hasBag('passwordReset') ? $errors->passwordReset->any() : false) !!};
    if (hasPwErr) {
      const el = document.getElementById('forgotPasswordModal');
      if (el) new bootstrap.Modal(el).show();
    }
  });
</script>

