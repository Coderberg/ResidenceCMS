'use strict';

$(document).ready(function () {

    let $city = $('#property_city');
    let $title = $('#property_title');
    let $description = $('#property_meta_description');
    let $address = $('#property_address');
    let $content = $('#property_content');

    $city.change(function () {

        let $form = $(this).closest('form');
        let data = getPropertyData();

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            success: function (html) {
                $('#property_district').replaceWith(
                    $(html).find('#property_district')
                );
                $('#property_neighborhood').replaceWith(
                    $(html).find('#property_neighborhood')
                );
                $('#property_metro_station').replaceWith(
                    $(html).find('#property_metro_station')
                );
            }
        });
    });

    function getPropertyData() {
        let data = {};
        data[$city.attr('name')] = $city.val();
        data[$title.attr('name')] = $title.val();
        data[$description.attr('name')] = $description.val();
        data[$address.attr('name')] = $address.val();
        data[$content.attr('name')] = $content.val();

        return data;
    }
});
