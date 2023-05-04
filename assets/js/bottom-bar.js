(function ($) {
    'use strict';

    const $phone = $('#phone');
    const $email = $('#email');

    $('body').append(`<div class="bottom-bar">
        <a class="btn-right" href="${$phone.attr(
            'href'
        )}">${$phone.attr('title')}</a>
        <a class="btn-left" href="${$email.attr(
            'href'
        )}">${$email.attr('title')}</a>
    </div>`);
})(window.jQuery);
