<!-- Avatar Upload JavaScript -->
<script>
    // Avatar upload preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageUpload = document.getElementById('imageUpload');
        const imgPreview = document.getElementById('imgPreview');
        const form = document.getElementById('profileForm');
        
        if (imageUpload && imgPreview) {
            imageUpload.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    console.log('File selected:', file.name, file.size, file.type);
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Clear any existing content and set background image
                        imgPreview.innerHTML = '';
                        imgPreview.style.backgroundImage = `url('${e.target.result}')`;
                        imgPreview.style.backgroundSize = 'cover';
                        imgPreview.style.backgroundPosition = 'center';
                        imgPreview.style.backgroundRepeat = 'no-repeat';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // Add form submit event listener
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form is being submitted...');
                const avatarFile = document.getElementById('imageUpload').files[0];
                if (avatarFile) {
                    console.log('Avatar file at submit time:', avatarFile.name, avatarFile.size);
                } else {
                    console.log('No avatar file at submit time');
                }
            });
        }
    });

    // Form validation and debugging
    function validateForm() {
        const form = document.querySelector('form');
        const formData = new FormData(form);
        
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            if (key === 'avatar') {
                console.log(key + ':', value.name, value.size, value.type);
            } else {
                console.log(key + ':', value);
            }
        }
        
        // Check if avatar file is actually in the form data
        const avatarFile = document.getElementById('imageUpload').files[0];
        if (avatarFile) {
            console.log('Avatar file found in input:', avatarFile.name, avatarFile.size);
        } else {
            console.log('No avatar file found in input');
        }
        
        return true; // Allow form submission
    }
</script>

@section('script')
    <!-- FilePond scripts for avatar upload -->
    <script src="{{ asset('assets/vendor/filepond/file-encode.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/validate-type.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/js/ready_to_use_form.js') }}"></script>
@endsection
