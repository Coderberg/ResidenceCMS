(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/map"],{

/***/ "./assets/js/map.js":
/*!**************************!*\
  !*** ./assets/js/map.js ***!
  \**************************/
/***/ (() => {

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
  let currentApartment = new ymaps.Placemark([latitude, longitude], {
    hintContent: hintContent,
    balloonContent: balloonContent
  }, {
    preset: 'islands#blueHomeIcon'
  });
  myMap.geoObjects.add(currentApartment);
}
$map.css({
  width: '100%',
  height: '280px',
  'padding-top': '20px'
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/map.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoianMvbWFwLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7O0FBQUE7QUFDQUEsS0FBSyxDQUFDQyxLQUFLLENBQUNDLElBQUksQ0FBQztBQUVqQixNQUFNQyxJQUFJLEdBQUdDLENBQUMsQ0FBQyxNQUFNLENBQUM7QUFDdEIsTUFBTUMsUUFBUSxHQUFHRixJQUFJLENBQUNHLElBQUksQ0FBQyxVQUFVLENBQUM7QUFDdEMsTUFBTUMsU0FBUyxHQUFHSixJQUFJLENBQUNHLElBQUksQ0FBQyxXQUFXLENBQUM7QUFDeEMsTUFBTUUsV0FBVyxHQUFHTCxJQUFJLENBQUNNLElBQUksQ0FBQyxrQkFBa0IsQ0FBQztBQUNqRCxNQUFNQyxjQUFjLEdBQUdQLElBQUksQ0FBQ00sSUFBSSxDQUFDLHFCQUFxQixDQUFDO0FBRXZELFNBQVNQLElBQUksR0FBRztFQUNaO0VBQ0EsSUFBSVMsS0FBSyxHQUFHLElBQUlYLEtBQUssQ0FBQ1ksR0FBRyxDQUFDLEtBQUssRUFBRTtJQUM3QkMsTUFBTSxFQUFFLENBQUNSLFFBQVEsRUFBRUUsU0FBUyxDQUFDO0lBQzdCTyxJQUFJLEVBQUU7RUFDVixDQUFDLENBQUM7RUFFRixJQUFJQyxnQkFBZ0IsR0FBRyxJQUFJZixLQUFLLENBQUNnQixTQUFTLENBQ3RDLENBQUNYLFFBQVEsRUFBRUUsU0FBUyxDQUFDLEVBQ3JCO0lBQ0lDLFdBQVcsRUFBRUEsV0FBVztJQUN4QkUsY0FBYyxFQUFFQTtFQUNwQixDQUFDLEVBQ0Q7SUFDSU8sTUFBTSxFQUFFO0VBQ1osQ0FBQyxDQUNKO0VBRUROLEtBQUssQ0FBQ08sVUFBVSxDQUFDQyxHQUFHLENBQUNKLGdCQUFnQixDQUFDO0FBQzFDO0FBRUFaLElBQUksQ0FBQ2lCLEdBQUcsQ0FBQztFQUNMQyxLQUFLLEVBQUUsTUFBTTtFQUNiQyxNQUFNLEVBQUUsT0FBTztFQUNmLGFBQWEsRUFBRTtBQUNuQixDQUFDLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbWFwLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIi8qKiBnbG9iYWw6IHltYXBzICovXG55bWFwcy5yZWFkeShpbml0KTtcblxuY29uc3QgJG1hcCA9ICQoJyNtYXAnKTtcbmNvbnN0IGxhdGl0dWRlID0gJG1hcC5kYXRhKCdsYXRpdHVkZScpO1xuY29uc3QgbG9uZ2l0dWRlID0gJG1hcC5kYXRhKCdsb25naXR1ZGUnKTtcbmNvbnN0IGhpbnRDb250ZW50ID0gJG1hcC5hdHRyKCdkYXRhLWhpbnRDb250ZW50Jyk7XG5jb25zdCBiYWxsb29uQ29udGVudCA9ICRtYXAuYXR0cignZGF0YS1iYWxsb29uQ29udGVudCcpO1xuXG5mdW5jdGlvbiBpbml0KCkge1xuICAgIC8vIENyZWF0aW5nIHRoZSBtYXAuXG4gICAgbGV0IG15TWFwID0gbmV3IHltYXBzLk1hcCgnbWFwJywge1xuICAgICAgICBjZW50ZXI6IFtsYXRpdHVkZSwgbG9uZ2l0dWRlXSxcbiAgICAgICAgem9vbTogMTNcbiAgICB9KTtcblxuICAgIGxldCBjdXJyZW50QXBhcnRtZW50ID0gbmV3IHltYXBzLlBsYWNlbWFyayhcbiAgICAgICAgW2xhdGl0dWRlLCBsb25naXR1ZGVdLFxuICAgICAgICB7XG4gICAgICAgICAgICBoaW50Q29udGVudDogaGludENvbnRlbnQsXG4gICAgICAgICAgICBiYWxsb29uQ29udGVudDogYmFsbG9vbkNvbnRlbnRcbiAgICAgICAgfSxcbiAgICAgICAge1xuICAgICAgICAgICAgcHJlc2V0OiAnaXNsYW5kcyNibHVlSG9tZUljb24nXG4gICAgICAgIH1cbiAgICApO1xuXG4gICAgbXlNYXAuZ2VvT2JqZWN0cy5hZGQoY3VycmVudEFwYXJ0bWVudCk7XG59XG5cbiRtYXAuY3NzKHtcbiAgICB3aWR0aDogJzEwMCUnLFxuICAgIGhlaWdodDogJzI4MHB4JyxcbiAgICAncGFkZGluZy10b3AnOiAnMjBweCdcbn0pO1xuIl0sIm5hbWVzIjpbInltYXBzIiwicmVhZHkiLCJpbml0IiwiJG1hcCIsIiQiLCJsYXRpdHVkZSIsImRhdGEiLCJsb25naXR1ZGUiLCJoaW50Q29udGVudCIsImF0dHIiLCJiYWxsb29uQ29udGVudCIsIm15TWFwIiwiTWFwIiwiY2VudGVyIiwiem9vbSIsImN1cnJlbnRBcGFydG1lbnQiLCJQbGFjZW1hcmsiLCJwcmVzZXQiLCJnZW9PYmplY3RzIiwiYWRkIiwiY3NzIiwid2lkdGgiLCJoZWlnaHQiXSwic291cmNlUm9vdCI6IiJ9