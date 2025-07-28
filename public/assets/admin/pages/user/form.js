$(document).ready(function () {
    // Password confirmation validation
    $('input[name="password_confirmation"]').on('keyup', function() {
        var password = $('input[name="password"]').val();
        var confirmPassword = $(this).val();
        
        if (password !== confirmPassword) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Passwords do not match</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Form submission
    $('form#myForm').submit(function(e){
        e.preventDefault();
        e.stopPropagation();
        
        // Check password confirmation
        var password = $('input[name="password"]').val();
        var confirmPassword = $('input[name="password_confirmation"]').val();
        
        if (password && password !== confirmPassword) {
            toastr["error"]("Passwords do not match");
            return false;
        }
        
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Processing...');
        
        $.ajax({
            url: update_url,
            method: 'post',
            data: formData,
            success: function (res) {
                if (res.success) {
                    toastr["success"](res.message || "Success");
                    setTimeout(function(){ 
                        window.location.href = list_url; 
                    }, 1500);
                } else {
                    toastr["error"](res.message || "An error occurred");
                    submitBtn.prop('disabled', false).text(originalText);
                }
            },
            error: function (res){
                submitBtn.prop('disabled', false).text(originalText);
                
                if (res.status === 422) {
                    // Validation errors
                    var errors = res.responseJSON.errors;
                    var errorMessage = '';
                    
                    // Clear previous validation errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    
                    $.each(errors, function(field, messages) {
                        var input = $('[name="' + field + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                        errorMessage += messages[0] + '<br>';
                    });
                    
                    toastr["error"](errorMessage);
                } else {
                    toastr["error"]("An error occurred while processing your request");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    // Image preview
    $("#wizard-picture").change(function(){
        readURL(this);
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
