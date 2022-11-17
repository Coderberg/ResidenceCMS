(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/admin"],{

/***/ "./assets/js/admin/admin.js":
/*!**********************************!*\
  !*** ./assets/js/admin/admin.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var js_cookie_src_js_cookie__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! js-cookie/src/js.cookie */ "./node_modules/js-cookie/src/js.cookie.js");
/* harmony import */ var js_cookie_src_js_cookie__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(js_cookie_src_js_cookie__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _common_delete_button__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/_delete_button */ "./assets/js/common/_delete_button.js");
/* harmony import */ var _common_delete_button__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_common_delete_button__WEBPACK_IMPORTED_MODULE_1__);




$(document).ready(() => {
  let currentUrl = window.location.href;
  $('.sidebar .nav-item').each(function () {
    let sidebarLink = $('a', this).attr('href');
    if (currentUrl.includes(sidebarLink) || sidebarLink.includes('locations') && currentUrl.includes('locations')) {
      $(this).addClass('active');
    }
  });

  // Toggle the side navigation
  $('#sidebarToggle').on('click', e => {
    let $body = $('body');
    e.preventDefault();
    if ($($body).is('.sidebar-toggled')) {
      js_cookie_src_js_cookie__WEBPACK_IMPORTED_MODULE_0___default().set('sidebar-toggled', false);
    } else {
      js_cookie_src_js_cookie__WEBPACK_IMPORTED_MODULE_0___default().set('sidebar-toggled', true);
    }
    $body.toggleClass('sidebar-toggled');
    $('.sidebar').toggleClass('toggled');
  });

  // Sorting
  $('#sort_by, #state').on('change', () => {
    let value = $('#sort_by').val();
    let state = $('#state').val();
    window.location.href = window.location.pathname + '?sort_by=' + value + '&state=' + state;
  });
});

/***/ }),

/***/ "./assets/js/common/_delete_button.js":
/*!********************************************!*\
  !*** ./assets/js/common/_delete_button.js ***!
  \********************************************/
/***/ (() => {

(function ($, bootbox) {
  'use strict';

  // Confirm deletion
  $('[data-type="delete"]').click(function (e) {
    e.preventDefault();
    const $form = $(this).closest('form');
    const message = $(this).data('message');
    const confirmationText = $(this).data('confirmation-text');
    const cancellationText = $(this).data('cancellation-text');
    bootbox.confirm({
      message,
      buttons: {
        cancel: {
          label: cancellationText,
          className: 'btn-light'
        },
        confirm: {
          label: confirmationText,
          className: 'btn-danger'
        }
      },
      callback: function (result) {
        if (result) {
          $form.submit();
        }
      }
    });
  });
})($, bootbox);

/***/ }),

/***/ "./node_modules/js-cookie/src/js.cookie.js":
/*!*************************************************!*\
  !*** ./node_modules/js-cookie/src/js.cookie.js ***!
  \*************************************************/
