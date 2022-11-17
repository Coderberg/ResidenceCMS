(self["webpackChunk"] = self["webpackChunk"] || []).push([["js/ekko-lightbox"],{

/***/ "./assets/js/ekko-lightbox.js":
/*!************************************!*\
  !*** ./assets/js/ekko-lightbox.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var ekko_lightbox_dist_ekko_lightbox__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ekko-lightbox/dist/ekko-lightbox */ "./node_modules/ekko-lightbox/dist/ekko-lightbox.js");
/* harmony import */ var ekko_lightbox_dist_ekko_lightbox__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(ekko_lightbox_dist_ekko_lightbox__WEBPACK_IMPORTED_MODULE_0__);


/***/ }),

/***/ "./node_modules/ekko-lightbox/dist/ekko-lightbox.js":
/*!**********************************************************!*\
  !*** ./node_modules/ekko-lightbox/dist/ekko-lightbox.js ***!
  \**********************************************************/
/***/ (() => {

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

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/js/ekko-lightbox.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoianMvZWtrby1saWdodGJveC5qcyIsIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FDQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUEsa0NBQWtDLDJDQUEyQyxnQkFBZ0Isa0JBQWtCLE9BQU8sMkJBQTJCLHdEQUF3RCxnQ0FBZ0MsdURBQXVELCtEQUErRCx5REFBeUQscUVBQXFFLDZEQUE2RCx3QkFBd0I7O0FBRWxqQixrREFBa0QsMENBQTBDOztBQUU1Rjs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsbUdBQW1HO0FBQ25HO0FBQ0E7QUFDQSw0QkFBNEI7QUFDNUIsNkJBQTZCO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0EsOEJBQThCO0FBQzlCLGdDQUFnQztBQUNoQyw4QkFBOEI7QUFDOUIsa0NBQWtDO0FBQ2xDLHNDQUFzQztBQUN0QztBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBLGdFQUFnRTtBQUNoRTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxHQUFHOztBQUVIO0FBQ0E7O0FBRUE7O0FBRUEsNkJBQTZCO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7QUFFQSx1RUFBdUU7QUFDdkUsdUpBQXVKOztBQUV2SjtBQUNBLDBIQUEwSDtBQUMxSDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxNQUFNO0FBQ047QUFDQTtBQUNBO0FBQ0EsTUFBTTtBQUNOO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxJQUFJOztBQUVKO0FBQ0E7QUFDQSxJQUFJO0FBQ0o7QUFDQTtBQUNBLElBQUk7QUFDSjtBQUNBO0FBQ0EsSUFBSTtBQUNKOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBLG9GQUFvRjtBQUNwRixNQUFNO0FBQ047O0FBRUE7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQSx1REFBdUQ7QUFDdkQsTUFBTTtBQUNOOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBLG1GQUFtRjs7QUFFbkYsK0dBQStHO0FBQy9HO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxNQUFNO0FBQ047QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EscUZBQXFGO0FBQ3JGLE1BQU07O0FBRU47QUFDQTtBQUNBO0FBQ0E7QUFDQSxNQUFNOztBQUVOO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGdEQUFnRDtBQUNoRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBLDhCQUE4QjtBQUM5QjtBQUNBO0FBQ0E7QUFDQSx1RUFBdUU7QUFDdkU7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx1RUFBdUU7QUFDdkU7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5REFBeUQ7QUFDekQsTUFBTTtBQUNOLE1BQU07QUFDTjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxPQUFPOztBQUVQO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EsMEVBQTBFOztBQUUxRTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsTUFBTTtBQUNOOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEdBQUc7QUFDSDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxHQUFHO0FBQ0g7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0EsTUFBTTs7QUFFTjtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLE9BQU87QUFDUDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsR0FBRztBQUNIO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSw4QkFBOEI7O0FBRTlCO0FBQ0EsS0FBSztBQUNMO0FBQ0EsR0FBRzs7QUFFSDtBQUNBLEVBQUU7O0FBRUY7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0EsQ0FBQztBQUNEOztBQUVBLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvZWtrby1saWdodGJveC5qcyIsIndlYnBhY2s6Ly8vLi9ub2RlX21vZHVsZXMvZWtrby1saWdodGJveC9kaXN0L2Vra28tbGlnaHRib3guanMiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0ICdla2tvLWxpZ2h0Ym94L2Rpc3QvZWtrby1saWdodGJveCc7XG4iLCIvKiFcbiAqIExpZ2h0Ym94IGZvciBCb290c3RyYXAgYnkgQGFzaGxleWR3XG4gKiBodHRwczovL2dpdGh1Yi5jb20vYXNobGV5ZHcvbGlnaHRib3hcbiAqXG4gKiBMaWNlbnNlOiBodHRwczovL2dpdGh1Yi5jb20vYXNobGV5ZHcvbGlnaHRib3gvYmxvYi9tYXN0ZXIvTElDRU5TRVxuICovXG4rZnVuY3Rpb24gKCQpIHtcblxuJ3VzZSBzdHJpY3QnO1xuXG52YXIgX2NyZWF0ZUNsYXNzID0gKGZ1bmN0aW9uICgpIHsgZnVuY3Rpb24gZGVmaW5lUHJvcGVydGllcyh0YXJnZXQsIHByb3BzKSB7IGZvciAodmFyIGkgPSAwOyBpIDwgcHJvcHMubGVuZ3RoOyBpKyspIHsgdmFyIGRlc2NyaXB0b3IgPSBwcm9wc1tpXTsgZGVzY3JpcHRvci5lbnVtZXJhYmxlID0gZGVzY3JpcHRvci5lbnVtZXJhYmxlIHx8IGZhbHNlOyBkZXNjcmlwdG9yLmNvbmZpZ3VyYWJsZSA9IHRydWU7IGlmICgndmFsdWUnIGluIGRlc2NyaXB0b3IpIGRlc2NyaXB0b3Iud3JpdGFibGUgPSB0cnVlOyBPYmplY3QuZGVmaW5lUHJvcGVydHkodGFyZ2V0LCBkZXNjcmlwdG9yLmtleSwgZGVzY3JpcHRvcik7IH0gfSByZXR1cm4gZnVuY3Rpb24gKENvbnN0cnVjdG9yLCBwcm90b1Byb3BzLCBzdGF0aWNQcm9wcykgeyBpZiAocHJvdG9Qcm9wcykgZGVmaW5lUHJvcGVydGllcyhDb25zdHJ1Y3Rvci5wcm90b3R5cGUsIHByb3RvUHJvcHMpOyBpZiAoc3RhdGljUHJvcHMpIGRlZmluZVByb3BlcnRpZXMoQ29uc3RydWN0b3IsIHN0YXRpY1Byb3BzKTsgcmV0dXJuIENvbnN0cnVjdG9yOyB9OyB9KSgpO1xuXG5mdW5jdGlvbiBfY2xhc3NDYWxsQ2hlY2soaW5zdGFuY2UsIENvbnN0cnVjdG9yKSB7IGlmICghKGluc3RhbmNlIGluc3RhbmNlb2YgQ29uc3RydWN0b3IpKSB7IHRocm93IG5ldyBUeXBlRXJyb3IoJ0Nhbm5vdCBjYWxsIGEgY2xhc3MgYXMgYSBmdW5jdGlvbicpOyB9IH1cblxudmFyIExpZ2h0Ym94ID0gKGZ1bmN0aW9uICgkKSB7XG5cblx0dmFyIE5BTUUgPSAnZWtrb0xpZ2h0Ym94Jztcblx0dmFyIEpRVUVSWV9OT19DT05GTElDVCA9ICQuZm5bTkFNRV07XG5cblx0dmFyIERlZmF1bHQgPSB7XG5cdFx0dGl0bGU6ICcnLFxuXHRcdGZvb3RlcjogJycsXG5cdFx0bWF4V2lkdGg6IDk5OTksXG5cdFx0bWF4SGVpZ2h0OiA5OTk5LFxuXHRcdHNob3dBcnJvd3M6IHRydWUsIC8vZGlzcGxheSB0aGUgbGVmdCAvIHJpZ2h0IGFycm93cyBvciBub3Rcblx0XHR3cmFwcGluZzogdHJ1ZSwgLy9pZiB0cnVlLCBnYWxsZXJ5IGxvb3BzIGluZmluaXRlbHlcblx0XHR0eXBlOiBudWxsLCAvL2ZvcmNlIHRoZSBsaWdodGJveCBpbnRvIGltYWdlIC8geW91dHViZSBtb2RlLiBpZiBudWxsLCBvciBub3QgaW1hZ2V8eW91dHViZXx2aW1lbzsgZGV0ZWN0IGl0XG5cdFx0YWx3YXlzU2hvd0Nsb3NlOiBmYWxzZSwgLy9hbHdheXMgc2hvdyB0aGUgY2xvc2UgYnV0dG9uLCBldmVuIGlmIHRoZXJlIGlzIG5vIHRpdGxlXG5cdFx0bG9hZGluZ01lc3NhZ2U6ICc8ZGl2IGNsYXNzPVwiZWtrby1saWdodGJveC1sb2FkZXJcIj48ZGl2PjxkaXY+PC9kaXY+PGRpdj48L2Rpdj48L2Rpdj48L2Rpdj4nLCAvLyBodHRwOi8vdG9iaWFzYWhsaW4uY29tL3NwaW5raXQvXG5cdFx0bGVmdEFycm93OiAnPHNwYW4+JiMxMDA5NDs8L3NwYW4+Jyxcblx0XHRyaWdodEFycm93OiAnPHNwYW4+JiMxMDA5NTs8L3NwYW4+Jyxcblx0XHRzdHJpbmdzOiB7XG5cdFx0XHRjbG9zZTogJ0Nsb3NlJyxcblx0XHRcdGZhaWw6ICdGYWlsZWQgdG8gbG9hZCBpbWFnZTonLFxuXHRcdFx0dHlwZTogJ0NvdWxkIG5vdCBkZXRlY3QgcmVtb3RlIHRhcmdldCB0eXBlLiBGb3JjZSB0aGUgdHlwZSB1c2luZyBkYXRhLXR5cGUnXG5cdFx0fSxcblx0XHRkb2M6IGRvY3VtZW50LCAvLyBpZiBpbiBhbiBpZnJhbWUgY2FuIHNwZWNpZnkgdG9wLmRvY3VtZW50XG5cdFx0b25TaG93OiBmdW5jdGlvbiBvblNob3coKSB7fSxcblx0XHRvblNob3duOiBmdW5jdGlvbiBvblNob3duKCkge30sXG5cdFx0b25IaWRlOiBmdW5jdGlvbiBvbkhpZGUoKSB7fSxcblx0XHRvbkhpZGRlbjogZnVuY3Rpb24gb25IaWRkZW4oKSB7fSxcblx0XHRvbk5hdmlnYXRlOiBmdW5jdGlvbiBvbk5hdmlnYXRlKCkge30sXG5cdFx0b25Db250ZW50TG9hZGVkOiBmdW5jdGlvbiBvbkNvbnRlbnRMb2FkZWQoKSB7fVxuXHR9O1xuXG5cdHZhciBMaWdodGJveCA9IChmdW5jdGlvbiAoKSB7XG5cdFx0X2NyZWF0ZUNsYXNzKExpZ2h0Ym94LCBudWxsLCBbe1xuXHRcdFx0a2V5OiAnRGVmYXVsdCcsXG5cblx0XHRcdC8qKlxuICAgICAgIENsYXNzIHByb3BlcnRpZXM6XG4gICBcdCBfJGVsZW1lbnQ6IG51bGwgLT4gdGhlIDxhPiBlbGVtZW50IGN1cnJlbnRseSBiZWluZyBkaXNwbGF5ZWRcbiAgICBfJG1vZGFsOiBUaGUgYm9vdHN0cmFwIG1vZGFsIGdlbmVyYXRlZFxuICAgICAgIF8kbW9kYWxEaWFsb2c6IFRoZSAubW9kYWwtZGlhbG9nXG4gICAgICAgXyRtb2RhbENvbnRlbnQ6IFRoZSAubW9kYWwtY29udGVudFxuICAgICAgIF8kbW9kYWxCb2R5OiBUaGUgLm1vZGFsLWJvZHlcbiAgICAgICBfJG1vZGFsSGVhZGVyOiBUaGUgLm1vZGFsLWhlYWRlclxuICAgICAgIF8kbW9kYWxGb290ZXI6IFRoZSAubW9kYWwtZm9vdGVyXG4gICAgXyRsaWdodGJveENvbnRhaW5lck9uZTogQ29udGFpbmVyIG9mIHRoZSBmaXJzdCBsaWdodGJveCBlbGVtZW50XG4gICAgXyRsaWdodGJveENvbnRhaW5lclR3bzogQ29udGFpbmVyIG9mIHRoZSBzZWNvbmQgbGlnaHRib3ggZWxlbWVudFxuICAgIF8kbGlnaHRib3hCb2R5OiBGaXJzdCBlbGVtZW50IGluIHRoZSBjb250YWluZXJcbiAgICBfJG1vZGFsQXJyb3dzOiBUaGUgb3ZlcmxheWVkIGFycm93cyBjb250YWluZXJcbiAgIFx0IF8kZ2FsbGVyeUl0ZW1zOiBPdGhlciA8YT4ncyBhdmFpbGFibGUgZm9yIHRoaXMgZ2FsbGVyeVxuICAgIF9nYWxsZXJ5TmFtZTogTmFtZSBvZiB0aGUgY3VycmVudCBkYXRhKCdnYWxsZXJ5Jykgc2hvd2luZ1xuICAgIF9nYWxsZXJ5SW5kZXg6IFRoZSBjdXJyZW50IGluZGV4IG9mIHRoZSBfJGdhbGxlcnlJdGVtcyBiZWluZyBzaG93blxuICAgXHQgX2NvbmZpZzoge30gdGhlIG9wdGlvbnMgZm9yIHRoZSBtb2RhbFxuICAgIF9tb2RhbElkOiB1bmlxdWUgaWQgZm9yIHRoZSBjdXJyZW50IGxpZ2h0Ym94XG4gICAgX3BhZGRpbmcgLyBfYm9yZGVyOiBDU1MgcHJvcGVydGllcyBmb3IgdGhlIG1vZGFsIGNvbnRhaW5lcjsgdGhlc2UgYXJlIHVzZWQgdG8gY2FsY3VsYXRlIHRoZSBhdmFpbGFibGUgc3BhY2UgZm9yIHRoZSBjb250ZW50XG4gICBcdCAqL1xuXG5cdFx0XHRnZXQ6IGZ1bmN0aW9uIGdldCgpIHtcblx0XHRcdFx0cmV0dXJuIERlZmF1bHQ7XG5cdFx0XHR9XG5cdFx0fV0pO1xuXG5cdFx0ZnVuY3Rpb24gTGlnaHRib3goJGVsZW1lbnQsIGNvbmZpZykge1xuXHRcdFx0dmFyIF90aGlzID0gdGhpcztcblxuXHRcdFx0X2NsYXNzQ2FsbENoZWNrKHRoaXMsIExpZ2h0Ym94KTtcblxuXHRcdFx0dGhpcy5fY29uZmlnID0gJC5leHRlbmQoe30sIERlZmF1bHQsIGNvbmZpZyk7XG5cdFx0XHR0aGlzLl8kbW9kYWxBcnJvd3MgPSBudWxsO1xuXHRcdFx0dGhpcy5fZ2FsbGVyeUluZGV4ID0gMDtcblx0XHRcdHRoaXMuX2dhbGxlcnlOYW1lID0gbnVsbDtcblx0XHRcdHRoaXMuX3BhZGRpbmcgPSBudWxsO1xuXHRcdFx0dGhpcy5fYm9yZGVyID0gbnVsbDtcblx0XHRcdHRoaXMuX3RpdGxlSXNTaG93biA9IGZhbHNlO1xuXHRcdFx0dGhpcy5fZm9vdGVySXNTaG93biA9IGZhbHNlO1xuXHRcdFx0dGhpcy5fd2FudGVkV2lkdGggPSAwO1xuXHRcdFx0dGhpcy5fd2FudGVkSGVpZ2h0ID0gMDtcblx0XHRcdHRoaXMuX3RvdWNoc3RhcnRYID0gMDtcblx0XHRcdHRoaXMuX3RvdWNoZW5kWCA9IDA7XG5cblx0XHRcdHRoaXMuX21vZGFsSWQgPSAnZWtrb0xpZ2h0Ym94LScgKyBNYXRoLmZsb29yKE1hdGgucmFuZG9tKCkgKiAxMDAwICsgMSk7XG5cdFx0XHR0aGlzLl8kZWxlbWVudCA9ICRlbGVtZW50IGluc3RhbmNlb2YgalF1ZXJ5ID8gJGVsZW1lbnQgOiAkKCRlbGVtZW50KTtcblxuXHRcdFx0dGhpcy5faXNCb290c3RyYXAzID0gJC5mbi5tb2RhbC5Db25zdHJ1Y3Rvci5WRVJTSU9OWzBdID09IDM7XG5cblx0XHRcdHZhciBoNCA9ICc8aDQgY2xhc3M9XCJtb2RhbC10aXRsZVwiPicgKyAodGhpcy5fY29uZmlnLnRpdGxlIHx8IFwiJm5ic3A7XCIpICsgJzwvaDQ+Jztcblx0XHRcdHZhciBidG4gPSAnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJjbG9zZVwiIGRhdGEtZGlzbWlzcz1cIm1vZGFsXCIgYXJpYS1sYWJlbD1cIicgKyB0aGlzLl9jb25maWcuc3RyaW5ncy5jbG9zZSArICdcIj48c3BhbiBhcmlhLWhpZGRlbj1cInRydWVcIj4mdGltZXM7PC9zcGFuPjwvYnV0dG9uPic7XG5cblx0XHRcdHZhciBoZWFkZXIgPSAnPGRpdiBjbGFzcz1cIm1vZGFsLWhlYWRlcicgKyAodGhpcy5fY29uZmlnLnRpdGxlIHx8IHRoaXMuX2NvbmZpZy5hbHdheXNTaG93Q2xvc2UgPyAnJyA6ICcgaGlkZScpICsgJ1wiPicgKyAodGhpcy5faXNCb290c3RyYXAzID8gYnRuICsgaDQgOiBoNCArIGJ0bikgKyAnPC9kaXY+Jztcblx0XHRcdHZhciBmb290ZXIgPSAnPGRpdiBjbGFzcz1cIm1vZGFsLWZvb3RlcicgKyAodGhpcy5fY29uZmlnLmZvb3RlciA/ICcnIDogJyBoaWRlJykgKyAnXCI+JyArICh0aGlzLl9jb25maWcuZm9vdGVyIHx8IFwiJm5ic3A7XCIpICsgJzwvZGl2Pic7XG5cdFx0XHR2YXIgYm9keSA9ICc8ZGl2IGNsYXNzPVwibW9kYWwtYm9keVwiPjxkaXYgY2xhc3M9XCJla2tvLWxpZ2h0Ym94LWNvbnRhaW5lclwiPjxkaXYgY2xhc3M9XCJla2tvLWxpZ2h0Ym94LWl0ZW0gZmFkZSBpbiBzaG93XCI+PC9kaXY+PGRpdiBjbGFzcz1cImVra28tbGlnaHRib3gtaXRlbSBmYWRlXCI+PC9kaXY+PC9kaXY+PC9kaXY+Jztcblx0XHRcdHZhciBkaWFsb2cgPSAnPGRpdiBjbGFzcz1cIm1vZGFsLWRpYWxvZ1wiIHJvbGU9XCJkb2N1bWVudFwiPjxkaXYgY2xhc3M9XCJtb2RhbC1jb250ZW50XCI+JyArIGhlYWRlciArIGJvZHkgKyBmb290ZXIgKyAnPC9kaXY+PC9kaXY+Jztcblx0XHRcdCQodGhpcy5fY29uZmlnLmRvYy5ib2R5KS5hcHBlbmQoJzxkaXYgaWQ9XCInICsgdGhpcy5fbW9kYWxJZCArICdcIiBjbGFzcz1cImVra28tbGlnaHRib3ggbW9kYWwgZmFkZVwiIHRhYmluZGV4PVwiLTFcIiB0YWJpbmRleD1cIi0xXCIgcm9sZT1cImRpYWxvZ1wiIGFyaWEtaGlkZGVuPVwidHJ1ZVwiPicgKyBkaWFsb2cgKyAnPC9kaXY+Jyk7XG5cblx0XHRcdHRoaXMuXyRtb2RhbCA9ICQoJyMnICsgdGhpcy5fbW9kYWxJZCwgdGhpcy5fY29uZmlnLmRvYyk7XG5cdFx0XHR0aGlzLl8kbW9kYWxEaWFsb2cgPSB0aGlzLl8kbW9kYWwuZmluZCgnLm1vZGFsLWRpYWxvZycpLmZpcnN0KCk7XG5cdFx0XHR0aGlzLl8kbW9kYWxDb250ZW50ID0gdGhpcy5fJG1vZGFsLmZpbmQoJy5tb2RhbC1jb250ZW50JykuZmlyc3QoKTtcblx0XHRcdHRoaXMuXyRtb2RhbEJvZHkgPSB0aGlzLl8kbW9kYWwuZmluZCgnLm1vZGFsLWJvZHknKS5maXJzdCgpO1xuXHRcdFx0dGhpcy5fJG1vZGFsSGVhZGVyID0gdGhpcy5fJG1vZGFsLmZpbmQoJy5tb2RhbC1oZWFkZXInKS5maXJzdCgpO1xuXHRcdFx0dGhpcy5fJG1vZGFsRm9vdGVyID0gdGhpcy5fJG1vZGFsLmZpbmQoJy5tb2RhbC1mb290ZXInKS5maXJzdCgpO1xuXG5cdFx0XHR0aGlzLl8kbGlnaHRib3hDb250YWluZXIgPSB0aGlzLl8kbW9kYWxCb2R5LmZpbmQoJy5la2tvLWxpZ2h0Ym94LWNvbnRhaW5lcicpLmZpcnN0KCk7XG5cdFx0XHR0aGlzLl8kbGlnaHRib3hCb2R5T25lID0gdGhpcy5fJGxpZ2h0Ym94Q29udGFpbmVyLmZpbmQoJz4gZGl2OmZpcnN0LWNoaWxkJykuZmlyc3QoKTtcblx0XHRcdHRoaXMuXyRsaWdodGJveEJvZHlUd28gPSB0aGlzLl8kbGlnaHRib3hDb250YWluZXIuZmluZCgnPiBkaXY6bGFzdC1jaGlsZCcpLmZpcnN0KCk7XG5cblx0XHRcdHRoaXMuX2JvcmRlciA9IHRoaXMuX2NhbGN1bGF0ZUJvcmRlcnMoKTtcblx0XHRcdHRoaXMuX3BhZGRpbmcgPSB0aGlzLl9jYWxjdWxhdGVQYWRkaW5nKCk7XG5cblx0XHRcdHRoaXMuX2dhbGxlcnlOYW1lID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnZ2FsbGVyeScpO1xuXHRcdFx0aWYgKHRoaXMuX2dhbGxlcnlOYW1lKSB7XG5cdFx0XHRcdHRoaXMuXyRnYWxsZXJ5SXRlbXMgPSAkKGRvY3VtZW50LmJvZHkpLmZpbmQoJypbZGF0YS1nYWxsZXJ5PVwiJyArIHRoaXMuX2dhbGxlcnlOYW1lICsgJ1wiXScpO1xuXHRcdFx0XHR0aGlzLl9nYWxsZXJ5SW5kZXggPSB0aGlzLl8kZ2FsbGVyeUl0ZW1zLmluZGV4KHRoaXMuXyRlbGVtZW50KTtcblx0XHRcdFx0JChkb2N1bWVudCkub24oJ2tleWRvd24uZWtrb0xpZ2h0Ym94JywgdGhpcy5fbmF2aWdhdGlvbmFsQmluZGVyLmJpbmQodGhpcykpO1xuXG5cdFx0XHRcdC8vIGFkZCB0aGUgZGlyZWN0aW9uYWwgYXJyb3dzIHRvIHRoZSBtb2RhbFxuXHRcdFx0XHRpZiAodGhpcy5fY29uZmlnLnNob3dBcnJvd3MgJiYgdGhpcy5fJGdhbGxlcnlJdGVtcy5sZW5ndGggPiAxKSB7XG5cdFx0XHRcdFx0dGhpcy5fJGxpZ2h0Ym94Q29udGFpbmVyLmFwcGVuZCgnPGRpdiBjbGFzcz1cImVra28tbGlnaHRib3gtbmF2LW92ZXJsYXlcIj48YSBocmVmPVwiI1wiPicgKyB0aGlzLl9jb25maWcubGVmdEFycm93ICsgJzwvYT48YSBocmVmPVwiI1wiPicgKyB0aGlzLl9jb25maWcucmlnaHRBcnJvdyArICc8L2E+PC9kaXY+Jyk7XG5cdFx0XHRcdFx0dGhpcy5fJG1vZGFsQXJyb3dzID0gdGhpcy5fJGxpZ2h0Ym94Q29udGFpbmVyLmZpbmQoJ2Rpdi5la2tvLWxpZ2h0Ym94LW5hdi1vdmVybGF5JykuZmlyc3QoKTtcblx0XHRcdFx0XHR0aGlzLl8kbGlnaHRib3hDb250YWluZXIub24oJ2NsaWNrJywgJ2E6Zmlyc3QtY2hpbGQnLCBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gX3RoaXMubmF2aWdhdGVMZWZ0KCk7XG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0dGhpcy5fJGxpZ2h0Ym94Q29udGFpbmVyLm9uKCdjbGljaycsICdhOmxhc3QtY2hpbGQnLCBmdW5jdGlvbiAoZXZlbnQpIHtcblx0XHRcdFx0XHRcdGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cdFx0XHRcdFx0XHRyZXR1cm4gX3RoaXMubmF2aWdhdGVSaWdodCgpO1xuXHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdHRoaXMudXBkYXRlTmF2aWdhdGlvbigpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cblx0XHRcdHRoaXMuXyRtb2RhbC5vbignc2hvdy5icy5tb2RhbCcsIHRoaXMuX2NvbmZpZy5vblNob3cuYmluZCh0aGlzKSkub24oJ3Nob3duLmJzLm1vZGFsJywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRfdGhpcy5fdG9nZ2xlTG9hZGluZyh0cnVlKTtcblx0XHRcdFx0X3RoaXMuX2hhbmRsZSgpO1xuXHRcdFx0XHRyZXR1cm4gX3RoaXMuX2NvbmZpZy5vblNob3duLmNhbGwoX3RoaXMpO1xuXHRcdFx0fSkub24oJ2hpZGUuYnMubW9kYWwnLCB0aGlzLl9jb25maWcub25IaWRlLmJpbmQodGhpcykpLm9uKCdoaWRkZW4uYnMubW9kYWwnLCBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdGlmIChfdGhpcy5fZ2FsbGVyeU5hbWUpIHtcblx0XHRcdFx0XHQkKGRvY3VtZW50KS5vZmYoJ2tleWRvd24uZWtrb0xpZ2h0Ym94Jyk7XG5cdFx0XHRcdFx0JCh3aW5kb3cpLm9mZigncmVzaXplLmVra29MaWdodGJveCcpO1xuXHRcdFx0XHR9XG5cdFx0XHRcdF90aGlzLl8kbW9kYWwucmVtb3ZlKCk7XG5cdFx0XHRcdHJldHVybiBfdGhpcy5fY29uZmlnLm9uSGlkZGVuLmNhbGwoX3RoaXMpO1xuXHRcdFx0fSkubW9kYWwodGhpcy5fY29uZmlnKTtcblxuXHRcdFx0JCh3aW5kb3cpLm9uKCdyZXNpemUuZWtrb0xpZ2h0Ym94JywgZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRfdGhpcy5fcmVzaXplKF90aGlzLl93YW50ZWRXaWR0aCwgX3RoaXMuX3dhbnRlZEhlaWdodCk7XG5cdFx0XHR9KTtcblx0XHRcdHRoaXMuXyRsaWdodGJveENvbnRhaW5lci5vbigndG91Y2hzdGFydCcsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0X3RoaXMuX3RvdWNoc3RhcnRYID0gZXZlbnQuY2hhbmdlZFRvdWNoZXNbMF0uc2NyZWVuWDtcblx0XHRcdH0pLm9uKCd0b3VjaGVuZCcsIGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0X3RoaXMuX3RvdWNoZW5kWCA9IGV2ZW50LmNoYW5nZWRUb3VjaGVzWzBdLnNjcmVlblg7XG5cdFx0XHRcdF90aGlzLl9zd2lwZUdlc3VyZSgpO1xuXHRcdFx0fSk7XG5cdFx0fVxuXG5cdFx0X2NyZWF0ZUNsYXNzKExpZ2h0Ym94LCBbe1xuXHRcdFx0a2V5OiAnZWxlbWVudCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gZWxlbWVudCgpIHtcblx0XHRcdFx0cmV0dXJuIHRoaXMuXyRlbGVtZW50O1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ21vZGFsJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBtb2RhbCgpIHtcblx0XHRcdFx0cmV0dXJuIHRoaXMuXyRtb2RhbDtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICduYXZpZ2F0ZVRvJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBuYXZpZ2F0ZVRvKGluZGV4KSB7XG5cblx0XHRcdFx0aWYgKGluZGV4IDwgMCB8fCBpbmRleCA+IHRoaXMuXyRnYWxsZXJ5SXRlbXMubGVuZ3RoIC0gMSkgcmV0dXJuIHRoaXM7XG5cblx0XHRcdFx0dGhpcy5fZ2FsbGVyeUluZGV4ID0gaW5kZXg7XG5cblx0XHRcdFx0dGhpcy51cGRhdGVOYXZpZ2F0aW9uKCk7XG5cblx0XHRcdFx0dGhpcy5fJGVsZW1lbnQgPSAkKHRoaXMuXyRnYWxsZXJ5SXRlbXMuZ2V0KHRoaXMuX2dhbGxlcnlJbmRleCkpO1xuXHRcdFx0XHR0aGlzLl9oYW5kbGUoKTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICduYXZpZ2F0ZUxlZnQnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIG5hdmlnYXRlTGVmdCgpIHtcblxuXHRcdFx0XHRpZiAoIXRoaXMuXyRnYWxsZXJ5SXRlbXMpIHJldHVybjtcblxuXHRcdFx0XHRpZiAodGhpcy5fJGdhbGxlcnlJdGVtcy5sZW5ndGggPT09IDEpIHJldHVybjtcblxuXHRcdFx0XHRpZiAodGhpcy5fZ2FsbGVyeUluZGV4ID09PSAwKSB7XG5cdFx0XHRcdFx0aWYgKHRoaXMuX2NvbmZpZy53cmFwcGluZykgdGhpcy5fZ2FsbGVyeUluZGV4ID0gdGhpcy5fJGdhbGxlcnlJdGVtcy5sZW5ndGggLSAxO2Vsc2UgcmV0dXJuO1xuXHRcdFx0XHR9IGVsc2UgLy9jaXJjdWxhclxuXHRcdFx0XHRcdHRoaXMuX2dhbGxlcnlJbmRleC0tO1xuXG5cdFx0XHRcdHRoaXMuX2NvbmZpZy5vbk5hdmlnYXRlLmNhbGwodGhpcywgJ2xlZnQnLCB0aGlzLl9nYWxsZXJ5SW5kZXgpO1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5uYXZpZ2F0ZVRvKHRoaXMuX2dhbGxlcnlJbmRleCk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnbmF2aWdhdGVSaWdodCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gbmF2aWdhdGVSaWdodCgpIHtcblxuXHRcdFx0XHRpZiAoIXRoaXMuXyRnYWxsZXJ5SXRlbXMpIHJldHVybjtcblxuXHRcdFx0XHRpZiAodGhpcy5fJGdhbGxlcnlJdGVtcy5sZW5ndGggPT09IDEpIHJldHVybjtcblxuXHRcdFx0XHRpZiAodGhpcy5fZ2FsbGVyeUluZGV4ID09PSB0aGlzLl8kZ2FsbGVyeUl0ZW1zLmxlbmd0aCAtIDEpIHtcblx0XHRcdFx0XHRpZiAodGhpcy5fY29uZmlnLndyYXBwaW5nKSB0aGlzLl9nYWxsZXJ5SW5kZXggPSAwO2Vsc2UgcmV0dXJuO1xuXHRcdFx0XHR9IGVsc2UgLy9jaXJjdWxhclxuXHRcdFx0XHRcdHRoaXMuX2dhbGxlcnlJbmRleCsrO1xuXG5cdFx0XHRcdHRoaXMuX2NvbmZpZy5vbk5hdmlnYXRlLmNhbGwodGhpcywgJ3JpZ2h0JywgdGhpcy5fZ2FsbGVyeUluZGV4KTtcblx0XHRcdFx0cmV0dXJuIHRoaXMubmF2aWdhdGVUbyh0aGlzLl9nYWxsZXJ5SW5kZXgpO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ3VwZGF0ZU5hdmlnYXRpb24nLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIHVwZGF0ZU5hdmlnYXRpb24oKSB7XG5cdFx0XHRcdGlmICghdGhpcy5fY29uZmlnLndyYXBwaW5nKSB7XG5cdFx0XHRcdFx0dmFyICRuYXYgPSB0aGlzLl8kbGlnaHRib3hDb250YWluZXIuZmluZCgnZGl2LmVra28tbGlnaHRib3gtbmF2LW92ZXJsYXknKTtcblx0XHRcdFx0XHRpZiAodGhpcy5fZ2FsbGVyeUluZGV4ID09PSAwKSAkbmF2LmZpbmQoJ2E6Zmlyc3QtY2hpbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtlbHNlICRuYXYuZmluZCgnYTpmaXJzdC1jaGlsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHRcdFx0aWYgKHRoaXMuX2dhbGxlcnlJbmRleCA9PT0gdGhpcy5fJGdhbGxlcnlJdGVtcy5sZW5ndGggLSAxKSAkbmF2LmZpbmQoJ2E6bGFzdC1jaGlsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO2Vsc2UgJG5hdi5maW5kKCdhOmxhc3QtY2hpbGQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0fVxuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ2Nsb3NlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBjbG9zZSgpIHtcblx0XHRcdFx0cmV0dXJuIHRoaXMuXyRtb2RhbC5tb2RhbCgnaGlkZScpO1xuXHRcdFx0fVxuXG5cdFx0XHQvLyBoZWxwZXIgcHJpdmF0ZSBtZXRob2RzXG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX25hdmlnYXRpb25hbEJpbmRlcicsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX25hdmlnYXRpb25hbEJpbmRlcihldmVudCkge1xuXHRcdFx0XHRldmVudCA9IGV2ZW50IHx8IHdpbmRvdy5ldmVudDtcblx0XHRcdFx0aWYgKGV2ZW50LmtleUNvZGUgPT09IDM5KSByZXR1cm4gdGhpcy5uYXZpZ2F0ZVJpZ2h0KCk7XG5cdFx0XHRcdGlmIChldmVudC5rZXlDb2RlID09PSAzNykgcmV0dXJuIHRoaXMubmF2aWdhdGVMZWZ0KCk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIHR5cGUgZGV0ZWN0aW9uIHByaXZhdGUgbWV0aG9kc1xuXHRcdH0sIHtcblx0XHRcdGtleTogJ19kZXRlY3RSZW1vdGVUeXBlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfZGV0ZWN0UmVtb3RlVHlwZShzcmMsIHR5cGUpIHtcblxuXHRcdFx0XHR0eXBlID0gdHlwZSB8fCBmYWxzZTtcblxuXHRcdFx0XHRpZiAoIXR5cGUgJiYgdGhpcy5faXNJbWFnZShzcmMpKSB0eXBlID0gJ2ltYWdlJztcblx0XHRcdFx0aWYgKCF0eXBlICYmIHRoaXMuX2dldFlvdXR1YmVJZChzcmMpKSB0eXBlID0gJ3lvdXR1YmUnO1xuXHRcdFx0XHRpZiAoIXR5cGUgJiYgdGhpcy5fZ2V0VmltZW9JZChzcmMpKSB0eXBlID0gJ3ZpbWVvJztcblx0XHRcdFx0aWYgKCF0eXBlICYmIHRoaXMuX2dldEluc3RhZ3JhbUlkKHNyYykpIHR5cGUgPSAnaW5zdGFncmFtJztcblxuXHRcdFx0XHRpZiAoIXR5cGUgfHwgWydpbWFnZScsICd5b3V0dWJlJywgJ3ZpbWVvJywgJ2luc3RhZ3JhbScsICd2aWRlbycsICd1cmwnXS5pbmRleE9mKHR5cGUpIDwgMCkgdHlwZSA9ICd1cmwnO1xuXG5cdFx0XHRcdHJldHVybiB0eXBlO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19pc0ltYWdlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfaXNJbWFnZShzdHJpbmcpIHtcblx0XHRcdFx0cmV0dXJuIHN0cmluZyAmJiBzdHJpbmcubWF0Y2goLyheZGF0YTppbWFnZVxcLy4qLCl8KFxcLihqcChlfGd8ZWcpfGdpZnxwbmd8Ym1wfHdlYnB8c3ZnKSgoXFw/fCMpLiopPyQpL2kpO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19jb250YWluZXJUb1VzZScsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2NvbnRhaW5lclRvVXNlKCkge1xuXHRcdFx0XHR2YXIgX3RoaXMyID0gdGhpcztcblxuXHRcdFx0XHQvLyBpZiBjdXJyZW50bHkgc2hvd2luZyBhbiBpbWFnZSwgZmFkZSBpdCBvdXQgYW5kIHJlbW92ZVxuXHRcdFx0XHR2YXIgJHRvVXNlID0gdGhpcy5fJGxpZ2h0Ym94Qm9keVR3bztcblx0XHRcdFx0dmFyICRjdXJyZW50ID0gdGhpcy5fJGxpZ2h0Ym94Qm9keU9uZTtcblxuXHRcdFx0XHRpZiAodGhpcy5fJGxpZ2h0Ym94Qm9keVR3by5oYXNDbGFzcygnaW4nKSkge1xuXHRcdFx0XHRcdCR0b1VzZSA9IHRoaXMuXyRsaWdodGJveEJvZHlPbmU7XG5cdFx0XHRcdFx0JGN1cnJlbnQgPSB0aGlzLl8kbGlnaHRib3hCb2R5VHdvO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0JGN1cnJlbnQucmVtb3ZlQ2xhc3MoJ2luIHNob3cnKTtcblx0XHRcdFx0c2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0aWYgKCFfdGhpczIuXyRsaWdodGJveEJvZHlUd28uaGFzQ2xhc3MoJ2luJykpIF90aGlzMi5fJGxpZ2h0Ym94Qm9keVR3by5lbXB0eSgpO1xuXHRcdFx0XHRcdGlmICghX3RoaXMyLl8kbGlnaHRib3hCb2R5T25lLmhhc0NsYXNzKCdpbicpKSBfdGhpczIuXyRsaWdodGJveEJvZHlPbmUuZW1wdHkoKTtcblx0XHRcdFx0fSwgNTAwKTtcblxuXHRcdFx0XHQkdG9Vc2UuYWRkQ2xhc3MoJ2luIHNob3cnKTtcblx0XHRcdFx0cmV0dXJuICR0b1VzZTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfaGFuZGxlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfaGFuZGxlKCkge1xuXG5cdFx0XHRcdHZhciAkdG9Vc2UgPSB0aGlzLl9jb250YWluZXJUb1VzZSgpO1xuXHRcdFx0XHR0aGlzLl91cGRhdGVUaXRsZUFuZEZvb3RlcigpO1xuXG5cdFx0XHRcdHZhciBjdXJyZW50UmVtb3RlID0gdGhpcy5fJGVsZW1lbnQuYXR0cignZGF0YS1yZW1vdGUnKSB8fCB0aGlzLl8kZWxlbWVudC5hdHRyKCdocmVmJyk7XG5cdFx0XHRcdHZhciBjdXJyZW50VHlwZSA9IHRoaXMuX2RldGVjdFJlbW90ZVR5cGUoY3VycmVudFJlbW90ZSwgdGhpcy5fJGVsZW1lbnQuYXR0cignZGF0YS10eXBlJykgfHwgZmFsc2UpO1xuXG5cdFx0XHRcdGlmIChbJ2ltYWdlJywgJ3lvdXR1YmUnLCAndmltZW8nLCAnaW5zdGFncmFtJywgJ3ZpZGVvJywgJ3VybCddLmluZGV4T2YoY3VycmVudFR5cGUpIDwgMCkgcmV0dXJuIHRoaXMuX2Vycm9yKHRoaXMuX2NvbmZpZy5zdHJpbmdzLnR5cGUpO1xuXG5cdFx0XHRcdHN3aXRjaCAoY3VycmVudFR5cGUpIHtcblx0XHRcdFx0XHRjYXNlICdpbWFnZSc6XG5cdFx0XHRcdFx0XHR0aGlzLl9wcmVsb2FkSW1hZ2UoY3VycmVudFJlbW90ZSwgJHRvVXNlKTtcblx0XHRcdFx0XHRcdHRoaXMuX3ByZWxvYWRJbWFnZUJ5SW5kZXgodGhpcy5fZ2FsbGVyeUluZGV4LCAzKTtcblx0XHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRcdGNhc2UgJ3lvdXR1YmUnOlxuXHRcdFx0XHRcdFx0dGhpcy5fc2hvd1lvdXR1YmVWaWRlbyhjdXJyZW50UmVtb3RlLCAkdG9Vc2UpO1xuXHRcdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdFx0Y2FzZSAndmltZW8nOlxuXHRcdFx0XHRcdFx0dGhpcy5fc2hvd1ZpbWVvVmlkZW8odGhpcy5fZ2V0VmltZW9JZChjdXJyZW50UmVtb3RlKSwgJHRvVXNlKTtcblx0XHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHRcdGNhc2UgJ2luc3RhZ3JhbSc6XG5cdFx0XHRcdFx0XHR0aGlzLl9zaG93SW5zdGFncmFtVmlkZW8odGhpcy5fZ2V0SW5zdGFncmFtSWQoY3VycmVudFJlbW90ZSksICR0b1VzZSk7XG5cdFx0XHRcdFx0XHRicmVhaztcblx0XHRcdFx0XHRjYXNlICd2aWRlbyc6XG5cdFx0XHRcdFx0XHR0aGlzLl9zaG93SHRtbDVWaWRlbyhjdXJyZW50UmVtb3RlLCAkdG9Vc2UpO1xuXHRcdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHRcdFx0ZGVmYXVsdDpcblx0XHRcdFx0XHRcdC8vIHVybFxuXHRcdFx0XHRcdFx0dGhpcy5fbG9hZFJlbW90ZUNvbnRlbnQoY3VycmVudFJlbW90ZSwgJHRvVXNlKTtcblx0XHRcdFx0XHRcdGJyZWFrO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2dldFlvdXR1YmVJZCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2dldFlvdXR1YmVJZChzdHJpbmcpIHtcblx0XHRcdFx0aWYgKCFzdHJpbmcpIHJldHVybiBmYWxzZTtcblx0XHRcdFx0dmFyIG1hdGNoZXMgPSBzdHJpbmcubWF0Y2goL14uKih5b3V0dS5iZVxcL3x2XFwvfHVcXC9cXHdcXC98ZW1iZWRcXC98d2F0Y2hcXD92PXxcXCZ2PSkoW14jXFwmXFw/XSopLiovKTtcblx0XHRcdFx0cmV0dXJuIG1hdGNoZXMgJiYgbWF0Y2hlc1syXS5sZW5ndGggPT09IDExID8gbWF0Y2hlc1syXSA6IGZhbHNlO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19nZXRWaW1lb0lkJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfZ2V0VmltZW9JZChzdHJpbmcpIHtcblx0XHRcdFx0cmV0dXJuIHN0cmluZyAmJiBzdHJpbmcuaW5kZXhPZigndmltZW8nKSA+IDAgPyBzdHJpbmcgOiBmYWxzZTtcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfZ2V0SW5zdGFncmFtSWQnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9nZXRJbnN0YWdyYW1JZChzdHJpbmcpIHtcblx0XHRcdFx0cmV0dXJuIHN0cmluZyAmJiBzdHJpbmcuaW5kZXhPZignaW5zdGFncmFtJykgPiAwID8gc3RyaW5nIDogZmFsc2U7XG5cdFx0XHR9XG5cblx0XHRcdC8vIGxheW91dCBwcml2YXRlIG1ldGhvZHNcblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfdG9nZ2xlTG9hZGluZycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3RvZ2dsZUxvYWRpbmcoc2hvdykge1xuXHRcdFx0XHRzaG93ID0gc2hvdyB8fCBmYWxzZTtcblx0XHRcdFx0aWYgKHNob3cpIHtcblx0XHRcdFx0XHR0aGlzLl8kbW9kYWxEaWFsb2cuY3NzKCdkaXNwbGF5JywgJ25vbmUnKTtcblx0XHRcdFx0XHR0aGlzLl8kbW9kYWwucmVtb3ZlQ2xhc3MoJ2luIHNob3cnKTtcblx0XHRcdFx0XHQkKCcubW9kYWwtYmFja2Ryb3AnKS5hcHBlbmQodGhpcy5fY29uZmlnLmxvYWRpbmdNZXNzYWdlKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHR0aGlzLl8kbW9kYWxEaWFsb2cuY3NzKCdkaXNwbGF5JywgJ2Jsb2NrJyk7XG5cdFx0XHRcdFx0dGhpcy5fJG1vZGFsLmFkZENsYXNzKCdpbiBzaG93Jyk7XG5cdFx0XHRcdFx0JCgnLm1vZGFsLWJhY2tkcm9wJykuZmluZCgnLmVra28tbGlnaHRib3gtbG9hZGVyJykucmVtb3ZlKCk7XG5cdFx0XHRcdH1cblx0XHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2NhbGN1bGF0ZUJvcmRlcnMnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9jYWxjdWxhdGVCb3JkZXJzKCkge1xuXHRcdFx0XHRyZXR1cm4ge1xuXHRcdFx0XHRcdHRvcDogdGhpcy5fdG90YWxDc3NCeUF0dHJpYnV0ZSgnYm9yZGVyLXRvcC13aWR0aCcpLFxuXHRcdFx0XHRcdHJpZ2h0OiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdib3JkZXItcmlnaHQtd2lkdGgnKSxcblx0XHRcdFx0XHRib3R0b206IHRoaXMuX3RvdGFsQ3NzQnlBdHRyaWJ1dGUoJ2JvcmRlci1ib3R0b20td2lkdGgnKSxcblx0XHRcdFx0XHRsZWZ0OiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdib3JkZXItbGVmdC13aWR0aCcpXG5cdFx0XHRcdH07XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2NhbGN1bGF0ZVBhZGRpbmcnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9jYWxjdWxhdGVQYWRkaW5nKCkge1xuXHRcdFx0XHRyZXR1cm4ge1xuXHRcdFx0XHRcdHRvcDogdGhpcy5fdG90YWxDc3NCeUF0dHJpYnV0ZSgncGFkZGluZy10b3AnKSxcblx0XHRcdFx0XHRyaWdodDogdGhpcy5fdG90YWxDc3NCeUF0dHJpYnV0ZSgncGFkZGluZy1yaWdodCcpLFxuXHRcdFx0XHRcdGJvdHRvbTogdGhpcy5fdG90YWxDc3NCeUF0dHJpYnV0ZSgncGFkZGluZy1ib3R0b20nKSxcblx0XHRcdFx0XHRsZWZ0OiB0aGlzLl90b3RhbENzc0J5QXR0cmlidXRlKCdwYWRkaW5nLWxlZnQnKVxuXHRcdFx0XHR9O1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ190b3RhbENzc0J5QXR0cmlidXRlJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfdG90YWxDc3NCeUF0dHJpYnV0ZShhdHRyaWJ1dGUpIHtcblx0XHRcdFx0cmV0dXJuIHBhcnNlSW50KHRoaXMuXyRtb2RhbERpYWxvZy5jc3MoYXR0cmlidXRlKSwgMTApICsgcGFyc2VJbnQodGhpcy5fJG1vZGFsQ29udGVudC5jc3MoYXR0cmlidXRlKSwgMTApICsgcGFyc2VJbnQodGhpcy5fJG1vZGFsQm9keS5jc3MoYXR0cmlidXRlKSwgMTApO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ191cGRhdGVUaXRsZUFuZEZvb3RlcicsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3VwZGF0ZVRpdGxlQW5kRm9vdGVyKCkge1xuXHRcdFx0XHR2YXIgdGl0bGUgPSB0aGlzLl8kZWxlbWVudC5kYXRhKCd0aXRsZScpIHx8IFwiXCI7XG5cdFx0XHRcdHZhciBjYXB0aW9uID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnZm9vdGVyJykgfHwgXCJcIjtcblxuXHRcdFx0XHR0aGlzLl90aXRsZUlzU2hvd24gPSBmYWxzZTtcblx0XHRcdFx0aWYgKHRpdGxlIHx8IHRoaXMuX2NvbmZpZy5hbHdheXNTaG93Q2xvc2UpIHtcblx0XHRcdFx0XHR0aGlzLl90aXRsZUlzU2hvd24gPSB0cnVlO1xuXHRcdFx0XHRcdHRoaXMuXyRtb2RhbEhlYWRlci5jc3MoJ2Rpc3BsYXknLCAnJykuZmluZCgnLm1vZGFsLXRpdGxlJykuaHRtbCh0aXRsZSB8fCBcIiZuYnNwO1wiKTtcblx0XHRcdFx0fSBlbHNlIHRoaXMuXyRtb2RhbEhlYWRlci5jc3MoJ2Rpc3BsYXknLCAnbm9uZScpO1xuXG5cdFx0XHRcdHRoaXMuX2Zvb3RlcklzU2hvd24gPSBmYWxzZTtcblx0XHRcdFx0aWYgKGNhcHRpb24pIHtcblx0XHRcdFx0XHR0aGlzLl9mb290ZXJJc1Nob3duID0gdHJ1ZTtcblx0XHRcdFx0XHR0aGlzLl8kbW9kYWxGb290ZXIuY3NzKCdkaXNwbGF5JywgJycpLmh0bWwoY2FwdGlvbik7XG5cdFx0XHRcdH0gZWxzZSB0aGlzLl8kbW9kYWxGb290ZXIuY3NzKCdkaXNwbGF5JywgJ25vbmUnKTtcblxuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfc2hvd1lvdXR1YmVWaWRlbycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3Nob3dZb3V0dWJlVmlkZW8ocmVtb3RlLCAkY29udGFpbmVyRm9yRWxlbWVudCkge1xuXHRcdFx0XHR2YXIgaWQgPSB0aGlzLl9nZXRZb3V0dWJlSWQocmVtb3RlKTtcblx0XHRcdFx0dmFyIHF1ZXJ5ID0gcmVtb3RlLmluZGV4T2YoJyYnKSA+IDAgPyByZW1vdGUuc3Vic3RyKHJlbW90ZS5pbmRleE9mKCcmJykpIDogJyc7XG5cdFx0XHRcdHZhciB3aWR0aCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ3dpZHRoJykgfHwgNTYwO1xuXHRcdFx0XHR2YXIgaGVpZ2h0ID0gdGhpcy5fJGVsZW1lbnQuZGF0YSgnaGVpZ2h0JykgfHwgd2lkdGggLyAoNTYwIC8gMzE1KTtcblx0XHRcdFx0cmV0dXJuIHRoaXMuX3Nob3dWaWRlb0lmcmFtZSgnLy93d3cueW91dHViZS5jb20vZW1iZWQvJyArIGlkICsgJz9iYWRnZT0wJmF1dG9wbGF5PTEmaHRtbDU9MScgKyBxdWVyeSwgd2lkdGgsIGhlaWdodCwgJGNvbnRhaW5lckZvckVsZW1lbnQpO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19zaG93VmltZW9WaWRlbycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3Nob3dWaW1lb1ZpZGVvKGlkLCAkY29udGFpbmVyRm9yRWxlbWVudCkge1xuXHRcdFx0XHR2YXIgd2lkdGggPSB0aGlzLl8kZWxlbWVudC5kYXRhKCd3aWR0aCcpIHx8IDUwMDtcblx0XHRcdFx0dmFyIGhlaWdodCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ2hlaWdodCcpIHx8IHdpZHRoIC8gKDU2MCAvIDMxNSk7XG5cdFx0XHRcdHJldHVybiB0aGlzLl9zaG93VmlkZW9JZnJhbWUoaWQgKyAnP2F1dG9wbGF5PTEnLCB3aWR0aCwgaGVpZ2h0LCAkY29udGFpbmVyRm9yRWxlbWVudCk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3Nob3dJbnN0YWdyYW1WaWRlbycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3Nob3dJbnN0YWdyYW1WaWRlbyhpZCwgJGNvbnRhaW5lckZvckVsZW1lbnQpIHtcblx0XHRcdFx0Ly8gaW5zdGFncmFtIGxvYWQgdGhlaXIgY29udGVudCBpbnRvIGlmcmFtZSdzIHNvIHRoaXMgY2FuIGJlIHB1dCBzdHJhaWdodCBpbnRvIHRoZSBlbGVtZW50XG5cdFx0XHRcdHZhciB3aWR0aCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ3dpZHRoJykgfHwgNjEyO1xuXHRcdFx0XHR2YXIgaGVpZ2h0ID0gd2lkdGggKyA4MDtcblx0XHRcdFx0aWQgPSBpZC5zdWJzdHIoLTEpICE9PSAnLycgPyBpZCArICcvJyA6IGlkOyAvLyBlbnN1cmUgaWQgaGFzIHRyYWlsaW5nIHNsYXNoXG5cdFx0XHRcdCRjb250YWluZXJGb3JFbGVtZW50Lmh0bWwoJzxpZnJhbWUgd2lkdGg9XCInICsgd2lkdGggKyAnXCIgaGVpZ2h0PVwiJyArIGhlaWdodCArICdcIiBzcmM9XCInICsgaWQgKyAnZW1iZWQvXCIgZnJhbWVib3JkZXI9XCIwXCIgYWxsb3dmdWxsc2NyZWVuPjwvaWZyYW1lPicpO1xuXHRcdFx0XHR0aGlzLl9yZXNpemUod2lkdGgsIGhlaWdodCk7XG5cdFx0XHRcdHRoaXMuX2NvbmZpZy5vbkNvbnRlbnRMb2FkZWQuY2FsbCh0aGlzKTtcblx0XHRcdFx0aWYgKHRoaXMuXyRtb2RhbEFycm93cykgLy9oaWRlIHRoZSBhcnJvd3Mgd2hlbiBzaG93aW5nIHZpZGVvXG5cdFx0XHRcdFx0dGhpcy5fJG1vZGFsQXJyb3dzLmNzcygnZGlzcGxheScsICdub25lJyk7XG5cdFx0XHRcdHRoaXMuX3RvZ2dsZUxvYWRpbmcoZmFsc2UpO1xuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfc2hvd1ZpZGVvSWZyYW1lJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfc2hvd1ZpZGVvSWZyYW1lKHVybCwgd2lkdGgsIGhlaWdodCwgJGNvbnRhaW5lckZvckVsZW1lbnQpIHtcblx0XHRcdFx0Ly8gc2hvdWxkIGJlIHVzZWQgZm9yIHZpZGVvcyBvbmx5LiBmb3IgcmVtb3RlIGNvbnRlbnQgdXNlIGxvYWRSZW1vdGVDb250ZW50IChkYXRhLXR5cGU9dXJsKVxuXHRcdFx0XHRoZWlnaHQgPSBoZWlnaHQgfHwgd2lkdGg7IC8vIGRlZmF1bHQgdG8gc3F1YXJlXG5cdFx0XHRcdCRjb250YWluZXJGb3JFbGVtZW50Lmh0bWwoJzxkaXYgY2xhc3M9XCJlbWJlZC1yZXNwb25zaXZlIGVtYmVkLXJlc3BvbnNpdmUtMTZieTlcIj48aWZyYW1lIHdpZHRoPVwiJyArIHdpZHRoICsgJ1wiIGhlaWdodD1cIicgKyBoZWlnaHQgKyAnXCIgc3JjPVwiJyArIHVybCArICdcIiBmcmFtZWJvcmRlcj1cIjBcIiBhbGxvd2Z1bGxzY3JlZW4gY2xhc3M9XCJlbWJlZC1yZXNwb25zaXZlLWl0ZW1cIj48L2lmcmFtZT48L2Rpdj4nKTtcblx0XHRcdFx0dGhpcy5fcmVzaXplKHdpZHRoLCBoZWlnaHQpO1xuXHRcdFx0XHR0aGlzLl9jb25maWcub25Db250ZW50TG9hZGVkLmNhbGwodGhpcyk7XG5cdFx0XHRcdGlmICh0aGlzLl8kbW9kYWxBcnJvd3MpIHRoaXMuXyRtb2RhbEFycm93cy5jc3MoJ2Rpc3BsYXknLCAnbm9uZScpOyAvL2hpZGUgdGhlIGFycm93cyB3aGVuIHNob3dpbmcgdmlkZW9cblx0XHRcdFx0dGhpcy5fdG9nZ2xlTG9hZGluZyhmYWxzZSk7XG5cdFx0XHRcdHJldHVybiB0aGlzO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19zaG93SHRtbDVWaWRlbycsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3Nob3dIdG1sNVZpZGVvKHVybCwgJGNvbnRhaW5lckZvckVsZW1lbnQpIHtcblx0XHRcdFx0Ly8gc2hvdWxkIGJlIHVzZWQgZm9yIHZpZGVvcyBvbmx5LiBmb3IgcmVtb3RlIGNvbnRlbnQgdXNlIGxvYWRSZW1vdGVDb250ZW50IChkYXRhLXR5cGU9dXJsKVxuXHRcdFx0XHR2YXIgd2lkdGggPSB0aGlzLl8kZWxlbWVudC5kYXRhKCd3aWR0aCcpIHx8IDU2MDtcblx0XHRcdFx0dmFyIGhlaWdodCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ2hlaWdodCcpIHx8IHdpZHRoIC8gKDU2MCAvIDMxNSk7XG5cdFx0XHRcdCRjb250YWluZXJGb3JFbGVtZW50Lmh0bWwoJzxkaXYgY2xhc3M9XCJlbWJlZC1yZXNwb25zaXZlIGVtYmVkLXJlc3BvbnNpdmUtMTZieTlcIj48dmlkZW8gd2lkdGg9XCInICsgd2lkdGggKyAnXCIgaGVpZ2h0PVwiJyArIGhlaWdodCArICdcIiBzcmM9XCInICsgdXJsICsgJ1wiIHByZWxvYWQ9XCJhdXRvXCIgYXV0b3BsYXkgY29udHJvbHMgY2xhc3M9XCJlbWJlZC1yZXNwb25zaXZlLWl0ZW1cIj48L3ZpZGVvPjwvZGl2PicpO1xuXHRcdFx0XHR0aGlzLl9yZXNpemUod2lkdGgsIGhlaWdodCk7XG5cdFx0XHRcdHRoaXMuX2NvbmZpZy5vbkNvbnRlbnRMb2FkZWQuY2FsbCh0aGlzKTtcblx0XHRcdFx0aWYgKHRoaXMuXyRtb2RhbEFycm93cykgdGhpcy5fJG1vZGFsQXJyb3dzLmNzcygnZGlzcGxheScsICdub25lJyk7IC8vaGlkZSB0aGUgYXJyb3dzIHdoZW4gc2hvd2luZyB2aWRlb1xuXHRcdFx0XHR0aGlzLl90b2dnbGVMb2FkaW5nKGZhbHNlKTtcblx0XHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2xvYWRSZW1vdGVDb250ZW50Jyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfbG9hZFJlbW90ZUNvbnRlbnQodXJsLCAkY29udGFpbmVyRm9yRWxlbWVudCkge1xuXHRcdFx0XHR2YXIgX3RoaXMzID0gdGhpcztcblxuXHRcdFx0XHR2YXIgd2lkdGggPSB0aGlzLl8kZWxlbWVudC5kYXRhKCd3aWR0aCcpIHx8IDU2MDtcblx0XHRcdFx0dmFyIGhlaWdodCA9IHRoaXMuXyRlbGVtZW50LmRhdGEoJ2hlaWdodCcpIHx8IDU2MDtcblxuXHRcdFx0XHR2YXIgZGlzYWJsZUV4dGVybmFsQ2hlY2sgPSB0aGlzLl8kZWxlbWVudC5kYXRhKCdkaXNhYmxlRXh0ZXJuYWxDaGVjaycpIHx8IGZhbHNlO1xuXHRcdFx0XHR0aGlzLl90b2dnbGVMb2FkaW5nKGZhbHNlKTtcblxuXHRcdFx0XHQvLyBleHRlcm5hbCB1cmxzIGFyZSBsb2FkaW5nIGludG8gYW4gaWZyYW1lXG5cdFx0XHRcdC8vIGxvY2FsIGFqYXggY2FuIGJlIGxvYWRlZCBpbnRvIHRoZSBjb250YWluZXIgaXRzZWxmXG5cdFx0XHRcdGlmICghZGlzYWJsZUV4dGVybmFsQ2hlY2sgJiYgIXRoaXMuX2lzRXh0ZXJuYWwodXJsKSkge1xuXHRcdFx0XHRcdCRjb250YWluZXJGb3JFbGVtZW50LmxvYWQodXJsLCAkLnByb3h5KGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHRcdHJldHVybiBfdGhpczMuXyRlbGVtZW50LnRyaWdnZXIoJ2xvYWRlZC5icy5tb2RhbCcpO2w7XG5cdFx0XHRcdFx0fSkpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdCRjb250YWluZXJGb3JFbGVtZW50Lmh0bWwoJzxpZnJhbWUgc3JjPVwiJyArIHVybCArICdcIiBmcmFtZWJvcmRlcj1cIjBcIiBhbGxvd2Z1bGxzY3JlZW4+PC9pZnJhbWU+Jyk7XG5cdFx0XHRcdFx0dGhpcy5fY29uZmlnLm9uQ29udGVudExvYWRlZC5jYWxsKHRoaXMpO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0aWYgKHRoaXMuXyRtb2RhbEFycm93cykgLy9oaWRlIHRoZSBhcnJvd3Mgd2hlbiByZW1vdGUgY29udGVudFxuXHRcdFx0XHRcdHRoaXMuXyRtb2RhbEFycm93cy5jc3MoJ2Rpc3BsYXknLCAnbm9uZScpO1xuXG5cdFx0XHRcdHRoaXMuX3Jlc2l6ZSh3aWR0aCwgaGVpZ2h0KTtcblx0XHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX2lzRXh0ZXJuYWwnLFxuXHRcdFx0dmFsdWU6IGZ1bmN0aW9uIF9pc0V4dGVybmFsKHVybCkge1xuXHRcdFx0XHR2YXIgbWF0Y2ggPSB1cmwubWF0Y2goL14oW146XFwvPyNdKzopPyg/OlxcL1xcLyhbXlxcLz8jXSopKT8oW14/I10rKT8oXFw/W14jXSopPygjLiopPy8pO1xuXHRcdFx0XHRpZiAodHlwZW9mIG1hdGNoWzFdID09PSBcInN0cmluZ1wiICYmIG1hdGNoWzFdLmxlbmd0aCA+IDAgJiYgbWF0Y2hbMV0udG9Mb3dlckNhc2UoKSAhPT0gbG9jYXRpb24ucHJvdG9jb2wpIHJldHVybiB0cnVlO1xuXG5cdFx0XHRcdGlmICh0eXBlb2YgbWF0Y2hbMl0gPT09IFwic3RyaW5nXCIgJiYgbWF0Y2hbMl0ubGVuZ3RoID4gMCAmJiBtYXRjaFsyXS5yZXBsYWNlKG5ldyBSZWdFeHAoJzooJyArICh7XG5cdFx0XHRcdFx0XCJodHRwOlwiOiA4MCxcblx0XHRcdFx0XHRcImh0dHBzOlwiOiA0NDNcblx0XHRcdFx0fSlbbG9jYXRpb24ucHJvdG9jb2xdICsgJyk/JCcpLCBcIlwiKSAhPT0gbG9jYXRpb24uaG9zdCkgcmV0dXJuIHRydWU7XG5cblx0XHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19lcnJvcicsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2Vycm9yKG1lc3NhZ2UpIHtcblx0XHRcdFx0Y29uc29sZS5lcnJvcihtZXNzYWdlKTtcblx0XHRcdFx0dGhpcy5fY29udGFpbmVyVG9Vc2UoKS5odG1sKG1lc3NhZ2UpO1xuXHRcdFx0XHR0aGlzLl9yZXNpemUoMzAwLCAzMDApO1xuXHRcdFx0XHRyZXR1cm4gdGhpcztcblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfcHJlbG9hZEltYWdlQnlJbmRleCcsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3ByZWxvYWRJbWFnZUJ5SW5kZXgoc3RhcnRJbmRleCwgbnVtYmVyT2ZUaW1lcykge1xuXG5cdFx0XHRcdGlmICghdGhpcy5fJGdhbGxlcnlJdGVtcykgcmV0dXJuO1xuXG5cdFx0XHRcdHZhciBuZXh0ID0gJCh0aGlzLl8kZ2FsbGVyeUl0ZW1zLmdldChzdGFydEluZGV4KSwgZmFsc2UpO1xuXHRcdFx0XHRpZiAodHlwZW9mIG5leHQgPT0gJ3VuZGVmaW5lZCcpIHJldHVybjtcblxuXHRcdFx0XHR2YXIgc3JjID0gbmV4dC5hdHRyKCdkYXRhLXJlbW90ZScpIHx8IG5leHQuYXR0cignaHJlZicpO1xuXHRcdFx0XHRpZiAobmV4dC5hdHRyKCdkYXRhLXR5cGUnKSA9PT0gJ2ltYWdlJyB8fCB0aGlzLl9pc0ltYWdlKHNyYykpIHRoaXMuX3ByZWxvYWRJbWFnZShzcmMsIGZhbHNlKTtcblxuXHRcdFx0XHRpZiAobnVtYmVyT2ZUaW1lcyA+IDApIHJldHVybiB0aGlzLl9wcmVsb2FkSW1hZ2VCeUluZGV4KHN0YXJ0SW5kZXggKyAxLCBudW1iZXJPZlRpbWVzIC0gMSk7XG5cdFx0XHR9XG5cdFx0fSwge1xuXHRcdFx0a2V5OiAnX3ByZWxvYWRJbWFnZScsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3ByZWxvYWRJbWFnZShzcmMsICRjb250YWluZXJGb3JJbWFnZSkge1xuXHRcdFx0XHR2YXIgX3RoaXM0ID0gdGhpcztcblxuXHRcdFx0XHQkY29udGFpbmVyRm9ySW1hZ2UgPSAkY29udGFpbmVyRm9ySW1hZ2UgfHwgZmFsc2U7XG5cblx0XHRcdFx0dmFyIGltZyA9IG5ldyBJbWFnZSgpO1xuXHRcdFx0XHRpZiAoJGNvbnRhaW5lckZvckltYWdlKSB7XG5cdFx0XHRcdFx0KGZ1bmN0aW9uICgpIHtcblxuXHRcdFx0XHRcdFx0Ly8gaWYgbG9hZGluZyB0YWtlcyA+IDIwMG1zIHNob3cgYSBsb2FkZXJcblx0XHRcdFx0XHRcdHZhciBsb2FkaW5nVGltZW91dCA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuXHRcdFx0XHRcdFx0XHQkY29udGFpbmVyRm9ySW1hZ2UuYXBwZW5kKF90aGlzNC5fY29uZmlnLmxvYWRpbmdNZXNzYWdlKTtcblx0XHRcdFx0XHRcdH0sIDIwMCk7XG5cblx0XHRcdFx0XHRcdGltZy5vbmxvYWQgPSBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRcdGlmIChsb2FkaW5nVGltZW91dCkgY2xlYXJUaW1lb3V0KGxvYWRpbmdUaW1lb3V0KTtcblx0XHRcdFx0XHRcdFx0bG9hZGluZ1RpbWVvdXQgPSBudWxsO1xuXHRcdFx0XHRcdFx0XHR2YXIgaW1hZ2UgPSAkKCc8aW1nIC8+Jyk7XG5cdFx0XHRcdFx0XHRcdGltYWdlLmF0dHIoJ3NyYycsIGltZy5zcmMpO1xuXHRcdFx0XHRcdFx0XHRpbWFnZS5hZGRDbGFzcygnaW1nLWZsdWlkJyk7XG5cblx0XHRcdFx0XHRcdFx0Ly8gYmFja3dhcmQgY29tcGF0aWJpbGl0eSBmb3IgYm9vdHN0cmFwIHYzXG5cdFx0XHRcdFx0XHRcdGltYWdlLmNzcygnd2lkdGgnLCAnMTAwJScpO1xuXG5cdFx0XHRcdFx0XHRcdCRjb250YWluZXJGb3JJbWFnZS5odG1sKGltYWdlKTtcblx0XHRcdFx0XHRcdFx0aWYgKF90aGlzNC5fJG1vZGFsQXJyb3dzKSBfdGhpczQuXyRtb2RhbEFycm93cy5jc3MoJ2Rpc3BsYXknLCAnJyk7IC8vIHJlbW92ZSBkaXNwbGF5IHRvIGRlZmF1bHQgdG8gY3NzIHByb3BlcnR5XG5cblx0XHRcdFx0XHRcdFx0X3RoaXM0Ll9yZXNpemUoaW1nLndpZHRoLCBpbWcuaGVpZ2h0KTtcblx0XHRcdFx0XHRcdFx0X3RoaXM0Ll90b2dnbGVMb2FkaW5nKGZhbHNlKTtcblx0XHRcdFx0XHRcdFx0cmV0dXJuIF90aGlzNC5fY29uZmlnLm9uQ29udGVudExvYWRlZC5jYWxsKF90aGlzNCk7XG5cdFx0XHRcdFx0XHR9O1xuXHRcdFx0XHRcdFx0aW1nLm9uZXJyb3IgPSBmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0XHRcdF90aGlzNC5fdG9nZ2xlTG9hZGluZyhmYWxzZSk7XG5cdFx0XHRcdFx0XHRcdHJldHVybiBfdGhpczQuX2Vycm9yKF90aGlzNC5fY29uZmlnLnN0cmluZ3MuZmFpbCArICgnICAnICsgc3JjKSk7XG5cdFx0XHRcdFx0XHR9O1xuXHRcdFx0XHRcdH0pKCk7XG5cdFx0XHRcdH1cblxuXHRcdFx0XHRpbWcuc3JjID0gc3JjO1xuXHRcdFx0XHRyZXR1cm4gaW1nO1xuXHRcdFx0fVxuXHRcdH0sIHtcblx0XHRcdGtleTogJ19zd2lwZUdlc3VyZScsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX3N3aXBlR2VzdXJlKCkge1xuXHRcdFx0XHRpZiAodGhpcy5fdG91Y2hlbmRYIDwgdGhpcy5fdG91Y2hzdGFydFgpIHtcblx0XHRcdFx0XHRyZXR1cm4gdGhpcy5uYXZpZ2F0ZVJpZ2h0KCk7XG5cdFx0XHRcdH1cblx0XHRcdFx0aWYgKHRoaXMuX3RvdWNoZW5kWCA+IHRoaXMuX3RvdWNoc3RhcnRYKSB7XG5cdFx0XHRcdFx0cmV0dXJuIHRoaXMubmF2aWdhdGVMZWZ0KCk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHR9LCB7XG5cdFx0XHRrZXk6ICdfcmVzaXplJyxcblx0XHRcdHZhbHVlOiBmdW5jdGlvbiBfcmVzaXplKHdpZHRoLCBoZWlnaHQpIHtcblxuXHRcdFx0XHRoZWlnaHQgPSBoZWlnaHQgfHwgd2lkdGg7XG5cdFx0XHRcdHRoaXMuX3dhbnRlZFdpZHRoID0gd2lkdGg7XG5cdFx0XHRcdHRoaXMuX3dhbnRlZEhlaWdodCA9IGhlaWdodDtcblxuXHRcdFx0XHR2YXIgaW1hZ2VBc3BlY1JhdGlvID0gd2lkdGggLyBoZWlnaHQ7XG5cblx0XHRcdFx0Ly8gaWYgd2lkdGggPiB0aGUgYXZhaWxhYmxlIHNwYWNlLCBzY2FsZSBkb3duIHRoZSBleHBlY3RlZCB3aWR0aCBhbmQgaGVpZ2h0XG5cdFx0XHRcdHZhciB3aWR0aEJvcmRlckFuZFBhZGRpbmcgPSB0aGlzLl9wYWRkaW5nLmxlZnQgKyB0aGlzLl9wYWRkaW5nLnJpZ2h0ICsgdGhpcy5fYm9yZGVyLmxlZnQgKyB0aGlzLl9ib3JkZXIucmlnaHQ7XG5cblx0XHRcdFx0Ly8gZm9yY2UgMTBweCBtYXJnaW4gaWYgd2luZG93IHNpemUgPiA1NzVweFxuXHRcdFx0XHR2YXIgYWRkTWFyZ2luID0gdGhpcy5fY29uZmlnLmRvYy5ib2R5LmNsaWVudFdpZHRoID4gNTc1ID8gMjAgOiAwO1xuXHRcdFx0XHR2YXIgZGlzY291bnRNYXJnaW4gPSB0aGlzLl9jb25maWcuZG9jLmJvZHkuY2xpZW50V2lkdGggPiA1NzUgPyAwIDogMjA7XG5cblx0XHRcdFx0dmFyIG1heFdpZHRoID0gTWF0aC5taW4od2lkdGggKyB3aWR0aEJvcmRlckFuZFBhZGRpbmcsIHRoaXMuX2NvbmZpZy5kb2MuYm9keS5jbGllbnRXaWR0aCAtIGFkZE1hcmdpbiwgdGhpcy5fY29uZmlnLm1heFdpZHRoKTtcblxuXHRcdFx0XHRpZiAod2lkdGggKyB3aWR0aEJvcmRlckFuZFBhZGRpbmcgPiBtYXhXaWR0aCkge1xuXHRcdFx0XHRcdGhlaWdodCA9IChtYXhXaWR0aCAtIHdpZHRoQm9yZGVyQW5kUGFkZGluZyAtIGRpc2NvdW50TWFyZ2luKSAvIGltYWdlQXNwZWNSYXRpbztcblx0XHRcdFx0XHR3aWR0aCA9IG1heFdpZHRoO1xuXHRcdFx0XHR9IGVsc2Ugd2lkdGggPSB3aWR0aCArIHdpZHRoQm9yZGVyQW5kUGFkZGluZztcblxuXHRcdFx0XHR2YXIgaGVhZGVySGVpZ2h0ID0gMCxcblx0XHRcdFx0ICAgIGZvb3RlckhlaWdodCA9IDA7XG5cblx0XHRcdFx0Ly8gYXMgdGhlIHJlc2l6ZSBpcyBwZXJmb3JtZWQgdGhlIG1vZGFsIGlzIHNob3csIHRoZSBjYWxjdWxhdGUgbWlnaHQgZmFpbFxuXHRcdFx0XHQvLyBpZiBzbywgZGVmYXVsdCB0byB0aGUgZGVmYXVsdCBzaXplc1xuXHRcdFx0XHRpZiAodGhpcy5fZm9vdGVySXNTaG93bikgZm9vdGVySGVpZ2h0ID0gdGhpcy5fJG1vZGFsRm9vdGVyLm91dGVySGVpZ2h0KHRydWUpIHx8IDU1O1xuXG5cdFx0XHRcdGlmICh0aGlzLl90aXRsZUlzU2hvd24pIGhlYWRlckhlaWdodCA9IHRoaXMuXyRtb2RhbEhlYWRlci5vdXRlckhlaWdodCh0cnVlKSB8fCA2NztcblxuXHRcdFx0XHR2YXIgYm9yZGVyUGFkZGluZyA9IHRoaXMuX3BhZGRpbmcudG9wICsgdGhpcy5fcGFkZGluZy5ib3R0b20gKyB0aGlzLl9ib3JkZXIuYm90dG9tICsgdGhpcy5fYm9yZGVyLnRvcDtcblxuXHRcdFx0XHQvL2NhbGN1bGF0ZWQgZWFjaCB0aW1lIGFzIHJlc2l6aW5nIHRoZSB3aW5kb3cgY2FuIGNhdXNlIHRoZW0gdG8gY2hhbmdlIGR1ZSB0byBCb290c3RyYXBzIGZsdWlkIG1hcmdpbnNcblx0XHRcdFx0dmFyIG1hcmdpbnMgPSBwYXJzZUZsb2F0KHRoaXMuXyRtb2RhbERpYWxvZy5jc3MoJ21hcmdpbi10b3AnKSkgKyBwYXJzZUZsb2F0KHRoaXMuXyRtb2RhbERpYWxvZy5jc3MoJ21hcmdpbi1ib3R0b20nKSk7XG5cblx0XHRcdFx0dmFyIG1heEhlaWdodCA9IE1hdGgubWluKGhlaWdodCwgJCh3aW5kb3cpLmhlaWdodCgpIC0gYm9yZGVyUGFkZGluZyAtIG1hcmdpbnMgLSBoZWFkZXJIZWlnaHQgLSBmb290ZXJIZWlnaHQsIHRoaXMuX2NvbmZpZy5tYXhIZWlnaHQgLSBib3JkZXJQYWRkaW5nIC0gaGVhZGVySGVpZ2h0IC0gZm9vdGVySGVpZ2h0KTtcblxuXHRcdFx0XHRpZiAoaGVpZ2h0ID4gbWF4SGVpZ2h0KSB7XG5cdFx0XHRcdFx0Ly8gaWYgaGVpZ2h0ID4gdGhlIGF2YWlsYWJsZSBoZWlnaHQsIHNjYWxlIGRvd24gdGhlIHdpZHRoXG5cdFx0XHRcdFx0d2lkdGggPSBNYXRoLmNlaWwobWF4SGVpZ2h0ICogaW1hZ2VBc3BlY1JhdGlvKSArIHdpZHRoQm9yZGVyQW5kUGFkZGluZztcblx0XHRcdFx0fVxuXG5cdFx0XHRcdHRoaXMuXyRsaWdodGJveENvbnRhaW5lci5jc3MoJ2hlaWdodCcsIG1heEhlaWdodCk7XG5cdFx0XHRcdHRoaXMuXyRtb2RhbERpYWxvZy5jc3MoJ2ZsZXgnLCAxKS5jc3MoJ21heFdpZHRoJywgd2lkdGgpO1xuXG5cdFx0XHRcdHZhciBtb2RhbCA9IHRoaXMuXyRtb2RhbC5kYXRhKCdicy5tb2RhbCcpO1xuXHRcdFx0XHRpZiAobW9kYWwpIHtcblx0XHRcdFx0XHQvLyB2NCBtZXRob2QgaXMgbWlzdGFrZW5seSBwcm90ZWN0ZWRcblx0XHRcdFx0XHR0cnkge1xuXHRcdFx0XHRcdFx0bW9kYWwuX2hhbmRsZVVwZGF0ZSgpO1xuXHRcdFx0XHRcdH0gY2F0Y2ggKEV4Y2VwdGlvbikge1xuXHRcdFx0XHRcdFx0bW9kYWwuaGFuZGxlVXBkYXRlKCk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHRcdHJldHVybiB0aGlzO1xuXHRcdFx0fVxuXHRcdH1dLCBbe1xuXHRcdFx0a2V5OiAnX2pRdWVyeUludGVyZmFjZScsXG5cdFx0XHR2YWx1ZTogZnVuY3Rpb24gX2pRdWVyeUludGVyZmFjZShjb25maWcpIHtcblx0XHRcdFx0dmFyIF90aGlzNSA9IHRoaXM7XG5cblx0XHRcdFx0Y29uZmlnID0gY29uZmlnIHx8IHt9O1xuXHRcdFx0XHRyZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uICgpIHtcblx0XHRcdFx0XHR2YXIgJHRoaXMgPSAkKF90aGlzNSk7XG5cdFx0XHRcdFx0dmFyIF9jb25maWcgPSAkLmV4dGVuZCh7fSwgTGlnaHRib3guRGVmYXVsdCwgJHRoaXMuZGF0YSgpLCB0eXBlb2YgY29uZmlnID09PSAnb2JqZWN0JyAmJiBjb25maWcpO1xuXG5cdFx0XHRcdFx0bmV3IExpZ2h0Ym94KF90aGlzNSwgX2NvbmZpZyk7XG5cdFx0XHRcdH0pO1xuXHRcdFx0fVxuXHRcdH1dKTtcblxuXHRcdHJldHVybiBMaWdodGJveDtcblx0fSkoKTtcblxuXHQkLmZuW05BTUVdID0gTGlnaHRib3guX2pRdWVyeUludGVyZmFjZTtcblx0JC5mbltOQU1FXS5Db25zdHJ1Y3RvciA9IExpZ2h0Ym94O1xuXHQkLmZuW05BTUVdLm5vQ29uZmxpY3QgPSBmdW5jdGlvbiAoKSB7XG5cdFx0JC5mbltOQU1FXSA9IEpRVUVSWV9OT19DT05GTElDVDtcblx0XHRyZXR1cm4gTGlnaHRib3guX2pRdWVyeUludGVyZmFjZTtcblx0fTtcblxuXHRyZXR1cm4gTGlnaHRib3g7XG59KShqUXVlcnkpO1xuLy8jIHNvdXJjZU1hcHBpbmdVUkw9ZWtrby1saWdodGJveC5qcy5tYXBcblxufShqUXVlcnkpO1xuIl0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9