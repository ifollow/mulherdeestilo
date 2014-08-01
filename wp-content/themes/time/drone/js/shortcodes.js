/**
 * Drone Shortcodes Script
 *
 * @since: 2.0
 * @subpackage: Drone
 */

// -----------------------------------------------------------------------------

(function() {
	
	'use strict';

	tinymce.create('tinymce.plugins.droneShortcodes', {
		
		// -----------------------------------------------------------------
		
		init: function(ed, url) { },
		
		// -----------------------------------------------------------------
		
		createControl: function(n, cm) {		
			if (n == 'drone-shortcodes') {
				var _this = this;
				var c = cm.createSplitButton('drone-shortcodes', {
					title: 'droneshortcodes.title',
					image: tinymce.PluginManager.urls.droneshortcodes+'/../img/shortcodes.png'
					// onclick: function() { }
				});
				c.onRenderMenu.add(function(c, m) {
					m.add({
						title: 'droneshortcodes.title',
						class: 'mceMenuItemTitle'
					}).setDisabled(1);
					for (var i in drone_shortcodes) {
						_this._addShortcode(m, i, drone_shortcodes[i]);
					}
				});
				return c;
			} else {
				return null;
			}			
		},
		
		// -----------------------------------------------------------------
		
		_stringID: function(s, separator) {
			if (typeof separator == 'undefined') {
				separator = '-';
			}
			var preg_separators = '-_\. \\\/\|';
			s = s.replace(new RegExp('[^'+preg_separators+'a-z0-9]', 'ig'), '');
			s = s.replace(/([^A-Z])([A-Z])|([^0-9])([0-9])/g, '$1$3'+separator+'$2$4');
			s = s.replace(new RegExp('['+preg_separators+']+', 'g'), separator);
			s = s.replace(new RegExp('^['+preg_separators+']|['+preg_separators+']$', 'g'), '');
			s = s.toLowerCase();
			return s;
		},
		
		// -----------------------------------------------------------------
		
		_addShortcode: function(m, caption, syntax) {
			if (syntax === '---') {
				m.addSeparator();
			} else if (typeof syntax == 'object') {
				var count = 0;
				for (var i in syntax) {
					if (syntax.hasOwnProperty(i)) {
						count++;
					}
				}
				if (count > 0) {
					m = m.addMenu({
						title: caption,
						icon:  this._stringID(caption)
					});
					for (var i in syntax) {
						this._addShortcode(m, i, syntax[i]);
					}
				}
			} else {
				m.add({
					title:   caption,
					syntax:  syntax,
					icon:    this._stringID(caption),
					onclick: function() {
						var ed   = tinyMCE.activeEditor;
						var sel  = ed.selection.getContent();
						var mark = '<!--b7530948d34bc784bb4c0406fb4684a1'+(new Date().getTime())+'-->';
						var s    = this.syntax.replace(/%s/g, sel ? sel : ' ... ');
						ed.selection.setContent(mark);
						ed.setContent(ed.getContent({format: 'raw'}).replace(new RegExp('(<p></p>)?'+mark+'(<p></p>)?', 'i'), s), {format: 'raw'});
					}
				});
			}
		}
		
	});
	
	tinymce.PluginManager.add('droneshortcodes', tinymce.plugins.droneShortcodes);

})();