/***/ ((module, exports, __webpack_require__) => {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
 * JavaScript Cookie v2.2.1
 * https://github.com/js-cookie/js-cookie
 *
 * Copyright 2006, 2015 Klaus Hartl & Fagner Brack
 * Released under the MIT license
 */
;(function (factory) {
	var registeredInModuleLoader;
	if (true) {
		!(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
		registeredInModuleLoader = true;
	}
	if (true) {
		module.exports = factory();
		registeredInModuleLoader = true;
	}
	if (!registeredInModuleLoader) {
		var OldCookies = window.Cookies;
		var api = window.Cookies = factory();
		api.noConflict = function () {
			window.Cookies = OldCookies;
			return api;
		};
	}
}(function () {
	function extend () {
		var i = 0;
		var result = {};
		for (; i < arguments.length; i++) {
			var attributes = arguments[ i ];
			for (var key in attributes) {
				result[key] = attributes[key];
			}
		}
		return result;
	}

	function decode (s) {
		return s.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}

	function init (converter) {
		function api() {}

		function set (key, value, attributes) {
			if (typeof document === 'undefined') {
				return;
			}

			attributes = extend({
				path: '/'
			}, api.defaults, attributes);

			if (typeof attributes.expires === 'number') {
				attributes.expires = new Date(new Date() * 1 + attributes.expires * 864e+5);
			}

			// We're using "expires" because "max-age" is not supported by IE
			attributes.expires = attributes.expires ? attributes.expires.toUTCString() : '';

			try {
				var result = JSON.stringify(value);
				if (/^[\{\[]/.test(result)) {
					value = result;
				}
			} catch (e) {}

			value = converter.write ?
				converter.write(value, key) :
				encodeURIComponent(String(value))
					.replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g, decodeURIComponent);

			key = encodeURIComponent(String(key))
				.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
				.replace(/[\(\)]/g, escape);

			var stringifiedAttributes = '';
			for (var attributeName in attributes) {
				if (!attributes[attributeName]) {
					continue;
				}
				stringifiedAttributes += '; ' + attributeName;
				if (attributes[attributeName] === true) {
					continue;
				}

				// Considers RFC 6265 section 5.2:
				// ...
				// 3.  If the remaining unparsed-attributes contains a %x3B (";")
				//     character:
				// Consume the characters of the unparsed-attributes up to,
				// not including, the first %x3B (";") character.
				// ...
				stringifiedAttributes += '=' + attributes[attributeName].split(';')[0];
			}

			return (document.cookie = key + '=' + value + stringifiedAttributes);
		}

		function get (key, json) {
			if (typeof document === 'undefined') {
				return;
			}

			var jar = {};
			// To prevent the for loop in the first place assign an empty array
			// in case there are no cookies at all.
			var cookies = document.cookie ? document.cookie.split('; ') : [];
			var i = 0;

			for (; i < cookies.length; i++) {
				var parts = cookies[i].split('=');
				var cookie = parts.slice(1).join('=');

				if (!json && cookie.charAt(0) === '"') {
					cookie = cookie.slice(1, -1);
				}

				try {
					var name = decode(parts[0]);
					cookie = (converter.read || converter)(cookie, name) ||
						decode(cookie);

					if (json) {
						try {
							cookie = JSON.parse(cookie);
						} catch (e) {}
					}

					jar[name] = cookie;

					if (key === name) {
						break;
					}
				} catch (e) {}
			}

			return key ? jar[key] : jar;
		}

		api.set = set;
		api.get = function (key) {
			return get(key, false /* read as raw */);
		};
		api.getJSON = function (key) {
			return get(key, true /* read as json */);
		};
		api.remove = function (key, attributes) {
			set(key, '', extend(attributes, {
				expires: -1
			}));
		};

		api.defaults = {};

		api.withConverter = init;

		return api;
	}

	return init(function () {});
}));


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/admin/admin.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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QTtBQUNBLDREQUE0RDtBQUM1RDs7QUFFQSxVQUFVLG9CQUFvQjtBQUM5QjtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxRQUFRO0FBQ1I7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsTUFBTTtBQUNOOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQSwyQkFBMkI7QUFDM0IsQ0FBQyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9qcy9hZG1pbi9hZG1pbi5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvY29tbW9uL19kZWxldGVfYnV0dG9uLmpzIiwid2VicGFjazovLy8uL25vZGVfbW9kdWxlcy9qcy1jb29raWUvc3JjL2pzLmNvb2tpZS5qcyJdLCJzb3VyY2VzQ29udGVudCI6WyIndXNlIHN0cmljdCc7XG5cbmltcG9ydCBDb29raWVzIGZyb20gJ2pzLWNvb2tpZS9zcmMvanMuY29va2llJztcbmltcG9ydCAnLi4vY29tbW9uL19kZWxldGVfYnV0dG9uJztcblxuJChkb2N1bWVudCkucmVhZHkoKCkgPT4ge1xuICAgIGxldCBjdXJyZW50VXJsID0gd2luZG93LmxvY2F0aW9uLmhyZWY7XG5cbiAgICAkKCcuc2lkZWJhciAubmF2LWl0ZW0nKS5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgbGV0IHNpZGViYXJMaW5rID0gJCgnYScsIHRoaXMpLmF0dHIoJ2hyZWYnKTtcbiAgICAgICAgaWYgKFxuICAgICAgICAgICAgY3VycmVudFVybC5pbmNsdWRlcyhzaWRlYmFyTGluaykgfHxcbiAgICAgICAgICAgIChzaWRlYmFyTGluay5pbmNsdWRlcygnbG9jYXRpb25zJykgJiZcbiAgICAgICAgICAgICAgICBjdXJyZW50VXJsLmluY2x1ZGVzKCdsb2NhdGlvbnMnKSlcbiAgICAgICAgKSB7XG4gICAgICAgICAgICAkKHRoaXMpLmFkZENsYXNzKCdhY3RpdmUnKTtcbiAgICAgICAgfVxuICAgIH0pO1xuXG4gICAgLy8gVG9nZ2xlIHRoZSBzaWRlIG5hdmlnYXRpb25cbiAgICAkKCcjc2lkZWJhclRvZ2dsZScpLm9uKCdjbGljaycsIChlKSA9PiB7XG4gICAgICAgIGxldCAkYm9keSA9ICQoJ2JvZHknKTtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG4gICAgICAgIGlmICgkKCRib2R5KS5pcygnLnNpZGViYXItdG9nZ2xlZCcpKSB7XG4gICAgICAgICAgICBDb29raWVzLnNldCgnc2lkZWJhci10b2dnbGVkJywgZmFsc2UpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgQ29va2llcy5zZXQoJ3NpZGViYXItdG9nZ2xlZCcsIHRydWUpO1xuICAgICAgICB9XG5cbiAgICAgICAgJGJvZHkudG9nZ2xlQ2xhc3MoJ3NpZGViYXItdG9nZ2xlZCcpO1xuICAgICAgICAkKCcuc2lkZWJhcicpLnRvZ2dsZUNsYXNzKCd0b2dnbGVkJyk7XG4gICAgfSk7XG5cbiAgICAvLyBTb3J0aW5nXG4gICAgJCgnI3NvcnRfYnksICNzdGF0ZScpLm9uKCdjaGFuZ2UnLCAoKSA9PiB7XG4gICAgICAgIGxldCB2YWx1ZSA9ICQoJyNzb3J0X2J5JykudmFsKCk7XG4gICAgICAgIGxldCBzdGF0ZSA9ICQoJyNzdGF0ZScpLnZhbCgpO1xuICAgICAgICB3aW5kb3cubG9jYXRpb24uaHJlZiA9XG4gICAgICAgICAgICB3aW5kb3cubG9jYXRpb24ucGF0aG5hbWUgK1xuICAgICAgICAgICAgJz9zb3J0X2J5PScgK1xuICAgICAgICAgICAgdmFsdWUgK1xuICAgICAgICAgICAgJyZzdGF0ZT0nICtcbiAgICAgICAgICAgIHN0YXRlO1xuICAgIH0pO1xufSk7XG4iLCIoZnVuY3Rpb24gKCQsIGJvb3Rib3gpIHtcbiAgICAndXNlIHN0cmljdCc7XG5cbiAgICAvLyBDb25maXJtIGRlbGV0aW9uXG4gICAgJCgnW2RhdGEtdHlwZT1cImRlbGV0ZVwiXScpLmNsaWNrKGZ1bmN0aW9uIChlKSB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgY29uc3QgJGZvcm0gPSAkKHRoaXMpLmNsb3Nlc3QoJ2Zvcm0nKTtcbiAgICAgICAgY29uc3QgbWVzc2FnZSA9ICQodGhpcykuZGF0YSgnbWVzc2FnZScpO1xuICAgICAgICBjb25zdCBjb25maXJtYXRpb25UZXh0ID0gJCh0aGlzKS5kYXRhKCdjb25maXJtYXRpb24tdGV4dCcpO1xuICAgICAgICBjb25zdCBjYW5jZWxsYXRpb25UZXh0ID0gJCh0aGlzKS5kYXRhKCdjYW5jZWxsYXRpb24tdGV4dCcpO1xuXG4gICAgICAgIGJvb3Rib3guY29uZmlybSh7XG4gICAgICAgICAgICBtZXNzYWdlLFxuICAgICAgICAgICAgYnV0dG9uczoge1xuICAgICAgICAgICAgICAgIGNhbmNlbDoge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogY2FuY2VsbGF0aW9uVGV4dCxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnYnRuLWxpZ2h0J1xuICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgY29uZmlybToge1xuICAgICAgICAgICAgICAgICAgICBsYWJlbDogY29uZmlybWF0aW9uVGV4dCxcbiAgICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lOiAnYnRuLWRhbmdlcidcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgY2FsbGJhY2s6IGZ1bmN0aW9uIChyZXN1bHQpIHtcbiAgICAgICAgICAgICAgICBpZiAocmVzdWx0KSB7XG4gICAgICAgICAgICAgICAgICAgICRmb3JtLnN1Ym1pdCgpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSk7XG59KSgkLCBib290Ym94KTtcbiIsIi8qIVxuICogSmF2YVNjcmlwdCBDb29raWUgdjIuMi4xXG4gKiBodHRwczovL2dpdGh1Yi5jb20vanMtY29va2llL2pzLWNvb2tpZVxuICpcbiAqIENvcHlyaWdodCAyMDA2LCAyMDE1IEtsYXVzIEhhcnRsICYgRmFnbmVyIEJyYWNrXG4gKiBSZWxlYXNlZCB1bmRlciB0aGUgTUlUIGxpY2Vuc2VcbiAqL1xuOyhmdW5jdGlvbiAoZmFjdG9yeSkge1xuXHR2YXIgcmVnaXN0ZXJlZEluTW9kdWxlTG9hZGVyO1xuXHRpZiAodHlwZW9mIGRlZmluZSA9PT0gJ2Z1bmN0aW9uJyAmJiBkZWZpbmUuYW1kKSB7XG5cdFx0ZGVmaW5lKGZhY3RvcnkpO1xuXHRcdHJlZ2lzdGVyZWRJbk1vZHVsZUxvYWRlciA9IHRydWU7XG5cdH1cblx0aWYgKHR5cGVvZiBleHBvcnRzID09PSAnb2JqZWN0Jykge1xuXHRcdG1vZHVsZS5leHBvcnRzID0gZmFjdG9yeSgpO1xuXHRcdHJlZ2lzdGVyZWRJbk1vZHVsZUxvYWRlciA9IHRydWU7XG5cdH1cblx0aWYgKCFyZWdpc3RlcmVkSW5Nb2R1bGVMb2FkZXIpIHtcblx0XHR2YXIgT2xkQ29va2llcyA9IHdpbmRvdy5Db29raWVzO1xuXHRcdHZhciBhcGkgPSB3aW5kb3cuQ29va2llcyA9IGZhY3RvcnkoKTtcblx0XHRhcGkubm9Db25mbGljdCA9IGZ1bmN0aW9uICgpIHtcblx0XHRcdHdpbmRvdy5Db29raWVzID0gT2xkQ29va2llcztcblx0XHRcdHJldHVybiBhcGk7XG5cdFx0fTtcblx0fVxufShmdW5jdGlvbiAoKSB7XG5cdGZ1bmN0aW9uIGV4dGVuZCAoKSB7XG5cdFx0dmFyIGkgPSAwO1xuXHRcdHZhciByZXN1bHQgPSB7fTtcblx0XHRmb3IgKDsgaSA8IGFyZ3VtZW50cy5sZW5ndGg7IGkrKykge1xuXHRcdFx0dmFyIGF0dHJpYnV0ZXMgPSBhcmd1bWVudHNbIGkgXTtcblx0XHRcdGZvciAodmFyIGtleSBpbiBhdHRyaWJ1dGVzKSB7XG5cdFx0XHRcdHJlc3VsdFtrZXldID0gYXR0cmlidXRlc1trZXldO1xuXHRcdFx0fVxuXHRcdH1cblx0XHRyZXR1cm4gcmVzdWx0O1xuXHR9XG5cblx0ZnVuY3Rpb24gZGVjb2RlIChzKSB7XG5cdFx0cmV0dXJuIHMucmVwbGFjZSgvKCVbMC05QS1aXXsyfSkrL2csIGRlY29kZVVSSUNvbXBvbmVudCk7XG5cdH1cblxuXHRmdW5jdGlvbiBpbml0IChjb252ZXJ0ZXIpIHtcblx0XHRmdW5jdGlvbiBhcGkoKSB7fVxuXG5cdFx0ZnVuY3Rpb24gc2V0IChrZXksIHZhbHVlLCBhdHRyaWJ1dGVzKSB7XG5cdFx0XHRpZiAodHlwZW9mIGRvY3VtZW50ID09PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdGF0dHJpYnV0ZXMgPSBleHRlbmQoe1xuXHRcdFx0XHRwYXRoOiAnLydcblx0XHRcdH0sIGFwaS5kZWZhdWx0cywgYXR0cmlidXRlcyk7XG5cblx0XHRcdGlmICh0eXBlb2YgYXR0cmlidXRlcy5leHBpcmVzID09PSAnbnVtYmVyJykge1xuXHRcdFx0XHRhdHRyaWJ1dGVzLmV4cGlyZXMgPSBuZXcgRGF0ZShuZXcgRGF0ZSgpICogMSArIGF0dHJpYnV0ZXMuZXhwaXJlcyAqIDg2NGUrNSk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIFdlJ3JlIHVzaW5nIFwiZXhwaXJlc1wiIGJlY2F1c2UgXCJtYXgtYWdlXCIgaXMgbm90IHN1cHBvcnRlZCBieSBJRVxuXHRcdFx0YXR0cmlidXRlcy5leHBpcmVzID0gYXR0cmlidXRlcy5leHBpcmVzID8gYXR0cmlidXRlcy5leHBpcmVzLnRvVVRDU3RyaW5nKCkgOiAnJztcblxuXHRcdFx0dHJ5IHtcblx0XHRcdFx0dmFyIHJlc3VsdCA9IEpTT04uc3RyaW5naWZ5KHZhbHVlKTtcblx0XHRcdFx0aWYgKC9eW1xce1xcW10vLnRlc3QocmVzdWx0KSkge1xuXHRcdFx0XHRcdHZhbHVlID0gcmVzdWx0O1xuXHRcdFx0XHR9XG5cdFx0XHR9IGNhdGNoIChlKSB7fVxuXG5cdFx0XHR2YWx1ZSA9IGNvbnZlcnRlci53cml0ZSA/XG5cdFx0XHRcdGNvbnZlcnRlci53cml0ZSh2YWx1ZSwga2V5KSA6XG5cdFx0XHRcdGVuY29kZVVSSUNvbXBvbmVudChTdHJpbmcodmFsdWUpKVxuXHRcdFx0XHRcdC5yZXBsYWNlKC8lKDIzfDI0fDI2fDJCfDNBfDNDfDNFfDNEfDJGfDNGfDQwfDVCfDVEfDVFfDYwfDdCfDdEfDdDKS9nLCBkZWNvZGVVUklDb21wb25lbnQpO1xuXG5cdFx0XHRrZXkgPSBlbmNvZGVVUklDb21wb25lbnQoU3RyaW5nKGtleSkpXG5cdFx0XHRcdC5yZXBsYWNlKC8lKDIzfDI0fDI2fDJCfDVFfDYwfDdDKS9nLCBkZWNvZGVVUklDb21wb25lbnQpXG5cdFx0XHRcdC5yZXBsYWNlKC9bXFwoXFwpXS9nLCBlc2NhcGUpO1xuXG5cdFx0XHR2YXIgc3RyaW5naWZpZWRBdHRyaWJ1dGVzID0gJyc7XG5cdFx0XHRmb3IgKHZhciBhdHRyaWJ1dGVOYW1lIGluIGF0dHJpYnV0ZXMpIHtcblx0XHRcdFx0aWYgKCFhdHRyaWJ1dGVzW2F0dHJpYnV0ZU5hbWVdKSB7XG5cdFx0XHRcdFx0Y29udGludWU7XG5cdFx0XHRcdH1cblx0XHRcdFx0c3RyaW5naWZpZWRBdHRyaWJ1dGVzICs9ICc7ICcgKyBhdHRyaWJ1dGVOYW1lO1xuXHRcdFx0XHRpZiAoYXR0cmlidXRlc1thdHRyaWJ1dGVOYW1lXSA9PT0gdHJ1ZSkge1xuXHRcdFx0XHRcdGNvbnRpbnVlO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0Ly8gQ29uc2lkZXJzIFJGQyA2MjY1IHNlY3Rpb24gNS4yOlxuXHRcdFx0XHQvLyAuLi5cblx0XHRcdFx0Ly8gMy4gIElmIHRoZSByZW1haW5pbmcgdW5wYXJzZWQtYXR0cmlidXRlcyBjb250YWlucyBhICV4M0IgKFwiO1wiKVxuXHRcdFx0XHQvLyAgICAgY2hhcmFjdGVyOlxuXHRcdFx0XHQvLyBDb25zdW1lIHRoZSBjaGFyYWN0ZXJzIG9mIHRoZSB1bnBhcnNlZC1hdHRyaWJ1dGVzIHVwIHRvLFxuXHRcdFx0XHQvLyBub3QgaW5jbHVkaW5nLCB0aGUgZmlyc3QgJXgzQiAoXCI7XCIpIGNoYXJhY3Rlci5cblx0XHRcdFx0Ly8gLi4uXG5cdFx0XHRcdHN0cmluZ2lmaWVkQXR0cmlidXRlcyArPSAnPScgKyBhdHRyaWJ1dGVzW2F0dHJpYnV0ZU5hbWVdLnNwbGl0KCc7JylbMF07XG5cdFx0XHR9XG5cblx0XHRcdHJldHVybiAoZG9jdW1lbnQuY29va2llID0ga2V5ICsgJz0nICsgdmFsdWUgKyBzdHJpbmdpZmllZEF0dHJpYnV0ZXMpO1xuXHRcdH1cblxuXHRcdGZ1bmN0aW9uIGdldCAoa2V5LCBqc29uKSB7XG5cdFx0XHRpZiAodHlwZW9mIGRvY3VtZW50ID09PSAndW5kZWZpbmVkJykge1xuXHRcdFx0XHRyZXR1cm47XG5cdFx0XHR9XG5cblx0XHRcdHZhciBqYXIgPSB7fTtcblx0XHRcdC8vIFRvIHByZXZlbnQgdGhlIGZvciBsb29wIGluIHRoZSBmaXJzdCBwbGFjZSBhc3NpZ24gYW4gZW1wdHkgYXJyYXlcblx0XHRcdC8vIGluIGNhc2UgdGhlcmUgYXJlIG5vIGNvb2tpZXMgYXQgYWxsLlxuXHRcdFx0dmFyIGNvb2tpZXMgPSBkb2N1bWVudC5jb29raWUgPyBkb2N1bWVudC5jb29raWUuc3BsaXQoJzsgJykgOiBbXTtcblx0XHRcdHZhciBpID0gMDtcblxuXHRcdFx0Zm9yICg7IGkgPCBjb29raWVzLmxlbmd0aDsgaSsrKSB7XG5cdFx0XHRcdHZhciBwYXJ0cyA9IGNvb2tpZXNbaV0uc3BsaXQoJz0nKTtcblx0XHRcdFx0dmFyIGNvb2tpZSA9IHBhcnRzLnNsaWNlKDEpLmpvaW4oJz0nKTtcblxuXHRcdFx0XHRpZiAoIWpzb24gJiYgY29va2llLmNoYXJBdCgwKSA9PT0gJ1wiJykge1xuXHRcdFx0XHRcdGNvb2tpZSA9IGNvb2tpZS5zbGljZSgxLCAtMSk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHR0cnkge1xuXHRcdFx0XHRcdHZhciBuYW1lID0gZGVjb2RlKHBhcnRzWzBdKTtcblx0XHRcdFx0XHRjb29raWUgPSAoY29udmVydGVyLnJlYWQgfHwgY29udmVydGVyKShjb29raWUsIG5hbWUpIHx8XG5cdFx0XHRcdFx0XHRkZWNvZGUoY29va2llKTtcblxuXHRcdFx0XHRcdGlmIChqc29uKSB7XG5cdFx0XHRcdFx0XHR0cnkge1xuXHRcdFx0XHRcdFx0XHRjb29raWUgPSBKU09OLnBhcnNlKGNvb2tpZSk7XG5cdFx0XHRcdFx0XHR9IGNhdGNoIChlKSB7fVxuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGphcltuYW1lXSA9IGNvb2tpZTtcblxuXHRcdFx0XHRcdGlmIChrZXkgPT09IG5hbWUpIHtcblx0XHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRcdH1cblx0XHRcdFx0fSBjYXRjaCAoZSkge31cblx0XHRcdH1cblxuXHRcdFx0cmV0dXJuIGtleSA/IGphcltrZXldIDogamFyO1xuXHRcdH1cblxuXHRcdGFwaS5zZXQgPSBzZXQ7XG5cdFx0YXBpLmdldCA9IGZ1bmN0aW9uIChrZXkpIHtcblx0XHRcdHJldHVybiBnZXQoa2V5LCBmYWxzZSAvKiByZWFkIGFzIHJhdyAqLyk7XG5cdFx0fTtcblx0XHRhcGkuZ2V0SlNPTiA9IGZ1bmN0aW9uIChrZXkpIHtcblx0XHRcdHJldHVybiBnZXQoa2V5LCB0cnVlIC8qIHJlYWQgYXMganNvbiAqLyk7XG5cdFx0fTtcblx0XHRhcGkucmVtb3ZlID0gZnVuY3Rpb24gKGtleSwgYXR0cmlidXRlcykge1xuXHRcdFx0c2V0KGtleSwgJycsIGV4dGVuZChhdHRyaWJ1dGVzLCB7XG5cdFx0XHRcdGV4cGlyZXM6IC0xXG5cdFx0XHR9KSk7XG5cdFx0fTtcblxuXHRcdGFwaS5kZWZhdWx0cyA9IHt9O1xuXG5cdFx0YXBpLndpdGhDb252ZXJ0ZXIgPSBpbml0O1xuXG5cdFx0cmV0dXJuIGFwaTtcblx0fVxuXG5cdHJldHVybiBpbml0KGZ1bmN0aW9uICgpIHt9KTtcbn0pKTtcbiJdLCJuYW1lcyI6WyJDb29raWVzIiwiJCIsImRvY3VtZW50IiwicmVhZHkiLCJjdXJyZW50VXJsIiwid2luZG93IiwibG9jYXRpb24iLCJocmVmIiwiZWFjaCIsInNpZGViYXJMaW5rIiwiYXR0ciIsImluY2x1ZGVzIiwiYWRkQ2xhc3MiLCJvbiIsImUiLCIkYm9keSIsInByZXZlbnREZWZhdWx0IiwiaXMiLCJzZXQiLCJ0b2dnbGVDbGFzcyIsInZhbHVlIiwidmFsIiwic3RhdGUiLCJwYXRobmFtZSIsImJvb3Rib3giLCJjbGljayIsIiRmb3JtIiwiY2xvc2VzdCIsIm1lc3NhZ2UiLCJkYXRhIiwiY29uZmlybWF0aW9uVGV4dCIsImNhbmNlbGxhdGlvblRleHQiLCJjb25maXJtIiwiYnV0dG9ucyIsImNhbmNlbCIsImxhYmVsIiwiY2xhc3NOYW1lIiwiY2FsbGJhY2siLCJyZXN1bHQiLCJzdWJtaXQiXSwic291cmNlUm9vdCI6IiJ9