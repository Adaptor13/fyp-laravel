// filepond
  const pondInput = FilePond.create(
    document.querySelector('#id'),
    {
      labelIdle: `<i class="ti ti-cloud-upload fs-4"></i> <div class="filepond--label-action text-decoration-none">Upload Your Images</div>`,
    }
  );
  
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        // Get the preview target from data-preview attribute
        var previewTarget = $(input).data('preview');
        if (previewTarget) {
          $(previewTarget)
            .css('background-image', 'url(' + e.target.result + ')')
            .hide()
            .fadeIn(650);
        }
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

// Attach event listener to all inputs that have data-preview
$("input[type='file'][data-preview]").change(function() {
  readURL(this);
});