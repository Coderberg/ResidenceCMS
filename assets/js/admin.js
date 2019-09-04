'use strict';

$(document).ready(function () {

    let currentUrl = window.location.href;

    $('.sidebar .nav-item').each(function () {

        let sidebarLink = $('a', this).attr('href');

        if (currentUrl.indexOf(sidebarLink) !== -1) {
            $(this).addClass('active');
        }

    });

});
