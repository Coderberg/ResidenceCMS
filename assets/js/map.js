/** global: ymaps */
ymaps.ready(function () {
    var t = new ymaps.Map("map", {center: [latitude, longitude], zoom: 13});
    cur_flat = new ymaps.Placemark([latitude, longitude], {
        hintContent: hintContent,
        balloonContent: balloonContent
    }, {preset: "islands#blueHomeIcon"}), t.geoObjects.add(cur_flat)
});

$("#map").css({width: "100%", height: "250px", "padding-top": "20px"});
