/* DisReview Admin JS */
(function ($) {
	'use strict';

	$(function () {
		// Initialize color pickers.
		if ($.fn.wpColorPicker) {
			$('.dr-color').wpColorPicker({
				change: function (event, ui) {
					var $input = $(this);
					var val = ui.color.toString();
					if ($input.attr('id') === 'dr-primary') {
						updatePreviewVar('--dr-primary', val);
						updatePreviewVar('--dr-primary-rgb', hexToRgb(val));
					} else if ($input.attr('id') === 'dr-star') {
						updatePreviewVar('--dr-star', val);
					} else if ($input.attr('id') === 'dr-muted') {
						updatePreviewVar('--dr-muted', val);
					}
				}
			});
		}

		// Theme selection -> swap live preview theme class.
		$('.dr-theme-pick input').on('change', function () {
			var theme = $(this).val();
			var frame = $('#drPreview');
			frame.removeClass(function (i, c) {
				return (c.match(/dr-theme-\S+/g) || []).join(' ');
			});
			frame.addClass('dr-theme-' + theme);
			loadThemePreview(theme);
		});

		// Border radius live update.
		$('#dr-radius').on('input', function () {
			updatePreviewVar('--dr-radius', $(this).val() + 'px');
		});

		// Font live update.
		$('#dr-font').on('input', function () {
			updatePreviewVar('--dr-font', $(this).val());
		});

		// Desktop / mobile preview toggle.
		$('.dr-view-btn').on('click', function () {
			var view = $(this).data('view');
			$('.dr-view-btn').removeClass('is-active');
			$(this).addClass('is-active');
			var frame = $('#drPreview');
			if ('mobile' === view) {
				frame.addClass('dr-preview-mobile');
			} else {
				frame.removeClass('dr-preview-mobile');
			}
		});

		// Settings / Help tabs.
		$('.dr-tab').on('click', function () {
			var tab = $(this).data('tab');
			$('.dr-tab').removeClass('is-active');
			$(this).addClass('is-active');
			$('.dr-tab-panel').removeClass('is-active');
			$('#dr-tab-' + tab).addClass('is-active');
		});

		function updatePreviewVar(name, value) {
			if (!value) return;
			$('#drPreview').get(0).style.setProperty(name, value);
		}

		function loadThemePreview(theme) {
			var url = DisReviewAdmin.pluginUrl + 'assets/css/themes/theme-' + theme + '.css';
			var link = $('#dr-theme-preview-css');
			if (link.length) {
				link.attr('href', url);
			} else {
				$('<link id="dr-theme-preview-css" rel="stylesheet" href="' + url + '">').appendTo('head');
			}
		}

		function hexToRgb(hex) {
			hex = hex.replace('#', '');
			if (hex.length === 3) {
				hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
			}
			var r = parseInt(hex.substr(0, 2), 16);
			var g = parseInt(hex.substr(2, 2), 16);
			var b = parseInt(hex.substr(4, 2), 16);
			return r + ', ' + g + ', ' + b;
		}
	});

})(jQuery);
