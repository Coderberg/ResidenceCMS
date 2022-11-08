(function ($, bootbox) {
    'use strict';

    // Confirm deletion
    $('[data-type="delete"]').click(function (e) {
        e.preventDefault();
        const $form = $(this).closest('form');
        const message = $(this).data('message');
        const confirmationText = $(this).data('confirmation-text');
        const cancellationText = $(this).data('cancellation-text');

        bootbox.confirm({
            message,
            buttons: {
                cancel: {
                    label: cancellationText,
                    className: 'btn-light'
                },
                confirm: {
                    label: confirmationText,
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $form.submit();
                }
            }
        });
    });
})($, bootbox);
