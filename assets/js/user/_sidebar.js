(function ($) {
    'use strict';

    let currentUrl = window.location.href;

    if (currentUrl.indexOf('profile') !== -1) {
        $('.list-group-item-action:eq(2)').addClass('active');
    } else if (currentUrl.indexOf('unpublished') !== -1) {
        $('.list-group-item-action:eq(1)').addClass('active');
    } else if (currentUrl.indexOf('security') !== -1) {
        $('.list-group-item-action:eq(3)').addClass('active');
    } else {
        $('.list-group-item-action:eq(0)').addClass('active');
    }
})(window.jQuery);
