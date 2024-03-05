(function ($) {
    'use strict';

    // Properties
    $('.btn-outline-secondary').click(function (e) {
        e.preventDefault();
        $(this).addClass('disabled');

        let url = $(this).attr('href');
        let $div = $(this).parent().parent().parent();

        $div.css({ opacity: '0.5' });

        $.get(url).done(function () {
            $div.fadeOut();
            changeCount();
        });
    });

    // Change count
    function changeCount() {
        let $counter = $('.js-counter');
        let counter = $counter.text();
        counter = Number.parseInt(counter);
        counter -= 1;
        $counter.text(counter);
    }
})($);
