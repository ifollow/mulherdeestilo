/**
 * Drone Options Script
 *
 * @since: 2.0
 * @subpackage: Drone
 */

// -----------------------------------------------------------------------------

(function($) {
	
	'use strict';
	
	// Drone Options
	var droneOptions = {
			
		// ---------------------------------------------------------------------
			
		fontWebSafeOptions:        '',
		fontGoogleWebfontsOptions: '',
		fontCustomFontfaceOptions: '',
			
		// ---------------------------------------------------------------------
			
		init: function() {
			
			// Font option
			if (typeof web_safe != 'undefined') {
				for (var font in web_safe) {
					this.fontWebSafeOptions += '<option value="'+font+'">'+web_safe[font]+'</option>';
				}
			}
			if (typeof google_webfonts != 'undefined') {
				for (var font in google_webfonts) {
					this.fontGoogleWebfontsOptions += '<option value="'+google_webfonts[font]+'">'+google_webfonts[font]+'</option>';
				}
			}
			if (typeof custom_fontface != 'undefined') {
				for (var font in custom_fontface) {
					this.fontCustomFontfaceOptions += '<option value="'+font+'">'+custom_fontface[font]+'</option>';
				}
			}

			// Attach
			this.attach();
			
			// Submit button
			$('.drone-theme-options #submit').prop('disabled', false);
			
			return this;
			
		},
			
		// ---------------------------------------------------------------------
			
		attach: function() {
			
			// Options
			var options = $('.drone-theme-options, .drone-post-options, #widgets-right .drone-widget-options');
			
			// Option parent
			$('[data-drone-parent]:not(.drone-parent-ready)', options).addClass('drone-parent-ready').each(function() {

				var _this = this;

				$('[id][name="'+$(this).data('drone-parent')+'"]')
					.change(function() {
						var val;
						if ($(this).is('[type="checkbox"]')) {
							val = $(this).prop('checked');
						} else if ($(this).is('[type="radio"]')) {
							val = $(this).filter(':checked').val();
						} else {
							val = $(this).val();
						}
						if (typeof val != 'undefined') {
							$(_this).closest('.drone-row').toggleClass('drone-hidden', $(_this).data('drone-parent-value').indexOf(val) == -1);
						}
					})
					.change();

			});
			
			// Group option
			$('.drone-option-group.drone-option-group-sortable:not(.drone-ready)', options).addClass('drone-ready').sortable({
				items:       '> label',
				placeholder: 'drone-option-group-placeholder'
			});
			
			// Image option
			// https://gist.github.com/mauryaratan/4461148
			$('.drone-option-image:not(.drone-ready)', options).addClass('drone-ready').each(function() {

				var _this = this;
				
				$('.button.select', this).click(function() {
					wp.media.frames.drone_image =
						wp.media({
							 title:    $(_this).data('drone-option-image-title'),
							 library:  {type: 'image'},
							 multiple: false
						 })
						 .on('select', function() {
							var attachment = wp.media.frames.drone_image.state().get('selection').first().toJSON();
							$('input', _this).val(attachment.url);
						 })
						 .open();
				});
				
				$('.button.clear', this).click(function() {
					$('input', _this).val('');
				});

			});
			
			// Attachment option
			$('.drone-option-attachment:not(.drone-ready)', options).addClass('drone-ready').each(function() {
				
				var _this = this;
				
				$('.button.select', this).click(function() {
					wp.media.frames.drone_attachment =
						wp.media({
							 title:    $(_this).data('drone-option-attachment-title'),
							 library:  {type: $(_this).data('drone-option-attachment-type')},
							 multiple: false
						 })
						 .on('select', function() {
							var attachment = wp.media.frames.drone_attachment.state().get('selection').first().toJSON();
							$('input', _this).val(attachment.id);
							$('span', _this).html('<code>'+attachment.mime+'</code> '+attachment.title);
						 })
						 .open();
				});
				
				$('.button.clear', this).click(function() {
					$('input', _this).val('0');
					$('span', _this).html('&nbsp;');
				});

			});

			// Array option
			$('.drone-option-array:not(.drone-ready)', options).addClass('drone-ready').each(function() {
				
				var _this = this;
				var id    = 0;
				
				$('> ul > li > div', this).each(function() {
					$('[name*="[__prototype]"]', this).attr('name', function() {
						return $(this).attr('name').replace('[__prototype]', '['+id+']');
					});
					id++;
				});
				
				if ($(this).hasClass('drone-option-array-sortable')) {
					$('> ul', this).sortable({
						axis:        'y',
						placeholder: 'drone-option-array-placeholder',
						start:        function(event, ui) {
							ui.placeholder.css('height', ui.item.innerHeight());
						}
					});
				}
				
				$('> .drone-option-array-controls > .button.add', this).click(function() {
					var option = $('> .drone-option-array-prototype', _this).clone().children();
					$('.drone-parent-ready, .drone-ready', option).removeClass('drone-parent-ready').removeClass('drone-ready');
					$('[name*="[__prototype]"]', option).attr('name', function() {
						return $(this).attr('name').replace('[__prototype]', '['+id+']');
					});
					$('<li />').append(option).appendTo($('> ul', _this));
					id++;
					droneOptions.attach();
				});
				
				$(this).on('click', '.button.delete', function() {
					$(this).parent().remove();
				});
				
			});
			
			// Font option
			$('.drone-option-font:not(.drone-ready)', options).addClass('drone-ready').each(function() {
				$('[name$="[family]"]', this).each(function() {
					$('optgroup[data-type="web_safe"]', this).html(droneOptions.fontWebSafeOptions);
					$('optgroup[data-type="google_webfonts"]', this).html(droneOptions.fontGoogleWebfontsOptions);
					$('optgroup[data-type="custom_fontface"]', this).html(droneOptions.fontCustomFontfaceOptions);
					$(this).val($(this).data('value'));
				});
			});

			return this;
			
		}
			
	};
	
	// jQuery
	$(document).ready(function($) {
		
		droneOptions.init();
		
		var attach = function() { droneOptions.attach(); };
		$(document).ajaxComplete(attach);
		$('#widget-list').children('.widget').on('dragstop', attach);	

	});
	
})(jQuery);