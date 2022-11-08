(function ($, bootbox) {
    'use strict';

    const link = $('#resend');

    // Show confirmation link
    if (link.length > 0) {
        $.ajax({
            url: '/en/auth/should_link_be_visible',
            type: 'GET',
            data: { csrf_token: link.data('token') },
            success: function (response) {
                if (response.display === true) {
                    link.show();
                }
            }
        });
    }

    const resend = () => {
        $.ajax({
            url: link.data('path'),
            type: 'POST',
            data: { csrf_token: link.data('token') },
            success: function (response) {
                link.hide();
                bootbox.alert(response.message);
            }
        });
    };

    link.click(resend);
})($, bootbox);
