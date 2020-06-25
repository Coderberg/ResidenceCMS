'use strict';

import Cookies from 'js-cookie/src/js.cookie';

$(document).ready(function () {

    let currentUrl = window.location.href;

    $('.sidebar .nav-item').each(function () {
        let sidebarLink = $('a', this).attr('href');
        if (currentUrl.indexOf(sidebarLink) !== -1) {
            $(this).addClass('active');
        } else if (sidebarLink.indexOf('locations') !== -1 && currentUrl.indexOf('locations') !== -1) {
            $(this).addClass('active');
        }
    });

    // Toggle the side navigation
    $("#sidebarToggle").on('click', function (e) {

        let $body = $('body');
        e.preventDefault();

        if ($($body).is(".sidebar-toggled")) {
            Cookies.set('sidebar-toggled', false);
        } else {
            Cookies.set('sidebar-toggled', true);
        }

        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
    });

    // Sorting
    $('#sort_by, #state').on('change', function () {
        let value = $('#sort_by').val();
        let state = $('#state').val();
        window.location.href = '/admin/property?sort_by=' + value + '&state=' + state;
    });

});
