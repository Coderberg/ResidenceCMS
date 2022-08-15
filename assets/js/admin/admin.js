'use strict';

import Cookies from 'js-cookie/src/js.cookie';
import '../common/_delete_button';

$(document).ready(() => {
    let currentUrl = window.location.href;

    $('.sidebar .nav-item').each(function () {
        let sidebarLink = $('a', this).attr('href');
        if (
            currentUrl.includes(sidebarLink) ||
            (sidebarLink.includes('locations') &&
                currentUrl.includes('locations'))
        ) {
            $(this).addClass('active');
        }
    });

    // Toggle the side navigation
    $('#sidebarToggle').on('click', (e) => {
        let $body = $('body');
        e.preventDefault();

        if ($($body).is('.sidebar-toggled')) {
            Cookies.set('sidebar-toggled', false);
        } else {
            Cookies.set('sidebar-toggled', true);
        }

        $body.toggleClass('sidebar-toggled');
        $('.sidebar').toggleClass('toggled');
    });

    // Sorting
    $('#sort_by, #state').on('change', () => {
        let value = $('#sort_by').val();
        let state = $('#state').val();
        window.location.href =
            window.location.pathname +
            '?sort_by=' +
            value +
            '&state=' +
            state;
    });
});
