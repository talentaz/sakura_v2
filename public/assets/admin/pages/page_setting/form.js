$(document).ready(function () {
    // Form submission
    $("form#pageForm").submit(function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Update TinyMCE content
        if (typeof tinymce !== "undefined") {
            tinymce.triggerSave();
        }

        var formData = new FormData(this);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.prop("disabled", true).text("Processing...");

        $.ajax({
            url: update_url,
            method: "post",
            data: formData,
            success: function (res) {
                if (res.success) {
                    toastr["success"](res.message || "Success");
                    setTimeout(function () {
                        window.location.href = list_url;
                    }, 1500);
                } else {
                    toastr["error"](res.message || "An error occurred");
                    submitBtn.prop("disabled", false).text(originalText);
                }
            },
            error: function (res) {
                submitBtn.prop("disabled", false).text(originalText);

                if (res.status === 422) {
                    // Validation errors
                    var errors = res.responseJSON.errors;
                    var errorMessage = "";

                    // Clear previous validation errors
                    $(".is-invalid").removeClass("is-invalid");
                    $(".invalid-feedback").remove();

                    $.each(errors, function (field, messages) {
                        var input = $('[name="' + field + '"]');
                        input.addClass("is-invalid");
                        input.after(
                            '<div class="invalid-feedback">' +
                                messages[0] +
                                "</div>"
                        );
                        errorMessage += messages[0] + "<br>";
                    });

                    toastr["error"](errorMessage);
                } else {
                    toastr["error"](
                        "An error occurred while processing your request"
                    );
                }
            },
            cache: false,
            contentType: false,
            processData: false,
        });
    });

    // Slug validation
    $('input[name="slug"]').on("blur", function () {
        let slug = $(this).val();
        if (slug) {
            // Clean up slug
            slug = slug
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, "")
                .replace(/\s+/g, "-")
                .replace(/-+/g, "-")
                .trim("-");
            $(this).val(slug);
        }
    });

    // Page type change handler
    $('select[name="page_type"]').change(function () {
        let pageType = $(this).val();

        // Auto-suggest slug based on page type
        if (!$('input[name="slug"]').val() && !$('input[name="title"]').val()) {
            switch (pageType) {
                case "home_page":
                    $('input[name="slug"]').val("home");
                    break;
                case "contact_page":
                    $('input[name="slug"]').val("contact");
                    break;
                case "about_page":
                    $('input[name="slug"]').val("about");
                    break;
            }
        }
    });

    // Character counter for description
    $('textarea[name="description"]').on("input", function () {
        let length = $(this).val().length;
        let maxLength = 160; // SEO recommended meta description length

        if (!$(this).next(".char-counter").length) {
            $(this).after('<small class="char-counter text-muted"></small>');
        }

        let counter = $(this).next(".char-counter");
        counter.text(length + " characters");

        if (length > maxLength) {
            counter.removeClass("text-muted").addClass("text-warning");
        } else {
            counter.removeClass("text-warning").addClass("text-muted");
        }
    });

    // Keywords helper
    $('input[name="keywords"]').on("blur", function () {
        let keywords = $(this).val();
        if (keywords) {
            // Clean up keywords - remove extra spaces and ensure comma separation
            keywords = keywords
                .split(",")
                .map((keyword) => keyword.trim())
                .filter((keyword) => keyword.length > 0)
                .join(", ");
            $(this).val(keywords);
        }
    });

    // Menu order validation
    $('input[name="on_menu_order"]').on("input", function () {
        let value = parseInt($(this).val());
        if (isNaN(value) || value < 0) {
            $(this).val(0);
        }
    });

    // External URL validation
    $('input[name="url"]').on("blur", function () {
        let url = $(this).val();
        if (url && !url.startsWith("http://") && !url.startsWith("https://")) {
            $(this).val("https://" + url);
        }
    });
});
