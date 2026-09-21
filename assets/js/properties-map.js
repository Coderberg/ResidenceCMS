/** global: ymaps */

const $map = $('#map');
const markers = $map.data('markers') || [];
const center = $map.data('center') || [27.188534, -81.128735];
const zoom = Number($map.data('zoom')) || 7;
const viewDetailsLabel = $map.data('viewDetails') || 'View details';

function escapeHtml(value) {
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function init() {
    const myMap = new ymaps.Map('map', {
        center: center,
        zoom: zoom,
    });

    markers.forEach((marker) => {
        const price = escapeHtml(marker.price);
        const category = escapeHtml(marker.category);
        const url = escapeHtml(marker.url);
        const label = escapeHtml(viewDetailsLabel);

        const geoObject = new ymaps.GeoObject({
            geometry: {
                type: 'Point',
                coordinates: [marker.lat, marker.lng],
            },
            properties: {
                hintContent: price,
                balloonContentHeader: price,
                balloonContentBody: `${category}<br><a href="${url}">${label}</a>`,
            },
        }, {preset: 'islands#blueHomeIcon'});

        myMap.geoObjects.add(geoObject);
    });
}

ymaps.ready(init);

$map.css({
    width: '100%',
    height: '85vh',
    'min-height': '400px',
});
