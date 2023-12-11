(function ($) {
    'use strict';

    const $form = $('#generate_google_auth_secret');
    const $secret = $('[name="generatedSecret"]');
    const $authentication_code = $('[name="authentication_code"]');

    // Open modal window
    $('#setUpAuthenticatorButton').click(function () {
        if ($form.data('generate-new-secret') === true) {
            $.ajax({
                method: 'GET',
                url: '/en/user/google_authenticator_code'
            }).done(function (response) {
                const { secret, qr_code } = response;
                const image = new Image();
                image.src = qr_code;

                $secret.val(secret);
                $('#generatedQrCode').html(image);
                $('#generatedSecret').html(secret);
            });
        }
    });

    // Enable 2fa
    $('#enable2fa').click(function () {
        let authentication_code = $authentication_code.val().trim();

        if (!authentication_code) {
            $authentication_code.addClass('is-invalid').focus();

            return;
        }

        $.ajax({
            method: 'PUT',
            url: $form.attr('action'),
            data: {
                secret: $secret.val(),
                authentication_code: authentication_code
            }
        })
            .done(function () {
                location.reload();
            })
            .fail(function (data) {
                showError(data.responseJSON.message);
            });
    });

    // Disable 2fa
    $('#disable2fa').click(function () {
        $.ajax({
            method: 'DELETE',
            url: $form.attr('action')
        })
            .done(function () {
                location.reload();
            })
            .fail(function () {
                location.reload();
            });
    });

    $authentication_code.keyup(function () {
        $(this).removeClass('is-invalid');
    });

    // Reset form state
    $('#setUpAuthenticator').on('hidden.bs.modal', function () {
        resetFormState();
    });

    function resetFormState() {
        $authentication_code.val('').removeClass('is-invalid');
        $('#twoFactorAuthErrorMessage').text('').addClass('d-none');
    }

    function showError(message) {
        $('#twoFactorAuthErrorMessage')
            .text(message)
            .removeClass('d-none');
    }
})(window.jQuery);
