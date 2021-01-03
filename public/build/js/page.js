(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/page"],{

/***/ "./assets/js/page.js":
/*!***************************!*\
  !*** ./assets/js/page.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


$(document).ready(function () {
  var $checkbox = $('#page_add_contact_form');
  var $emailContainer = $('#email-address');
  var $emailField = $('#page_contact_email_address');

  if ($checkbox.attr("checked") === 'checked') {
    $emailContainer.slideDown();
    $emailField.prop('required', true);
  } // Event Handler


  $checkbox.on('change', function () {
    updateDisplay();
  }); // Action

  function updateDisplay() {
    var isChecked = $checkbox.is(':checked');

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

},[["./assets/js/page.js","runtime"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvcGFnZS5qcyJdLCJuYW1lcyI6WyIkIiwiZG9jdW1lbnQiLCJyZWFkeSIsIiRjaGVja2JveCIsIiRlbWFpbENvbnRhaW5lciIsIiRlbWFpbEZpZWxkIiwiYXR0ciIsInNsaWRlRG93biIsInByb3AiLCJvbiIsInVwZGF0ZURpc3BsYXkiLCJpc0NoZWNrZWQiLCJpcyIsImZvY3VzIiwic2xpZGVVcCJdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7OztBQUFhOztBQUViQSxDQUFDLENBQUNDLFFBQUQsQ0FBRCxDQUFZQyxLQUFaLENBQWtCLFlBQVk7QUFFMUIsTUFBSUMsU0FBUyxHQUFHSCxDQUFDLENBQUMsd0JBQUQsQ0FBakI7QUFDQSxNQUFJSSxlQUFlLEdBQUdKLENBQUMsQ0FBQyxnQkFBRCxDQUF2QjtBQUNBLE1BQUlLLFdBQVcsR0FBR0wsQ0FBQyxDQUFDLDZCQUFELENBQW5COztBQUdBLE1BQUlHLFNBQVMsQ0FBQ0csSUFBVixDQUFlLFNBQWYsTUFBOEIsU0FBbEMsRUFBNkM7QUFDekNGLG1CQUFlLENBQUNHLFNBQWhCO0FBQ0FGLGVBQVcsQ0FBQ0csSUFBWixDQUFpQixVQUFqQixFQUE2QixJQUE3QjtBQUNILEdBVnlCLENBWTFCOzs7QUFDQUwsV0FBUyxDQUFDTSxFQUFWLENBQWEsUUFBYixFQUF1QixZQUFZO0FBRS9CQyxpQkFBYTtBQUNoQixHQUhELEVBYjBCLENBa0IxQjs7QUFFQSxXQUFTQSxhQUFULEdBQXlCO0FBRXJCLFFBQUlDLFNBQVMsR0FBR1IsU0FBUyxDQUFDUyxFQUFWLENBQWEsVUFBYixDQUFoQjs7QUFFQSxRQUFJRCxTQUFKLEVBQWU7QUFDWFAscUJBQWUsQ0FBQ0csU0FBaEI7QUFDQUYsaUJBQVcsQ0FBQ1EsS0FBWixHQUFvQkwsSUFBcEIsQ0FBeUIsVUFBekIsRUFBcUMsSUFBckM7QUFDSCxLQUhELE1BR087QUFDSEoscUJBQWUsQ0FBQ1UsT0FBaEI7QUFDQVQsaUJBQVcsQ0FBQ0csSUFBWixDQUFpQixVQUFqQixFQUE2QixLQUE3QjtBQUNIO0FBQ0o7QUFFSixDQWpDRCxFIiwiZmlsZSI6ImpzL3BhZ2UuanMiLCJzb3VyY2VzQ29udGVudCI6WyIndXNlIHN0cmljdCc7XHJcblxyXG4kKGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XHJcblxyXG4gICAgbGV0ICRjaGVja2JveCA9ICQoJyNwYWdlX2FkZF9jb250YWN0X2Zvcm0nKTtcclxuICAgIGxldCAkZW1haWxDb250YWluZXIgPSAkKCcjZW1haWwtYWRkcmVzcycpO1xyXG4gICAgbGV0ICRlbWFpbEZpZWxkID0gJCgnI3BhZ2VfY29udGFjdF9lbWFpbF9hZGRyZXNzJyk7XHJcblxyXG5cclxuICAgIGlmICgkY2hlY2tib3guYXR0cihcImNoZWNrZWRcIikgPT09ICdjaGVja2VkJykge1xyXG4gICAgICAgICRlbWFpbENvbnRhaW5lci5zbGlkZURvd24oKTtcclxuICAgICAgICAkZW1haWxGaWVsZC5wcm9wKCdyZXF1aXJlZCcsIHRydWUpO1xyXG4gICAgfVxyXG5cclxuICAgIC8vIEV2ZW50IEhhbmRsZXJcclxuICAgICRjaGVja2JveC5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICB1cGRhdGVEaXNwbGF5KCk7XHJcbiAgICB9KTtcclxuXHJcbiAgICAvLyBBY3Rpb25cclxuXHJcbiAgICBmdW5jdGlvbiB1cGRhdGVEaXNwbGF5KCkge1xyXG5cclxuICAgICAgICBsZXQgaXNDaGVja2VkID0gJGNoZWNrYm94LmlzKCc6Y2hlY2tlZCcpO1xyXG5cclxuICAgICAgICBpZiAoaXNDaGVja2VkKSB7XHJcbiAgICAgICAgICAgICRlbWFpbENvbnRhaW5lci5zbGlkZURvd24oKTtcclxuICAgICAgICAgICAgJGVtYWlsRmllbGQuZm9jdXMoKS5wcm9wKCdyZXF1aXJlZCcsIHRydWUpO1xyXG4gICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICRlbWFpbENvbnRhaW5lci5zbGlkZVVwKCk7XHJcbiAgICAgICAgICAgICRlbWFpbEZpZWxkLnByb3AoJ3JlcXVpcmVkJywgZmFsc2UpO1xyXG4gICAgICAgIH1cclxuICAgIH1cclxuXHJcbn0pO1xyXG4iXSwic291cmNlUm9vdCI6IiJ9