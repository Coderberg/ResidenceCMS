(function ($) {
    'use strict';

    const renderOptions = (item) =>
        `<option value="${item.id}">${item.name}</option>`;

    const removeOptions = (selectIds) => {
        $.each(selectIds, function (index, selectId) {
            $(selectId + ' option').each(function () {
                if ($(this).val() !== '') {
                    $(this).remove();
                }
            });
        });
    };

    const clearOptions = () =>
        removeOptions([
            '#property_district',
            '#property_neighborhood',
            '#property_metro_station'
        ]);

    $('body').on('change', '#property_city', function () {
        clearOptions();
        let cityId = $(this).val();

        const url = '/en/city/' + cityId + '.json';

        if ('' === cityId) {
            return;
        }

        $.get(url).done((data) => {
            let { districts, neighborhoods, metro_stations } = data;

            districts = districts.map((item) => renderOptions(item));
            neighborhoods = neighborhoods.map((item) =>
                renderOptions(item)
            );
            metro_stations = metro_stations.map((item) =>
                renderOptions(item)
            );

            $('#property_district').append(districts);
            $('#property_neighborhood').append(neighborhoods);
            $('#property_metro_station').append(metro_stations);
        });
    });
})(window.jQuery);
