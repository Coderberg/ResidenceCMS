(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["js/ekko-lightbox"],{

/***/ "./assets/js/ekko-lightbox.js":
/*!************************************!*\
  !*** ./assets/js/ekko-lightbox.js ***!
  \************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var ekko_lightbox_dist_ekko_lightbox__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ekko-lightbox/dist/ekko-lightbox */ "./node_modules/ekko-lightbox/dist/ekko-lightbox.js");
/* harmony import */ var ekko_lightbox_dist_ekko_lightbox__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(ekko_lightbox_dist_ekko_lightbox__WEBPACK_IMPORTED_MODULE_0__);


/***/ }),

/***/ "./node_modules/ekko-lightbox/dist/ekko-lightbox.js":
/*!**********************************************************!*\
  !*** ./node_modules/ekko-lightbox/dist/ekko-lightbox.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*!
 * Lightbox for Bootstrap by @ashleydw
 * https://github.com/ashleydw/lightbox
 *
 * License: https://github.com/ashleydw/lightbox/blob/master/LICENSE
 */
+function ($) {

'use strict';

var _createClass = (function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ('value' in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError('Cannot call a class as a function'); } }

var Lightbox = (function ($) {

	var NAME = 'ekkoLightbox';
	var JQUERY_NO_CONFLICT = $.fn[NAME];

	var Default = {
		title: '',
		footer: '',
		maxWidth: 9999,
		maxHeight: 9999,
		showArrows: true, //display the left / right arrows or not
		wrapping: true, //if true, gallery loops infinitely
		type: null, //force the lightbox into image / youtube mode. if null, or not image|youtube|vimeo; detect it
		alwaysShowClose: false, //always show the close button, even if there is no title
		loadingMessage: '<div class="ekko-lightbox-loader"><div><div></div><div></div></div></div>', // http://tobiasahlin.com/spinkit/
		leftArrow: '<span>&#10094;</span>',
		rightArrow: '<span>&#10095;</span>',
		strings: {
			close: 'Close',
			fail: 'Failed to load image:',
			type: 'Could not detect remote target type. Force the type using data-type'
		},
		doc: document, // if in an iframe can specify top.document
		onShow: function onShow() {},
		onShown: function onShown() {},
		onHide: function onHide() {},
		onHidden: function onHidden() {},
		onNavigate: function onNavigate() {},
		onContentLoaded: function onContentLoaded() {}
	};

	var Lightbox = (function () {
		_createClass(Lightbox, null, [{
			key: 'Default',

			/**
       Class properties:
   	 _$element: null -> the <a> element currently being displayed
    _$modal: The bootstrap modal generated
       _$modalDialog: The .modal-dialog
       _$modalContent: The .modal-content
       _$modalBody: The .modal-body
       _$modalHeader: The .modal-header
       _$modalFooter: The .modal-footer
    _$lightboxContainerOne: Container of the first lightbox element
    _$lightboxContainerTwo: Container of the second lightbox element
    _$lightboxBody: First element in the container
    _$modalArrows: The overlayed arrows container
   	 _$galleryItems: Other <a>'s available for this gallery
    _galleryName: Name of the current data('gallery') showing
    _galleryIndex: The current index of the _$galleryItems being shown
   	 _config: {} the options for the modal
    _modalId: unique id for the current lightbox
    _padding / _border: CSS properties for the modal container; these are used to calculate the available space for the content
   	 */

			get: function get() {
				return Default;
			}
		}]);

		function Lightbox($element, config) {
			var _this = this;

			_classCallCheck(this, Lightbox);

			this._config = $.extend({}, Default, config);
			this._$modalArrows = null;
			this._galleryIndex = 0;
			this._galleryName = null;
			this._padding = null;
			this._border = null;
			this._titleIsShown = false;
			this._footerIsShown = false;
			this._wantedWidth = 0;
			this._wantedHeight = 0;
			this._touchstartX = 0;
			this._touchendX = 0;

			this._modalId = 'ekkoLightbox-' + Math.floor(Math.random() * 1000 + 1);
			this._$element = $element instanceof jQuery ? $element : $($element);

			this._isBootstrap3 = $.fn.modal.Constructor.VERSION[0] == 3;

			var h4 = '<h4 class="modal-title">' + (this._config.title || "&nbsp;") + '</h4>';
			var btn = '<button type="button" class="close" data-dismiss="modal" aria-label="' + this._config.strings.close + '"><span aria-hidden="true">&times;</span></button>';

			var header = '<div class="modal-header' + (this._config.title || this._config.alwaysShowClose ? '' : ' hide') + '">' + (this._isBootstrap3 ? btn + h4 : h4 + btn) + '</div>';
			var footer = '<div class="modal-footer' + (this._config.footer ? '' : ' hide') + '">' + (this._config.footer || "&nbsp;") + '</div>';
			var body = '<div class="modal-body"><div class="ekko-lightbox-container"><div class="ekko-lightbox-item fade in show"></div><div class="ekko-lightbox-item fade"></div></div></div>';
			var dialog = '<div class="modal-dialog" role="document"><div class="modal-content">' + header + body + footer + '</div></div>';
			$(this._config.doc.body).append('<div id="' + this._modalId + '" class="ekko-lightbox modal fade" tabindex="-1" tabindex="-1" role="dialog" aria-hidden="true">' + dialog + '</div>');

			this._$modal = $('#' + this._modalId, this._config.doc);
			this._$modalDialog = this._$modal.find('.modal-dialog').first();
			this._$modalContent = this._$modal.find('.modal-content').first();
			this._$modalBody = this._$modal.find('.modal-body').first();
			this._$modalHeader = this._$modal.find('.modal-header').first();
			this._$modalFooter = this._$modal.find('.modal-footer').first();

			this._$lightboxContainer = this._$modalBody.find('.ekko-lightbox-container').first();
			this._$lightboxBodyOne = this._$lightboxContainer.find('> div:first-child').first();
			this._$lightboxBodyTwo = this._$lightboxContainer.find('> div:last-child').first();

			this._border = this._calculateBorders();
			this._padding = this._calculatePadding();

			this._galleryName = this._$element.data('gallery');
			if (this._galleryName) {
				this._$galleryItems = $(document.body).find('*[data-gallery="' + this._galleryName + '"]');
				this._galleryIndex = this._$galleryItems.index(this._$element);
				$(document).on('keydown.ekkoLightbox', this._navigationalBinder.bind(this));

				// add the directional arrows to the modal
				if (this._config.showArrows && this._$galleryItems.length > 1) {
					this._$lightboxContainer.append('<div class="ekko-lightbox-nav-overlay"><a href="#">' + this._config.leftArrow + '</a><a href="#">' + this._config.rightArrow + '</a></div>');
					this._$modalArrows = this._$lightboxContainer.find('div.ekko-lightbox-nav-overlay').first();
					this._$lightboxContainer.on('click', 'a:first-child', function (event) {
						event.preventDefault();
						return _this.navigateLeft();
					});
					this._$lightboxContainer.on('click', 'a:last-child', function (event) {
						event.preventDefault();
						return _this.navigateRight();
					});
					this.updateNavigation();
				}
			}

			this._$modal.on('show.bs.modal', this._config.onShow.bind(this)).on('shown.bs.modal', function () {
				_this._toggleLoading(true);
				_this._handle();
				return _this._config.onShown.call(_this);
			}).on('hide.bs.modal', this._config.onHide.bind(this)).on('hidden.bs.modal', function () {
				if (_this._galleryName) {
					$(document).off('keydown.ekkoLightbox');
					$(window).off('resize.ekkoLightbox');
				}
				_this._$modal.remove();
				return _this._config.onHidden.call(_this);
			}).modal(this._config);

			$(window).on('resize.ekkoLightbox', function () {
				_this._resize(_this._wantedWidth, _this._wantedHeight);
			});
			this._$lightboxContainer.on('touchstart', function () {
				_this._touchstartX = event.changedTouches[0].screenX;
			}).on('touchend', function () {
				_this._touchendX = event.changedTouches[0].screenX;
				_this._swipeGesure();
			});
		}

		_createClass(Lightbox, [{
			key: 'element',
			value: function element() {
				return this._$element;
			}
		}, {
			key: 'modal',
			value: function modal() {
				return this._$modal;
			}
		}, {
			key: 'navigateTo',
			value: function navigateTo(index) {

				if (index < 0 || index > this._$galleryItems.length - 1) return this;

				this._galleryIndex = index;

				this.updateNavigation();

				this._$element = $(this._$galleryItems.get(this._galleryIndex));
				this._handle();
			}
		}, {
			key: 'navigateLeft',
			value: function navigateLeft() {

				if (!this._$galleryItems) return;

				if (this._$galleryItems.length === 1) return;

				if (this._galleryIndex === 0) {
					if (this._config.wrapping) this._galleryIndex = this._$galleryItems.length - 1;else return;
				} else //circular
					this._galleryIndex--;

				this._config.onNavigate.call(this, 'left', this._galleryIndex);
				return this.navigateTo(this._galleryIndex);
			}
		}, {
			key: 'navigateRight',
			value: function navigateRight() {

				if (!this._$galleryItems) return;

				if (this._$galleryItems.length === 1) return;

				if (this._galleryIndex === this._$galleryItems.length - 1) {
					if (this._config.wrapping) this._galleryIndex = 0;else return;
				} else //circular
					this._galleryIndex++;

				this._config.onNavigate.call(this, 'right', this._galleryIndex);
				return this.navigateTo(this._galleryIndex);
			}
		}, {
			key: 'updateNavigation',
			value: function updateNavigation() {
				if (!this._config.wrapping) {
					var $nav = this._$lightboxContainer.find('div.ekko-lightbox-nav-overlay');
					if (this._galleryIndex === 0) $nav.find('a:first-child').addClass('disabled');else $nav.find('a:first-child').removeClass('disabled');

					if (this._galleryIndex === this._$galleryItems.length - 1) $nav.find('a:last-child').addClass('disabled');else $nav.find('a:last-child').removeClass('disabled');
				}
			}
		}, {
			key: 'close',
			value: function close() {
				return this._$modal.modal('hide');
			}

			// helper private methods
		}, {
			key: '_navigationalBinder',
			value: function _navigationalBinder(event) {
				event = event || window.event;
				if (event.keyCode === 39) return this.navigateRight();
				if (event.keyCode === 37) return this.navigateLeft();
			}

			// type detection private methods
		}, {
			key: '_detectRemoteType',
			value: function _detectRemoteType(src, type) {

				type = type || false;

				if (!type && this._isImage(src)) type = 'image';
				if (!type && this._getYoutubeId(src)) type = 'youtube';
				if (!type && this._getVimeoId(src)) type = 'vimeo';
				if (!type && this._getInstagramId(src)) type = 'instagram';

				if (!type || ['image', 'youtube', 'vimeo', 'instagram', 'video', 'url'].indexOf(type) < 0) type = 'url';

				return type;
			}
		}, {
			key: '_isImage',
			value: function _isImage(string) {
				return string && string.match(/(^data:image\/.*,)|(\.(jp(e|g|eg)|gif|png|bmp|webp|svg)((\?|#).*)?$)/i);
			}
		}, {
			key: '_containerToUse',
			value: function _containerToUse() {
				var _this2 = this;

				// if currently showing an image, fade it out and remove
				var $toUse = this._$lightboxBodyTwo;
				var $current = this._$lightboxBodyOne;

				if (this._$lightboxBodyTwo.hasClass('in')) {
					$toUse = this._$lightboxBodyOne;
					$current = this._$lightboxBodyTwo;
				}

				$current.removeClass('in show');
				setTimeout(function () {
					if (!_this2._$lightboxBodyTwo.hasClass('in')) _this2._$lightboxBodyTwo.empty();
					if (!_this2._$lightboxBodyOne.hasClass('in')) _this2._$lightboxBodyOne.empty();
				}, 500);

				$toUse.addClass('in show');
				return $toUse;
			}
		}, {
			key: '_handle',
			value: function _handle() {

				var $toUse = this._containerToUse();
				this._updateTitleAndFooter();

				var currentRemote = this._$element.attr('data-remote') || this._$element.attr('href');
				var currentType = this._detectRemoteType(currentRemote, this._$element.attr('data-type') || false);

				if (['image', 'youtube', 'vimeo', 'instagram', 'video', 'url'].indexOf(currentType) < 0) return this._error(this._config.strings.type);

				switch (currentType) {
					case 'image':
						this._preloadImage(currentRemote, $toUse);
						this._preloadImageByIndex(this._galleryIndex, 3);
						break;
					case 'youtube':
						this._showYoutubeVideo(currentRemote, $toUse);
						break;
					case 'vimeo':
						this._showVimeoVideo(this._getVimeoId(currentRemote), $toUse);
						break;
					case 'instagram':
						this._showInstagramVideo(this._getInstagramId(currentRemote), $toUse);
						break;
					case 'video':
						this._showHtml5Video(currentRemote, $toUse);
						break;
					default:
						// url
						this._loadRemoteContent(currentRemote, $toUse);
						break;
				}

				return this;
			}
		}, {
			key: '_getYoutubeId',
			value: function _getYoutubeId(string) {
				if (!string) return false;
				var matches = string.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/);
				return matches && matches[2].length === 11 ? matches[2] : false;
			}
		}, {
			key: '_getVimeoId',
			value: function _getVimeoId(string) {
				return string && string.indexOf('vimeo') > 0 ? string : false;
			}
		}, {
			key: '_getInstagramId',
			value: function _getInstagramId(string) {
				return string && string.indexOf('instagram') > 0 ? string : false;
			}

			// layout private methods
		}, {
			key: '_toggleLoading',
			value: function _toggleLoading(show) {
				show = show || false;
				if (show) {
					this._$modalDialog.css('display', 'none');
					this._$modal.removeClass('in show');
					$('.modal-backdrop').append(this._config.loadingMessage);
				} else {
					this._$modalDialog.css('display', 'block');
					this._$modal.addClass('in show');
					$('.modal-backdrop').find('.ekko-lightbox-loader').remove();
				}
				return this;
			}
		}, {
			key: '_calculateBorders',
			value: function _calculateBorders() {
				return {
					top: this._totalCssByAttribute('border-top-width'),
					right: this._totalCssByAttribute('border-right-width'),
					bottom: this._totalCssByAttribute('border-bottom-width'),
					left: this._totalCssByAttribute('border-left-width')
				};
			}
		}, {
			key: '_calculatePadding',
			value: function _calculatePadding() {
				return {
					top: this._totalCssByAttribute('padding-top'),
					right: this._totalCssByAttribute('padding-right'),
					bottom: this._totalCssByAttribute('padding-bottom'),
					left: this._totalCssByAttribute('padding-left')
				};
			}
		}, {
			key: '_totalCssByAttribute',
			value: function _totalCssByAttribute(attribute) {
				return parseInt(this._$modalDialog.css(attribute), 10) + parseInt(this._$modalContent.css(attribute), 10) + parseInt(this._$modalBody.css(attribute), 10);
			}
		}, {
			key: '_updateTitleAndFooter',
			value: function _updateTitleAndFooter() {
				var title = this._$element.data('title') || "";
				var caption = this._$element.data('footer') || "";

				this._titleIsShown = false;
				if (title || this._config.alwaysShowClose) {
					this._titleIsShown = true;
					this._$modalHeader.css('display', '').find('.modal-title').html(title || "&nbsp;");
				} else this._$modalHeader.css('display', 'none');

				this._footerIsShown = false;
				if (caption) {
					this._footerIsShown = true;
					this._$modalFooter.css('display', '').html(caption);
				} else this._$modalFooter.css('display', 'none');

				return this;
			}
		}, {
			key: '_showYoutubeVideo',
			value: function _showYoutubeVideo(remote, $containerForElement) {
				var id = this._getYoutubeId(remote);
				var query = remote.indexOf('&') > 0 ? remote.substr(remote.indexOf('&')) : '';
				var width = this._$element.data('width') || 560;
				var height = this._$element.data('height') || width / (560 / 315);
				return this._showVideoIframe('//www.youtube.com/embed/' + id + '?badge=0&autoplay=1&html5=1' + query, width, height, $containerForElement);
			}
		}, {
			key: '_showVimeoVideo',
			value: function _showVimeoVideo(id, $containerForElement) {
				var width = this._$element.data('width') || 500;
				var height = this._$element.data('height') || width / (560 / 315);
				return this._showVideoIframe(id + '?autoplay=1', width, height, $containerForElement);
			}
		}, {
			key: '_showInstagramVideo',
			value: function _showInstagramVideo(id, $containerForElement) {
				// instagram load their content into iframe's so this can be put straight into the element
				var width = this._$element.data('width') || 612;
				var height = width + 80;
				id = id.substr(-1) !== '/' ? id + '/' : id; // ensure id has trailing slash
				$containerForElement.html('<iframe width="' + width + '" height="' + height + '" src="' + id + 'embed/" frameborder="0" allowfullscreen></iframe>');
				this._resize(width, height);
				this._config.onContentLoaded.call(this);
				if (this._$modalArrows) //hide the arrows when showing video
					this._$modalArrows.css('display', 'none');
				this._toggleLoading(false);
				return this;
			}
		}, {
			key: '_showVideoIframe',
			value: function _showVideoIframe(url, width, height, $containerForElement) {
				// should be used for videos only. for remote content use loadRemoteContent (data-type=url)
				height = height || width; // default to square
				$containerForElement.html('<div class="embed-responsive embed-responsive-16by9"><iframe width="' + width + '" height="' + height + '" src="' + url + '" frameborder="0" allowfullscreen class="embed-responsive-item"></iframe></div>');
				this._resize(width, height);
				this._config.onContentLoaded.call(this);
				if (this._$modalArrows) this._$modalArrows.css('display', 'none'); //hide the arrows when showing video
				this._toggleLoading(false);
				return this;
			}
		}, {
			key: '_showHtml5Video',
			value: function _showHtml5Video(url, $containerForElement) {
				// should be used for videos only. for remote content use loadRemoteContent (data-type=url)
				var width = this._$element.data('width') || 560;
				var height = this._$element.data('height') || width / (560 / 315);
				$containerForElement.html('<div class="embed-responsive embed-responsive-16by9"><video width="' + width + '" height="' + height + '" src="' + url + '" preload="auto" autoplay controls class="embed-responsive-item"></video></div>');
				this._resize(width, height);
				this._config.onContentLoaded.call(this);
				if (this._$modalArrows) this._$modalArrows.css('display', 'none'); //hide the arrows when showing video
				this._toggleLoading(false);
				return this;
			}
		}, {
			key: '_loadRemoteContent',
			value: function _loadRemoteContent(url, $containerForElement) {
				var _this3 = this;

				var width = this._$element.data('width') || 560;
				var height = this._$element.data('height') || 560;

				var disableExternalCheck = this._$element.data('disableExternalCheck') || false;
				this._toggleLoading(false);

				// external urls are loading into an iframe
				// local ajax can be loaded into the container itself
				if (!disableExternalCheck && !this._isExternal(url)) {
					$containerForElement.load(url, $.proxy(function () {
						return _this3._$element.trigger('loaded.bs.modal');l;
					}));
				} else {
					$containerForElement.html('<iframe src="' + url + '" frameborder="0" allowfullscreen></iframe>');
					this._config.onContentLoaded.call(this);
				}

				if (this._$modalArrows) //hide the arrows when remote content
					this._$modalArrows.css('display', 'none');

				this._resize(width, height);
				return this;
			}
		}, {
			key: '_isExternal',
			value: function _isExternal(url) {
				var match = url.match(/^([^:\/?#]+:)?(?:\/\/([^\/?#]*))?([^?#]+)?(\?[^#]*)?(#.*)?/);
				if (typeof match[1] === "string" && match[1].length > 0 && match[1].toLowerCase() !== location.protocol) return true;

				if (typeof match[2] === "string" && match[2].length > 0 && match[2].replace(new RegExp(':(' + ({
					"http:": 80,
					"https:": 443
				})[location.protocol] + ')?$'), "") !== location.host) return true;

				return false;
			}
		}, {
			key: '_error',
			value: function _error(message) {
				console.error(message);
				this._containerToUse().html(message);
				this._resize(300, 300);
				return this;
			}
		}, {
			key: '_preloadImageByIndex',
			value: function _preloadImageByIndex(startIndex, numberOfTimes) {

				if (!this._$galleryItems) return;

				var next = $(this._$galleryItems.get(startIndex), false);
				if (typeof next == 'undefined') return;

				var src = next.attr('data-remote') || next.attr('href');
				if (next.attr('data-type') === 'image' || this._isImage(src)) this._preloadImage(src, false);

				if (numberOfTimes > 0) return this._preloadImageByIndex(startIndex + 1, numberOfTimes - 1);
			}
		}, {
			key: '_preloadImage',
			value: function _preloadImage(src, $containerForImage) {
				var _this4 = this;

				$containerForImage = $containerForImage || false;

				var img = new Image();
				if ($containerForImage) {
					(function () {

						// if loading takes > 200ms show a loader
						var loadingTimeout = setTimeout(function () {
							$containerForImage.append(_this4._config.loadingMessage);
						}, 200);

						img.onload = function () {
							if (loadingTimeout) clearTimeout(loadingTimeout);
							loadingTimeout = null;
							var image = $('<img />');
							image.attr('src', img.src);
							image.addClass('img-fluid');

							// backward compatibility for bootstrap v3
							image.css('width', '100%');

							$containerForImage.html(image);
							if (_this4._$modalArrows) _this4._$modalArrows.css('display', ''); // remove display to default to css property

							_this4._resize(img.width, img.height);
							_this4._toggleLoading(false);
							return _this4._config.onContentLoaded.call(_this4);
						};
						img.onerror = function () {
							_this4._toggleLoading(false);
							return _this4._error(_this4._config.strings.fail + ('  ' + src));
						};
					})();
				}

				img.src = src;
				return img;
			}
		}, {
			key: '_swipeGesure',
			value: function _swipeGesure() {
				if (this._touchendX < this._touchstartX) {
					return this.navigateRight();
				}
				if (this._touchendX > this._touchstartX) {
					return this.navigateLeft();
				}
			}
		}, {
			key: '_resize',
			value: function _resize(width, height) {

				height = height || width;
				this._wantedWidth = width;
				this._wantedHeight = height;

				var imageAspecRatio = width / height;

				// if width > the available space, scale down the expected width and height
				var widthBorderAndPadding = this._padding.left + this._padding.right + this._border.left + this._border.right;

				// force 10px margin if window size > 575px
				var addMargin = this._config.doc.body.clientWidth > 575 ? 20 : 0;
				var discountMargin = this._config.doc.body.clientWidth > 575 ? 0 : 20;

				var maxWidth = Math.min(width + widthBorderAndPadding, this._config.doc.body.clientWidth - addMargin, this._config.maxWidth);

				if (width + widthBorderAndPadding > maxWidth) {
					height = (maxWidth - widthBorderAndPadding - discountMargin) / imageAspecRatio;
					width = maxWidth;
				} else width = width + widthBorderAndPadding;

				var headerHeight = 0,
				    footerHeight = 0;

				// as the resize is performed the modal is show, the calculate might fail
				// if so, default to the default sizes
				if (this._footerIsShown) footerHeight = this._$modalFooter.outerHeight(true) || 55;

				if (this._titleIsShown) headerHeight = this._$modalHeader.outerHeight(true) || 67;

				var borderPadding = this._padding.top + this._padding.bottom + this._border.bottom + this._border.top;

				//calculated each time as resizing the window can cause them to change due to Bootstraps fluid margins
				var margins = parseFloat(this._$modalDialog.css('margin-top')) + parseFloat(this._$modalDialog.css('margin-bottom'));

				var maxHeight = Math.min(height, $(window).height() - borderPadding - margins - headerHeight - footerHeight, this._config.maxHeight - borderPadding - headerHeight - footerHeight);

				if (height > maxHeight) {
					// if height > the available height, scale down the width
					width = Math.ceil(maxHeight * imageAspecRatio) + widthBorderAndPadding;
				}

				this._$lightboxContainer.css('height', maxHeight);
				this._$modalDialog.css('flex', 1).css('maxWidth', width);

				var modal = this._$modal.data('bs.modal');
				if (modal) {
					// v4 method is mistakenly protected
					try {
						modal._handleUpdate();
					} catch (Exception) {
						modal.handleUpdate();
					}
				}
				return this;
			}
		}], [{
			key: '_jQueryInterface',
			value: function _jQueryInterface(config) {
				var _this5 = this;

				config = config || {};
				return this.each(function () {
					var $this = $(_this5);
					var _config = $.extend({}, Lightbox.Default, $this.data(), typeof config === 'object' && config);

					new Lightbox(_this5, _config);
				});
			}
		}]);

		return Lightbox;
	})();

	$.fn[NAME] = Lightbox._jQueryInterface;
	$.fn[NAME].Constructor = Lightbox;
	$.fn[NAME].noConflict = function () {
		$.fn[NAME] = JQUERY_NO_CONFLICT;
		return Lightbox._jQueryInterface;
	};

	return Lightbox;
})(jQuery);
//# sourceMappingURL=ekko-lightbox.js.map

}(jQuery);


/***/ })

},[["./assets/js/ekko-lightbox.js","runtime"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvZWtrby1saWdodGJveC5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvZWtrby1saWdodGJveC9kaXN0L2Vra28tbGlnaHRib3guanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7OztBQUFBO0FBQUE7QUFBQTs7Ozs7Ozs7Ozs7O0FDQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsaUNBQWlDLDJDQUEyQyxnQkFBZ0Isa0JBQWtCLE9BQU8sMkJBQTJCLHdEQUF3RCxnQ0FBZ0MsdURBQXVELDJEQUEyRCxFQUFFLEVBQUUseURBQXlELHFFQUFxRSw2REFBNkQsb0JBQW9CLEdBQUcsRUFBRTs7QUFFbGpCLGlEQUFpRCwwQ0FBMEMsMERBQTBELEVBQUU7O0FBRXZKOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxrR0FBa0c7QUFDbEc7QUFDQTtBQUNBLDRCQUE0QjtBQUM1Qiw2QkFBNkI7QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQSw4QkFBOEI7QUFDOUIsZ0NBQWdDO0FBQ2hDLDhCQUE4QjtBQUM5QixrQ0FBa0M7QUFDbEMsc0NBQXNDO0FBQ3RDO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsZ0JBQWdCO0FBQ2hCO0FBQ0EsK0RBQStEO0FBQy9EOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7O0FBRUg7QUFDQTs7QUFFQTs7QUFFQSw2QkFBNkI7QUFDN0I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBLHVFQUF1RTtBQUN2RSx1SkFBdUo7O0FBRXZKO0FBQ0EsMEhBQTBIO0FBQzFIO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE1BQU07QUFDTjtBQUNBO0FBQ0E7QUFDQSxNQUFNO0FBQ047QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUk7O0FBRUo7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0EsSUFBSTtBQUNKO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBOztBQUVBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0Esb0ZBQW9GO0FBQ3BGLEtBQUs7QUFDTDs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBLHVEQUF1RDtBQUN2RCxLQUFLO0FBQ0w7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsbUZBQW1GOztBQUVuRiwrR0FBK0c7QUFDL0c7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxxRkFBcUY7QUFDckYsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsK0NBQStDO0FBQy9DO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0EsNkJBQTZCO0FBQzdCO0FBQ0E7QUFDQTtBQUNBLHNFQUFzRTtBQUN0RTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHNFQUFzRTtBQUN0RTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHlEQUF5RDtBQUN6RCxNQUFNO0FBQ04sS0FBSztBQUNMO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLE9BQU87O0FBRVA7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQSx5RUFBeUU7O0FBRXpFO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxNQUFNO0FBQ047O0FBRUE7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsTUFBTTtBQUNOO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLDhCQUE4Qjs7QUFFOUI7QUFDQSxLQUFLO0FBQ0w7QUFDQSxHQUFHOztBQUVIO0FBQ0EsRUFBRTs7QUFFRjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxDQUFDO0FBQ0Q7O0FBRUEsQ0FBQyIsImZpbGUiOiJqcy9la2tvLWxpZ2h0Ym94LmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0ICdla2tvLWxpZ2h0Ym94L2Rpc3QvZWtrby1saWdodGJveCc7XHJcbiIsIi8qIVxuICogTGlnaHRib3ggZm9yIEJvb3RzdHJhcCBieSBAYXNobGV5ZHdcbiAqIGh0dHBzOi8vZ2l0aHViLmNvbS9hc2hsZXlkdy9saWdodGJveFxuICpcbiAqIExpY2Vuc2U6IGh0dHBzOi8vZ2l0aHViLmNvbS9hc2hsZXlkdy9saWdodGJveC9ibG9iL21hc3Rlci9MSUNFTlNFXG4gKi9cbitmdW5jdGlvbiAoJCkge1xuXG4ndXNlIHN0cmljdCc7XG5cbnZhciBfY3JlYXRlQ2xhc3MgPSAoZnVuY3Rpb24gKCkgeyBmdW5jdGlvbiBkZWZpbmVQcm9wZXJ0aWVzKHRhcmdldCwgcHJvcHMpIHsgZm9yICh2YXIgaSA9IDA7IGkgPCBwcm9wcy5sZW5ndGg7IGkrKykgeyB2YXIgZGVzY3JpcHRvciA9IHByb3BzW2ldOyBkZXNjcmlwdG9yLmVudW1lcmFibGUgPSBkZXNjcmlwdG9yLmVudW1lcmFibGUgfHwgZmFsc2U7IGRlc2NyaXB0b3IuY29uZmlndXJhYmxlID0gdHJ1ZTsgaWYgKCd2YWx1ZScgaW4gZGVzY3JpcHRvcikgZGVzY3JpcHRvci53cml0YWJsZSA9IHRydWU7IE9iamVjdC5kZWZpbmVQcm9wZXJ0eSh0YXJnZXQsIGRlc2NyaXB0b3Iua2V5LCBkZXNjcmlwdG9yKTsgfSB9IHJldHVybiBmdW5jdGlvbiAoQ29uc3RydWN0b3IsIHByb3RvUHJvcHMsIHN0YXRpY1Byb3BzKSB7IGlmIChwcm90b1Byb3BzKSBkZWZpbmVQcm9wZXJ0aWVzKENvbnN0cnVjdG9yLnByb3RvdHlwZSwgcHJvdG9Qcm9wcyk7IGlmIChzdGF0aWNQcm9wcykgZGVmaW5lUHJvcGVydGllcyhDb25zdHJ1Y3Rvciwgc3RhdGljUHJvcHMpOyByZXR1cm4gQ29uc3RydWN0b3I7IH07IH0pKCk7XG5cbmZ1bmN0aW9uIF9jbGFzc0NhbGxDaGVjayhpbnN0YW5jZSwgQ29uc3RydWN0b3IpIHsgaWYgKCEoaW5zdGFuY2UgaW5zdGFuY2VvZiBDb25zdHJ1Y3RvcikpIHsgdGhyb3cgbmV3IFR5cGVFcnJvcignQ2Fubm90IGNhbGwgYSBjbGFzcyBhcyBhIGZ1bmN0aW9uJyk7IH0gfVxuXG52YXIgTGlnaHRib3ggPSAoZnVuY3Rpb24gKCQpIHtcblxuXHR2YXIgTkFNRSA9ICdla2tvTGlnaHRib3gnO1xuXHR2YXIgSlFVRVJZX05PX0NPTkZMSUNUID0gJC5mbltOQU1FXTtcblxuXHR2YXIgRGVmYXVsdCA9IHtcblx0XHR0aXRsZTogJycsXG5cdFx0Zm9vdGVyOiAnJyxcblx0XHRtYXhXaWR0aDogOTk5OSxcblx0XHRtYXhIZWlnaHQ6IDk5OTksXG5cdFx0c2hvd0Fycm93czogdHJ1ZSwgLy9kaXNwbGF5IHRoZSBsZWZ0IC8gcmlnaHQgYXJyb3dzIG9yIG5vdFxuXHRcdHdyYXBwaW5nOiB0cnVlLCAvL2lmIHRydWUsIGdhbGxlcnkgbG9vcHMgaW5maW5pdGVseVxuXHRcdHR5cGU6IG51bGwsIC8vZm9yY2UgdGhlIGxpZ2h0Ym94IGludG8gaW1hZ2UgLyB5b3V0dWJlIG1vZGUuIGlmIG51bGwsIG9yIG5vdCBpbWFnZXx5b3V0dWJlfHZpbWVvOyBkZXRlY3QgaXRcblx0XHRhbHdheXNTaG93Q2xvc2U6IGZhbHNlLCAvL2Fsd2F5cyBzaG93IHRoZSBjbG9zZSBidXR0b24sIGV2ZW4gaWYgdGhlcmUgaXMgbm8gdGl0bGVcblx0XHRsb2FkaW5nTWVzc2FnZTogJzxkaXYgY2xhc3M9XCJla2tvLWxpZ2h0Ym94LWxvYWRlclwiPjxkaXY+PGRpdj48L2Rpdj48ZGl2PjwvZGl2PjwvZGl2PjwvZGl2PicsIC8vIGh0dHA6Ly90b2JpYXNhaGxpbi5jb20vc3BpbmtpdC9cblx0XHRsZWZ0QXJyb3c6ICc8c3Bhbj4mIzEwMDk0Ozwvc3Bhbj4nLFxuXHRcdHJpZ2h0QXJyb3c6ICc8c3Bhbj4mIzEwMDk1Ozwvc3Bhbj4nLFxuXHRcdHN0cmluZ3M6IHtcblx0XHRcdGNsb3NlOiAnQ2xvc2UnLFxuXHRcdFx0ZmFpbDogJ0ZhaWxlZCB0byBsb2FkIGltYWdlOicsXG5cdFx0XHR0eXBlOiAnQ291bGQgbm90IGRldGVjdCByZW1vdGUgdGFyZ2V0IHR5cGUuIEZvcmNlIHRoZSB0eXBlIHVzaW5nIGRhdGEtdHlwZSdcblx0XHR9LFxuXHRcdGRvYzogZG9jdW1lbnQsIC8vIGlmIGluIGFuIGlmcmFtZSBjYW4gc3BlY2lmeSB0b3AuZG9jdW1lbnRcblx0XHRvblNob3c6IGZ1bmN0aW9uIG9uU2hvdygpIHt9LFxuXHRcdG9uU2hvd246IGZ1bmN0aW9uIG9uU2hvd24oKSB7fSxcblx0XHRvbkhpZGU6IGZ1bmN0aW9uIG9uSGlkZSgpIHt9LFxuXHRcdG9uSGlkZGVuOiBmdW5jdGlvbiBvbkhpZGRlbigpIHt9LFxuXHRcdG9uTmF2aWdhdGU6IGZ1bmN0aW9uIG9uTmF2aWdhdGUoKSB7fSxcblx0XHRvbkNvbnRlbnRMb2FkZWQ6IGZ1bmN0aW9uIG9uQ29udGVudExvYWRlZCgpIHt9XG5cdH07XG5cblx0dmFyIExpZ2h0Ym94ID0gKGZ1bmN0aW9uICgpIHtcblx0XHRfY3JlYXRlQ2xhc3MoTGlnaHRib3gsIG51bGwsIFt7XG5cdFx0XHRrZXk6ICdEZWZhdWx0JyxcblxuXHRcdFx0LyoqXG4gICAgICAgQ2xhc3MgcHJvcGVydGllczpcbiAgIFx0IF8kZWxlbWVudDogbnVsbCAtPiB0aGUgPGE+IGVsZW1lbnQgY3VycmVudGx5IGJlaW5nIGRpc3BsYXllZFxuICAgIF8kbW9kYWw6IFRoZSBib290c3RyYXAgbW9kYWwgZ2VuZXJhdGVkXG4gICAgICAgXyRtb2RhbERpYWxvZzogVGhlIC5tb2RhbC1kaWFsb2dcbiAgICAgICBfJG1vZGFsQ29udGVudDogVGhlIC5tb2RhbC1jb250ZW50XG4gICAgICAgXyRtb2RhbEJvZHk6IFRoZSAubW9kYWwtYm9keVxuICAgICAgIF8kbW9kYWxIZWFkZXI6IFRoZSAubW9kYWwtaGVhZGVyXG4gICAgICAgXyRtb2RhbEZvb3RlcjogVGhlIC5tb2RhbC1mb290ZXJcbiAgICBfJGxpZ2h0Ym94Q29udGFpbmVyT25lOiBDb250YWluZXIgb2YgdGhlIGZpcnN0IGxpZ2h0Ym94IGVsZW1lbnRcbiAgICBfJGxpZ2h0Ym94Q29udGFpbmVyVHdvOiBDb250YWluZXIgb2YgdGhlIHNlY29uZCBsaWdodGJveCBlbGVtZW50XG4gICAgXyRsaWdodGJveEJvZHk6IEZpcnN0IGVsZW1lbnQgaW4gdGhlIGNvbnRhaW5lclxuICAgIF8kbW9kYWxBcnJvd3M6IFRoZSBvdmVybGF5ZWQgYXJyb3dzIGNvbnRhaW5lclxuICAgXHQgXyRnYWxsZXJ5SXRlbXM6IE90aGVyIDxhPidzIGF2YWlsYWJsZSBmb3IgdGhpcyBnYWxsZXJ5XG4gICAgX2dhbGxlcnlOYW1lOiBOYW1lIG9mIHRoZSBjdXJyZW50IGRhdGEoJ2dhbGxlcnknKSBzaG93aW5nXG4gICAgX2dhbGxlcnlJbmRleDogVGhlIGN1cnJlbnQgaW5kZXggb2YgdGhlIF8kZ2FsbGVyeUl0ZW1zIGJlaW5nIHNob3duXG4gICBcdCBfY29uZmlnOiB7fSB0aGUgb3B0aW9ucyBmb3IgdGhlIG1vZGFsXG4gICAgX21vZGFsSWQ6IHVuaXF1ZSBpZCBmb3IgdGhlIGN1cnJlbnQgbGlnaHRib3hcbiAgICBfcGFkZGluZyAvIF9ib3JkZXI6IENTUyBwcm9wZXJ0aWVzIGZvciB0aGUgbW9kYWwgY29udGFpbmVyOyB0aGVzZSBhcmUgdXNlZCB0byBjYWxjdWxhdGUgdGhlIGF2YWlsYWJsZSBzcGFjZSBmb3IgdGhlIGNvbnRlbnRcbiAgIFx0ICovXG5cblx0XHRcdGdldDogZnVuY3Rpb24gZ2V0KCkge1xuXHRcdFx0XHRyZXR1cm4gRGVmYXVsdDtcblx0XHRcdH1cblx0XHR9XSk7XG5cblx0XHRmdW5jdGlvbiBMaWdodGJveCgkZWxlbWVudCwgY29uZmlnKSB7XG5cdFx0XHR2YXIgX3RoaXMgPSB0aGlzO1xuXG5cdFx0XHRfY2xhc3NDYWxsQ2hlY2sodGhpcywgTGlnaHRib3gpO1xuXG5cdFx0XHR0aGlzLl9jb25maWcgPSAkLmV4dGVuZCh7fSwgRGVmYXVsdCwgY29uZmlnKTtcblx0XHRcdHRoaXMuXyRtb2RhbEFycm93cyA9IG51bGw7XG5cdFx0XHR0aGlzLl9nYWxsZXJ5SW5kZXggPSAwO1xuXHRcdFx0dGhpcy5fZ2FsbGVyeU5hbWUgPSBudWxsO1xuXHRcdFx0dGhpcy5fcGFkZGluZyA9IG51bGw7XG5cdFx0XHR0aGlzLl9ib3JkZXIgPSBudWxsO1xuXHRcdFx0dGhpcy5fdGl0bGVJc1Nob3duID0gZmFsc2U7XG5cdFx0XHR0aGlzLl9mb290ZXJJc1Nob3duID0gZmFsc2U7XG5cdFx0XHR0aGlzLl93YW50ZWRXaWR0aCA9IDA7XG5cdFx0XHR0aGlzLl93YW50ZWRIZWlnaHQgPSAwO1xuXHRcdFx0dGhpcy5fdG91Y2hzdGFydFggPSAwO1xuXHRcdFx0dGhpcy5fdG91Y2hlbmRYID0gMDtcblxuXHRcdFx0dGhpcy5fbW9kYWxJZCA9ICdla2tvTGlnaHRib3gtJyArIE1hdGguZmxvb3IoTWF0aC5yYW5kb20oKSAqIDEwMDAgKyAxKTtcblx0XHRcdHRoaXMuXyRlbGVtZW50ID0gJGVsZW1lbnQgaW5zdGFuY2VvZiBqUXVlcnkgPyAkZWxlbWVudCA6ICQoJGVsZW1lbnQpO1xuXG5cdFx0XHR0aGlzLl9pc0Jvb3RzdHJhcDMgPSAkLmZuLm1vZGFsLkNvbnN0cnVjdG9yLlZFUlNJT05bMF0gPT0gMztcblxuXHRcdFx0dmFyIGg0ID0gJzxoNCBjbGFzcz1cIm1vZGFsLXRpdGxlXCI+JyArICh0aGlzLl9jb25maWcudGl0bGUgfHwgXCImbmJzcDtcIikgKyAnPC9oND4nO1xuXHRcdFx0dmFyIGJ0biA9ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImNsb3NlXCIgZGF0YS1kaXNtaXNzPVwibW9kYWxcIiBhcmlhLWxhYmVsPVwiJyArIHRoaXMuX2NvbmZpZy5zdHJpbmdzLmNsb3NlICsgJ1wiPjxzcGFuIGFyaWEtaGlkZGVuPVwidHJ1ZVwiPiZ0aW1lczs8L3NwYW4+PC9idXR0b24+JztcblxuXHRcdFx0dmFyIGhlYWRlciA9ICc8ZGl2IGNsYXNzPVwibW9kYWwtaGVhZGVyJyArICh0aGlzLl9jb25maWcudGl0bGUgfHwgdGhpcy5fY29uZmlnLmFsd2F5c1Nob3dDbG9zZSA/ICcnIDogJyBoaWRlJykgKyAnXCI+JyArICh0aGlzLl9pc0Jvb3RzdHJhcDMgPyBidG4gKyBoNCA6IGg0ICsgYnRuKSArICc8L2Rpdj4nO1xuXHRcdFx0dmFyIGZvb3RlciA9ICc8ZGl2IGNsYXNzPVwibW9kYWwtZm9vdGVyJyArICh0aGlzLl9jb25maWcuZm9vdGVyID8gJycgOiAnIGhpZGUnKSArICdcIj4nICsgKHRoaXMuX2NvbmZpZy5mb290ZXIgfHwgXCImbmJzcDtcIikgKyAnPC9kaXY+Jztcblx0XHRcdHZhciBib2R5ID0gJzxkaXYgY2xhc3M9XCJtb2RhbC1ib2R5XCI+PGRpdiBjbGFzcz1cImVra28tbGlnaHRib3gtY29udGFpbmVyXCI+PGRpdiBjbGFzcz1cImVra28tbGlnaHRib3gtaXRlbSBmYWRlIGluIHNob3dcIj48L2Rpdj48ZGl2IGNsYXNzPVwiZWtrby1saWdodGJveC1pdGVtIGZhZGVcIj48L2Rpdj48L2Rpdj48L2Rpdj4nO1xuXHRcdFx0dmFyIGRpYWxvZyA9ICc8ZGl2IGNsYXNzPVwibW9kYWwtZGlhbG9nXCIgcm9sZT1cImRvY3VtZW50XCI+PGRpdiBjbGFzcz1cIm1vZGFsLWNvbnRlbnRcIj4nICsgaGVhZGVyICsgYm9keSArIGZvb3RlciArICc8L2Rpdj48L2Rpdj4nO1xuXHRcdFx0JCh0aGlzLl9jb25maWcuZG9jLmJvZHkpLmFwcGVuZCgnPGRpdiBpZD1cIicgKyB0aGlzLl9tb2RhbElkICsgJ1wiIGNsYXNzPVwiZWtrby1saWdodGJveCBtb2RhbCBmYWRlXCIgdGFiaW5kZXg9XCItMVwiIHRhYmluZGV4PVwiLTFcIiByb2xlPVwiZGlhbG9nXCIgYXJpYS1oaWRkZW49XCJ0cnVlXCI+JyArIGRpYWxvZyArICc8L2Rpdj4nKTtcblxuXHRcdFx0dGhpcy5fJG1vZGFsID0gJCgnIycgKyB0aGlzLl9tb2RhbElkLCB0aGlzLl9jb25maWcuZG9jKTtcblx0XHRcdHRoaXMuXyRtb2RhbERpYWxvZyA9IHRoaXMuXyRtb2RhbC5maW5kKCcubW9kYWwtZGlhbG9nJykuZmlyc3QoKTtcblx0XHRcdHRoaXMuXyRtb2RhbENvbnRlbnQgPSB0aGlzLl8kbW9kYWwuZmluZCgnLm1vZGFsLWNvbnRlbnQnKS5maXJzdCgpO1xuXHRcdFx0dGhpcy5fJG1vZGFsQm9keSA9IHRoaXMuXyRtb2RhbC5maW5kKCcubW9kYWwtYm9keScpLmZpcnN0KCk7XG5cdFx0XHR0aGlzLl8kbW9kYWxIZWFkZXIgPSB0aGlzLl8kbW9kYWwuZmluZCgnLm1vZGFsLWhlYWRlcicpLmZpcnN0KCk7XG5cdFx0XHR0aGlzLl8kbW9kYWxGb290ZXIgPSB0aGlzLl8kbW9kYWwuZmluZCgnLm1vZGFsLWZvb3RlcicpLmZpcnN0KCk7XG5cblx0XHRcdHRoaXMuXyRsaWdodGJveENvbnRhaW5lciA9IHRoaXMuXyRtb2RhbEJvZHkuZmluZCgnLmVra28tbGlnaHRib3gtY29udGFpbmVyJykuZmlyc3QoKTtcblx0XHRcdHRoaXMuXyRsaWdodGJveEJvZHlPbmUgPSB0aGlzLl8kbGlnaHRib3hDb250YWluZXIuZmluZCgnPiBkaXY6Zmlyc3QtY2hpbGQnKS5maXJzdCgpO1xuXHRcdFx0dGhpcy5fJGxpZ2h0Ym94Qm9keVR3byA9IHRoaXMuXyRsaWdodGJveENvbnRhaW5lci5maW5kKCc+IGRpdjpsYXN0LWNoaWxkJykuZmlyc3QoKTtcblxuXHRcdFx0dGhpcy5fYm9yZGVyID0gdGhpcy5fY2FsY3VsYXRlQm9yZGVycygpO1xuXHRcdFx0dGhpcy5fcGFkZGluZyA9IHRoaXMuX2NhbGN1bGF0ZVBhZGRpbmcoKTtcblxuXHRcdFx0dGhpcy5fZ2FsbGVyeU5hbWUgPSB0aGlzLl8kZWxlbWVudC5kYXRhKCdnYWxsZXJ5Jyk7XG5cdFx0XHRpZiAodGhpcy5fZ2FsbGVyeU5hbWUpIHtcblx0XHRcdFx0dGhpcy5fJGdhbGxlcnlJdGVtcyA9ICQoZG9jdW1lbnQuYm9keSkuZmluZCgnKltkYXRhLWdhbGxlcnk9XCInICsgdGhpcy5fZ2FsbGVyeU5hbWUgKyAnXCJdJyk7XG5cdFx0XHRcdHRoaXMuX2dhbGxlcnlJbmRleCA9IHRoaXMuXyRnYWxsZXJ5SXRlbXMuaW5kZXgodGhpcy5fJGVsZW1lbnQpO1xuXHRcdFx0XHQkKGRvY3VtZW50KS5vbigna2V5ZG93bi5la2tvTGlnaHRib3gnLCB0aGlzLl9uYXZpZ2F0aW9uYWxCaW5kZXIuYmluZCh0aGlzKSk7XG5cblx0XHRcdFx0Ly8gYWRkIHRoZSBkaXJlY3Rpb25hbCBhcnJvd3MgdG8gdGhlIG1vZGFsXG5cdFx0XHRcdGlmICh0aGlzLl9jb25maWcuc2hvd0Fycm93cyAmJiB0aGlzLl8kZ2FsbGVyeUl0ZW1zLmxlbmd0aCA+IDEpIHtcblx0XHRcdFx0XHR0aGlzLl8kbGlnaHRib3hDb250YWluZXIuYXBwZW5kKCc8ZGl2IGNsYXNzPVwiZWtrby1saWdodGJveC1uYXYtb3ZlcmxheVwiPjxhIGhyZWY9XCIjXCI+JyArIHRoaXMuX2NvbmZpZy5sZWZ0QXJyb3cgKyAnPC9hPjxhIGhyZWY9XCIjXCI+JyArIHRoaXMuX2NvbmZpZy5yaWdodEFycm93ICsgJzwvYT48L2Rpdj4nKTtcblx0XHRcdFx0XHR0aGlzLl8kbW9kYWxBcnJvd3MgPSB0aGlzLl8kbGlnaHRib3hDb250YWluZXIuZmluZCgnZGl2LmVra28tbGlnaHRib3gtbmF2LW92ZXJsYXknKS5maXJzdCgpO1xuXHRcdFx0XHRcdHRoaXMuXyRsaWdodGJveENvbnRhaW5lci5vbignY2xpY2snLCAnYTpmaXJzdC1jaGlsZCcsIGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHRcdHJldHVybiBfdGhpcy5uYXZpZ2F0ZUxlZnQoKTtcblx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHR0aGlzLl8kbGlnaHRib3hDb250YWluZXIub24oJ2NsaWNrJywgJ2E6bGFzdC1jaGlsZCcsIGZ1bmN0aW9uIChldmVudCkge1xuXHRcdFx0XHRcdFx0ZXZlbnQucHJldmVudERlZmF1bHQoKTtcblx0XHRcdFx0XHRcdHJldHVybiBfdGhpcy5uYXZpZ2F0ZVJpZ2h0KCk7XG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0dGhpcy51cGRhdGVOYXZpZ2F0aW9uKCk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0dGhpcy5fJG1vZGFsLm9uKCdzaG93LmJzLm1vZGFsJywgdGhpcy5fY29uZmlnLm9uU2hvdy5iaW5kKHRoaXMpKS5vbignc2hvd24uYnMubW9kYWwnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdF90aGlzLl90b2dnbGVMb2FkaW5nKHRydWUpO1xuXHRcdFx0XHRfdGhpcy5faGFuZGxlKCk7XG5cdFx0XHRcdHJldHVybiBfdGhpcy5fY29uZmlnLm9uU2hvd24uY2FsbChfdGhpcyk7XG5cdFx0XHR9KS5vbignaGlkZS5icy5tb2RhbCcsIHRoaXMuX2NvbmZpZy5vbkhpZGUuYmluZCh0aGlzKSkub24oJ2hpZGRlbi5icy5tb2RhbCcsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0aWYgKF90aGlzLl9nYWxsZXJ5TmFtZSkge1xuXHRcdFx0XHRcdCQoZG9jdW1lbnQpLm9mZigna2V5ZG93bi5la2tvTGlnaHRib3gnKTtcblx0XHRcdFx0XHQkKHdpbmRvdykub2ZmKCdyZXNpemUuZWtrb0xpZ2h0Ym94Jyk7XG5cdFx0XHRcdH1cblx0XHRcdFx0X3RoaXMuXyRtb2RhbC5yZW1vdmUoKTtcblx0XHRcdFx0cmV0dXJuIF90aGlzLl9jb25maWcub25IaWRkZW4uY2FsbChfdGhpcyk7XG5cdFx0XHR9KS5tb2RhbCh0aGlzLl9jb25maWcpO1xuXG5cdFx0XHQkKHdpbmRvdykub24oJ3Jlc2l6ZS5la2tvTGlnaHRib3gnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdF90aGlzLl9yZXNpemUoX3RoaXMuX3dhbnRlZFdpZHRoLCBfdGhpcy5fd2FudGVkSGVpZ2h0KTtcblx0XHRcdH0pO1xuXHRcdFx0dGhpcy5fJGxpZ2h0Ym94Q29udGFpbmVyLm9uKCd0b3VjaHN0YXJ0JywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRfdGhpcy5fdG91Y2hzdGFydFggPSBldmVudC5jaGFuZ2VkVG91Y2hlc1swXS5zY3JlZW5YO1xuXHRcdFx0fSkub24oJ3RvdWNoZW5kJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRfdGhpcy5fdG91Y2hlbmRYID0gZXZlbnQuY2hhbmdlZFRvdWNoZXNbMF0uc2NyZWVuWDtcblx0XHRcdFx0X3RoaXMuX3N3aXBlR2VzdXJlKCk7XG5cdFx0XHR9KTtcblx0XHR9XG5cblx0XHRfY3JlYXRlQ2xhc3MoTGlnaHRib3gsIFt7XG5cdFx0XHRrZXk6ICdlbGVtZW50Jyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBlbGVtZW50KCkge1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5fJGVsZW1lbnQ7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnbW9kYWwnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIG1vZGFsKCkge1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5fJG1vZGFsO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ25hdmlnYXRlVG8nLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIG5hdmlnYXRlVG8oaW5kZXgpIHtcblxuXHRcdFx0XHRpZiAoaW5kZXggPCAwIHx8IGluZGV4ID4gdGhpcy5fJGdhbGxlcnlJdGVtcy5sZW5ndGggLSAxKSByZXR1cm4gdGhpcztcblxuXHRcdFx0XHR0aGlzLl9nYWxsZXJ5SW5kZXggPSBpbmRleDtcblxuXHRcdFx0XHR0aGlzLnVwZGF0ZU5hdmlnYXRpb24oKTtcblxuXHRcdFx0XHR0aGlzLl8kZWxlbWVudCA9ICQodGhpcy5fJGdhbGxlcnlJdGVtcy5nZXQodGhpcy5fZ2FsbGVyeUluZGV4KSk7XG5cdFx0XHRcdHRoaXMuX2hhbmRsZSgpO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ25hdmlnYXRlTGVmdCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gbmF2aWdhdGVMZWZ0KCkge1xuXG5cdFx0XHRcdGlmICghdGhpcy5fJGdhbGxlcnlJdGVtcykgcmV0dXJuO1xuXG5cdFx0XHRcdGlmICh0aGlzLl8kZ2FsbGVyeUl0ZW1zLmxlbmd0aCA9PT0gMSkgcmV0dXJuO1xuXG5cdFx0XHRcdGlmICh0aGlzLl9nYWxsZXJ5SW5kZXggPT09IDApIHtcblx0XHRcdFx0XHRpZiAodGhpcy5fY29uZmlnLndyYXBwaW5nKSB0aGlzLl9nYWxsZXJ5SW5kZXggPSB0aGlzLl8kZ2FsbGVyeUl0ZW1zLmxlbmd0aCAtIDE7ZWxzZSByZXR1cm47XG5cdFx0XHRcdH0gZWxzZSAvL2NpcmN1bGFyXG5cdFx0XHRcdFx0dGhpcy5fZ2FsbGVyeUluZGV4LS07XG5cblx0XHRcdFx0dGhpcy5fY29uZmlnLm9uTmF2aWdhdGUuY2FsbCh0aGlzLCAnbGVmdCcsIHRoaXMuX2dhbGxlcnlJbmRleCk7XG5cdFx0XHRcdHJldHVybiB0aGlzLm5hdmlnYXRlVG8odGhpcy5fZ2FsbGVyeUluZGV4KTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICduYXZpZ2F0ZVJpZ2h0Jyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBuYXZpZ2F0ZVJpZ2h0KCkge1xuXG5cdFx0XHRcdGlmICghdGhpcy5fJGdhbGxlcnlJdGVtcykgcmV0dXJuO1xuXG5cdFx0XHRcdGlmICh0aGlzLl8kZ2FsbGVyeUl0ZW1zLmxlbmd0aCA9PT0gMSkgcmV0dXJuO1xuXG5cdFx0XHRcdGlmICh0aGlzLl9nYWxsZXJ5SW5kZXggPT09IHRoaXMuXyRnYWxsZXJ5SXRlbXMubGVuZ3RoIC0gMSkge1xuXHRcdFx0XHRcdGlmICh0aGlzLl9jb25maWcud3JhcHBpbmcpIHRoaXMuX2dhbGxlcnlJbmRleCA9IDA7ZWxzZSByZXR1cm47XG5cdFx0XHRcdH0gZWxzZSAvL2NpcmN1bGFyXG5cdFx0XHRcdFx0dGhpcy5fZ2FsbGVyeUluZGV4Kys7XG5cblx0XHRcdFx0dGhpcy5fY29uZmlnLm9uTmF2aWdhdGUuY2FsbCh0aGlzLCAncmlnaHQnLCB0aGlzLl9nYWxsZXJ5SW5kZXgpO1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5uYXZpZ2F0ZVRvKHRoaXMuX2dhbGxlcnlJbmRleCk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAndXBkYXRlTmF2aWdhdGlvbicsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gdXBkYXRlTmF2aWdhdGlvbigpIHtcblx0XHRcdFx0aWYgKCF0aGlzLl9jb25maWcud3JhcHBpbmcpIHtcblx0XHRcdFx0XHR2YXIgJG5hdiA9IHRoaXMuXyRsaWdodGJveENvbnRhaW5lci5maW5kKCdkaXYuZWtrby1saWdodGJveC1uYXYtb3ZlcmxheScpO1xuXHRcdFx0XHRcdGlmICh0aGlzLl9nYWxsZXJ5SW5kZXggPT09IDApICRuYXYuZmluZCgnYTpmaXJzdC1jaGlsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO2Vsc2UgJG5hdi5maW5kKCdhOmZpcnN0LWNoaWxkJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cblx0XHRcdFx0XHRpZiAodGhpcy5fZ2FsbGVyeUluZGV4ID09PSB0aGlzLl8kZ2FsbGVyeUl0ZW1zLmxlbmd0aCAtIDEpICRuYXYuZmluZCgnYTpsYXN0LWNoaWxkJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7ZWxzZSAkbmF2LmZpbmQoJ2E6bGFzdC1jaGlsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnY2xvc2UnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIGNsb3NlKCkge1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5fJG1vZGFsLm1vZGFsKCdoaWRlJyk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIGhlbHBlciBwcml2YXRlIG1ldGhvZHNcblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfbmF2aWdhdGlvbmFsQmluZGVyJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfbmF2aWdhdGlvbmFsQmluZGVyKGV2ZW50KSB7XG5cdFx0XHRcdGV2ZW50ID0gZXZlbnQgfHwgd2luZG93LmV2ZW50O1xuXHRcdFx0XHRpZiAoZXZlbnQua2V5Q29kZSA9PT0gMzkpIHJldHVybiB0aGlzLm5hdmlnYXRlUmlnaHQoKTtcblx0XHRcdFx0aWYgKGV2ZW50LmtleUNvZGUgPT09IDM3KSByZXR1cm4gdGhpcy5uYXZpZ2F0ZUxlZnQoKTtcblx0XHRcdH1cblxuXHRcdFx0Ly8gdHlwZSBkZXRlY3Rpb24gcHJpdmF0ZSBtZXRob2RzXG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2RldGVjdFJlbW90ZVR5cGUnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9kZXRlY3RSZW1vdGVUeXBlKHNyYywgdHlwZSkge1xuXG5cdFx0XHRcdHR5cGUgPSB0eXBlIHx8IGZhbHNlO1xuXG5cdFx0XHRcdGlmICghdHlwZSAmJiB0aGlzLl9pc0ltYWdlKHNyYykpIHR5cGUgPSAnaW1hZ2UnO1xuXHRcdFx0XHRpZiAoIXR5cGUgJiYgdGhpcy5fZ2V0WW91dHViZUlkKHNyYykpIHR5cGUgPSAneW91dHViZSc7XG5cdFx0XHRcdGlmICghdHlwZSAmJiB0aGlzLl9nZXRWaW1lb0lkKHNyYykpIHR5cGUgPSAndmltZW8nO1xuXHRcdFx0XHRpZiAoIXR5cGUgJiYgdGhpcy5fZ2V0SW5zdGFncmFtSWQoc3JjKSkgdHlwZSA9ICdpbnN0YWdyYW0nO1xuXG5cdFx0XHRcdGlmICghdHlwZSB8fCBbJ2ltYWdlJywgJ3lvdXR1YmUnLCAndmltZW8nLCAnaW5zdGFncmFtJywgJ3ZpZGVvJywgJ3VybCddLmluZGV4T2YodHlwZSkgPCAwKSB0eXBlID0gJ3VybCc7XG5cblx0XHRcdFx0cmV0dXJuIHR5cGU7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2lzSW1hZ2UnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9pc0ltYWdlKHN0cmluZykge1xuXHRcdFx0XHRyZXR1cm4gc3RyaW5nICYmIHN0cmluZy5tYXRjaCgvKF5kYXRhOmltYWdlXFwvLiosKXwoXFwuKGpwKGV8Z3xlZyl8Z2lmfHBuZ3xibXB8d2VicHxzdmcpKChcXD98IykuKik/JCkvaSk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2NvbnRhaW5lclRvVXNlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfY29udGFpbmVyVG9Vc2UoKSB7XG5cdFx0XHRcdHZhciBfdGhpczIgPSB0aGlzO1xuXG5cdFx0XHRcdC8vIGlmIGN1cnJlbnRseSBzaG93aW5nIGFuIGltYWdlLCBmYWRlIGl0IG91dCBhbmQgcmVtb3ZlXG5cdFx0XHRcdHZhciAkdG9Vc2UgPSB0aGlzLl8kbGlnaHRib3hCb2R5VHdvO1xuXHRcdFx0XHR2YXIgJGN1cnJlbnQgPSB0aGlzLl8kbGlnaHRib3hCb2R5T25lO1xuXG5cdFx0XHRcdGlmICh0aGlzLl8kbGlnaHRib3hCb2R5VHdvLmhhc0NsYXNzKCdpbicpKSB7XG5cdFx0XHRcdFx0JHRvVXNlID0gdGhpcy5fJGxpZ2h0Ym94Qm9keU9uZTtcblx0XHRcdFx0XHQkY3VycmVudCA9IHRoaXMuXyRsaWdodGJveEJvZHlUd287XG5cdFx0XHRcdH1cblxuXHRcdFx0XHQkY3VycmVudC5yZW1vdmVDbGFzcygnaW4gc2hvdycpO1xuXHRcdFx0XHRzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRpZiAoIV90aGlzMi5fJGxpZ2h0Ym94Qm9keVR3by5oYXNDbGFzcygnaW4nKSkgX3RoaXMyLl8kbGlnaHRib3hCb2R5VHdvLmVtcHR5KCk7XG5cdFx0XHRcdFx0aWYgKCFfdGhpczIuXyRsaWdodGJveEJvZHlPbmUuaGFzQ2xhc3MoJ2luJykpIF90aGlzMi5fJGxpZ2h0Ym94Qm9keU9uZS5lbXB0eSgpO1xuXHRcdFx0XHR9LCA1MDApO1xuXG5cdFx0XHRcdCR0b1VzZS5hZGRDbGFzcygnaW4gc2hvdycpO1xuXHRcdFx0XHRyZXR1cm4gJHRvVXNlO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19oYW5kbGUnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9oYW5kbGUoKSB7XG5cblx0XHRcdFx0dmFyICR0b1VzZSA9IHRoaXMuX2NvbnRhaW5lclRvVXNlKCk7XG5cdFx0XHRcdHRoaXMuX3VwZGF0ZVRpdGxlQW5kRm9vdGVyKCk7XG5cblx0XHRcdFx0dmFyIGN1cnJlbnRSZW1vdGUgPSB0aGlzLl8kZWxlbWVudC5hdHRyKCdkYXRhLXJlbW90ZScpIHx8IHRoaXMuXyRlbGVtZW50LmF0dHIoJ2hyZWYnKTtcblx0XHRcdFx0dmFyIGN1cnJlbnRUeXBlID0gdGhpcy5fZGV0ZWN0UmVtb3RlVHlwZShjdXJyZW50UmVtb3RlLCB0aGlzLl8kZWxlbWVudC5hdHRyKCdkYXRhLXR5cGUnKSB8fCBmYWxzZSk7XG5cblx0XHRcdFx0aWYgKFsnaW1hZ2UnLCAneW91dHViZScsICd2aW1lbycsICdpbnN0YWdyYW0nLCAndmlkZW8nLCAndXJsJ10uaW5kZXhPZihjdXJyZW50VHlwZSkgPCAwKSByZXR1cm4gdGhpcy5fZXJyb3IodGhpcy5fY29uZmlnLnN0cmluZ3MudHlwZSk7XG5cblx0XHRcdFx0c3dpdGNoIChjdXJyZW50VHlwZSkge1xuXHRcdFx0XHRcdGNhc2UgJ2ltYWdlJzpcblx0XHRcdFx0XHRcdHRoaXMuX3ByZWxvYWRJbWFnZShjdXJyZW50UmVtb3RlLCAkdG9Vc2UpO1xuXHRcdFx0XHRcdFx0dGhpcy5fcHJlbG9hZEltYWdlQnlJbmRleCh0aGlzLl9nYWxsZXJ5SW5kZXgsIDMpO1xuXHRcdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdFx0Y2FzZSAneW91dHViZSc6XG5cdFx0XHRcdFx0XHR0aGlzLl9zaG93WW91dHViZVZpZGVvKGN1cnJlbnRSZW1vdGUsICR0b1VzZSk7XG5cdFx0XHRcdFx0XHRicmVhaztcblx0XHRcdFx0XHRjYXNlICd2aW1lbyc6XG5cdFx0XHRcdFx0XHR0aGlzLl9zaG93VmltZW9WaWRlbyh0aGlzLl9nZXRWaW1lb0lkKGN1cnJlbnRSZW1vdGUpLCAkdG9Vc2UpO1xuXHRcdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdFx0Y2FzZSAnaW5zdGFncmFtJzpcblx0XHRcdFx0XHRcdHRoaXMuX3Nob3dJbnN0YWdyYW1WaWRlbyh0aGlzLl9nZXRJbnN0YWdyYW1JZChjdXJyZW50UmVtb3RlKSwgJHRvVXNlKTtcblx0XHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRcdGNhc2UgJ3ZpZGVvJzpcblx0XHRcdFx0XHRcdHRoaXMuX3Nob3dIdG1sNVZpZGVvKGN1cnJlbnRSZW1vdGUsICR0b1VzZSk7XG5cdFx0XHRcdFx0XHRicmVhaztcblx0XHRcdFx0XHRkZWZhdWx0OlxuXHRcdFx0XHRcdFx0Ly8gdXJsXG5cdFx0XHRcdFx0XHR0aGlzLl9sb2FkUmVtb3RlQ29udGVudChjdXJyZW50UmVtb3RlLCAkdG9Vc2UpO1xuXHRcdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfZ2V0WW91dHViZUlkJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfZ2V0WW91dHViZUlkKHN0cmluZykge1xuXHRcdFx0XHRpZiAoIXN0cmluZykgcmV0dXJuIGZhbHNlO1xuXHRcdFx0XHR2YXIgbWF0Y2hlcyA9IHN0cmluZy5tYXRjaCgvXi4qKHlvdXR1LmJlXFwvfHZcXC98dVxcL1xcd1xcL3xlbWJlZFxcL3x3YXRjaFxcP3Y9fFxcJnY9KShbXiNcXCZcXD9dKikuKi8pO1xuXHRcdFx0XHRyZXR1cm4gbWF0Y2hlcyAmJiBtYXRjaGVzWzJdLmxlbmd0aCA9PT0gMTEgPyBtYXRjaGVzWzJdIDogZmFsc2U7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2dldFZpbWVvSWQnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9nZXRWaW1lb0lkKHN0cmluZykge1xuXHRcdFx0XHRyZXR1cm4gc3RyaW5nICYmIHN0cmluZy5pbmRleE9mKCd2aW1lbycpID4gMCA/IHN0cmluZyA6IGZhbHNlO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19nZXRJbnN0YWdyYW1JZCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2dldEluc3RhZ3JhbUlkKHN0cmluZykge1xuXHRcdFx0XHRyZXR1cm4gc3RyaW5nICYmIHN0cmluZy5pbmRleE9mKCdpbnN0YWdyYW0nKSA+IDAgPyBzdHJpbmcgOiBmYWxzZTtcblx0XHRcdH1cblxuXHRcdFx0Ly8gbGF5b3V0IHByaXZhdGUgbWV0aG9kc1xuXHRcdH0sIHtcblx0XHRcdGtleTogJ190b2dnbGVMb2FkaW5nJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfdG9nZ2xlTG9hZGluZyhzaG93KSB7XG5cdFx0XHRcdHNob3cgPSBzaG93IHx8IGZhbHNlO1xuXHRcdFx0XHRpZiAoc2hvdykge1xuXHRcdFx0XHRcdHRoaXMuXyRtb2RhbERpYWxvZy5jc3MoJ2Rpc3BsYXknLCAnbm9uZScpO1xuXHRcdFx0XHRcdHRoaXMuXyRtb2RhbC5yZW1vdmVDbGFzcygnaW4gc2hvdycpO1xuXHRcdFx0XHRcdCQoJy5tb2RhbC1iYWNrZHJvcCcpLmFwcGVuZCh0aGlzLl9jb25maWcubG9hZGluZ01lc3NhZ2UpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdHRoaXMuXyRtb2RhbERpYWxvZy5jc3MoJ2Rpc3BsYXknLCAnYmxvY2snKTtcblx0XHRcdFx0XHR0aGlzLl8kbW9kYWwuYWRkQ2xhc3MoJ2luIHNob3cnKTtcblx0XHRcdFx0XHQkKCcubW9kYWwtYmFja2Ryb3AnKS5maW5kKCcuZWtrby1saWdodGJveC1sb2FkZXInKS5yZW1vdmUoKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfY2FsY3VsYXRlQm9yZGVycycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2NhbGN1bGF0ZUJvcmRlcnMoKSB7XG5cdFx0XHRcdHJldHVybiB7XG5cdFx0XHRcdFx0dG9wOiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdib3JkZXItdG9wLXdpZHRoJyksXG5cdFx0XHRcdFx0cmlnaHQ6IHRoaXMuX3RvdGFsQ3NzQnlBdHRyaWJ1dGUoJ2JvcmRlci1yaWdodC13aWR0aCcpLFxuXHRcdFx0XHRcdGJvdHRvbTogdGhpcy5fdG90YWxDc3NCeUF0dHJpYnV0ZSgnYm9yZGVyLWJvdHRvbS13aWR0aCcpLFxuXHRcdFx0XHRcdGxlZnQ6IHRoaXMuX3RvdGFsQ3NzQnlBdHRyaWJ1dGUoJ2JvcmRlci1sZWZ0LXdpZHRoJylcblx0XHRcdFx0fTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfY2FsY3VsYXRlUGFkZGluZycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2NhbGN1bGF0ZVBhZGRpbmcoKSB7XG5cdFx0XHRcdHJldHVybiB7XG5cdFx0XHRcdFx0dG9wOiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdwYWRkaW5nLXRvcCcpLFxuXHRcdFx0XHRcdHJpZ2h0OiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdwYWRkaW5nLXJpZ2h0JyksXG5cdFx0XHRcdFx0Ym90dG9tOiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdwYWRkaW5nLWJvdHRvbScpLFxuXHRcdFx0XHRcdGxlZnQ6IHRoaXMuX3RvdGFsQ3NzQnlBdHRyaWJ1dGUoJ3BhZGRpbmctbGVmdCcpXG5cdFx0XHRcdH07XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3RvdGFsQ3NzQnlBdHRyaWJ1dGUnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF90b3RhbENzc0J5QXR0cmlidXRlKGF0dHJpYnV0ZSkge1xuXHRcdFx0XHRyZXR1cm4gcGFyc2VJbnQodGhpcy5fJG1vZGFsRGlhbG9nLmNzcyhhdHRyaWJ1dGUpLCAxMCkgKyBwYXJzZUludCh0aGlzLl8kbW9kYWxDb250ZW50LmNzcyhhdHRyaWJ1dGUpLCAxMCkgKyBwYXJzZUludCh0aGlzLl8kbW9kYWxCb2R5LmNzcyhhdHRyaWJ1dGUpLCAxMCk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3VwZGF0ZVRpdGxlQW5kRm9vdGVyJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfdXBkYXRlVGl0bGVBbmRGb290ZXIoKSB7XG5cdFx0XHRcdHZhciB0aXRsZSA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ3RpdGxlJykgfHwgXCJcIjtcblx0XHRcdFx0dmFyIGNhcHRpb24gPSB0aGlzLl8kZWxlbWVudC5kYXRhKCdmb290ZXInKSB8fCBcIlwiO1xuXG5cdFx0XHRcdHRoaXMuX3RpdGxlSXNTaG93biA9IGZhbHNlO1xuXHRcdFx0XHRpZiAodGl0bGUgfHwgdGhpcy5fY29uZmlnLmFsd2F5c1Nob3dDbG9zZSkge1xuXHRcdFx0XHRcdHRoaXMuX3RpdGxlSXNTaG93biA9IHRydWU7XG5cdFx0XHRcdFx0dGhpcy5fJG1vZGFsSGVhZGVyLmNzcygnZGlzcGxheScsICcnKS5maW5kKCcubW9kYWwtdGl0bGUnKS5odG1sKHRpdGxlIHx8IFwiJm5ic3A7XCIpO1xuXHRcdFx0XHR9IGVsc2UgdGhpcy5fJG1vZGFsSGVhZGVyLmNzcygnZGlzcGxheScsICdub25lJyk7XG5cblx0XHRcdFx0dGhpcy5fZm9vdGVySXNTaG93biA9IGZhbHNlO1xuXHRcdFx0XHRpZiAoY2FwdGlvbikge1xuXHRcdFx0XHRcdHRoaXMuX2Zvb3RlcklzU2hvd24gPSB0cnVlO1xuXHRcdFx0XHRcdHRoaXMuXyRtb2RhbEZvb3Rlci5jc3MoJ2Rpc3BsYXknLCAnJykuaHRtbChjYXB0aW9uKTtcblx0XHRcdFx0fSBlbHNlIHRoaXMuXyRtb2RhbEZvb3Rlci5jc3MoJ2Rpc3BsYXknLCAnbm9uZScpO1xuXG5cdFx0XHRcdHJldHVybiB0aGlzO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19zaG93WW91dHViZVZpZGVvJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfc2hvd1lvdXR1YmVWaWRlbyhyZW1vdGUsICRjb250YWluZXJGb3JFbGVtZW50KSB7XG5cdFx0XHRcdHZhciBpZCA9IHRoaXMuX2dldFlvdXR1YmVJZChyZW1vdGUpO1xuXHRcdFx0XHR2YXIgcXVlcnkgPSByZW1vdGUuaW5kZXhPZignJicpID4gMCA/IHJlbW90ZS5zdWJzdHIocmVtb3RlLmluZGV4T2YoJyYnKSkgOiAnJztcblx0XHRcdFx0dmFyIHdpZHRoID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnd2lkdGgnKSB8fCA1NjA7XG5cdFx0XHRcdHZhciBoZWlnaHQgPSB0aGlzLl8kZWxlbWVudC5kYXRhKCdoZWlnaHQnKSB8fCB3aWR0aCAvICg1NjAgLyAzMTUpO1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5fc2hvd1ZpZGVvSWZyYW1lKCcvL3d3dy55b3V0dWJlLmNvbS9lbWJlZC8nICsgaWQgKyAnP2JhZGdlPTAmYXV0b3BsYXk9MSZodG1sNT0xJyArIHF1ZXJ5LCB3aWR0aCwgaGVpZ2h0LCAkY29udGFpbmVyRm9yRWxlbWVudCk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3Nob3dWaW1lb1ZpZGVvJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfc2hvd1ZpbWVvVmlkZW8oaWQsICRjb250YWluZXJGb3JFbGVtZW50KSB7XG5cdFx0XHRcdHZhciB3aWR0aCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ3dpZHRoJykgfHwgNTAwO1xuXHRcdFx0XHR2YXIgaGVpZ2h0ID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnaGVpZ2h0JykgfHwgd2lkdGggLyAoNTYwIC8gMzE1KTtcblx0XHRcdFx0cmV0dXJuIHRoaXMuX3Nob3dWaWRlb0lmcmFtZShpZCArICc/YXV0b3BsYXk9MScsIHdpZHRoLCBoZWlnaHQsICRjb250YWluZXJGb3JFbGVtZW50KTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfc2hvd0luc3RhZ3JhbVZpZGVvJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfc2hvd0luc3RhZ3JhbVZpZGVvKGlkLCAkY29udGFpbmVyRm9yRWxlbWVudCkge1xuXHRcdFx0XHQvLyBpbnN0YWdyYW0gbG9hZCB0aGVpciBjb250ZW50IGludG8gaWZyYW1lJ3Mgc28gdGhpcyBjYW4gYmUgcHV0IHN0cmFpZ2h0IGludG8gdGhlIGVsZW1lbnRcblx0XHRcdFx0dmFyIHdpZHRoID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnd2lkdGgnKSB8fCA2MTI7XG5cdFx0XHRcdHZhciBoZWlnaHQgPSB3aWR0aCArIDgwO1xuXHRcdFx0XHRpZCA9IGlkLnN1YnN0cigtMSkgIT09ICcvJyA/IGlkICsgJy8nIDogaWQ7IC8vIGVuc3VyZSBpZCBoYXMgdHJhaWxpbmcgc2xhc2hcblx0XHRcdFx0JGNvbnRhaW5lckZvckVsZW1lbnQuaHRtbCgnPGlmcmFtZSB3aWR0aD1cIicgKyB3aWR0aCArICdcIiBoZWlnaHQ9XCInICsgaGVpZ2h0ICsgJ1wiIHNyYz1cIicgKyBpZCArICdlbWJlZC9cIiBmcmFtZWJvcmRlcj1cIjBcIiBhbGxvd2Z1bGxzY3JlZW4+PC9pZnJhbWU+Jyk7XG5cdFx0XHRcdHRoaXMuX3Jlc2l6ZSh3aWR0aCwgaGVpZ2h0KTtcblx0XHRcdFx0dGhpcy5fY29uZmlnLm9uQ29udGVudExvYWRlZC5jYWxsKHRoaXMpO1xuXHRcdFx0XHRpZiAodGhpcy5fJG1vZGFsQXJyb3dzKSAvL2hpZGUgdGhlIGFycm93cyB3aGVuIHNob3dpbmcgdmlkZW9cblx0XHRcdFx0XHR0aGlzLl8kbW9kYWxBcnJvd3MuY3NzKCdkaXNwbGF5JywgJ25vbmUnKTtcblx0XHRcdFx0dGhpcy5fdG9nZ2xlTG9hZGluZyhmYWxzZSk7XG5cdFx0XHRcdHJldHVybiB0aGlzO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19zaG93VmlkZW9JZnJhbWUnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9zaG93VmlkZW9JZnJhbWUodXJsLCB3aWR0aCwgaGVpZ2h0LCAkY29udGFpbmVyRm9yRWxlbWVudCkge1xuXHRcdFx0XHQvLyBzaG91bGQgYmUgdXNlZCBmb3IgdmlkZW9zIG9ubHkuIGZvciByZW1vdGUgY29udGVudCB1c2UgbG9hZFJlbW90ZUNvbnRlbnQgKGRhdGEtdHlwZT11cmwpXG5cdFx0XHRcdGhlaWdodCA9IGhlaWdodCB8fCB3aWR0aDsgLy8gZGVmYXVsdCB0byBzcXVhcmVcblx0XHRcdFx0JGNvbnRhaW5lckZvckVsZW1lbnQuaHRtbCgnPGRpdiBjbGFzcz1cImVtYmVkLXJlc3BvbnNpdmUgZW1iZWQtcmVzcG9uc2l2ZS0xNmJ5OVwiPjxpZnJhbWUgd2lkdGg9XCInICsgd2lkdGggKyAnXCIgaGVpZ2h0PVwiJyArIGhlaWdodCArICdcIiBzcmM9XCInICsgdXJsICsgJ1wiIGZyYW1lYm9yZGVyPVwiMFwiIGFsbG93ZnVsbHNjcmVlbiBjbGFzcz1cImVtYmVkLXJlc3BvbnNpdmUtaXRlbVwiPjwvaWZyYW1lPjwvZGl2PicpO1xuXHRcdFx0XHR0aGlzLl9yZXNpemUod2lkdGgsIGhlaWdodCk7XG5cdFx0XHRcdHRoaXMuX2NvbmZpZy5vbkNvbnRlbnRMb2FkZWQuY2FsbCh0aGlzKTtcblx0XHRcdFx0aWYgKHRoaXMuXyRtb2RhbEFycm93cykgdGhpcy5fJG1vZGFsQXJyb3dzLmNzcygnZGlzcGxheScsICdub25lJyk7IC8vaGlkZSB0aGUgYXJyb3dzIHdoZW4gc2hvd2luZyB2aWRlb1xuXHRcdFx0XHR0aGlzLl90b2dnbGVMb2FkaW5nKGZhbHNlKTtcblx0XHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3Nob3dIdG1sNVZpZGVvJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfc2hvd0h0bWw1VmlkZW8odXJsLCAkY29udGFpbmVyRm9yRWxlbWVudCkge1xuXHRcdFx0XHQvLyBzaG91bGQgYmUgdXNlZCBmb3IgdmlkZW9zIG9ubHkuIGZvciByZW1vdGUgY29udGVudCB1c2UgbG9hZFJlbW90ZUNvbnRlbnQgKGRhdGEtdHlwZT11cmwpXG5cdFx0XHRcdHZhciB3aWR0aCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ3dpZHRoJykgfHwgNTYwO1xuXHRcdFx0XHR2YXIgaGVpZ2h0ID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnaGVpZ2h0JykgfHwgd2lkdGggLyAoNTYwIC8gMzE1KTtcblx0XHRcdFx0JGNvbnRhaW5lckZvckVsZW1lbnQuaHRtbCgnPGRpdiBjbGFzcz1cImVtYmVkLXJlc3BvbnNpdmUgZW1iZWQtcmVzcG9uc2l2ZS0xNmJ5OVwiPjx2aWRlbyB3aWR0aD1cIicgKyB3aWR0aCArICdcIiBoZWlnaHQ9XCInICsgaGVpZ2h0ICsgJ1wiIHNyYz1cIicgKyB1cmwgKyAnXCIgcHJlbG9hZD1cImF1dG9cIiBhdXRvcGxheSBjb250cm9scyBjbGFzcz1cImVtYmVkLXJlc3BvbnNpdmUtaXRlbVwiPjwvdmlkZW8+PC9kaXY+Jyk7XG5cdFx0XHRcdHRoaXMuX3Jlc2l6ZSh3aWR0aCwgaGVpZ2h0KTtcblx0XHRcdFx0dGhpcy5fY29uZmlnLm9uQ29udGVudExvYWRlZC5jYWxsKHRoaXMpO1xuXHRcdFx0XHRpZiAodGhpcy5fJG1vZGFsQXJyb3dzKSB0aGlzLl8kbW9kYWxBcnJvd3MuY3NzKCdkaXNwbGF5JywgJ25vbmUnKTsgLy9oaWRlIHRoZSBhcnJvd3Mgd2hlbiBzaG93aW5nIHZpZGVvXG5cdFx0XHRcdHRoaXMuX3RvZ2dsZUxvYWRpbmcoZmFsc2UpO1xuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfbG9hZFJlbW90ZUNvbnRlbnQnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9sb2FkUmVtb3RlQ29udGVudCh1cmwsICRjb250YWluZXJGb3JFbGVtZW50KSB7XG5cdFx0XHRcdHZhciBfdGhpczMgPSB0aGlzO1xuXG5cdFx0XHRcdHZhciB3aWR0aCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ3dpZHRoJykgfHwgNTYwO1xuXHRcdFx0XHR2YXIgaGVpZ2h0ID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnaGVpZ2h0JykgfHwgNTYwO1xuXG5cdFx0XHRcdHZhciBkaXNhYmxlRXh0ZXJuYWxDaGVjayA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ2Rpc2FibGVFeHRlcm5hbENoZWNrJykgfHwgZmFsc2U7XG5cdFx0XHRcdHRoaXMuX3RvZ2dsZUxvYWRpbmcoZmFsc2UpO1xuXG5cdFx0XHRcdC8vIGV4dGVybmFsIHVybHMgYXJlIGxvYWRpbmcgaW50byBhbiBpZnJhbWVcblx0XHRcdFx0Ly8gbG9jYWwgYWpheCBjYW4gYmUgbG9hZGVkIGludG8gdGhlIGNvbnRhaW5lciBpdHNlbGZcblx0XHRcdFx0aWYgKCFkaXNhYmxlRXh0ZXJuYWxDaGVjayAmJiAhdGhpcy5faXNFeHRlcm5hbCh1cmwpKSB7XG5cdFx0XHRcdFx0JGNvbnRhaW5lckZvckVsZW1lbnQubG9hZCh1cmwsICQucHJveHkoZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdFx0cmV0dXJuIF90aGlzMy5fJGVsZW1lbnQudHJpZ2dlcignbG9hZGVkLmJzLm1vZGFsJyk7bDtcblx0XHRcdFx0XHR9KSk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0JGNvbnRhaW5lckZvckVsZW1lbnQuaHRtbCgnPGlmcmFtZSBzcmM9XCInICsgdXJsICsgJ1wiIGZyYW1lYm9yZGVyPVwiMFwiIGFsbG93ZnVsbHNjcmVlbj48L2lmcmFtZT4nKTtcblx0XHRcdFx0XHR0aGlzLl9jb25maWcub25Db250ZW50TG9hZGVkLmNhbGwodGhpcyk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRpZiAodGhpcy5fJG1vZGFsQXJyb3dzKSAvL2hpZGUgdGhlIGFycm93cyB3aGVuIHJlbW90ZSBjb250ZW50XG5cdFx0XHRcdFx0dGhpcy5fJG1vZGFsQXJyb3dzLmNzcygnZGlzcGxheScsICdub25lJyk7XG5cblx0XHRcdFx0dGhpcy5fcmVzaXplKHdpZHRoLCBoZWlnaHQpO1xuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfaXNFeHRlcm5hbCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2lzRXh0ZXJuYWwodXJsKSB7XG5cdFx0XHRcdHZhciBtYXRjaCA9IHVybC5tYXRjaCgvXihbXjpcXC8/I10rOik/KD86XFwvXFwvKFteXFwvPyNdKikpPyhbXj8jXSspPyhcXD9bXiNdKik/KCMuKik/Lyk7XG5cdFx0XHRcdGlmICh0eXBlb2YgbWF0Y2hbMV0gPT09IFwic3RyaW5nXCIgJiYgbWF0Y2hbMV0ubGVuZ3RoID4gMCAmJiBtYXRjaFsxXS50b0xvd2VyQ2FzZSgpICE9PSBsb2NhdGlvbi5wcm90b2NvbCkgcmV0dXJuIHRydWU7XG5cblx0XHRcdFx0aWYgKHR5cGVvZiBtYXRjaFsyXSA9PT0gXCJzdHJpbmdcIiAmJiBtYXRjaFsyXS5sZW5ndGggPiAwICYmIG1hdGNoWzJdLnJlcGxhY2UobmV3IFJlZ0V4cCgnOignICsgKHtcblx0XHRcdFx0XHRcImh0dHA6XCI6IDgwLFxuXHRcdFx0XHRcdFwiaHR0cHM6XCI6IDQ0M1xuXHRcdFx0XHR9KVtsb2NhdGlvbi5wcm90b2NvbF0gKyAnKT8kJyksIFwiXCIpICE9PSBsb2NhdGlvbi5ob3N0KSByZXR1cm4gdHJ1ZTtcblxuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2Vycm9yJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfZXJyb3IobWVzc2FnZSkge1xuXHRcdFx0XHRjb25zb2xlLmVycm9yKG1lc3NhZ2UpO1xuXHRcdFx0XHR0aGlzLl9jb250YWluZXJUb1VzZSgpLmh0bWwobWVzc2FnZSk7XG5cdFx0XHRcdHRoaXMuX3Jlc2l6ZSgzMDAsIDMwMCk7XG5cdFx0XHRcdHJldHVybiB0aGlzO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19wcmVsb2FkSW1hZ2VCeUluZGV4Jyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfcHJlbG9hZEltYWdlQnlJbmRleChzdGFydEluZGV4LCBudW1iZXJPZlRpbWVzKSB7XG5cblx0XHRcdFx0aWYgKCF0aGlzLl8kZ2FsbGVyeUl0ZW1zKSByZXR1cm47XG5cblx0XHRcdFx0dmFyIG5leHQgPSAkKHRoaXMuXyRnYWxsZXJ5SXRlbXMuZ2V0KHN0YXJ0SW5kZXgpLCBmYWxzZSk7XG5cdFx0XHRcdGlmICh0eXBlb2YgbmV4dCA9PSAndW5kZWZpbmVkJykgcmV0dXJuO1xuXG5cdFx0XHRcdHZhciBzcmMgPSBuZXh0LmF0dHIoJ2RhdGEtcmVtb3RlJykgfHwgbmV4dC5hdHRyKCdocmVmJyk7XG5cdFx0XHRcdGlmIChuZXh0LmF0dHIoJ2RhdGEtdHlwZScpID09PSAnaW1hZ2UnIHx8IHRoaXMuX2lzSW1hZ2Uoc3JjKSkgdGhpcy5fcHJlbG9hZEltYWdlKHNyYywgZmFsc2UpO1xuXG5cdFx0XHRcdGlmIChudW1iZXJPZlRpbWVzID4gMCkgcmV0dXJuIHRoaXMuX3ByZWxvYWRJbWFnZUJ5SW5kZXgoc3RhcnRJbmRleCArIDEsIG51bWJlck9mVGltZXMgLSAxKTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfcHJlbG9hZEltYWdlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfcHJlbG9hZEltYWdlKHNyYywgJGNvbnRhaW5lckZvckltYWdlKSB7XG5cdFx0XHRcdHZhciBfdGhpczQgPSB0aGlzO1xuXG5cdFx0XHRcdCRjb250YWluZXJGb3JJbWFnZSA9ICRjb250YWluZXJGb3JJbWFnZSB8fCBmYWxzZTtcblxuXHRcdFx0XHR2YXIgaW1nID0gbmV3IEltYWdlKCk7XG5cdFx0XHRcdGlmICgkY29udGFpbmVyRm9ySW1hZ2UpIHtcblx0XHRcdFx0XHQoZnVuY3Rpb24gKCkge1xuXG5cdFx0XHRcdFx0XHQvLyBpZiBsb2FkaW5nIHRha2VzID4gMjAwbXMgc2hvdyBhIGxvYWRlclxuXHRcdFx0XHRcdFx0dmFyIGxvYWRpbmdUaW1lb3V0ID0gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRcdCRjb250YWluZXJGb3JJbWFnZS5hcHBlbmQoX3RoaXM0Ll9jb25maWcubG9hZGluZ01lc3NhZ2UpO1xuXHRcdFx0XHRcdFx0fSwgMjAwKTtcblxuXHRcdFx0XHRcdFx0aW1nLm9ubG9hZCA9IGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdFx0aWYgKGxvYWRpbmdUaW1lb3V0KSBjbGVhclRpbWVvdXQobG9hZGluZ1RpbWVvdXQpO1xuXHRcdFx0XHRcdFx0XHRsb2FkaW5nVGltZW91dCA9IG51bGw7XG5cdFx0XHRcdFx0XHRcdHZhciBpbWFnZSA9ICQoJzxpbWcgLz4nKTtcblx0XHRcdFx0XHRcdFx0aW1hZ2UuYXR0cignc3JjJywgaW1nLnNyYyk7XG5cdFx0XHRcdFx0XHRcdGltYWdlLmFkZENsYXNzKCdpbWctZmx1aWQnKTtcblxuXHRcdFx0XHRcdFx0XHQvLyBiYWNrd2FyZCBjb21wYXRpYmlsaXR5IGZvciBib290c3RyYXAgdjNcblx0XHRcdFx0XHRcdFx0aW1hZ2UuY3NzKCd3aWR0aCcsICcxMDAlJyk7XG5cblx0XHRcdFx0XHRcdFx0JGNvbnRhaW5lckZvckltYWdlLmh0bWwoaW1hZ2UpO1xuXHRcdFx0XHRcdFx0XHRpZiAoX3RoaXM0Ll8kbW9kYWxBcnJvd3MpIF90aGlzNC5fJG1vZGFsQXJyb3dzLmNzcygnZGlzcGxheScsICcnKTsgLy8gcmVtb3ZlIGRpc3BsYXkgdG8gZGVmYXVsdCB0byBjc3MgcHJvcGVydHlcblxuXHRcdFx0XHRcdFx0XHRfdGhpczQuX3Jlc2l6ZShpbWcud2lkdGgsIGltZy5oZWlnaHQpO1xuXHRcdFx0XHRcdFx0XHRfdGhpczQuX3RvZ2dsZUxvYWRpbmcoZmFsc2UpO1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gX3RoaXM0Ll9jb25maWcub25Db250ZW50TG9hZGVkLmNhbGwoX3RoaXM0KTtcblx0XHRcdFx0XHRcdH07XG5cdFx0XHRcdFx0XHRpbWcub25lcnJvciA9IGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdFx0X3RoaXM0Ll90b2dnbGVMb2FkaW5nKGZhbHNlKTtcblx0XHRcdFx0XHRcdFx0cmV0dXJuIF90aGlzNC5fZXJyb3IoX3RoaXM0Ll9jb25maWcuc3RyaW5ncy5mYWlsICsgKCcgICcgKyBzcmMpKTtcblx0XHRcdFx0XHRcdH07XG5cdFx0XHRcdFx0fSkoKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGltZy5zcmMgPSBzcmM7XG5cdFx0XHRcdHJldHVybiBpbWc7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3N3aXBlR2VzdXJlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfc3dpcGVHZXN1cmUoKSB7XG5cdFx0XHRcdGlmICh0aGlzLl90b3VjaGVuZFggPCB0aGlzLl90b3VjaHN0YXJ0WCkge1xuXHRcdFx0XHRcdHJldHVybiB0aGlzLm5hdmlnYXRlUmlnaHQoKTtcblx0XHRcdFx0fVxuXHRcdFx0XHRpZiAodGhpcy5fdG91Y2hlbmRYID4gdGhpcy5fdG91Y2hzdGFydFgpIHtcblx0XHRcdFx0XHRyZXR1cm4gdGhpcy5uYXZpZ2F0ZUxlZnQoKTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19yZXNpemUnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9yZXNpemUod2lkdGgsIGhlaWdodCkge1xuXG5cdFx0XHRcdGhlaWdodCA9IGhlaWdodCB8fCB3aWR0aDtcblx0XHRcdFx0dGhpcy5fd2FudGVkV2lkdGggPSB3aWR0aDtcblx0XHRcdFx0dGhpcy5fd2FudGVkSGVpZ2h0ID0gaGVpZ2h0O1xuXG5cdFx0XHRcdHZhciBpbWFnZUFzcGVjUmF0aW8gPSB3aWR0aCAvIGhlaWdodDtcblxuXHRcdFx0XHQvLyBpZiB3aWR0aCA+IHRoZSBhdmFpbGFibGUgc3BhY2UsIHNjYWxlIGRvd24gdGhlIGV4cGVjdGVkIHdpZHRoIGFuZCBoZWlnaHRcblx0XHRcdFx0dmFyIHdpZHRoQm9yZGVyQW5kUGFkZGluZyA9IHRoaXMuX3BhZGRpbmcubGVmdCArIHRoaXMuX3BhZGRpbmcucmlnaHQgKyB0aGlzLl9ib3JkZXIubGVmdCArIHRoaXMuX2JvcmRlci5yaWdodDtcblxuXHRcdFx0XHQvLyBmb3JjZSAxMHB4IG1hcmdpbiBpZiB3aW5kb3cgc2l6ZSA+IDU3NXB4XG5cdFx0XHRcdHZhciBhZGRNYXJnaW4gPSB0aGlzLl9jb25maWcuZG9jLmJvZHkuY2xpZW50V2lkdGggPiA1NzUgPyAyMCA6IDA7XG5cdFx0XHRcdHZhciBkaXNjb3VudE1hcmdpbiA9IHRoaXMuX2NvbmZpZy5kb2MuYm9keS5jbGllbnRXaWR0aCA+IDU3NSA/IDAgOiAyMDtcblxuXHRcdFx0XHR2YXIgbWF4V2lkdGggPSBNYXRoLm1pbih3aWR0aCArIHdpZHRoQm9yZGVyQW5kUGFkZGluZywgdGhpcy5fY29uZmlnLmRvYy5ib2R5LmNsaWVudFdpZHRoIC0gYWRkTWFyZ2luLCB0aGlzLl9jb25maWcubWF4V2lkdGgpO1xuXG5cdFx0XHRcdGlmICh3aWR0aCArIHdpZHRoQm9yZGVyQW5kUGFkZGluZyA+IG1heFdpZHRoKSB7XG5cdFx0XHRcdFx0aGVpZ2h0ID0gKG1heFdpZHRoIC0gd2lkdGhCb3JkZXJBbmRQYWRkaW5nIC0gZGlzY291bnRNYXJnaW4pIC8gaW1hZ2VBc3BlY1JhdGlvO1xuXHRcdFx0XHRcdHdpZHRoID0gbWF4V2lkdGg7XG5cdFx0XHRcdH0gZWxzZSB3aWR0aCA9IHdpZHRoICsgd2lkdGhCb3JkZXJBbmRQYWRkaW5nO1xuXG5cdFx0XHRcdHZhciBoZWFkZXJIZWlnaHQgPSAwLFxuXHRcdFx0XHQgICAgZm9vdGVySGVpZ2h0ID0gMDtcblxuXHRcdFx0XHQvLyBhcyB0aGUgcmVzaXplIGlzIHBlcmZvcm1lZCB0aGUgbW9kYWwgaXMgc2hvdywgdGhlIGNhbGN1bGF0ZSBtaWdodCBmYWlsXG5cdFx0XHRcdC8vIGlmIHNvLCBkZWZhdWx0IHRvIHRoZSBkZWZhdWx0IHNpemVzXG5cdFx0XHRcdGlmICh0aGlzLl9mb290ZXJJc1Nob3duKSBmb290ZXJIZWlnaHQgPSB0aGlzLl8kbW9kYWxGb290ZXIub3V0ZXJIZWlnaHQodHJ1ZSkgfHwgNTU7XG5cblx0XHRcdFx0aWYgKHRoaXMuX3RpdGxlSXNTaG93bikgaGVhZGVySGVpZ2h0ID0gdGhpcy5fJG1vZGFsSGVhZGVyLm91dGVySGVpZ2h0KHRydWUpIHx8IDY3O1xuXG5cdFx0XHRcdHZhciBib3JkZXJQYWRkaW5nID0gdGhpcy5fcGFkZGluZy50b3AgKyB0aGlzLl9wYWRkaW5nLmJvdHRvbSArIHRoaXMuX2JvcmRlci5ib3R0b20gKyB0aGlzLl9ib3JkZXIudG9wO1xuXG5cdFx0XHRcdC8vY2FsY3VsYXRlZCBlYWNoIHRpbWUgYXMgcmVzaXppbmcgdGhlIHdpbmRvdyBjYW4gY2F1c2UgdGhlbSB0byBjaGFuZ2UgZHVlIHRvIEJvb3RzdHJhcHMgZmx1aWQgbWFyZ2luc1xuXHRcdFx0XHR2YXIgbWFyZ2lucyA9IHBhcnNlRmxvYXQodGhpcy5fJG1vZGFsRGlhbG9nLmNzcygnbWFyZ2luLXRvcCcpKSArIHBhcnNlRmxvYXQodGhpcy5fJG1vZGFsRGlhbG9nLmNzcygnbWFyZ2luLWJvdHRvbScpKTtcblxuXHRcdFx0XHR2YXIgbWF4SGVpZ2h0ID0gTWF0aC5taW4oaGVpZ2h0LCAkKHdpbmRvdykuaGVpZ2h0KCkgLSBib3JkZXJQYWRkaW5nIC0gbWFyZ2lucyAtIGhlYWRlckhlaWdodCAtIGZvb3RlckhlaWdodCwgdGhpcy5fY29uZmlnLm1heEhlaWdodCAtIGJvcmRlclBhZGRpbmcgLSBoZWFkZXJIZWlnaHQgLSBmb290ZXJIZWlnaHQpO1xuXG5cdFx0XHRcdGlmIChoZWlnaHQgPiBtYXhIZWlnaHQpIHtcblx0XHRcdFx0XHQvLyBpZiBoZWlnaHQgPiB0aGUgYXZhaWxhYmxlIGhlaWdodCwgc2NhbGUgZG93biB0aGUgd2lkdGhcblx0XHRcdFx0XHR3aWR0aCA9IE1hdGguY2VpbChtYXhIZWlnaHQgKiBpbWFnZUFzcGVjUmF0aW8pICsgd2lkdGhCb3JkZXJBbmRQYWRkaW5nO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0dGhpcy5fJGxpZ2h0Ym94Q29udGFpbmVyLmNzcygnaGVpZ2h0JywgbWF4SGVpZ2h0KTtcblx0XHRcdFx0dGhpcy5fJG1vZGFsRGlhbG9nLmNzcygnZmxleCcsIDEpLmNzcygnbWF4V2lkdGgnLCB3aWR0aCk7XG5cblx0XHRcdFx0dmFyIG1vZGFsID0gdGhpcy5fJG1vZGFsLmRhdGEoJ2JzLm1vZGFsJyk7XG5cdFx0XHRcdGlmIChtb2RhbCkge1xuXHRcdFx0XHRcdC8vIHY0IG1ldGhvZCBpcyBtaXN0YWtlbmx5IHByb3RlY3RlZFxuXHRcdFx0XHRcdHRyeSB7XG5cdFx0XHRcdFx0XHRtb2RhbC5faGFuZGxlVXBkYXRlKCk7XG5cdFx0XHRcdFx0fSBjYXRjaCAoRXhjZXB0aW9uKSB7XG5cdFx0XHRcdFx0XHRtb2RhbC5oYW5kbGVVcGRhdGUoKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0XHR9XG5cdFx0fV0sIFt7XG5cdFx0XHRrZXk6ICdfalF1ZXJ5SW50ZXJmYWNlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfalF1ZXJ5SW50ZXJmYWNlKGNvbmZpZykge1xuXHRcdFx0XHR2YXIgX3RoaXM1ID0gdGhpcztcblxuXHRcdFx0XHRjb25maWcgPSBjb25maWcgfHwge307XG5cdFx0XHRcdHJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdHZhciAkdGhpcyA9ICQoX3RoaXM1KTtcblx0XHRcdFx0XHR2YXIgX2NvbmZpZyA9ICQuZXh0ZW5kKHt9LCBMaWdodGJveC5EZWZhdWx0LCAkdGhpcy5kYXRhKCksIHR5cGVvZiBjb25maWcgPT09ICdvYmplY3QnICYmIGNvbmZpZyk7XG5cblx0XHRcdFx0XHRuZXcgTGlnaHRib3goX3RoaXM1LCBfY29uZmlnKTtcblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0fV0pO1xuXG5cdFx0cmV0dXJuIExpZ2h0Ym94O1xuXHR9KSgpO1xuXG5cdCQuZm5bTkFNRV0gPSBMaWdodGJveC5falF1ZXJ5SW50ZXJmYWNlO1xuXHQkLmZuW05BTUVdLkNvbnN0cnVjdG9yID0gTGlnaHRib3g7XG5cdCQuZm5bTkFNRV0ubm9Db25mbGljdCA9IGZ1bmN0aW9uICgpIHtcblx0XHQkLmZuW05BTUVdID0gSlFVRVJZX05PX0NPTkZMSUNUO1xuXHRcdHJldHVybiBMaWdodGJveC5falF1ZXJ5SW50ZXJmYWNlO1xuXHR9O1xuXG5cdHJldHVybiBMaWdodGJveDtcbn0pKGpRdWVyeSk7XG4vLyMgc291cmNlTWFwcGluZ1VSTD1la2tvLWxpZ2h0Ym94LmpzLm1hcFxuXG59KGpRdWVyeSk7XG4iXSwic291cmNlUm9vdCI6IiJ9