(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/password"],{

/***/ "./assets/js/user/password/_update_password.js":
/*!*****************************************************!*\
  !*** ./assets/js/user/password/_update_password.js ***!
  \*****************************************************/
/***/ (() => {

(function ($) {
  'use strict';

  const $modal = $('#changePassword');
  const $button = $('#savePassword');
  const $alert = $('.alert', '#changePassword');

  // Send request
  $button.click(function () {
    let token = $('[name="password_token"]').val();
    $(this).attr('disabled', true).text($button.data('progress-text'));
    $.ajax({
      method: 'POST',
      url: $('#passwordForm').attr('action'),
      data: {
        csrf_token: token,
        password1: $('#password1').val(),
        password2: $('#password2').val()
      }
    }).done(function () {
      location.reload();
    }).fail(function (data) {
      const message = JSON.parse(data.responseText).message;
      $alert.text(message).slideDown();
    });
  });

  // Reset form state
  $modal.on('hidden.bs.modal', function () {
    resetFormState();
  });
  function resetFormState() {
    $('#password1').val('');
    $('#password2').val('').attr('disabled', true);
    $button.text($button.data('default-text')).attr('disabled', true);
    $alert.text('').hide();
    $('.fa-check', '#changePassword').removeClass('fa-check text-success').addClass('fa-times text-danger');
  }
})($);

/***/ }),

/***/ "./assets/js/user/password/_validate_password.js":
/*!*******************************************************!*\
  !*** ./assets/js/user/password/_validate_password.js ***!
  \*******************************************************/
/***/ (() => {

(function ($) {
  'use strict';

  const $button = $('#savePassword');
  const changeIcon = ($element, pass) => {
    if (true === pass) {
      $element.removeClass('fa-times text-danger').addClass('fa-check text-success');
    } else {
      $element.removeClass('fa-check text-success').addClass('fa-times text-danger');
    }
  };
  const isPasswordMatch = (password1, password2) => {
    return '' !== password1.trim() && password1.trim() === password2.trim();
  };

  // Validate input
  $('input[type=password]').keyup(function () {
    $(this).val($(this).val().trim());
    $('.alert', '#changePassword').text('').hide();
    const uppercase = /[A-ZА-Я]+/;
    const lowercase = /[a-zа-я]+/;
    const numbers = /\d+/;
    let $password2 = $('#password2');
    let password1 = $('#password1').val();
    let password2 = $password2.val();
    changeIcon($('#10char'), password1.length >= 10);
    changeIcon($('#uppercase'), uppercase.test(password1));
    changeIcon($('#lowercase'), lowercase.test(password1));
    changeIcon($('#numbers'), numbers.test(password1));
    changeIcon($('#match'), isPasswordMatch(password1, password2));
    if (password1.length >= 10 && uppercase.test(password1) && lowercase.test(password1) && numbers.test(password1)) {
      $password2.attr('disabled', false);
    }
    $button.attr('disabled', !isPasswordMatch(password1, password2)).text($button.data('default-text'));
  });
})($);

/***/ }),

/***/ "./assets/js/user/password/password.js":
/*!*********************************************!*\
  !*** ./assets/js/user/password/password.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _validate_password__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./_validate_password */ "./assets/js/user/password/_validate_password.js");
/* harmony import */ var _validate_password__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_validate_password__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _update_password__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./_update_password */ "./assets/js/user/password/_update_password.js");
/* harmony import */ var _update_password__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_update_password__WEBPACK_IMPORTED_MODULE_1__);





