document.addEventListener('DOMContentLoaded', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.showValidationErrors = (errors, isScroll = true) => {
        let firstErrorElement = null;

        for (const key in errors) {
            const errorEl = $(`.${key}_error`);
            errorEl.html(errors[key]);

            if (!firstErrorElement) {
                firstErrorElement = errorEl;
            }
        }

        if (isScroll && firstErrorElement && firstErrorElement.length) {
            $('html, body').animate({
                scrollTop: firstErrorElement.offset().top - 200
            }, 600);
        }
    };

    window.showAlert = (title = '', html = '', icon = 'success') => {
        Swal.fire({
            title: title,
            text: html,
            icon: icon
        });
    }
});