/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// -----------------------------------------------------------------------------

(function($) {

	'use strict';
	
	// -------------------------------------------------------------------------
	
	// Array unique
	Array.prototype.unique = function() {
		var unique = [];
		for (var i = 0; i < this.length; i++) {
			if ($.inArray(this[i], unique) == -1) {
				unique.push(this[i]);
			}
		}
		return unique;
	};

	// -------------------------------------------------------------------------
	
	// Get data
	$.fn.getData = function(key, defaultValue) {
		return this.is('[data-'+key+']') ? this.data(key) : defaultValue;
	};
	
	// -------------------------------------------------------------------------
	
	// Discard white space
	$.fn.discardWhiteSpace = function() {
		return this.each(function() {
			$(this).contents().filter(function() {
				return this.nodeType === 3;
			}).remove();
		});
	};
	
	// -------------------------------------------------------------------------
	
	// Movable container
	$.fn.movableContainer = function(forceTouchDevice) {
		
		// Touch device
		var touchDevice = ('ontouchstart' in document.documentElement) || (typeof window.navigator.msPointerEnabled != 'undefined');
		if (typeof forceTouchDevice != 'undefined') {
			touchDevice = touchDevice || forceTouchDevice;
		}

		// Movable container
		return this.removeClass('movable-container').each(function() {
						
			// Original margins
			var margins = {
				marginTop:    $(this).css('margin-top'),
				marginBottom: $(this).css('margin-bottom')
			};
			
			// Wrapping
			var content = $(this).addClass('movable-container-content').wrap('<div class="movable-container" />');
			var mc      = content.parent().css(margins);

			// Max left position
			var maxLeft = function() {
				return mc.width() - content.width() - (touchDevice ? nav.outerWidth(true) : 0);
			};
			
			// Touchable device
			if (touchDevice) {
				
				var nav = $('<div />', {'class': 'movable-container-nav'})
					.append('<a class="button"><i class="icon-fast-backward"></i></a>')
					.append('<a class="button"><i class="icon-fast-forward"></i></a>')
					.appendTo(mc);
				
				var buttons = $('.button', nav).click(function() {
					
					// Disabled
					if ($(this).is('.disabled')) {
						return;
					}
					
					// Position
					var s = ($(this).index() == 0 ? 1 : -1) * Math.round((mc.width()-nav.outerWidth(true))*0.9);
					var x = Math.max(Math.min(content.position().left + s, 0), maxLeft());
					
					// Buttons
					buttons.eq(0).toggleClass('disabled', x == 0);
					buttons.eq(1).toggleClass('disabled', x == maxLeft());
					
					// Content animation
					content.stop(true).animate({left: x}, 400);
					
				});
				buttons.eq(0).addClass('disabled');
				
			}
			
			// Non-touchable device
			else {
				$(mc)
					.mousemove(function(event) {
						var f = Math.min(Math.max((event.pageX-mc.offset().left-20) / (mc.width()-40), 0), 1);
						var x = Math.round((mc.width() - content.width()) * f);
						content.stop(true).css('left', x);
					})
					.mouseleave(function() {
						content.stop(true).animate({left: '+=0'}, 1600).animate({left: 0}, 400);
					});
			}
			
			// Resize event
			var on_resize = function() {
				content.css('left', Math.max(content.position().left, maxLeft()));
				if (touchDevice) {
					if (content.width() > mc.width()) {
						nav.show();
						buttons.eq(0).toggleClass('disabled', content.position().left == 0);
						buttons.eq(1).toggleClass('disabled', content.position().left == maxLeft());
					} else {
						nav.hide();
						content.css('left', 0);
					}
				}
			};
			$(window).resize(on_resize); on_resize();
		
			content.imagesLoaded(on_resize);
			
		});
		
	};
	
	// -------------------------------------------------------------------------
	
	// Scroller
	$.fn.scroller = function(counter) {
		
		if (typeof counter == 'undefined') {
			counter = true;
		}
		
		this.filter('ul').removeClass('scroller').each(function() {
			
			// Original margins
			var margins = {
				marginTop:    $(this).css('margin-top'),
				marginBottom: $(this).css('margin-bottom')
			};

			// Wrapping
			var content  = $(this).addClass('scroller-content').wrap('<div class="scroller" />');
			var items    = $('> li', content);
			var scroller = content.parent().css(margins);
	
			// Content & items
			content.css('width', (items.length*100)+'%');
			items.eq(0).addClass('active');
				
			// Navigation
			var nav = $('<div></div>', {'class': 'scroller-nav'})
				.append('<a class="button"><i class="icon-left-open"></i></a>')
				.append('<a class="button"><i class="icon-right-open"></i></a>')
				.appendTo(scroller);
			if (counter) {
				nav.append('<small>1/'+items.length+'</small>');
			}
			
			var buttons = $('.button', nav).click(function() {
				
				// Disabled
				if ($(this).is('.disabled')) {
					return;
				}
				
				// Active & next item
				var active = items.filter('.active');
				var next   = items.eq(Math.min(Math.max(active.index() + ($(this).index() == 0 ? -1 : 1), 0), items.length-1));
	
				active.removeClass('active');
				next.addClass('active');
				
				// Buttons
				buttons.eq(0).toggleClass('disabled', next.index() == 0);
				buttons.eq(1).toggleClass('disabled', next.index() == items.length-1);
				
				// Counter
				if (counter) {
					$('small', nav).text((next.index()+1)+'/'+items.length);
				}

				// Content scroll
				content.stop(true).animate({
					left:   -next.position().left,
					height: next.outerHeight()
				}, 400);
				
			});
			buttons.eq(0).addClass('disabled');
			
			// Resize event
			var on_resize = function() {
				var active = items.filter('.active');
				items.css('width', scroller.width());
				content.stop(true).css({
					left:   -active.position().left,
					height: active.outerHeight()
				});
			};
			$(window).resize(on_resize); on_resize();
			
		});
		
		return this;
	
	};
	
	// -------------------------------------------------------------------------
	
	// jQuery
	$(document).ready(function($) {
	
		// No-js
		$('html').removeClass('no-js').addClass('js');
			
		// Configuration
		var conf = $.extend({}, {
			templatePath:               '.',
			tableMobileColsThreshold:   3,
			columnsMobileColsThreshold: 3,
			zoomHoverIcons:             {
				image:     'icon-search',
				mail:      'icon-mail',
				title:     'icon-right',
				'default': 'icon-plus-circled'
			},
			fancyboxOptions:            {},
			flexsliderOptions:          {
				animation:      'slide',
				slideshow:      false,
				slideshowSpeed: 3000,
				animationSpeed: 400
			},
			layersliderOptions:         {
				skin:                'time-bright',
				autoStart:           false,
				autoPlayVideos:      false,
				navStartStop:        false,
				navButtons:          true,
				thumbnailNavigation: 'disabled'
			},
			masonryOptions:             {},
			captions:                   {
				bricksAllButton: 'all',
				timeDaysAgo:     'about %d days ago',
				timeDayAgo:      'about a day ago',
				timeHoursAgo:    'about %d hours ago',
				timeHourAgo:     'about an hour ago',
				timeMinutesAgo:  'about %d minutes ago',
				timeSecondsAgo:  'about %d seconds ago',
				timeNow:         'just now'
			}
		}, typeof timeConfig != 'undefined' ? timeConfig : {});
	
		// Mobile
		/*
		var isMobile = function() {
			return $('html').css('border-top-style') == 'hidden';
		};
		*/
		
		// Human time difference
		var humanTimeDiff = function(from, to)
		{
			if (typeof to == 'undefined') {
				to = new Date();
			}
			var delta = Math.abs((to.getTime() - from.getTime()) / 1000);
			if (delta < 1) {
				delta = 0;
			}
			var time_ago = {
				days:    parseInt(delta / 86400, 10),
				hours:   parseInt(delta / 3600, 10),
				minutes: parseInt(delta / 60, 10),
				seconds: parseInt(delta, 10)
			};
			if (time_ago.days > 2)     return conf.captions.timeDaysAgo.replace('%d', time_ago.days);
			if (time_ago.hours > 24)   return conf.captions.timeDayAgo;
			if (time_ago.hours > 2)    return conf.captions.timeHoursAgo.replace('%d', time_ago.hours);
			if (time_ago.minutes > 45) return conf.captions.timeHourAgo;
			if (time_ago.minutes > 2)  return conf.captions.timeMinutesAgo.replace('%d', time_ago.minutes);
			if (time_ago.seconds > 1)  return conf.captions.timeSecondsAgo.replace('%d', time_ago.seconds);
			return conf.captions.timeNow;
		};
		
		// Internet Explorer (< 9)
		if ($('html').is('.lt-ie9')) {
			$('<div />', {'class': 'before'}).prependTo($('#top'));
		}
		
		// Device pixel ratio
		var dpr = typeof window.devicePixelRatio == 'undefined' ? 1 : window.devicePixelRatio;
		$('html').addClass(dpr >= 2 ? 'dpr-2x' : 'dpr-1x');

		// High resolution image
		if (dpr >= 2) {
			$('img[data-2x]').attr('src', function() {
				return $(this).data('2x');
			});
			$(':not(img)[data-bg-2x]').css('background-image', function() {
				return 'url('+$(this).data('bg-2x')+')';
			});
		}
		$(':not(img)[data-1x][data-2x]').replaceWith(function() {
			return $('<img />').attr('src', $(this).data(dpr >= 2 ? '2x' : '1x'));
		});

		// Horizontal align
		$(window).bind('load', function() {
			$('.horizontal-align')
				.css('width', function() { return $(this).outerWidth(); })
				.css('float', 'none');
		});

		// Vertical align
		(function() {
			var on_resize = function() {
				$('.vertical-align').each(function() {
					$(this).css('top', ($(this).parent().height() - $(this).outerHeight(true))*0.5);
				});
			};
			$(window).resize(on_resize); $(window).bind('load', on_resize); on_resize();
		})();

		// Movable container
		$('.movable-container').each(function() {
			$(this).movableContainer($(this).is('[data-movable-container-force-touch-device="true"]'));
		});

		// Scroller
		$('.scroller').scroller();

		// Zoom hover
		$('.zoom-hover').each(function() {

			// Layers
			var overlay         = $('<div />', {'class': 'zoom-hover-overlay'}).appendTo(this);
			var title_container = $('<div />', {'class': 'zoom-hover-title-container'}).appendTo(overlay);
			var title;

			// Icon
			var icon;
			if ($(this).attr('href').match(/\.(jpe?g|png|gif|bmp)$/i) && $(this).is('a[href].fb')) {
				icon = conf.zoomHoverIcons.image;
			} else if ($(this).is('a[href^="mailto:"]')) {
				icon = conf.zoomHoverIcons.mail;
			} else {
				icon = $(this).is('[title]') ? conf.zoomHoverIcons.title : conf.zoomHoverIcons['default'];
			}
			icon = $(this).getData('zoom-hover-icon', icon);

			// Title
			if ($(this).is('[title]')) {
				title = $('<h3 />').text($(this).attr('title'));
				if (icon) {
					title.append($('<i />', {'class': icon}));
				}
			} else {
				title = icon ? $('<i />', {'class': icon}) : $('<div />');
			}
			title.addClass('zoom-hover-title').appendTo(title_container);

			// Title position
			var title_left;

			// Hover
			$(this)
				.hover(function() {
					title
						.toggleClass('tiny', title.is('i') && !$('html').is('.lt-ie9') && ($(this).innerWidth() < 100 || $(this).innerHeight() < 100))
						.css('top', Math.round(-0.5*title.innerHeight()));
					title_left = Math.round(-0.5*title.innerWidth());
					if ($('html').is('.lt-ie9')) {
						title.css('left', title_left);
					} else {
						overlay.stop(true).fadeTo(100, 1, 'linear');
						title.stop(true).css('left', title_left-10).animate({left: title_left}, 100);
					}
				}, function() {
					if (!$('html').is('.lt-ie9')) {
						overlay.stop(true).fadeTo(100, 0, 'linear');
						title.stop(true).animate({left: title_left+10}, 100);
					}
				});
			
		});
		
		// Grayscale hover
		$('.grayscale-hover:has(> img)').each(function() {		
			$(this).addClass('image-hover');
			var img = $('> img', this);
			img.clone().appendTo($(this));
			img.addClass('grayscale');
		});
	
		// Embed
		$('.embed').each(function() {
			var video = $('> iframe, > object, > embed', this).filter('[width][height]').first();
			if (video.length > 0) {
				var ratio = (parseInt(video.attr('height'), 10) / parseInt(video.attr('width'), 10))*100;
				$(this).css({'padding-bottom': ratio+'%', height: 0});
			}
		});
			
		// Table
		$('table:not(.fixed):has(thead):has(tbody)').each(function() {
			if ($('thead tr > *', this).length >= conf.tableMobileColsThreshold) {
				$('tbody tr > *', this).each(function() {
					var label = $.trim($(this).closest('table').find('thead th').eq($(this).index()).text());
					if (label) {
						$(this)
							.addClass('alt-mobile-labeled')
							.prepend($('<label />', {'class': 'alt-mobile-label'}).text(label));
					}
				});
				$(this).addClass('alt-mobile');
			}
		});
	
		// Input
		$('.ie input[type="text"], .ie textarea').filter('[placeholder]').each(function() {
			var ph = $(this).attr('placeholder');
			$(this)
				.focus(function() {
					if ($(this).hasClass('placeholder')) {
						$(this).removeClass('placeholder').val('');
					}
				})
				.blur(function() {
					if ($(this).val() === '') {
						$(this).addClass('placeholder').val(ph);
					}
				})
				.blur();
		});
		
		// Button
		$('.button, button, input[type="button"]').filter('[data-button-href]').click(function() {
			switch ($(this).getData('button-target', '_self')) {
				case '_blank':  window.open($(this).data('button-href')); break;
				case '_top':    window.top.location    = $(this).data('button-href'); break;
				case '_parent': window.parent.location = $(this).data('button-href'); break;
				default:        window.location        = $(this).data('button-href');
			}
		});
	
		// Message
		$('.message[data-message-closable="true"]').each(function() {
			var _this = this;
			$('<i class="icon-cancel close"></i>').click(function() {
				if ($(_this).is(':animated')) {
					return;
				}
				var prev = $(_this).prev();
				var m    = prev.length > 0 ? prev.css('margin-bottom') : $(_this).css('margin-top');
				$(_this)
					.fadeTo(300, 0)
					.animate({'border-width': 0, 'margin-top': '-'+m, padding: 0, height: 0}, 300)
					.hide(0);
			}).appendTo($(this));
		});
		
		// Tooltip
		if ($('.tipsy-tooltip').length > 0) {
			$.getScript(conf.templatePath+'/data/js/jquery.tipsy.min.js', function() {
				$('.tipsy-tooltip').each(function() {
					$(this).tipsy({
						gravity: $(this).getData('tipsy-tooltip-gravity', 's'),
						fade:    $(this).getData('tipsy-tooltip-fade', false)
					});
				});
			});
		}
	
		// Columns
		$('.columns').each(function() {
			
			var cols = $('> ul > li', this);
			
			// Alternative mode
			if (!$(this).hasClass('alt-mobile') && cols.length >= conf.columnsMobileColsThreshold) {
				var dens = [];
				cols.each(function() {
					var m = $(this).attr('class').match(/\bcol-1-([0-9]+)\b/);
					if (m !== null) {
						dens.push(parseInt(m[1]));
					}
				});
				if (dens.length == cols.length) {			
					if (dens.unique().length == 1) {
						$(this).addClass('alt-mobile');
					} else {
						do {
							var changed = false;
							var i = 0;
							while (i+1 < dens.length) {
								if (dens[i] % 2 == 0 && dens[i] == dens[i+1]) {
									dens.splice(i, 2, dens[i] / 2);
									changed = true;
								} else {
									i++;
								}
							}		
						} while (changed);
						if (dens.unique().length == 1) {
							$(this).addClass('alt-mobile');
						}
					}					
				}
			}
			
			// Rows clear
			var lcm = 232792560; // LCM(1-20)
			var sum = {desktop: 0, mobile: [0, 0]};
			cols.each(function() {
				if (sum.desktop >= lcm) {
					$(this).addClass('clear-row');
					sum.desktop = 0;
				}
				if (sum.mobile[0] >= lcm) {
					$(this).addClass('mobile-1-clear-row');
					sum.mobile[0] = 0;
				}
				if (sum.mobile[1] >= lcm) {
					$(this).addClass('mobile-2-clear-row');
					sum.mobile[1] = 0;
				}
				var m = $(this).attr('class').match(/\bcol-([0-9]+)-([0-9]+)\b/); // todo: spr. czy ma class w ogole
				if (m !== null) {
					sum.desktop   += m[1]*(lcm/m[2]);
					sum.mobile[0] += m[1]*(lcm/Math.ceil(m[2]/2));
					sum.mobile[1] += m[1]*(lcm/Math.ceil(m[2]/4));
				}
			});
			
		});
		
		// Tabs
		$('.tabs').each(function() {
			
			var nav = $('<ul />', {'class': 'nav'}).prependTo(this);
			var tabs = $('> div[title]', this);
			
			// Tabs
			tabs
				.each(function() {
					$('<li />', {'class': $(this).hasClass('active') ? 'active' : ''})
						.text($(this).attr('title'))
						.click(function() {
							$(this).addClass('active').siblings().removeClass('active');
							tabs.removeClass('active').eq($(this).index()).addClass('active');
						})
						.appendTo(nav);
				})
				.attr('title', '');
			
			// Navigation
			nav.movableContainer();
			
			$('> :first-child, > .active', nav).click();

			// Deep linking
			var onhashchange = function() {
				var hash = unescape(self.document.location.hash).substring(1);
				if (!hash) {
					return;
				}
				var tab = tabs.filter('#'+hash);
				if (tab.length == 0) {
					return;
				}
				$('> :eq('+(tab.index()-1)+')', nav).click();
				$(window).scrollTop(tab.offset().top);
			}	
			if ('onhashchange' in window) {
				window.onhashchange = onhashchange;
			}
			onhashchange();
			
		});
		
		// Super tabs
		$('.super-tabs').each(function() {
	
			var nav     = $('<ul />', {'class': 'nav'}).appendTo(this);
			var tabs    = $('> div[title]', this);
			var ordered = $(this).is('[data-super-tabs-ordered="true"]');
			
			// Wrapping
			$(this).wrapInner($('<div />'));
			var wrapper = $('> div', this);
			var on_resize = function() {
				wrapper.css('height', tabs.filter('.active').height());
			};
			$(window).resize(on_resize);
	
			// Tabs
			tabs
				.each(function(i) {
					$('<li />', {'class': $(this).hasClass('active') ? 'active' : ''})
						.append($('<div />', {'class': 'table-vertical-align'})
							.append($('<div />')
								.append($('<h2 />')
									.text($(this).attr('title'))
									.prepend(ordered ? $('<span />').text(i+1) : null)	
								)
								.append($(this).is('[data-super-tabs-description]') && $(this).data('super-tabs-description') ? $('<small />').text($(this).data('super-tabs-description')) : null)
							)
						)
						.click(function() {
							$(this).addClass('active').siblings().removeClass('active');
							tabs.removeClass('active').eq($(this).index()).addClass('active');
							on_resize();
						})
						.appendTo(nav);
				})
				.attr('title', '');
	
			// Navigation
			$('li', nav).css('height', (100 / tabs.length).toFixed(2)+'%');
			$('> :first-child, > .active', nav).click();
			
			$(this).imagesLoaded(on_resize);
		
		});
		
		// Toggles
		$('.toggles').each(function() {
			var _this = this;
			$('> div[title]', this).each(function() {
				
				// Title
				var title = $('<h3 />')
					.text($(this).attr('title'))
					.prepend('<i class="icon-plus-circled"></i>')
					.prepend('<i class="icon-minus-circled" style="display: none;"></i>')
					.click(function() {	
						if ($(_this).is('[data-toggles-singular="true"]') && !$(this).next('div[title]').is(':visible')) {
							$(this).parent().siblings().each(function() {
								$('> h3 > i', this).css('display', function(i) { return i > 0 ? 'block' : 'none'; });
								$('> div[title]', this).stop(true).slideUp();
							});
						}					
						$('i', this).toggle();
						$(this).next('div[title]').stop(true).slideToggle();
					});
				
				// Wrap
				$(this)
					.attr('title', '')
					.wrap('<div></div>')
					.parent()
						.prepend(title);
				
				// Active
				if ($(this).hasClass('active')) {
					$(this).show().prev('h3').find('i').toggle();
				}
				
			});
	
		});
		
		// Fancybox
		$('a[href].fb')
			.each(function() {
				var youtube = $(this).attr('href').match(/^https?:\/\/(www\.youtube\.com\/watch\?v=|youtu\.be\/)([-_a-z0-9]+)/i);
				var vimeo   = $(this).attr('href').match(/^https?:\/\/vimeo.com\/([-_a-z0-9]+)/i);
				if (youtube != null) {
					$(this).data({'fancybox-type': 'iframe', 'fancybox-href': 'http://www.youtube.com/embed/'+youtube[2]+'?wmode=opaque'});
				}
				else if (vimeo != null) {
					$(this).data({'fancybox-type': 'iframe', 'fancybox-href': 'http://player.vimeo.com/video/'+vimeo[1]});
				}
			})
			.fancybox($.extend({}, conf.fancyboxOptions, {
				margin:      [30, 70, 30, 70],
				padding:     2,
				aspectRatio: true
			}));
		
		// Social buttons
		$('.social-buttons ul').discardWhiteSpace();
	
		// Contact form
		$('.contact-form').submit(function() {
			if ($('input[type="submit"]', this).prop('disabled')) {
				return false;
			}
			var _this = this;
			$('input[type="submit"]', this).prop('disabled', true);
			$('.load', this).stop(true).fadeIn(200);
			$('.msg', this).stop(true).fadeOut(200);
			$.ajax({
				url:      $(this).attr('action'),
				type:     'POST',
				data:     $(this).serialize(),
				dataType: 'json',
				complete: function() {
					$('input[type="submit"]', _this).prop('disabled', false);	
				},
				success: function(data) {
					$('.load', _this).fadeOut(200, function() {
						if (data === null) {
							$('.msg', _this).text('Unknown error.');
						} else {
							$('.msg', _this).text(data.message);
							if (data.result) {
								$('input[type="text"], textarea', _this).val('');
							}
						}
						$('.msg', _this).fadeIn(200);
					});
				}
			});
			return false;
		});
		
		// Login form
		$('[href="#login-form"]').fancybox($.extend({}, conf.fancyboxOptions, {
			type:     'inline',
			margin:   [10, 10, 10, 10],
			padding:  20,
			width:    180,
			height:   'auto',
			autoSize: false,
			tpl:      {
				wrap: '<div class="fancybox-wrap fancybox-login-form" tabIndex="-1"><div class="fancybox-skin"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>'
			}
		}));
		
		// Slider
		$('.slider').each(function() {
			var slider = $(this);
			slider.imagesLoaded(function() {
				slider
					.flexslider($.extend({}, conf.flexsliderOptions, {
						namespace:    '',	
						smoothHeight: true,
						useCSS:       false,
						video:        true,
						prevText:     '<i class="icon-left-open-mini"></i>',
						nextText:     '<i class="icon-right-open-mini"></i>',
						start:        function(slider) {						
			
							if (typeof window.addEventListener == 'function') { // window.attachEvent('onmessage', on_message_received, false);

								// Pause slideshow on YouTube and Vimeo player play
								window.addEventListener('message', function(e) {
									try {
										var data = $.parseJSON(e.data);
										switch (data.event) {
											case 'ready':
												// https://github.com/CSS-Tricks/AnythingSlider/blob/master/js/jquery.anythingslider.video.js
												$('iframe[src*="//www.youtube.com"]', slider.slides).each(function() {
													this.contentWindow.postMessage(JSON.stringify({event: 'listening', func: 'onStateChange'}), '*');
												});
												$('iframe[src*="//player.vimeo.com"]', slider.slides).each(function() {
													this.contentWindow.postMessage(JSON.stringify({method: 'addEventListener', value: 'play'}), $(this).attr('src').split('?')[0]);
												});
												break;
											case 'onStateChange': // YouTube
												if (data.info.playerState == 1) {
													slider.pause();
												}
												break;
											case 'play': // Vimeo
												slider.pause();
												break;	
										}
									} catch (e) {}
								}, false);
								
							}
			
							// Hidding control-nav on embed slides
							if (slider.slides.eq(slider.currentSlide).is(':has(.embed)')) {
								$('.control-nav', slider).hide();
							}
			
						},
						before:       function(slider) {
							
							var current_slide = slider.slides.eq(slider.currentSlide);
							
							// Pause YouTube and Vimeo players
							var youtube = $('iframe[src*="//www.youtube.com"]', current_slide);
							var vimeo = $('iframe[src*="//player.vimeo.com"]', current_slide);
							if (youtube.length > 0) {
								youtube[0].contentWindow.postMessage(JSON.stringify({event: 'command', func: 'pauseVideo'}), '*');
							}
							if (vimeo.length > 0) {
								vimeo[0].contentWindow.postMessage(JSON.stringify({method: 'pause'}), vimeo.attr('src').split('?')[0]);
							}
							$('audio, video', current_slide).each(function() {
								this.player.media.pause();
							});
							
							// Hidding control-nav on embed slides
							if (slider.slides.eq(slider.animatingTo).is(':has(.embed)')) {
								$('.control-nav', slider).fadeOut(100);
							} else {
								$('.control-nav', slider).fadeIn(100);
							}
							
						}
					}))
					.hover(function() {
						$('.direction-nav a', this).stop(true).fadeTo(100, 1);
					}, function() {
						$('.direction-nav a', this).stop(true).fadeTo(100, 0);
					})
					.find('.direction-nav a')
						.addClass('alt');
			});
		});
		
		// Bricks
		if ($('.bricks').length > 0) {
			
			// Preparing
			$('.bricks').each(function() {
				if ($(this).getData('bricks-columns', 2) >= conf.columnsMobileColsThreshold) {
					$(this).addClass('alt-mobile');
				}
				$('> div', this).addClass('bricks-box');
			});
	
			$.getScript(conf.templatePath+'/data/js/jquery.masonry.min.js', function() {
				
				$('.bricks').each(function() {
	
					var _this   = this;
					var boxes   = $('.bricks-box', this);
					var masonry = $('<div />', {'class': 'bricks-masonry'}).append(boxes).appendTo($(this)); 
	
					masonry.imagesLoaded(function() {
						
						// Masonry
						masonry.masonry($.extend({}, conf.masonryOptions, {
							itemSelector:     '.bricks-box:not(.bricks-box-hidden)',
							isAnimated:       true,
							animationOptions: {duration: 300},
							columnWidth:      1
						}));
					
						// Filter
						if ($(_this).is('[data-bricks-filter="true"]') && boxes.filter('[rel]').length > 0) {
							
							var filter = $('<div />', {'class': 'bricks-filter'}).prependTo($(_this));
		
							// All button
							$('<a />', {'class': 'button', href: '#*'})
								.text(conf.captions.bricksAllButton)
								.click(function() {
									boxes.removeClass('bricks-box-hidden');
								})
								.appendTo(filter);
							
							// Rel buttons
							var rels = [];
							boxes.filter('[rel][rel!=""]').each(function() {
								$.merge(rels, $.grep($(this).attr('rel').split(' '), function(rel) {
									return rels.indexOf(rel) == -1;
								}));
							});
							rels.sort();
							$.each(rels, function(i, rel) {
								$('<a />', {'class': 'button', href: '#'+rel})
									.text(rel.replace(/_/g, ' '))
									.click(function() {
										boxes.filter('[rel~="'+rel+'"]').removeClass('bricks-box-hidden');
										boxes.filter(':not([rel~="'+rel+'"])').addClass('bricks-box-hidden');
									})
									.appendTo(filter);
							});					
		
							// Buttons
							$('.button', filter).click(function() {
								$(this).addClass('active').siblings().removeClass('active');
								masonry.masonry('reload');
							});
							
							// Deep linking
							var onhashchange = function() {
								var hash = unescape(self.document.location.hash).substring(1);
								if (!hash) {
									hash = '*';
								}
								var button = $('.button[href="#'+hash+'"]', filter);
								if (button.length == 0) {
									return;
								}
								$('.button[href="#'+hash+'"]', filter).click();
							}	
							if ('onhashchange' in window) {
								window.onhashchange = onhashchange;
							}
							onhashchange();
								
						}
					
					});
		
				});
	
			});
			
		}
		
		// Twitter	
		$('.twitter[data-twitter-username]').each(function() {
			var _this       = this;
			var count       = $(this).getData('twitter-count', 3);
			var orientation = $(this).getData('twitter-orientation', 'vertical');
			$.getJSON(conf.templatePath+'/data/php/twitter.php', {
				username:         $(this).data('twitter-username'),
				include_retweets: $(this).getData('twitter-include-retweets', true),
				exclude_replies:  $(this).getData('twitter-exclude-replies', false),
				count:            count,
			}, function(data) {
				var tweets = $('<ul />').appendTo(_this);
				$.each(data, function() {
					$('<li />')
						.html('<i class="icon-twitter"></i>'+this.html+'<br /><small><a href="'+this.url+'" class="alt">'+humanTimeDiff(new Date(this.date*1000))+'</a></small>')
						.appendTo(tweets);
				});
				if (orientation == 'scrollable') {
					tweets.scroller(false);
				} else if (orientation == 'horizontal') {
					tweets.wrap($('<div />', {'class': 'columns'})).find('li').addClass('col-1-'+count);
				}
			});
		});
		
		// Flickr
		// http://idgettr.com/
		$('.flickr[data-flickr-id]').each(function() {
			var _this = this;
			var count = $(this).getData('flickr-count', 4);
			var rel   = 'flickr-'+$(this).data('flickr-id').replace('@', '_');
			$.getJSON('http://api.flickr.com/services/feeds/photos_public.gne?jsoncallback=?', {
				id:     $(this).data('flickr-id'),
				format: 'json'
			}, function(data) {
				var photos = $('<ul />').appendTo($(_this));
				$.each(data.items, function(i, item) {
					if (i < count) {
						$('<li />').append(
							$('<a />', {rel: rel, href: item.media.m.replace('_m', '_b'), title: item.title}).append(
								$('<img />', {src: item.media.m.replace('_m', '_s')}).attr('width', 41).attr('height', 41)
							)
						).appendTo(photos);
					}
				});
				$('a[rel="'+rel+'"]', photos).fancybox($.extend({}, conf.fancyboxOptions, { // FancyBox strange behaviour, context is ignored
					margin:  [30, 70, 30, 70],
					padding: 2
				}));
			});
		});
		
		// Audio, video
		if ($('audio, video').length > 0) {
			$.getScript(conf.templatePath+'/data/js/jquery.mejs.min.js', function() {
				$('audio, video').mediaelementplayer({
					pluginPath:  conf.templatePath+'/data/mejs/',
					videoWidth:  '100%',
					videoHeight: '100%',
					audioWidth:  '100%',
					videoVolume: 'horizontal',
					success: function(mediaElement, domObject) {
						var slider = $(domObject).closest('.slider');
						if (slider.length > 0) {
							mediaElement.addEventListener('play', function() {
								slider.flexslider('pause');
							});
						}
					}
				});			
			});
		}
		
		// Upper container
		$('.upper-container.fixed:has(.header)').each(function() {
			var _this = this;
			var on_resize = function() {
				$('#top').css('padding-top', parseInt($(_this).css('top'))+$('> .outer-container:has(.header)', _this).height());
			};
			$(window).resize(on_resize); on_resize();
			$(this).imagesLoaded(on_resize);
		});
		
		// Under container
		$('.under-container + .outer-container.transparent').prev().addClass('transparent-next');
		var on_resize = function() {
			$('.layout-boxed .under-container').each(function() {
				var gap = Math.min($('#top').innerHeight() - ($(this).position().top+$(this).height()), 1000);
				$(this).css({marginBottom: -gap, paddingBottom: gap});
			});
		};
		$(window).resize(on_resize); on_resize();
		$('#top').imagesLoaded(on_resize);
			
		// Navigation
		$('nav ul, nav li').discardWhiteSpace();
		$('nav li:has(li)').addClass('sub');
		$('nav a[href="#"]').click(function() {
			return false;
		});
		
		// Mobile navigation
		$('nav.mobile a:last-child').prepend(function() {
			return $(this).is(':has(img)') ? '<i></i>' : '<i class="icon-dot"></i>';
		});
		$('nav.mobile .sub > a')
			.prepend('<i class="toggle icon-plus-circled"></i>')
			.prepend('<i class="toggle icon-minus-circled" style="display: none;"></i>')
			.find('> i')
				.click(function() {
					var a = $(this).parent();
					a.next('ul').slideToggle();
					$('> i', a).toggle();
					return false;
				});
		$('nav.mobile ul ul .current').parents('ul').slice(0, -1).each(function() {
			$(this).toggle().prev('a').find('> i').toggle();
		});
		
		// Mobile helper
		$('.mobile-helper .button[href^="#"]').click(function() {
			$('nav.mobile').filter($(this).attr('href')).slideToggle().siblings('nav.mobile:visible').slideUp();
			return false;
		});
		
		// Secondary navigation
		$('nav.secondary').each(function() {
			$('ul:first', this).movableContainer();		
			$('.movable-container', this).mousemove(function() {
				$('ul ul', this).each(function() {
					var parent = $(this).parent();
					var top    = $(window).scrollTop();
					var offset = parent.offset();
					if ($(this).is('nav.secondary ul ul ul')) {
						$(this).css({left: offset.left+parent.width()-1, top: -top+offset.top});
					} else {
						$(this).css({left: offset.left, top: -top+offset.top+parent.height()});
					}
				});
			});
		});
	
		// LayerSlider
		if ($('#layerslider').length > 0) {
			$.getScript(conf.templatePath+'/data/js/jquery.layerslider.min.js', function() {
				
				var ls          = $('#layerslider');
				var backgrounds = $('.backgrounds');
				
				// Full screen
				if ($('body').hasClass('full-screen')) {
					var height = $(window).height()-ls.offset().top;
					ls.css('height', height);
					ls.closest('.container').css('max-height', height);
				}
				
				// LayerSlider
				ls.layerSlider($.extend({}, conf.layersliderOptions, {
					responsive:        !$('body').hasClass('full-screen'),
					animateFirstLayer: true,
					hoverPrevNext:     false,
					skinsPath:         conf.templatePath+'/data/img/layerslider/',
					durationIn:        0,
					durationOut:       0,
					cbInit:            function(ls) {
						
						// Prev, next
						var on_resize = function() {
							var pos = -Math.min($('.ls-nav-prev', ls).outerWidth(true), Math.max($(window).width()-ls.width(), 0)*0.5);
							$('.ls-nav-prev', ls).css('left', pos);
							$('.ls-nav-next', ls).css('right', pos);
						};
						$(window).resize(on_resize); on_resize();
						
						// Backgrounds
						$('.ls-layer', ls).each(function() {
							var bg    = $('<div />').appendTo(backgrounds);
							var ls_bg = $('.ls-bg[src]', this);
							if (ls_bg.length > 0) {
								bg
									.attr('class', ls_bg.attr('class'))
									.css('background-image', 'url('+ls_bg.attr('src')+')');
								if (ls_bg.is('[data-2x]')) {
									bg.attr('data-bg-2x', ls_bg.attr('data-2x'));
								}
							} else {
								bg.addClass('ls-bg');
							}					
						});
						$('.ls-bg', backgrounds).eq(0).show();
	
						// Dev mode
						/*if (ls.hasClass('ls-dev-mode')) {
							$.getScript(conf.templatePath+'/data/js/jquery.ui.min.js', function() {
								var info_box = $('<div />', {'class': 'info-box'}).appendTo(ls);
								var updateInfoBox = function() {
									var s = parseInt(ls.parent().css('max-width'), 10) / ls.width();
									var x = Math.round($(this).position().left*s);
									var y = Math.round($(this).position().top*s);
									var name;
									if ($(this).is('img') || $(this).is(':has(img)')) {
										var src = $(this).is('img') ? $(this).attr('src') : $('img', this).attr('src');
										name = src.substring(src.lastIndexOf('/')+1);
									} else {
										name = '&lt;'+$(this).get(0).tagName.toLowerCase()+'&gt;';
									}
									info_box.html(name+': <strong>'+x+'px</strong> x <strong>'+y+'px</strong>');
								};
								$('[class^="ls-s"]', ls)
									.draggable({
										cancel: false,
										cursor: 'move',
										drag:   updateInfoBox,
										stop:   updateInfoBox
									})
									.mouseenter(updateInfoBox)
									.hover(function() {
										info_box.show();
									}, function() {
										info_box.hide();
									});
							});
						}*/
	
					},
					cbAnimStop:        function(data) {
						backgrounds.each(function() {
							$('.ls-bg', this).eq(data.nextLayerIndex-1).stop(true).fadeIn(400).siblings('.ls-bg').stop(true).fadeOut(400);
						});
					}
				}));
			});
				
		}
		
		// Social media
		if ($('.fb-like, .fb-like-box').length > 0) {
			$('body').prepend($('<div />', {id: 'fb-root'}));
			var lang = $('html').attr('lang');
			lang = lang.indexOf('_') == -1 ? lang.toLowerCase()+'_'+lang.toUpperCase() : lang.replace('-', '_');
			$.getScript('//connect.facebook.net/'+lang+'/all.js#xfbml=1', function() { // http://developers.facebook.com/docs/reference/plugins/like/
				FB.init({status: true, cookie: true, xfbml: true});
			});
		}
		if ($('.twitter-share-button').length > 0) {
			$.getScript('http://platform.twitter.com/widgets.js'); // https://dev.twitter.com/docs/tweet-button
		}
		if ($('.g-plusone').length > 0) {
			$.getScript('https://apis.google.com/js/plusone.js'); // https://developers.google.com/+/plugins/+1button/
		}
		if ($('[data-pin-do]').length > 0) {
			$.getScript('//assets.pinterest.com/js/pinit.js'); // http://business.pinterest.com/widget-builder/#do_pin_it_button
		}
		if ($('.inshare').length > 0) {
			$.getScript('//platform.linkedin.com/in.js'); // http://developer.linkedin.com/plugins/share-plugin-generator
		}
		
		// WooCommerce
		$('body').on('wc_fragments_loaded wc_fragments_refreshed cart_page_refreshed added_to_cart', function() {
			$('.section > .widget_shopping_cart .hide_cart_widget_if_empty').each(function() {
				$(this).closest('.section').toggle(!$(this).is(':has(li.empty)'));
			});
		});
	
	});

})(jQuery);