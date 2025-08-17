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
              <form class="app-form">
                <div class="row">
                  <div class="col-12">
                    <div class="mb-5 text-center text-lg-start">
                      <h2 class="text-primary f-w-600">Reset Your Password</h2>
                      <p>Create a new password and sign in to admin</p>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mb-3">
                      <label for="password" class="form-label">New Password</label>
                      <input type="password" class="form-control" placeholder="Enter Your Password" id="password">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mb-3">
                      <label for="password" class="form-label">Confirm Password</label>
                      <input type="password" class="form-control" placeholder="Enter Your Password" id="password1"
                        required="">
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="mb-3">
                      <a href="{{route('sign_in')}}" role="button" class="btn btn-primary w-100">Reset Password</a>
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
@endsection

