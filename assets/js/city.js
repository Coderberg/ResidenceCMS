'use strict';

$(document).ready(function () {

    let $locality = $('#property_locality');
    let $title = $('#property_title');
    let $description = $('#property_description');
    let $address = $('#property_address');
    let $content = $('#property_content');

    $locality.change(function () {

        let $form = $(this).closest('form');
        let data = {};
        data[$locality.attr('name')] = $locality.val();
        data[$title.attr('name')] = $title.val();
        data[$description.attr('name')] = $description.val();
        data[$address.attr('name')] = $address.val();
        data[$content.attr('name')] = $content.val();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function (html) {
                $('#property_area').replaceWith(
                    $(html).find('#property_area')
                );
            }
        });
    });

});
