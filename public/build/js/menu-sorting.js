"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/menu-sorting"],{

/***/ "./assets/js/admin/menu-sorting.js":
/*!*****************************************!*\
  !*** ./assets/js/admin/menu-sorting.js ***!
  \*****************************************/
/***/ (() => {



const changeButtons = () => {
  $('.js-down-one').removeClass('js-down-one').addClass('js-up-one').html('<i class="fas fa-arrow-up"></i> Up one');
  $('.js-move:first').removeClass('js-up-one').addClass('js-down-one').html('<i class="fas fa-arrow-down"></i> Down one');
};
const sendRequest = () => {
  const item = $('.js-move');
  const token = $('#menu').data('token');
  if (item.length > 1) {
    let items = [];
    item.each(function () {
      items.push(parseInt($(this).attr('id'), 10));
    });
    $.ajax({
      method: 'POST',
      url: '/en/admin/menu/sort',
      data: {
        csrf_token: token,
        items: items
      }
    });
  }
};
$(document).ready(function () {
  $('body').on('click', '.js-move', function () {
    let row = $(this).parents('tr:first');
    if ($(this).is('.js-up-one')) {
      row.insertBefore(row.prev());
    } else {
      row.insertAfter(row.next());
    }
    changeButtons();
    sendRequest();
  });
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/admin/menu-sorting.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoianMvbWVudS1zb3J0aW5nLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7OztBQUFhOztBQUViLE1BQU1BLGFBQWEsR0FBRyxNQUFNO0VBQ3hCQyxDQUFDLENBQUMsY0FBYyxDQUFDLENBQ1pDLFdBQVcsQ0FBQyxhQUFhLENBQUMsQ0FDMUJDLFFBQVEsQ0FBQyxXQUFXLENBQUMsQ0FDckJDLElBQUksQ0FBQyx3Q0FBd0MsQ0FBQztFQUVuREgsQ0FBQyxDQUFDLGdCQUFnQixDQUFDLENBQ2RDLFdBQVcsQ0FBQyxXQUFXLENBQUMsQ0FDeEJDLFFBQVEsQ0FBQyxhQUFhLENBQUMsQ0FDdkJDLElBQUksQ0FBQyw0Q0FBNEMsQ0FBQztBQUMzRCxDQUFDO0FBRUQsTUFBTUMsV0FBVyxHQUFHLE1BQU07RUFDdEIsTUFBTUMsSUFBSSxHQUFHTCxDQUFDLENBQUMsVUFBVSxDQUFDO0VBQzFCLE1BQU1NLEtBQUssR0FBR04sQ0FBQyxDQUFDLE9BQU8sQ0FBQyxDQUFDTyxJQUFJLENBQUMsT0FBTyxDQUFDO0VBRXRDLElBQUlGLElBQUksQ0FBQ0csTUFBTSxHQUFHLENBQUMsRUFBRTtJQUNqQixJQUFJQyxLQUFLLEdBQUcsRUFBRTtJQUVkSixJQUFJLENBQUNLLElBQUksQ0FBQyxZQUFZO01BQ2xCRCxLQUFLLENBQUNFLElBQUksQ0FBQ0MsUUFBUSxDQUFDWixDQUFDLENBQUMsSUFBSSxDQUFDLENBQUNhLElBQUksQ0FBQyxJQUFJLENBQUMsRUFBRSxFQUFFLENBQUMsQ0FBQztJQUNoRCxDQUFDLENBQUM7SUFFRmIsQ0FBQyxDQUFDYyxJQUFJLENBQUM7TUFDSEMsTUFBTSxFQUFFLE1BQU07TUFDZEMsR0FBRyxFQUFFLHFCQUFxQjtNQUMxQlQsSUFBSSxFQUFFO1FBQUVVLFVBQVUsRUFBRVgsS0FBSztRQUFFRyxLQUFLLEVBQUVBO01BQU07SUFDNUMsQ0FBQyxDQUFDO0VBQ047QUFDSixDQUFDO0FBRURULENBQUMsQ0FBQ2tCLFFBQVEsQ0FBQyxDQUFDQyxLQUFLLENBQUMsWUFBWTtFQUMxQm5CLENBQUMsQ0FBQyxNQUFNLENBQUMsQ0FBQ29CLEVBQUUsQ0FBQyxPQUFPLEVBQUUsVUFBVSxFQUFFLFlBQVk7SUFDMUMsSUFBSUMsR0FBRyxHQUFHckIsQ0FBQyxDQUFDLElBQUksQ0FBQyxDQUFDc0IsT0FBTyxDQUFDLFVBQVUsQ0FBQztJQUVyQyxJQUFJdEIsQ0FBQyxDQUFDLElBQUksQ0FBQyxDQUFDdUIsRUFBRSxDQUFDLFlBQVksQ0FBQyxFQUFFO01BQzFCRixHQUFHLENBQUNHLFlBQVksQ0FBQ0gsR0FBRyxDQUFDSSxJQUFJLEVBQUUsQ0FBQztJQUNoQyxDQUFDLE1BQU07TUFDSEosR0FBRyxDQUFDSyxXQUFXLENBQUNMLEdBQUcsQ0FBQ00sSUFBSSxFQUFFLENBQUM7SUFDL0I7SUFFQTVCLGFBQWEsRUFBRTtJQUNmSyxXQUFXLEVBQUU7RUFDakIsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2pzL2FkbWluL21lbnUtc29ydGluZy5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIndXNlIHN0cmljdCc7XG5cbmNvbnN0IGNoYW5nZUJ1dHRvbnMgPSAoKSA9PiB7XG4gICAgJCgnLmpzLWRvd24tb25lJylcbiAgICAgICAgLnJlbW92ZUNsYXNzKCdqcy1kb3duLW9uZScpXG4gICAgICAgIC5hZGRDbGFzcygnanMtdXAtb25lJylcbiAgICAgICAgLmh0bWwoJzxpIGNsYXNzPVwiZmFzIGZhLWFycm93LXVwXCI+PC9pPiBVcCBvbmUnKTtcblxuICAgICQoJy5qcy1tb3ZlOmZpcnN0JylcbiAgICAgICAgLnJlbW92ZUNsYXNzKCdqcy11cC1vbmUnKVxuICAgICAgICAuYWRkQ2xhc3MoJ2pzLWRvd24tb25lJylcbiAgICAgICAgLmh0bWwoJzxpIGNsYXNzPVwiZmFzIGZhLWFycm93LWRvd25cIj48L2k+IERvd24gb25lJyk7XG59O1xuXG5jb25zdCBzZW5kUmVxdWVzdCA9ICgpID0+IHtcbiAgICBjb25zdCBpdGVtID0gJCgnLmpzLW1vdmUnKTtcbiAgICBjb25zdCB0b2tlbiA9ICQoJyNtZW51JykuZGF0YSgndG9rZW4nKTtcblxuICAgIGlmIChpdGVtLmxlbmd0aCA+IDEpIHtcbiAgICAgICAgbGV0IGl0ZW1zID0gW107XG5cbiAgICAgICAgaXRlbS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGl0ZW1zLnB1c2gocGFyc2VJbnQoJCh0aGlzKS5hdHRyKCdpZCcpLCAxMCkpO1xuICAgICAgICB9KTtcblxuICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgbWV0aG9kOiAnUE9TVCcsXG4gICAgICAgICAgICB1cmw6ICcvZW4vYWRtaW4vbWVudS9zb3J0JyxcbiAgICAgICAgICAgIGRhdGE6IHsgY3NyZl90b2tlbjogdG9rZW4sIGl0ZW1zOiBpdGVtcyB9XG4gICAgICAgIH0pO1xuICAgIH1cbn07XG5cbiQoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uICgpIHtcbiAgICAkKCdib2R5Jykub24oJ2NsaWNrJywgJy5qcy1tb3ZlJywgZnVuY3Rpb24gKCkge1xuICAgICAgICBsZXQgcm93ID0gJCh0aGlzKS5wYXJlbnRzKCd0cjpmaXJzdCcpO1xuXG4gICAgICAgIGlmICgkKHRoaXMpLmlzKCcuanMtdXAtb25lJykpIHtcbiAgICAgICAgICAgIHJvdy5pbnNlcnRCZWZvcmUocm93LnByZXYoKSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICByb3cuaW5zZXJ0QWZ0ZXIocm93Lm5leHQoKSk7XG4gICAgICAgIH1cblxuICAgICAgICBjaGFuZ2VCdXR0b25zKCk7XG4gICAgICAgIHNlbmRSZXF1ZXN0KCk7XG4gICAgfSk7XG59KTtcbiJdLCJuYW1lcyI6WyJjaGFuZ2VCdXR0b25zIiwiJCIsInJlbW92ZUNsYXNzIiwiYWRkQ2xhc3MiLCJodG1sIiwic2VuZFJlcXVlc3QiLCJpdGVtIiwidG9rZW4iLCJkYXRhIiwibGVuZ3RoIiwiaXRlbXMiLCJlYWNoIiwicHVzaCIsInBhcnNlSW50IiwiYXR0ciIsImFqYXgiLCJtZXRob2QiLCJ1cmwiLCJjc3JmX3Rva2VuIiwiZG9jdW1lbnQiLCJyZWFkeSIsIm9uIiwicm93IiwicGFyZW50cyIsImlzIiwiaW5zZXJ0QmVmb3JlIiwicHJldiIsImluc2VydEFmdGVyIiwibmV4dCJdLCJzb3VyY2VSb290IjoiIn0=