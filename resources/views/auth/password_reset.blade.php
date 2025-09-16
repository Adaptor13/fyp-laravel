@section('title', 'Password Reset')
@include('layout.head')

@include('layout.css')

<body>
  <div class="app-wrapper d-block">
    <div class="">
      <!-- Body main section starts -->
      <!-- Reset Your Password start -->
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-7 col-xl-8 d-none d-lg-block p-0">
            <div class="image-contentbox">
              <img src="{{asset('assets/images/login/03.png')}}" class="img-fluid" alt="">
            </div>
          </div>
          <div class="col-lg-5 col-xl-4 p-0 bg-white">
            <div class="form-container">
              <form class="app-form" method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="row">
                  <div class="col-12">
                    <div class="mb-5 text-center text-lg-start">
                      <h2 class="text-primary f-w-600">Reset Your Password</h2>
                      <p>Create a new password for your account</p>
                    </div>
                  </div>
                  
                  @if ($errors->any())
                    <div class="col-12">
                      <div class="alert alert-danger">
                        <ul class="mb-0">
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  @endif
                  
                  <div class="col-12">
                    <div class="mb-3">
                      <label for="password" class="form-label">New Password</label>
                      <input type="password" 
                             class="form-control @error('password') is-invalid @enderror" 
                             placeholder="Enter Your Password" 
                             id="password" 
                             name="password" 
                             required
                             minlength="8">
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <div class="form-text">
                        Password must be at least 8 characters long and cannot contain spaces.
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-12">
                    <div class="mb-3">
                      <label for="password_confirmation" class="form-label">Confirm Password</label>
                      <input type="password" 
                             class="form-control @error('password_confirmation') is-invalid @enderror" 
                             placeholder="Confirm Your Password" 
                             id="password_confirmation" 
                             name="password_confirmation" 
                             required>
                      @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                      <div id="password-match-error" class="invalid-feedback d-none">
                        Passwords do not match.
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-12">
                    <div class="mb-3">
                      <button type="submit" class="btn btn-primary w-100" id="submitBtn">Reset Password</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Reset Your Password end -->
      <!-- Body main section ends -->
    </div>
  </div>

</body>


@section('script')
    <!-- latest jquery-->
    <script src="{{asset('assets/js/jquery-3.6.3.min.js')}}"></script>

    <!-- Bootstrap js-->
    <script src="{{asset('assets/vendor/bootstrap/bootstrap.bundle.min.js')}}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const submitBtn = document.getElementById('submitBtn');
            const passwordMatchError = document.getElementById('password-match-error');
            
            // Password validation
            function validatePassword(password) {
                const minLength = 8;
                const hasNoSpaces = !/\s/.test(password);
                
                return {
                    isValid: password.length >= minLength && hasNoSpaces,
                    minLength: password.length >= minLength,
                    hasNoSpaces: hasNoSpaces
                };
            }
            
            // Real-time password validation
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                const validation = validatePassword(password);
                
                // Remove existing validation classes
                this.classList.remove('is-valid', 'is-invalid');
                
                if (password.length > 0) {
                    if (validation.isValid) {
                        this.classList.add('is-valid');
                    } else {
                        this.classList.add('is-invalid');
                    }
                }
                
                // Check password confirmation if it has a value
                if (confirmPasswordInput.value) {
                    checkPasswordMatch();
                }
            });
            
            // Password confirmation validation
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
                passwordMatchError.classList.add('d-none');
                
                if (confirmPassword.length > 0) {
                    if (password === confirmPassword && password.length > 0) {
                        confirmPasswordInput.classList.add('is-valid');
                    } else {
                        confirmPasswordInput.classList.add('is-invalid');
                        passwordMatchError.classList.remove('d-none');
                    }
                }
            }
            
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const validation = validatePassword(password);
                
                let isValid = true;
                
                // Validate password strength
                if (!validation.isValid) {
                    passwordInput.classList.add('is-invalid');
                    isValid = false;
                }
                
                // Validate password confirmation
                if (password !== confirmPassword) {
                    confirmPasswordInput.classList.add('is-invalid');
                    passwordMatchError.classList.remove('d-none');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Show error message
                    if (!validation.minLength) {
                        alert('Password must be at least 8 characters long.');
                    } else if (!validation.hasNoSpaces) {
                        alert('Password cannot contain spaces.');
                    } else if (password !== confirmPassword) {
                        alert('Passwords do not match.');
                    }
                }
            });
            
            // Show/hide password functionality
            function togglePasswordVisibility(inputId, toggleId) {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                
                if (toggle) {
                    toggle.addEventListener('click', function() {
                        if (input.type === 'password') {
                            input.type = 'text';
                            this.innerHTML = '<i class="fa fa-eye-slash"></i>';
                        } else {
                            input.type = 'password';
                            this.innerHTML = '<i class="fa fa-eye"></i>';
                        }
                    });
                }
            }
        });
    </script>
@endsection

