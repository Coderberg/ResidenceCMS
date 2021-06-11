'use strict';

$(document).ready(function () {
    let currentUrl = window.location.href;

    // Sidebar
    if (currentUrl.indexOf('unpublished') !== -1) {
        $('.list-group-item-action:eq(1)').addClass('active');
    } else {
        $('.list-group-item-action:eq(0)').addClass('active');
    }

    // Properties
    $('.btn-outline-secondary').click(function (e) {
        e.preventDefault();
        $(this).addClass('disabled');

        let url = $(this).attr('href');
        let $div = $(this).parent().parent().parent();

        $div.css({ opacity: '0.5' });

        $.get(url, function (data) {
            if ('ok' === data.status) {
                $div.fadeOut();
                changeCount();
            }
        });
    });

    // Change count
    function changeCount() {
        let $counter = $('.js-counter');
        let counter = $counter.text();
        counter = Number.parseInt(counter);
        counter = counter - 1;
        $counter.text(counter);
    }
});
