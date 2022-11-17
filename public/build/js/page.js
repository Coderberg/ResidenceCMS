"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/page"],{

/***/ "./assets/js/page.js":
/*!***************************!*\
  !*** ./assets/js/page.js ***!
  \***************************/
/***/ (() => {



$(document).ready(function () {
  let $checkbox = $('#page_add_contact_form');
  let $emailContainer = $('#email-address');
  let $emailField = $('#page_contact_email_address');
  if ($checkbox.attr('checked') === 'checked') {
    $emailContainer.slideDown();
    $emailField.prop('required', true);
  }

  // Event Handler
  $checkbox.on('change', function () {
    updateDisplay();
  });

  // Action

  function updateDisplay() {
    let isChecked = $checkbox.is(':checked');
    if (isChecked) {
      $emailContainer.slideDown();
      $emailField.focus().prop('required', true);
    } else {
      $emailContainer.slideUp();
      $emailField.prop('required', false);
    }
  }
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/page.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoianMvcGFnZS5qcyIsIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7QUFBYTs7QUFFYkEsQ0FBQyxDQUFDQyxRQUFRLENBQUMsQ0FBQ0MsS0FBSyxDQUFDLFlBQVk7RUFDMUIsSUFBSUMsU0FBUyxHQUFHSCxDQUFDLENBQUMsd0JBQXdCLENBQUM7RUFDM0MsSUFBSUksZUFBZSxHQUFHSixDQUFDLENBQUMsZ0JBQWdCLENBQUM7RUFDekMsSUFBSUssV0FBVyxHQUFHTCxDQUFDLENBQUMsNkJBQTZCLENBQUM7RUFFbEQsSUFBSUcsU0FBUyxDQUFDRyxJQUFJLENBQUMsU0FBUyxDQUFDLEtBQUssU0FBUyxFQUFFO0lBQ3pDRixlQUFlLENBQUNHLFNBQVMsRUFBRTtJQUMzQkYsV0FBVyxDQUFDRyxJQUFJLENBQUMsVUFBVSxFQUFFLElBQUksQ0FBQztFQUN0Qzs7RUFFQTtFQUNBTCxTQUFTLENBQUNNLEVBQUUsQ0FBQyxRQUFRLEVBQUUsWUFBWTtJQUMvQkMsYUFBYSxFQUFFO0VBQ25CLENBQUMsQ0FBQzs7RUFFRjs7RUFFQSxTQUFTQSxhQUFhLEdBQUc7SUFDckIsSUFBSUMsU0FBUyxHQUFHUixTQUFTLENBQUNTLEVBQUUsQ0FBQyxVQUFVLENBQUM7SUFFeEMsSUFBSUQsU0FBUyxFQUFFO01BQ1hQLGVBQWUsQ0FBQ0csU0FBUyxFQUFFO01BQzNCRixXQUFXLENBQUNRLEtBQUssRUFBRSxDQUFDTCxJQUFJLENBQUMsVUFBVSxFQUFFLElBQUksQ0FBQztJQUM5QyxDQUFDLE1BQU07TUFDSEosZUFBZSxDQUFDVSxPQUFPLEVBQUU7TUFDekJULFdBQVcsQ0FBQ0csSUFBSSxDQUFDLFVBQVUsRUFBRSxLQUFLLENBQUM7SUFDdkM7RUFDSjtBQUNKLENBQUMsQ0FBQyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9qcy9wYWdlLmpzIl0sInNvdXJjZXNDb250ZW50IjpbIid1c2Ugc3RyaWN0JztcblxuJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkge1xuICAgIGxldCAkY2hlY2tib3ggPSAkKCcjcGFnZV9hZGRfY29udGFjdF9mb3JtJyk7XG4gICAgbGV0ICRlbWFpbENvbnRhaW5lciA9ICQoJyNlbWFpbC1hZGRyZXNzJyk7XG4gICAgbGV0ICRlbWFpbEZpZWxkID0gJCgnI3BhZ2VfY29udGFjdF9lbWFpbF9hZGRyZXNzJyk7XG5cbiAgICBpZiAoJGNoZWNrYm94LmF0dHIoJ2NoZWNrZWQnKSA9PT0gJ2NoZWNrZWQnKSB7XG4gICAgICAgICRlbWFpbENvbnRhaW5lci5zbGlkZURvd24oKTtcbiAgICAgICAgJGVtYWlsRmllbGQucHJvcCgncmVxdWlyZWQnLCB0cnVlKTtcbiAgICB9XG5cbiAgICAvLyBFdmVudCBIYW5kbGVyXG4gICAgJGNoZWNrYm94Lm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHVwZGF0ZURpc3BsYXkoKTtcbiAgICB9KTtcblxuICAgIC8vIEFjdGlvblxuXG4gICAgZnVuY3Rpb24gdXBkYXRlRGlzcGxheSgpIHtcbiAgICAgICAgbGV0IGlzQ2hlY2tlZCA9ICRjaGVja2JveC5pcygnOmNoZWNrZWQnKTtcblxuICAgICAgICBpZiAoaXNDaGVja2VkKSB7XG4gICAgICAgICAgICAkZW1haWxDb250YWluZXIuc2xpZGVEb3duKCk7XG4gICAgICAgICAgICAkZW1haWxGaWVsZC5mb2N1cygpLnByb3AoJ3JlcXVpcmVkJywgdHJ1ZSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAkZW1haWxDb250YWluZXIuc2xpZGVVcCgpO1xuICAgICAgICAgICAgJGVtYWlsRmllbGQucHJvcCgncmVxdWlyZWQnLCBmYWxzZSk7XG4gICAgICAgIH1cbiAgICB9XG59KTtcbiJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsIiRjaGVja2JveCIsIiRlbWFpbENvbnRhaW5lciIsIiRlbWFpbEZpZWxkIiwiYXR0ciIsInNsaWRlRG93biIsInByb3AiLCJvbiIsInVwZGF0ZURpc3BsYXkiLCJpc0NoZWNrZWQiLCJpcyIsImZvY3VzIiwic2xpZGVVcCJdLCJzb3VyY2VSb290IjoiIn0=