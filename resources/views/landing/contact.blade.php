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
                                               id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" name="subject" value="{{ old('subject') }}" required placeholder="How can we help?" 
                                           maxlength="100" data-max-words="15">
                                    <div class="form-text">
                                        <span id="subject-word-count">0</span>/15 words
                                    </div>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" required placeholder="Type your message here..." 
                                              maxlength="1000" data-max-words="150">{{ old('message') }}</textarea>
                                    <div class="form-text">
                                        <span id="message-word-count">0</span>/150 words
                                    </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Word counting function
            function countWords(text) {
                return text.trim().split(/\s+/).filter(word => word.length > 0).length;
            }

            // Update word count display
            function updateWordCount(input, counterId, maxWords) {
                const wordCount = countWords(input.value);
                const counter = document.getElementById(counterId);
                counter.textContent = wordCount;
                
                // Change color based on word count
                if (wordCount > maxWords) {
                    counter.style.color = '#dc3545'; // Red
                    input.classList.add('is-invalid');
                } else if (wordCount > maxWords * 0.8) {
                    counter.style.color = '#ffc107'; // Yellow
                    input.classList.remove('is-invalid');
                } else {
                    counter.style.color = '#6c757d'; // Gray
                    input.classList.remove('is-invalid');
                }
            }

            // Get form elements
            const subjectInput = document.getElementById('subject');
            const messageInput = document.getElementById('message');
            const form = document.querySelector('form');

            // Initialize word counts
            updateWordCount(subjectInput, 'subject-word-count', 15);
            updateWordCount(messageInput, 'message-word-count', 150);

            // Add event listeners
            subjectInput.addEventListener('input', function() {
                updateWordCount(this, 'subject-word-count', 15);
            });

            messageInput.addEventListener('input', function() {
                updateWordCount(this, 'message-word-count', 150);
            });

            // Form validation
            form.addEventListener('submit', function(e) {
                const subjectWords = countWords(subjectInput.value);
                const messageWords = countWords(messageInput.value);
                
                if (subjectWords > 15) {
                    e.preventDefault();
                    alert('Subject must not exceed 15 words. Current: ' + subjectWords + ' words.');
                    subjectInput.focus();
                    return false;
                }
                
                if (messageWords > 150) {
                    e.preventDefault();
                    alert('Message must not exceed 150 words. Current: ' + messageWords + ' words.');
                    messageInput.focus();
                    return false;
                }
            });
        });
    </script>
@endsection
