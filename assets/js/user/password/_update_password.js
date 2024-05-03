(function ($) {
    'use strict';

    const $modal = $('#changePassword');
    const $button = $('#savePassword');
    const $alert = $('.alert', '#changePassword');

    // Send request
    $button.click(function () {
        $(this)
            .attr('disabled', true)
            .text($button.data('progress-text'));
        $.ajax({
            method: 'POST',
            url: $('#passwordForm').attr('action'),
            data: {
                password1: $('#password1').val(),
                password2: $('#password2').val()
            }
        })
            .done(function () {
                location.reload();
            })
            .fail(function (data) {
                const { message } = JSON.parse(data.responseText);
                $alert.text(message).slideDown();
            });
    });

    // Reset form state
    $modal.on('hidden.bs.modal', function () {
        resetFormState();
    });

    function resetFormState() {
        $('#password1').val('');
        $('#password2').val('').attr('disabled', true);
        $button
            .text($button.data('default-text'))
            .attr('disabled', true);
        $alert.text('').hide();
        $('.fa-check', '#changePassword')
            .removeClass('fa-check text-success')
            .addClass('fa-times text-danger');
    }
})($);