/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/user/password/password.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QUMsRUFBRXFDLFNBQVMsQ0FBQ0ksSUFBSSxDQUFDM0IsU0FBUyxDQUFDLENBQUM7SUFDdERnQixVQUFVLENBQUM5QixDQUFDLENBQUMsVUFBVSxDQUFDLEVBQUVzQyxPQUFPLENBQUNHLElBQUksQ0FBQzNCLFNBQVMsQ0FBQyxDQUFDO0lBQ2xEZ0IsVUFBVSxDQUNOOUIsQ0FBQyxDQUFDLFFBQVEsQ0FBQyxFQUNYaUMsZUFBZSxDQUFDbkIsU0FBUyxFQUFFQyxTQUFTLENBQUMsQ0FDeEM7SUFFRCxJQUNJRCxTQUFTLENBQUMwQixNQUFNLElBQUksRUFBRSxJQUN0QkosU0FBUyxDQUFDSyxJQUFJLENBQUMzQixTQUFTLENBQUMsSUFDekJ1QixTQUFTLENBQUNJLElBQUksQ0FBQzNCLFNBQVMsQ0FBQyxJQUN6QndCLE9BQU8sQ0FBQ0csSUFBSSxDQUFDM0IsU0FBUyxDQUFDLEVBQ3pCO01BQ0V5QixVQUFVLENBQUNoQyxJQUFJLENBQUMsVUFBVSxFQUFFLEtBQUssQ0FBQztJQUN0QztJQUVBTCxPQUFPLENBQ0ZLLElBQUksQ0FBQyxVQUFVLEVBQUUsQ0FBQzBCLGVBQWUsQ0FBQ25CLFNBQVMsRUFBRUMsU0FBUyxDQUFDLENBQUMsQ0FDeERQLElBQUksQ0FBQ04sT0FBTyxDQUFDTyxJQUFJLENBQUMsY0FBYyxDQUFDLENBQUM7RUFDM0MsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxFQUFFVCxDQUFDLENBQUM7Ozs7Ozs7Ozs7Ozs7Ozs7QUMxRFE7O0FBRWlCIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXIvcGFzc3dvcmQvX3VwZGF0ZV9wYXNzd29yZC5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvdXNlci9wYXNzd29yZC9fdmFsaWRhdGVfcGFzc3dvcmQuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL3VzZXIvcGFzc3dvcmQvcGFzc3dvcmQuanMiXSwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uICgkKSB7XG4gICAgJ3VzZSBzdHJpY3QnO1xuXG4gICAgY29uc3QgJG1vZGFsID0gJCgnI2NoYW5nZVBhc3N3b3JkJyk7XG4gICAgY29uc3QgJGJ1dHRvbiA9ICQoJyNzYXZlUGFzc3dvcmQnKTtcbiAgICBjb25zdCAkYWxlcnQgPSAkKCcuYWxlcnQnLCAnI2NoYW5nZVBhc3N3b3JkJyk7XG5cbiAgICAvLyBTZW5kIHJlcXVlc3RcbiAgICAkYnV0dG9uLmNsaWNrKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbGV0IHRva2VuID0gJCgnW25hbWU9XCJwYXNzd29yZF90b2tlblwiXScpLnZhbCgpO1xuICAgICAgICAkKHRoaXMpXG4gICAgICAgICAgICAuYXR0cignZGlzYWJsZWQnLCB0cnVlKVxuICAgICAgICAgICAgLnRleHQoJGJ1dHRvbi5kYXRhKCdwcm9ncmVzcy10ZXh0JykpO1xuICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgbWV0aG9kOiAnUE9TVCcsXG4gICAgICAgICAgICB1cmw6ICQoJyNwYXNzd29yZEZvcm0nKS5hdHRyKCdhY3Rpb24nKSxcbiAgICAgICAgICAgIGRhdGE6IHtcbiAgICAgICAgICAgICAgICBjc3JmX3Rva2VuOiB0b2tlbixcbiAgICAgICAgICAgICAgICBwYXNzd29yZDE6ICQoJyNwYXNzd29yZDEnKS52YWwoKSxcbiAgICAgICAgICAgICAgICBwYXNzd29yZDI6ICQoJyNwYXNzd29yZDInKS52YWwoKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgICAgICAgICAgLmRvbmUoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIC5mYWlsKGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgY29uc3QgbWVzc2FnZSA9IEpTT04ucGFyc2UoZGF0YS5yZXNwb25zZVRleHQpLm1lc3NhZ2U7XG4gICAgICAgICAgICAgICAgJGFsZXJ0LnRleHQobWVzc2FnZSkuc2xpZGVEb3duKCk7XG4gICAgICAgICAgICB9KTtcbiAgICB9KTtcblxuICAgIC8vIFJlc2V0IGZvcm0gc3RhdGVcbiAgICAkbW9kYWwub24oJ2hpZGRlbi5icy5tb2RhbCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgcmVzZXRGb3JtU3RhdGUoKTtcbiAgICB9KTtcblxuICAgIGZ1bmN0aW9uIHJlc2V0Rm9ybVN0YXRlKCkge1xuICAgICAgICAkKCcjcGFzc3dvcmQxJykudmFsKCcnKTtcbiAgICAgICAgJCgnI3Bhc3N3b3JkMicpLnZhbCgnJykuYXR0cignZGlzYWJsZWQnLCB0cnVlKTtcbiAgICAgICAgJGJ1dHRvblxuICAgICAgICAgICAgLnRleHQoJGJ1dHRvbi5kYXRhKCdkZWZhdWx0LXRleHQnKSlcbiAgICAgICAgICAgIC5hdHRyKCdkaXNhYmxlZCcsIHRydWUpO1xuICAgICAgICAkYWxlcnQudGV4dCgnJykuaGlkZSgpO1xuICAgICAgICAkKCcuZmEtY2hlY2snLCAnI2NoYW5nZVBhc3N3b3JkJylcbiAgICAgICAgICAgIC5yZW1vdmVDbGFzcygnZmEtY2hlY2sgdGV4dC1zdWNjZXNzJylcbiAgICAgICAgICAgIC5hZGRDbGFzcygnZmEtdGltZXMgdGV4dC1kYW5nZXInKTtcbiAgICB9XG59KSgkKTtcbiIsIihmdW5jdGlvbiAoJCkge1xuICAgICd1c2Ugc3RyaWN0JztcblxuICAgIGNvbnN0ICRidXR0b24gPSAkKCcjc2F2ZVBhc3N3b3JkJyk7XG5cbiAgICBjb25zdCBjaGFuZ2VJY29uID0gKCRlbGVtZW50LCBwYXNzKSA9PiB7XG4gICAgICAgIGlmICh0cnVlID09PSBwYXNzKSB7XG4gICAgICAgICAgICAkZWxlbWVudFxuICAgICAgICAgICAgICAgIC5yZW1vdmVDbGFzcygnZmEtdGltZXMgdGV4dC1kYW5nZXInKVxuICAgICAgICAgICAgICAgIC5hZGRDbGFzcygnZmEtY2hlY2sgdGV4dC1zdWNjZXNzJyk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAkZWxlbWVudFxuICAgICAgICAgICAgICAgIC5yZW1vdmVDbGFzcygnZmEtY2hlY2sgdGV4dC1zdWNjZXNzJylcbiAgICAgICAgICAgICAgICAuYWRkQ2xhc3MoJ2ZhLXRpbWVzIHRleHQtZGFuZ2VyJyk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgY29uc3QgaXNQYXNzd29yZE1hdGNoID0gKHBhc3N3b3JkMSwgcGFzc3dvcmQyKSA9PiB7XG4gICAgICAgIHJldHVybiAoXG4gICAgICAgICAgICAnJyAhPT0gcGFzc3dvcmQxLnRyaW0oKSAmJlxuICAgICAgICAgICAgcGFzc3dvcmQxLnRyaW0oKSA9PT0gcGFzc3dvcmQyLnRyaW0oKVxuICAgICAgICApO1xuICAgIH07XG5cbiAgICAvLyBWYWxpZGF0ZSBpbnB1dFxuICAgICQoJ2lucHV0W3R5cGU9cGFzc3dvcmRdJykua2V5dXAoZnVuY3Rpb24gKCkge1xuICAgICAgICAkKHRoaXMpLnZhbCgkKHRoaXMpLnZhbCgpLnRyaW0oKSk7XG4gICAgICAgICQoJy5hbGVydCcsICcjY2hhbmdlUGFzc3dvcmQnKS50ZXh0KCcnKS5oaWRlKCk7XG5cbiAgICAgICAgY29uc3QgdXBwZXJjYXNlID0gL1tBLVrQkC3Qr10rLztcbiAgICAgICAgY29uc3QgbG93ZXJjYXNlID0gL1thLXrQsC3Rj10rLztcbiAgICAgICAgY29uc3QgbnVtYmVycyA9IC9cXGQrLztcbiAgICAgICAgbGV0ICRwYXNzd29yZDIgPSAkKCcjcGFzc3dvcmQyJyk7XG4gICAgICAgIGxldCBwYXNzd29yZDEgPSAkKCcjcGFzc3dvcmQxJykudmFsKCk7XG4gICAgICAgIGxldCBwYXNzd29yZDIgPSAkcGFzc3dvcmQyLnZhbCgpO1xuXG4gICAgICAgIGNoYW5nZUljb24oJCgnIzEwY2hhcicpLCBwYXNzd29yZDEubGVuZ3RoID49IDEwKTtcbiAgICAgICAgY2hhbmdlSWNvbigkKCcjdXBwZXJjYXNlJyksIHVwcGVyY2FzZS50ZXN0KHBhc3N3b3JkMSkpO1xuICAgICAgICBjaGFuZ2VJY29uKCQoJyNsb3dlcmNhc2UnKSwgbG93ZXJjYXNlLnRlc3QocGFzc3dvcmQxKSk7XG4gICAgICAgIGNoYW5nZUljb24oJCgnI251bWJlcnMnKSwgbnVtYmVycy50ZXN0KHBhc3N3b3JkMSkpO1xuICAgICAgICBjaGFuZ2VJY29uKFxuICAgICAgICAgICAgJCgnI21hdGNoJyksXG4gICAgICAgICAgICBpc1Bhc3N3b3JkTWF0Y2gocGFzc3dvcmQxLCBwYXNzd29yZDIpXG4gICAgICAgICk7XG5cbiAgICAgICAgaWYgKFxuICAgICAgICAgICAgcGFzc3dvcmQxLmxlbmd0aCA+PSAxMCAmJlxuICAgICAgICAgICAgdXBwZXJjYXNlLnRlc3QocGFzc3dvcmQxKSAmJlxuICAgICAgICAgICAgbG93ZXJjYXNlLnRlc3QocGFzc3dvcmQxKSAmJlxuICAgICAgICAgICAgbnVtYmVycy50ZXN0KHBhc3N3b3JkMSlcbiAgICAgICAgKSB7XG4gICAgICAgICAgICAkcGFzc3dvcmQyLmF0dHIoJ2Rpc2FibGVkJywgZmFsc2UpO1xuICAgICAgICB9XG5cbiAgICAgICAgJGJ1dHRvblxuICAgICAgICAgICAgLmF0dHIoJ2Rpc2FibGVkJywgIWlzUGFzc3dvcmRNYXRjaChwYXNzd29yZDEsIHBhc3N3b3JkMikpXG4gICAgICAgICAgICAudGV4dCgkYnV0dG9uLmRhdGEoJ2RlZmF1bHQtdGV4dCcpKTtcbiAgICB9KTtcbn0pKCQpO1xuIiwiJ3VzZSBzdHJpY3QnO1xuXG5pbXBvcnQgJy4vX3ZhbGlkYXRlX3Bhc3N3b3JkJztcbmltcG9ydCAnLi9fdXBkYXRlX3Bhc3N3b3JkJztcbiJdLCJuYW1lcyI6WyIkIiwiJG1vZGFsIiwiJGJ1dHRvbiIsIiRhbGVydCIsImNsaWNrIiwidG9rZW4iLCJ2YWwiLCJhdHRyIiwidGV4dCIsImRhdGEiLCJhamF4IiwibWV0aG9kIiwidXJsIiwiY3NyZl90b2tlbiIsInBhc3N3b3JkMSIsInBhc3N3b3JkMiIsImRvbmUiLCJsb2NhdGlvbiIsInJlbG9hZCIsImZhaWwiLCJtZXNzYWdlIiwiSlNPTiIsInBhcnNlIiwicmVzcG9uc2VUZXh0Iiwic2xpZGVEb3duIiwib24iLCJyZXNldEZvcm1TdGF0ZSIsImhpZGUiLCJyZW1vdmVDbGFzcyIsImFkZENsYXNzIiwiY2hhbmdlSWNvbiIsIiRlbGVtZW50IiwicGFzcyIsImlzUGFzc3dvcmRNYXRjaCIsInRyaW0iLCJrZXl1cCIsInVwcGVyY2FzZSIsImxvd2VyY2FzZSIsIm51bWJlcnMiLCIkcGFzc3dvcmQyIiwibGVuZ3RoIiwidGVzdCJdLCJzb3VyY2VSb290IjoiIn0=