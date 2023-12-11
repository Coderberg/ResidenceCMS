'use strict';

const changeButtons = () => {
    $('.js-down-one')
        .removeClass('js-down-one')
        .addClass('js-up-one')
        .html('<i class="fas fa-arrow-up"></i> Up one');

    $('.js-move:first')
        .removeClass('js-up-one')
        .addClass('js-down-one')
        .html('<i class="fas fa-arrow-down"></i> Down one');
};

const sendRequest = () => {
    const item = $('.js-move');

    if (item.length > 1) {
        let items = [];

        item.each(function () {
            items.push(parseInt($(this).attr('id'), 10));
        });

        $.ajax({
            method: 'POST',
            url: '/en/admin/menu/sort',
            data: { items: items }
        });
    }
};

$(document).ready(function () {
    $('body').on('click', '.js-move', function () {
        let row = $(this).parents('tr:first');

        if ($(this).is('.js-up-one')) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }

        changeButtons();
        sendRequest();
    });
});
