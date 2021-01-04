(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/map"],{

/***/ "./assets/js/map.js":
/*!**************************!*\
  !*** ./assets/js/map.js ***!
  \**************************/
/*! no static exports found */
/***/ (function(module, exports) {

/** global: ymaps */
ymaps.ready(init);
var $map = $('#map');
var latitude = $map.attr('data-latitude');
var longitude = $map.attr('data-longitude');
var hintContent = $map.attr('data-hintContent');
var balloonContent = $map.attr('data-balloonContent');

function init() {
  // Creating the map.
  var myMap = new ymaps.Map("map", {
    center: [latitude, longitude],
    zoom: 13
  });
  var currentApartment = new ymaps.Placemark([latitude, longitude], {
    hintContent: hintContent,
    balloonContent: balloonContent
  }, {
    preset: "islands#blueHomeIcon"
  });
  myMap.geoObjects.add(currentApartment);
}

$("#map").css({
  width: "100%",
  height: "280px",
  "padding-top": "20px"
});

/***/ })

},[["./assets/js/map.js","runtime"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvbWFwLmpzIl0sIm5hbWVzIjpbInltYXBzIiwicmVhZHkiLCJpbml0IiwiJG1hcCIsIiQiLCJsYXRpdHVkZSIsImF0dHIiLCJsb25naXR1ZGUiLCJoaW50Q29udGVudCIsImJhbGxvb25Db250ZW50IiwibXlNYXAiLCJNYXAiLCJjZW50ZXIiLCJ6b29tIiwiY3VycmVudEFwYXJ0bWVudCIsIlBsYWNlbWFyayIsInByZXNldCIsImdlb09iamVjdHMiLCJhZGQiLCJjc3MiLCJ3aWR0aCIsImhlaWdodCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7O0FBQUE7QUFDQUEsS0FBSyxDQUFDQyxLQUFOLENBQVlDLElBQVo7QUFFQSxJQUFNQyxJQUFJLEdBQUdDLENBQUMsQ0FBQyxNQUFELENBQWQ7QUFDQSxJQUFNQyxRQUFRLEdBQUdGLElBQUksQ0FBQ0csSUFBTCxDQUFVLGVBQVYsQ0FBakI7QUFDQSxJQUFNQyxTQUFTLEdBQUdKLElBQUksQ0FBQ0csSUFBTCxDQUFVLGdCQUFWLENBQWxCO0FBQ0EsSUFBTUUsV0FBVyxHQUFHTCxJQUFJLENBQUNHLElBQUwsQ0FBVSxrQkFBVixDQUFwQjtBQUNBLElBQU1HLGNBQWMsR0FBR04sSUFBSSxDQUFDRyxJQUFMLENBQVUscUJBQVYsQ0FBdkI7O0FBRUEsU0FBU0osSUFBVCxHQUFlO0FBQ1g7QUFDQSxNQUFJUSxLQUFLLEdBQUcsSUFBSVYsS0FBSyxDQUFDVyxHQUFWLENBQWMsS0FBZCxFQUFxQjtBQUM3QkMsVUFBTSxFQUFFLENBQUNQLFFBQUQsRUFBV0UsU0FBWCxDQURxQjtBQUU3Qk0sUUFBSSxFQUFFO0FBRnVCLEdBQXJCLENBQVo7QUFLQSxNQUFJQyxnQkFBZ0IsR0FBRyxJQUFJZCxLQUFLLENBQUNlLFNBQVYsQ0FBb0IsQ0FBQ1YsUUFBRCxFQUFXRSxTQUFYLENBQXBCLEVBQTJDO0FBQzlEQyxlQUFXLEVBQUVBLFdBRGlEO0FBRTlEQyxrQkFBYyxFQUFFQTtBQUY4QyxHQUEzQyxFQUdwQjtBQUNDTyxVQUFNLEVBQUU7QUFEVCxHQUhvQixDQUF2QjtBQU9BTixPQUFLLENBQUNPLFVBQU4sQ0FBaUJDLEdBQWpCLENBQXFCSixnQkFBckI7QUFDSDs7QUFFRFYsQ0FBQyxDQUFDLE1BQUQsQ0FBRCxDQUFVZSxHQUFWLENBQWM7QUFDVkMsT0FBSyxFQUFFLE1BREc7QUFFVkMsUUFBTSxFQUFFLE9BRkU7QUFFTyxpQkFBZTtBQUZ0QixDQUFkLEUiLCJmaWxlIjoianMvbWFwLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyoqIGdsb2JhbDogeW1hcHMgKi9cclxueW1hcHMucmVhZHkoaW5pdCk7XHJcblxyXG5jb25zdCAkbWFwID0gJCgnI21hcCcpO1xyXG5jb25zdCBsYXRpdHVkZSA9ICRtYXAuYXR0cignZGF0YS1sYXRpdHVkZScpO1xyXG5jb25zdCBsb25naXR1ZGUgPSAkbWFwLmF0dHIoJ2RhdGEtbG9uZ2l0dWRlJyk7XHJcbmNvbnN0IGhpbnRDb250ZW50ID0gJG1hcC5hdHRyKCdkYXRhLWhpbnRDb250ZW50Jyk7XHJcbmNvbnN0IGJhbGxvb25Db250ZW50ID0gJG1hcC5hdHRyKCdkYXRhLWJhbGxvb25Db250ZW50Jyk7XHJcblxyXG5mdW5jdGlvbiBpbml0KCl7XHJcbiAgICAvLyBDcmVhdGluZyB0aGUgbWFwLlxyXG4gICAgbGV0IG15TWFwID0gbmV3IHltYXBzLk1hcChcIm1hcFwiLCB7XHJcbiAgICAgICAgY2VudGVyOiBbbGF0aXR1ZGUsIGxvbmdpdHVkZV0sXHJcbiAgICAgICAgem9vbTogMTNcclxuICAgIH0pO1xyXG5cclxuICAgIGxldCBjdXJyZW50QXBhcnRtZW50ID0gbmV3IHltYXBzLlBsYWNlbWFyayhbbGF0aXR1ZGUsIGxvbmdpdHVkZV0sIHtcclxuICAgICAgICBoaW50Q29udGVudDogaGludENvbnRlbnQsXHJcbiAgICAgICAgYmFsbG9vbkNvbnRlbnQ6IGJhbGxvb25Db250ZW50XHJcbiAgICB9LCB7XHJcbiAgICAgICAgcHJlc2V0OiBcImlzbGFuZHMjYmx1ZUhvbWVJY29uXCJcclxuICAgIH0pO1xyXG5cclxuICAgIG15TWFwLmdlb09iamVjdHMuYWRkKGN1cnJlbnRBcGFydG1lbnQpXHJcbn1cclxuXHJcbiQoXCIjbWFwXCIpLmNzcyh7XHJcbiAgICB3aWR0aDogXCIxMDAlXCIsXHJcbiAgICBoZWlnaHQ6IFwiMjgwcHhcIiwgXCJwYWRkaW5nLXRvcFwiOiBcIjIwcHhcIlxyXG59KTtcclxuIl0sInNvdXJjZVJvb3QiOiIifQ==