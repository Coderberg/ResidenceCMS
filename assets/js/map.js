/** global: ymaps */
ymaps.ready(init);

const $map = $('#map');
const latitude = $map.data('latitude');
const longitude = $map.data('longitude');
const hintContent = $map.attr('data-hintContent');
const balloonContent = $map.attr('data-balloonContent');

function init() {
    // Creating the map.
    let myMap = new ymaps.Map('map', {
        center: [latitude, longitude],
        zoom: 13
    });

    let currentApartment = new ymaps.Placemark(
        [latitude, longitude],
        {
            hintContent: hintContent,
            balloonContent: balloonContent
        },
        {
            preset: 'islands#blueHomeIcon'
        }
    );

    myMap.geoObjects.add(currentApartment);
}

$map.css({
    width: '100%',
    height: '280px',
    'padding-top': '20px'
});
