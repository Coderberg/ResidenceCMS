(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/auth"],{

/***/ "./assets/js/auth/_resend.js":
/*!***********************************!*\
  !*** ./assets/js/auth/_resend.js ***!
  \***********************************/
/***/ (() => {

(function ($, bootbox) {
  'use strict';

  const link = $('#resend');

  // Show confirmation link
  if (link.length > 0) {
    $.ajax({
      url: '/en/auth/should_link_be_visible',
      type: 'GET',
      data: {
        csrf_token: link.data('token')
      },
      success: function (response) {
        if (response.display === true) {
          link.show();
        }
      }
    });
  }
  const resend = () => {
    $.ajax({
      url: link.data('path'),
      type: 'POST',
      data: {
        csrf_token: link.data('token')
      },
      success: function (response) {
        link.hide();
        bootbox.alert(response.message);
      }
    });
  };
  link.click(resend);
})($, bootbox);

/***/ }),

/***/ "./assets/js/auth/auth.js":
/*!********************************!*\
  !*** ./assets/js/auth/auth.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _resend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./_resend */ "./assets/js/auth/_resend.js");
/* harmony import */ var _resend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_resend__WEBPACK_IMPORTED_MODULE_0__);




/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/auth/auth.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoianMvYXV0aC5qcyIsIm1hcHBpbmdzIjoiOzs7Ozs7OztBQUFBLENBQUMsVUFBVUEsQ0FBQyxFQUFFQyxPQUFPLEVBQUU7RUFDbkIsWUFBWTs7RUFFWixNQUFNQyxJQUFJLEdBQUdGLENBQUMsQ0FBQyxTQUFTLENBQUM7O0VBRXpCO0VBQ0EsSUFBSUUsSUFBSSxDQUFDQyxNQUFNLEdBQUcsQ0FBQyxFQUFFO0lBQ2pCSCxDQUFDLENBQUNJLElBQUksQ0FBQztNQUNIQyxHQUFHLEVBQUUsaUNBQWlDO01BQ3RDQyxJQUFJLEVBQUUsS0FBSztNQUNYQyxJQUFJLEVBQUU7UUFBRUMsVUFBVSxFQUFFTixJQUFJLENBQUNLLElBQUksQ0FBQyxPQUFPO01BQUUsQ0FBQztNQUN4Q0UsT0FBTyxFQUFFLFVBQVVDLFFBQVEsRUFBRTtRQUN6QixJQUFJQSxRQUFRLENBQUNDLE9BQU8sS0FBSyxJQUFJLEVBQUU7VUFDM0JULElBQUksQ0FBQ1UsSUFBSSxFQUFFO1FBQ2Y7TUFDSjtJQUNKLENBQUMsQ0FBQztFQUNOO0VBRUEsTUFBTUMsTUFBTSxHQUFHLE1BQU07SUFDakJiLENBQUMsQ0FBQ0ksSUFBSSxDQUFDO01BQ0hDLEdBQUcsRUFBRUgsSUFBSSxDQUFDSyxJQUFJLENBQUMsTUFBTSxDQUFDO01BQ3RCRCxJQUFJLEVBQUUsTUFBTTtNQUNaQyxJQUFJLEVBQUU7UUFBRUMsVUFBVSxFQUFFTixJQUFJLENBQUNLLElBQUksQ0FBQyxPQUFPO01BQUUsQ0FBQztNQUN4Q0UsT0FBTyxFQUFFLFVBQVVDLFFBQVEsRUFBRTtRQUN6QlIsSUFBSSxDQUFDWSxJQUFJLEVBQUU7UUFDWGIsT0FBTyxDQUFDYyxLQUFLLENBQUNMLFFBQVEsQ0FBQ00sT0FBTyxDQUFDO01BQ25DO0lBQ0osQ0FBQyxDQUFDO0VBQ04sQ0FBQztFQUVEZCxJQUFJLENBQUNlLEtBQUssQ0FBQ0osTUFBTSxDQUFDO0FBQ3RCLENBQUMsRUFBRWIsQ0FBQyxFQUFFQyxPQUFPLENBQUM7Ozs7Ozs7Ozs7Ozs7O0FDaENEIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2F1dGgvX3Jlc2VuZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvYXV0aC9hdXRoLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiAoJCwgYm9vdGJveCkge1xuICAgICd1c2Ugc3RyaWN0JztcblxuICAgIGNvbnN0IGxpbmsgPSAkKCcjcmVzZW5kJyk7XG5cbiAgICAvLyBTaG93IGNvbmZpcm1hdGlvbiBsaW5rXG4gICAgaWYgKGxpbmsubGVuZ3RoID4gMCkge1xuICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgdXJsOiAnL2VuL2F1dGgvc2hvdWxkX2xpbmtfYmVfdmlzaWJsZScsXG4gICAgICAgICAgICB0eXBlOiAnR0VUJyxcbiAgICAgICAgICAgIGRhdGE6IHsgY3NyZl90b2tlbjogbGluay5kYXRhKCd0b2tlbicpIH0sXG4gICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAocmVzcG9uc2UpIHtcbiAgICAgICAgICAgICAgICBpZiAocmVzcG9uc2UuZGlzcGxheSA9PT0gdHJ1ZSkge1xuICAgICAgICAgICAgICAgICAgICBsaW5rLnNob3coKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIGNvbnN0IHJlc2VuZCA9ICgpID0+IHtcbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHVybDogbGluay5kYXRhKCdwYXRoJyksXG4gICAgICAgICAgICB0eXBlOiAnUE9TVCcsXG4gICAgICAgICAgICBkYXRhOiB7IGNzcmZfdG9rZW46IGxpbmsuZGF0YSgndG9rZW4nKSB9LFxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKHJlc3BvbnNlKSB7XG4gICAgICAgICAgICAgICAgbGluay5oaWRlKCk7XG4gICAgICAgICAgICAgICAgYm9vdGJveC5hbGVydChyZXNwb25zZS5tZXNzYWdlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfTtcblxuICAgIGxpbmsuY2xpY2socmVzZW5kKTtcbn0pKCQsIGJvb3Rib3gpO1xuIiwiJ3VzZSBzdHJpY3QnO1xuXG5pbXBvcnQgJy4vX3Jlc2VuZCc7XG4iXSwibmFtZXMiOlsiJCIsImJvb3Rib3giLCJsaW5rIiwibGVuZ3RoIiwiYWpheCIsInVybCIsInR5cGUiLCJkYXRhIiwiY3NyZl90b2tlbiIsInN1Y2Nlc3MiLCJyZXNwb25zZSIsImRpc3BsYXkiLCJzaG93IiwicmVzZW5kIiwiaGlkZSIsImFsZXJ0IiwibWVzc2FnZSIsImNsaWNrIl0sInNvdXJjZVJvb3QiOiIifQ==