@extends('layout.landing')

@section('title', 'Contact Us - SinDa')

@section('content')
    <!-- Page Header -->
    <header class="masthead">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="text-center text-white">
                        <h1 class="mb-4">Contact Us</h1>
                        <p class="lead mb-0">Have questions about SinDa? We're here to help.</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contact Form Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Send us a Message</h3>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('contact.store') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send me-2"></i>Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mt-5">
                        <div class="col-md-4 text-center mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-geo-alt text-primary" style="font-size: 2rem;"></i>
                                    <h5 class="mt-3">Address</h5>
                                    <p class="text-muted">Ministry of Women, Family and Community Development<br>
                                    Putrajaya, Malaysia</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-telephone text-primary" style="font-size: 2rem;"></i>
                                    <h5 class="mt-3">Phone</h5>
                                    <p class="text-muted">+60 3-8000 8000<br>
                                    Emergency: 15999</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <i class="bi bi-envelope text-primary" style="font-size: 2rem;"></i>
                                    <h5 class="mt-3">Email</h5>
                                    <p class="text-muted">info@sinda.gov.my<br>
                                    support@sinda.gov.my</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
