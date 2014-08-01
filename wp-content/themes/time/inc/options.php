<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

// Nav menus
$nav_menus = DroneFunc::wpTermsList('nav_menu');

// Cart icons
if (Time::$plugins['woocommerce']) {
	$cart_icons_options = array();
	foreach (DroneFunc::filesList($this->template_dir.'/data/img/wp/carts') as $filename) {
		$cart_icons_options[basename($filename, '.png')] = $this->template_uri.'/data/img/wp/carts/'.$filename;
	}
	uasort($cart_icons_options, create_function('$a, $b', "return strnatcmp(str_replace('-', ' ', \$a), str_replace('-', ' ', \$b));"));
}

// Hover effect icons
$hover_icons_options = array();
foreach (DroneFunc::filesList($this->template_dir.'/data/img/wp/icons') as $filename) {
	$hover_icons_options[basename($filename, '.png')] = $this->template_uri.'/data/img/wp/icons/'.$filename;
}


// -----------------------------------------------------------------------------

// General
$general = $theme_options->addGroup('general', __('General', 'time'));

$general->addOption('group', 'layout', 'boxed', __('Layout type', 'time'), '', array('options' => array(
	'boxed' => __('Boxed', 'time'),
	'open'  => __('Open', 'time')
)));

$general->addOption('group', 'scheme', 'bright', __('Color scheme', 'time'), '', array('options' => array(
	'bright' => __('Bright', 'time'),
	'dark'   => __('Dark', 'time')
)));

$general->addOption('color', 'color', '#ef0000', __('Leading color', 'time'));

$general->addOption('boolean', 'responsive', true, __('Responsive design', 'time'), '', array('caption' => __('Enabled', 'time')));

$general->addOption('boolean', 'retina', true, __("Retina display's support", 'time'), '', array('caption' => __('Enabled', 'time')));

$general->addOption('number', 'max_width', 980, __('Maximum width', 'time'), '', array('min' => 768, 'unit' => 'px'));

$background = $general->addGroup('background', __('Background', 'time'));
$custom = $background->addOption('boolean', 'custom', false, '', '', array('caption' => __('Use custom background', 'time')));
$background->addOption('background', 'background', array('image_ex' => Time::to('general/retina') ? array('image1x' => 0, 'image2x' => 0) : array('image1x' => 0), 'color' => '#ffffff', 'alignment' => 'cover', 'position' => 'center top', 'attachment' => 'fixed', 'stripes' => false), '', sprintf(__('For open layout type, recommended settings are %1$s and %2$s.', 'time'), __('Fit (contain)', 'time'), __('Scroll', 'time')), array('indent' => true, 'parent' => $custom));

$this->addThemeFeature('option-favicon', array('group' => 'general', 'default' => sprintf($this->template_uri.'/data/img/favicon/%s.ico', substr(preg_replace('/[^a-z]/', '', strtolower(get_bloginfo('name'))), 0, 1))));

// -----------------------------------------------------------------------------

// Header
$header = $theme_options->addGroup('header', __('Header', 'time'));

$header->addOption('boolean', 'hide_bar', false, __('Top color bar', 'time'), '', array('caption' => __('Hide top color bar', 'time')));

$header->addOption('group', 'style', '', __('Style', 'time'), '', array('options' => array(
	''      => __('Default', 'time'),
	'fixed' => __('Sticky', 'time'),
	'blank' => __('Transparent', 'time')
)));

$logo = $header->addGroup('logo', __('Logo', 'time'));
$logo->addOption('retina_attachment', 'image', Time::to('general/retina') ? array('image1x' => 0, 'image2x' => 0) : array('image1x' => 0));
$logo->addOption('boolean', 'center', false, '', __('This option will hide primary menu (you can use secondary menu instead).', 'time'), array('caption' => __('Centered', 'time')));

$header->addOption('group', 'primary', array('desktop', 'mobile'), __('Show primary menu on', 'time'), '', array('multiple' => true, 'options' => array(
	'desktop' => __('Desktop devices', 'time'),
	'mobile'  => __('Mobile devices', 'time')
)));

$cart = $header->addGroup('cart', __('Cart', 'time'), sprintf(__('The <a href="%s">WooCommerce plugin</a> is required for shop features.', 'time'), 'http://www.woothemes.com/woocommerce/'));
$enabled = $cart->addOption('boolean', 'enabled', Time::$plugins['woocommerce'], '', '', array('caption' => __('Show cart menu item', 'time'), 'disabled' => !Time::$plugins['woocommerce']));
$cart->addOption('group', 'content', array('icon', 'total'), '', '', array('options' => array(
	'icon'   => __('Cart icon', 'time'),
	'phrase' => __('Cart phrase', 'time'),
	'count'  => __('Items count', 'time'),
	'total'  => __('Items amount', 'time')
), 'multiple' => true, 'parent' => $enabled, 'indent' => true));

$header->addOption('group', 'search', array('desktop', 'mobile'), __('Show search form on', 'time'), '', array('multiple' => true, 'options' => array(
	'desktop' => __('Desktop devices', 'time'),
	'mobile'  => __('Mobile devices', 'time')
)));

if (Time::$plugins['wpml']) {
	$lang_count = count(icl_get_languages('skip_missing=0'));
}
$header->addOption('group', 'lang', Time::$plugins['wpml'] ? ($lang_count > 3 ? 'long' : 'short') : '', __('Language menu', 'time'), sprintf(__('For multi-language site, a <a href="%s">WPML plugin</a> is required.', 'time'), Time::WPML_REFERRAL_URL), array('options' => array(
	''      => __('None', 'time'),
	'short' => __('Short (for 2-3 languages only)', 'time'),
	'long'  => __('Long', 'time')
), 'disabled' => !Time::$plugins['wpml'] ? array('short', 'long') : ($lang_count > 3 ? array('short') : array())));

// -----------------------------------------------------------------------------

// Footer
$footer = $theme_options->addGroup('footer', __('Footer', 'time'));

$layout = $footer->addGroup('layout', __('Layout', 'time'), __('You can specify footer content in Appearance / Widgets.', 'time'));
$_layout = $layout->addOption('list', 'layout', '14_14_14_14', '', '', array('options' => array(
	'11'                => __('Full width', 'time'),
	'12_12'             => __('Two columns', 'time'),
	'13_13_13'          => __('Three columns', 'time'),
	'14_14_14_14'       => __('Four columns', 'time'),
	'15_15_15_15_15'    => __('Five columns', 'time'),
	'16_16_16_16_16_16' => __('Six columns', 'time'),
	'14_34' => '25% + 75%',
	'34_14' => '75% + 25%',
	'14_14_12' => '25% + 25% + 50%',
	'12_14_14' => '50% + 25% + 25%',
	'14_12_14' => '25% + 50% + 25%',
	'disabled' => __('Disabled', 'time'),
	'custom'   => __('Custom', 'time')
), 'groups' => array(
	__('Basic', 'time')         => array('11', '12_12', '13_13_13', '14_14_14_14', '15_15_15_15_15', '16_16_16_16_16_16'),
	__('Two columns', 'time')   => array('14_34', '34_14'),
	__('Three columns', 'time') => array('14_14_12', '12_14_14', '14_12_14'),
	__('Other', 'time')         => array('disabled', 'custom')
)));
$layout->addOption('codeline', 'custom', '', '', sprintf(__('Example: %s.', 'time'), '1/2 + 1/6 + 1/6 + 1/6'), array(
	'maxlength'    => 100,
	'regexpr'      => '/^( *[1-9] *\/ *[1-9] *(\+|$))+$/',
	'indent'       => true,
	'parent'       => $_layout,
	'parent_value' => 'custom',
	'on_sanitize'  => create_function('$o, $ov, &$v', '$v = str_replace(array(" ", "+"), array("", " + "), trim($v, " +"));')
));

$end_note = $footer->addGroup('end_note', __('End note', 'time'));
$visible = $end_note->addOption('boolean', 'visible', true, '', '', array('caption' => __('Show end note', 'time')));
$left = $end_note->addOption('memo', 'left', sprintf(__('&copy; Copyright %s', 'time'), date('Y'))."\n".sprintf(__('%1$s by <a href="%3$s">%2$s</a>', 'time'), get_bloginfo('name'), get_userdata(1)->display_name, esc_url(home_url('/'))), __('Left', 'time'), '', array(
	'parent'  => $visible,
	'on_html' => create_function('$o, $html', '$html->style("height: 70px;");')
));
$right = $end_note->addOption('memo', 'right', sprintf(__('powered by %s theme', 'time'), '<a href="http://themeforest.net/user/kubasto/?ref=kubasto">Time</a>'), __('Right', 'time'), '', array(
	'parent'  => $visible,
	'on_html' => create_function('$o, $html', '$html->style("height: 70px;");')
));

// -----------------------------------------------------------------------------

// Banner
$banner = $theme_options->addGroup('banner', __('Banner', 'time'));

$content = $banner->addGroup('content');
$default = $content->addGroup('default', __('Default content', 'time'));
$type = $default->addOption('list', 'type', '', '', '', array('options' => $this->getBannerTypeOption('inherit')));
$default->addOption('number', 'height', 200, '', '', array('unit' => 'px', 'min' => 0, 'indent' => true, 'parent' => $type, 'parent_value' => 'empty'));
$default->addOption('attachment', 'image', 0, '', '', array('indent' => true, 'parent' => $type, 'parent_value' => 'image'));
if (Time::$plugins['layerslider']) {
	$default->addOption('list', 'layerslider', '', '', '', array('options' => Time::$sliders_array, 'indent' => true, 'parent' => $type, 'parent_value' => 'layerslider'));
}
$default->addOption('editor', 'custom', '', '', '', array('indent' => true, 'parent' => $type, 'parent_value' => 'custom'));
foreach (Time::$conditional_tags_array[1] as $name => $caption) {
	$group = $content->addGroup($name, $caption);
	$type = $group->addOption('list', 'type', 'inherit', '', '', array('options' =>
		$this->getBannerTypeOption(!preg_match('/^(front_page|singular\((post|gallery|portfolio)\)|page)$/', $name) ? 'thumbnail' : array())
	));
	$group->addOption('number', 'height', 200, '', '', array('unit' => 'px', 'min' => 0, 'indent' => true, 'parent' => $type, 'parent_value' => 'empty'));
	$group->addOption('attachment', 'image', 0, '', '', array('indent' => true, 'parent' => $type, 'parent_value' => 'image'));
	if (Time::$plugins['layerslider']) {
		$group->addOption('list', 'layerslider', 0, '', '', array('options' => Time::$sliders_array, 'indent' => true, 'parent' => $type, 'parent_value' => 'layerslider'));
	}
	$group->addOption('editor', 'custom', '', '', '', array('indent' => true, 'parent' => $type, 'parent_value' => 'custom'));
}

// -----------------------------------------------------------------------------

// Navigation
$nav = $theme_options->addGroup('nav', __('Navigation', 'time'));

$secondary = $nav->addGroup('secondary');
$secondary->addOption('group', 'upper', Time::$conditional_tags_default_array, __('Show upper secondary menu on', 'time'), __('Matters only if you specify upper secondary menu in Appearance / Menus.', 'time'), array('multiple' => true, 'options' => Time::$conditional_tags_array[0]));
$secondary->addOption('group', 'lower', Time::$conditional_tags_default_array, __('Show lower secondary menu on', 'time'), __('Matters only if you specify lower secondary menu in Appearance / Menus.', 'time'), array('multiple' => true, 'options' => Time::$conditional_tags_array[0]));

$headline = $nav->addGroup('headline', __('Page headline', 'time'));
$headline->addOption('group', 'visible', array_values(array_diff(Time::$conditional_tags_default_array, array('front_page'))), __('Show on', 'time'), '', array('multiple' => true, 'options' => Time::$conditional_tags_array[0]));
$headline->addOption('group', 'content', Time::$plugins['breadcrumbs'] ? 'breadcrumbs' : 'navigation', __('Content', 'time'), '', array('options' => array(
	''            => __('None', 'time'),
	'breadcrumbs' => __('Breadcrumbs', 'time'),
	'mixed'       => __('Navigation (if possible) or breadcrumbs', 'time'),
	'navigation'  => __('Only navigation (if possible)', 'time')
), 'disabled' => !Time::$plugins['breadcrumbs'] ? array('breadcrumbs', 'mixed') : array()));

// -----------------------------------------------------------------------------

// Sidebar
$sidebar = $theme_options->addGroup('sidebar', __('Sidebars', 'time'));

$sidebar->addOption('number', 'count', 3, __('Available sidebars', 'time'), '', array('min' => 1, 'max' => count(Time::$sidebars_array)));
Time::$sidebars_array = array_slice(Time::$sidebars_array, 0, Time::to('sidebar/count'));
$sidebar_options = array('' => __('(None)', 'time')) + Time::$sidebars_array;

$width = $sidebar->addGroup('width', __('Widths', 'time'), sprintf(__('Notice: sidebars on the left side of content are always %dpx wide.', 'time'), Time::DEFAULT_SIDEBAR_WIDTH).'<br />'.__('Usable width is 40px smaller because of paddings.', 'time'));
foreach (Time::$sidebars_array as $id => $name) {
	$width->addOption('number', $id, Time::DEFAULT_SIDEBAR_WIDTH, sprintf(__('Sidebar %s', 'time'), "<em>{$name}</em>"), '', array('min' => 60, 'max' => 400, 'unit' => 'px'));
}

$layout = $sidebar->addGroup('layout');
$default = $layout->addGroup('default', __('Default layout', 'time'));
$default->addOption('sidebar', 'sidebar', array('#', '', 'alpha'), '', '', array('options' => $sidebar_options));
foreach (Time::$conditional_tags_array[1] as $name => $caption) {
	$group = $layout->addGroup($name, $caption);
	$enabled = $group->addOption('boolean', 'enabled', false, '', '', array('caption' => __('Custom', 'time')));
	$group->addOption('sidebar', 'sidebar', array('#', '', ''), '', '', array('options' => $sidebar_options, 'indent' => true, 'parent' => $enabled));
}

// -----------------------------------------------------------------------------

// Color
$color = $theme_options->addGroup('color', __('Colors', 'time'));

$color->addEnabledOption(
	'color', 'header', false, '#ffffff',
	__('Header', 'time'), __('Custom', 'time'), '', array('tag' => '.upper-container .outer-container')
);

$color->addEnabledOption(
	'color', 'content', false, '#ffffff',
	__('Content', 'time'), __('Custom', 'time'), '', array('tag' => '.outer-container')
);

$color->addEnabledOption(
	'color', 'footer', false, '#ffffff',
	__('Footer', 'time'), __('Custom', 'time'), '', array('tag' => '#bottom .outer-container')
);

$color->addEnabledOption(
	'color', 'bottom', false, '#ffffff',
	__('Site bottom', 'time'), __('Custom', 'time'), '', array('tag' => 'body, #bottom')
);

// -----------------------------------------------------------------------------

// Font
$font = $theme_options->addGroup('font', __('Fonts', 'time'));

$font->addEnabledOption(
	'font', 'body',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 13, 'line_height' => 22),
	__('Main', 'time'), __('Custom', 'time'), '',
	array('tag' => 'body, input, select, textarea, button, .button', 'line_height_unit' => 'px')
);

$font->addEnabledOption(
	'font', 'logo',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 42, 'line_height' => 42, 'styles' => array('bold')),
	__('Logo', 'time'), __('Custom', 'time'), '',
	array('tag' => '.logo, .logo a:hover', 'line_height_unit' => 'px')
);

$nav = $font->addGroup('nav', __('Navigation', 'time'));
$nav->addEnabledOption(
	'font', 'primary',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 15, 'styles' => array()),
	 __('Primary', 'time'), __('Custom', 'time'), '',
	array('tag' => 'nav.primary ul, nav.primary a:not(:hover)')
);
$nav->addEnabledOption(
	'font', 'secondary',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 12, 'styles' => array()),
	__('Secondary', 'time'), __('Custom', 'time'), '',
	array('tag' => 'nav.secondary ul, nav.secondary a:not(:hover)')
);

$headline = $font->addGroup('headline', __('Page headline', 'time'));
$headline->addEnabledOption(
	'font', 'title',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 22, 'styles' => array('bold')),
	__('Title', 'time'), __('Custom', 'time'), '',
	array('tag' => '.headline h1')
);
$headline->addEnabledOption(
	'font', 'breadcrumbs',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 15, 'styles' => array()),
	__('Breadcrumbs', 'time'), __('Custom', 'time'), '',
	array('tag' => '.headline .breadcrumbs')
);

$widget_title = $font->addGroup('widget_title', __('Widget title', 'time'));
$widget_title->addEnabledOption(
	'font', 'top',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 18, 'line_height' => 128.0, 'styles' => array('bold')),
	__('In sidebar', 'time'), __('Custom', 'time'), '',
	array('tag' => '#top .widget > .title')
);
$widget_title->addEnabledOption(
	'font', 'bottom',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 18, 'line_height' => 128.0, 'styles' => array('bold')),
	__('In footer', 'time'), __('Custom', 'time'), '',
	array('tag' => '#bottom .widget > .title')
);

$post = $font->addGroup('post', __('Post/page', 'time'));
$post->addEnabledOption(
	'font', 'title',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 22, 'line_height' => 128.0, 'styles' => array('bold')),
	__('Title', 'time'), __('Custom', 'time'), '',
	array('tag' => '.post h1.title')
);
$post->addEnabledOption(
	'font', 'meta',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 11, 'line_height' => 22, 'styles' => array()),
	__('Meta', 'time'), __('Custom', 'time'), '',
	array('tag' => '.meta:not(.social)', 'line_height_unit' => 'px')
);

$h = $font->addGroup('h', __('Headlines', 'time'));
$h_size = array(22, 18, 14, 14, 14, 14);
for ($i = 1; $i <= 6; $i++) {
	$h->addEnabledOption(
		'font', 'h'.$i,
		false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => $h_size[$i-1], 'line_height' => 128.0, 'styles' => array('bold')),
		'H'.$i, __('Custom', 'time'), '',
		array('tag' => 'h'.$i)
	);
}

$button = $font->addGroup('button', __('Buttons', 'time'));
$button->addEnabledOption(
	'font', 'normal',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 14, 'styles' => array()),
	__('Normal', 'time'), __('Custom', 'time'), '',
	array('tag' => 'input[type="submit"]:not(.big):not(.huge), input[type="reset"]:not(.big):not(.huge), input[type="button"]:not(.big):not(.huge), button:not(.big):not(.huge), .button:not(.big):not(.huge)',)
);
$button->addEnabledOption(
	'font', 'big',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 18, 'styles' => array('bold')),
	__('Big', 'time'), __('Custom', 'time'), '',
	array('tag' => 'input[type="submit"].big, input[type="reset"].big, input[type="button"].big, button.big, .button.big',)
);
$button->addEnabledOption(
	'font', 'huge',
	false, array('family' => 'Helvetica, Arial, sans-serif', 'color' => '', 'size' => 22, 'styles' => array('bold')),
	__('Huge', 'time'), __('Custom', 'time'), '',
	array('tag' => 'input[type="submit"].huge, input[type="reset"].huge, input[type="button"].huge, button.huge, .button.huge',)
);

$font->addOption('array', 'custom', array('id' => __('New font', 'time'), 'family' => 'Helvetica, Arial, sans-serif', 'size' => 13, 'line_height' => 150.0), __('Custom', 'time'), '', array('type' => 'custom_font'));

// -----------------------------------------------------------------------------

// Site
$site = $theme_options->addGroup('site', __('Site', 'time'));

$blog = $site->addGroup('blog', __('Blog style', 'time'));
$style = $blog->addOption('group', 'style', 'classic', '', '', array('options' => array(
	'classic' => __('Classic', 'time'),
	'bricks'  => __('Columns', 'time')
)));
$blog->addOption('number', 'columns', 2, '', '', array('min' => 1, 'max' => 8, 'parent' => $style, 'parent_value' => 'bricks', 'indent' => true));
$blog->addOption('boolean', 'filter', false, '', '', array('caption' => __('Display category filter', 'time'), 'parent' => $style, 'parent_value' => 'bricks'));

$image = $site->addGroup('image', __('Images', 'time'));
$image->addOption('group', 'settings', array('hover', 'fancybox'), '', '', array('options' => array(
	'border'   => __('Border', 'time'),
	'hover'    => __('Hover effect', 'time'),
	'fancybox' => __('Open in FancyBox', 'time')
), 'multiple' => true));

$hover_icons = $site->addGroup('hover_icons', __('Image hover effect icons', 'time'), __('Depending on link type.', 'time'));
$hover_icons->addOption('image_group', 'default', 'icon-plus-circled', __('Default', 'time'), '', array('options' => $hover_icons_options));
$hover_icons->addOption('image_group', 'image', 'icon-search', __('Images', 'time'), '', array('options' => $hover_icons_options));
$hover_icons->addOption('image_group', 'mail', 'icon-mail', __('E-mail addresses', 'time'), '', array('options' => $hover_icons_options));
$hover_icons->addOption('image_group', 'title', 'icon-right', __('Links with title', 'time'), '', array('options' => $hover_icons_options));

$slider = $site->addGroup('slider', __('Sliders', 'time'));
$slider->addOption('list', 'animation', 'slide', __('Animation type', 'time'), '', array('options' => array(
	'fade'  => __('Fade', 'time'),
	'slide' => __('Slide', 'time')
)));
$slider->addOption('list', 'direction', 'horizontal', __('Sliding direction', 'time'), '', array('options' => array(
	'horizontal' => __('Horizontal', 'time'),
	'vertical'   => __('Vertical', 'time')
)));
$slider->addOption('number', 'animation_speed', 600, __('Animation speed', 'time'), '', array('unit' => 'ms', 'min' => 0));
$slider->addOption('number', 'slideshow_speed', 7000, __('Exposure time', 'time'), '', array('unit' => 'ms', 'min' => 1000));
$slider->addOption('boolean', 'slideshow', false, '', '', array('caption' => __('Animate slider automatically', 'time')));

$site->addOption('list', 'pagination', 'numbers_navigation', __('Pagination', 'time'), '', array('options' => array(
	'numbers'            => __('Numbers', 'time'),
	'numbers_navigation' => __('Numbers + navigation', 'time')
)));

$site->addOption('list', 'page_pagination', 'numbers', __('Page break pagination', 'time'), '', array('options' => array(
	'numbers'    => __('Numbers', 'time'),
	'navigation' => __('Navigation', 'time')
)));

$comments = $site->addGroup('comments', __('Comments', 'time'));
$comments->addOption('list', 'date_format', 'relative', __('Date', 'time'), __('If you select absolute, you can specify one of methods in Settings / General.', 'time'), array('options' => array(
	''         => __('None', 'time'),
	'relative' => __('Relative', 'time'),
	'absolute' => __('Absolute', 'time')
)));
$comments->addOption('list', 'pagination', 'numbers_navigation', __('Pagination', 'time'), '', array('options' => array(
	'numbers'            => __('Numbers', 'time'),
	'numbers_navigation' => __('Numbers + navigation', 'time')
)));

// -----------------------------------------------------------------------------

// Post
$post = $theme_options->addGroup('post', __('Posts', 'time'));

$post->addOption('boolean', 'hide_icons', false, __('Format posts icons', 'time'), '', array('caption' => __('Hide post format icon', 'time')));

$thumbnail = $post->addGroup('thumbnail', __('Featured image', 'time'));
$thumbnail->addOption('group', 'align', 'left', __('Align', 'time'), '', array('options' => array(
	'left'  => __('Left', 'time'),
	'right' => __('Right', 'time')
)));
$thumbnail->addOption('size', 'size', array('width' => 135, 'height' => 135), __('Size', 'time'));

$post->addOption('text', 'readmore', __('Read more', 'time'), __('Read more phrase', 'time'));

$post->addOption('boolean', 'author_bio', false, __('Author details', 'time'), '', array('caption' => __('Show author details inside post', 'time')));

$meta = $post->addGroup('meta', __('Meta', 'time'));
foreach (array('list' => __('On posts list', 'time'), 'single' => __('Inside post', 'time')) as $name => $label) {
	$group = $meta->addGroup($name, $label);
	$this->addPostMetaOptions($group, true, array('date', 'comments', 'categories'));
}

$social_buttons = $post->addGroup('social_buttons', __('Social buttons', 'time'));
foreach (array('list' => __('On posts list', 'time'), 'single' => __('Inside post', 'time')) as $name => $label) {
	$group = $social_buttons->addGroup($name, $label);
	$this->addSocialButtonsOptions($group, $name == 'single', array('facebook', 'twitter', 'googleplus'));
}

$post->addOption('boolean', 'comments', true, __('Comments', 'time'), '', array('caption' => __('Allow comments', 'time')));

// -----------------------------------------------------------------------------

// Format posts
$format_posts = $theme_options->addGroup('format_posts', __('Format posts', 'time'));

$standard = $format_posts->addGroup('standard', __('Standard post', 'time'));
$standard->addOption('group', 'thumbnail', array('list'), __('Show featured image', 'time'), '', array('options' => array(
	'list'   => __('On posts list', 'time'),
	'single' => __('Inside post', 'time')
), 'multiple' => true));
$standard->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), __('Regular content means everything before the "Read more" tag.', 'time'), array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$aside = $format_posts->addGroup('aside', __('Aside post', 'time'));
$aside->addOption('group', 'thumbnail', array('list'), __('Show featured image', 'time'), '', array('options' => array(
	'list'   => __('On posts list', 'time'),
	'single' => __('Inside post', 'time')
), 'multiple' => true));
$aside->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$audio = $format_posts->addGroup('audio', __('Audio post', 'time'));
$audio->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$chat = $format_posts->addGroup('chat', __('Chat post', 'time'));
$chat->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$gallery = $format_posts->addGroup('gallery', __('Gallery post', 'time'));
$gallery->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$image = $format_posts->addGroup('image', __('Image post', 'time'));
$image->addOption('group', 'thumbnail', array('list', 'single'), __('Show featured image', 'time'), '', array('options' => array(
	'list'   => __('On posts list', 'time'),
	'single' => __('Inside post', 'time')
), 'multiple' => true));
$image->addOption('group', 'link', 'fancybox', __('Featured image click action', 'time'), __('Click action refers to posts list only. Inside posts, clicked featured images always open in Fancybox window.', 'time'), array('options' => array(
	'post'     => __('Go to post', 'time'),
	'fancybox' => __('Open image in Fancybox', 'time')
)));
$image->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$link = $format_posts->addGroup('link', __('Link post', 'time'));
$link->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$quote = $format_posts->addGroup('quote', __('Quote post', 'time'));
$quote->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$status = $format_posts->addGroup('status', __('Status post', 'time'));
$status->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

$video = $format_posts->addGroup('video', __('Video post', 'time'));
$video->addOption('group', 'content', 'excerpt_content', __('Content on posts list', 'time'), '', array('options' => array(
	'content'         => __('Regular content', 'time'),
	'excerpt_content' => __('Excerpt or regular content', 'time'),
	'excerpt'         => __('Excerpt', 'time'),
	''                => __('None', 'time')
)));

// -----------------------------------------------------------------------------

// Page
$page = $theme_options->addGroup('page', __('Pages', 'time'));

$page->addOption('boolean', 'hide_title', false, __('Title', 'time'), '', array('caption' => __('Hide page title in content area', 'time')));

$page->addOption('boolean', 'author_bio', false, __('Author details', 'time'), '', array('caption' => __('Show author details', 'time')));

$meta = $page->addGroup('meta', __('Meta', 'time'));
$this->addMetaOptions($meta, false, array('author', 'permalink'));

$social_buttons = $page->addGroup('social_buttons', __('Social buttons', 'time'));
$this->addSocialButtonsOptions($social_buttons, true, array('facebook', 'twitter', 'googleplus'));

$page->addOption('boolean', 'comments', true, __('Comments', 'time'), '', array('caption' => __('Allow comments', 'time')));

// -----------------------------------------------------------------------------

// Attachment
$attachment = $theme_options->addGroup('attachment', __('Attachment pages', 'time'));

$attachment->addOption('boolean', 'author_bio', false, __('Author details', 'time'), '', array('caption' => __('Show author details', 'time')));

$meta = $attachment->addGroup('meta', __('Meta', 'time'));
$this->addMetaOptions($meta, false, array('date_time', 'permalink'));

$social_buttons = $attachment->addGroup('social_buttons', __('Social buttons', 'time'));
$this->addSocialButtonsOptions($social_buttons, false, array('facebook', 'twitter', 'googleplus'));

$attachment->addOption('boolean', 'comments', false, __('Comments', 'time'), '', array('caption' => __('Allow comments', 'time')));

// -----------------------------------------------------------------------------

// Gallery
$gallery = $theme_options->addGroup('gallery', __('Galleries', 'time'));

$gallery->addOption('codeline', 'slug', 'gallery', __('Slug', 'time'), __('For the changes to take effect, go to Settings/Permalinks.', 'time'), array('required' => true));

$gallery->addOption('boolean', 'author_bio', false, __('Author details', 'time'), '', array('caption' => __('Show author details', 'time')));

$meta = $gallery->addGroup('meta', __('Meta', 'time'));
$this->addMetaOptions($meta, false, array('author', 'permalink'));

$social_buttons = $gallery->addGroup('social_buttons', __('Social buttons', 'time'));
$this->addSocialButtonsOptions($social_buttons, true, array('facebook', 'twitter', 'googleplus'));

$gallery->addOption('boolean', 'comments', true, __('Comments', 'time'), '', array('caption' => __('Allow comments', 'time')));

// -----------------------------------------------------------------------------

// Portfolio
$portfolio = $theme_options->addGroup('portfolio', __('Portfolios', 'time'));

$portfolio->addOption('codeline', 'slug', 'portfolio', __('Slug', 'time'), __('For the changes to take effect, go to Settings/Permalinks.', 'time'), array('required' => true));

$portfolio->addOption('boolean', 'author_bio', false, __('Author details', 'time'), '', array('caption' => __('Show author details', 'time')));

$meta = $portfolio->addGroup('meta', __('Meta', 'time'));
$this->addPostMetaOptions($meta, true, array('tags'));

$social_buttons = $portfolio->addGroup('social_buttons', __('Social buttons', 'time'));
$this->addSocialButtonsOptions($social_buttons, true, array('facebook', 'twitter', 'googleplus'));

$portfolio->addOption('boolean', 'comments', true, __('Comments', 'time'), '', array('caption' => __('Allow comments', 'time')));

$default = $portfolio->addGroup('default', __('Default layout', 'time'));
$default->addOption('list', 'columns', 4, __('Number of columns', 'time'), '', array('options' => array(
	'1'  => __('One column', 'time'),
	'1+' => __('One+ column', 'time'),
	'2'  => __('Two columns', 'time'),
	'3'  => __('Three columns', 'time'),
	'4'  => __('Four columns', 'time')
)));
$default->addOption('list', 'filter', 'category', __('Filter by', 'time'), '', array('options' => array(
	''         => __('None', 'time'),
	'category' => __('Categories', 'time'),
	'tag'      => __('Tags', 'time')
)));
$default->addOption('list', 'orderby', 'date', __('Sort by', 'time'), '', array('options' => array(
	'title'         => __('Title', 'time'),
	'date'          => __('Date', 'time'),
	'modified'      => __('Modified date', 'time'),
	'comment_count' => __('Comment count', 'time'),
	'rand'          => __('Random order', 'time'),
	'menu_order'    => __('Custom order', 'time')
)));
$default->addOption('list', 'order', 'desc', __('Sort order', 'time'), '', array('options' => array(
	'asc'  => __('Ascending', 'time'),
	'desc' => __('Descending', 'time')
)));
$limit = $default->addGroup('limit', __('Limit', 'time'));
$enabled = $limit->addOption('boolean', 'enabled', false, '', '', array('caption' => __('Enabled', 'time')));
$limit->addOption('number', 'limit', 10, '', '', array('indent' => true, 'parent' => $enabled, 'min' => 1, 'max' => 100));
$default->addOption('boolean', 'pagination', true, __('Pagination', 'time'), '', array('caption' => __('Show paginate links', 'time'), 'parent' => $enabled));
$default->addOption('boolean', 'title', true, __('Title', 'time'), '', array('caption' => __('Show titles', 'time')));
$default->addOption('boolean', 'excerpt', true, __('Excerpt', 'time'), '', array('caption' => __('Show excerpts', 'time')));
$taxonomy = $default->addGroup('taxonomy', __('Taxonomy', 'time'));
$visible = $taxonomy->addOption('boolean', 'visible', true, '', '', array('caption' => __('Show taxonomies', 'time')));
$taxonomy->addOption('list', 'taxonomy', 'tag', '', '', array('indent' => true, 'parent' => $visible, 'options' => array(
	'category' => __('Categories', 'time'),
	'tag'      => __('Tags', 'time')
)));

$archive = $portfolio->addGroup('archive', __('Default archive layout', 'time'));
$archive->addOption('number', 'count', 10, __('Number of items per page', 'time'), '', array('min' => 1));
$archive->addOption('number', 'columns', 4, __('Number of columns', 'time'), '', array('min' => 1, 'max' => 4));
$archive->addOption('boolean', 'title', true, __('Title', 'time'), '', array('caption' => __('Show title', 'time')));
$archive->addOption('boolean', 'excerpt', true, __('Excerpt', 'time'), '', array('caption' => __('Show excerpt', 'time')));
$taxonomy = $archive->addGroup('taxonomy', __('Taxonomy', 'time'));
$visible = $taxonomy->addOption('boolean', 'visible', true, '', '', array('caption' => __('Show taxonomies', 'time')));
$taxonomy->addOption('list', 'taxonomy', 'tag', '', '', array('indent' => true, 'parent' => $visible, 'options' => array(
	'category' => __('Categories', 'time'),
	'tag'      => __('Tags', 'time')
)));

// -----------------------------------------------------------------------------

// bbPress
if (Time::$plugins['bbpress']) {

	$bbpress = $theme_options->addGroup('bbpress', __('bbPress', 'time'));

	$bbpress->addOption('boolean', 'breadcrumbs', Time::$plugins['breadcrumbs'], __('Breadcrumbs', 'time'), '', array('caption' => __('Use bbPress breadcrumbs', 'time'), 'disabled' => !Time::$plugins['breadcrumbs']));

}

// -----------------------------------------------------------------------------

// WooCommerce
if (Time::$plugins['woocommerce']) {

	$woocommerce = $theme_options->addGroup('woocommerce', __('WooCommerce', 'time'));

	$woocommerce->addOption('boolean', 'breadcrumbs', Time::$plugins['breadcrumbs'], __('Breadcrumbs', 'time'), '', array('caption' => __('Use WooCommerce breadcrumbs', 'time'), 'disabled' => !Time::$plugins['breadcrumbs']));

	$shop = $woocommerce->addGroup('shop', __('Shop', 'time'));
	$shop->addOption('number', 'columns', 4, __('Columns', 'time'), '', array('min' => 1, 'max' => 8));
	$shop->addOption('number', 'per_page', 8, __('Products per page', 'time'), '', array('min' => 1));
	$shop->addOption('list', 'pagination', 'numbers_navigation', __('Pagination', 'time'), '', array('options' => array(
		'numbers'            => __('Numbers', 'time'),
		'numbers_navigation' => __('Numbers + navigation', 'time')
	)));
	$shop->addOption('list', 'image_hover', 'image', __('Images hover effect', 'time'), '', array('options' => array(
		'inherit'   => __('Inherit', 'time'),
		''          => __('None', 'time'),
		'zoom'      => __('Default', 'time'),
		'grayscale' => __('Grayscale', 'time'),
		'image'     => __('Second gallery image', 'time')
	)));

	$product = $woocommerce->addGroup('product', __('Product', 'time'));
	$product->addOption('list', 'image_size', '12_12', __('Image &amp; gallery width', 'time'), '', array('options' => array(
		'14_34' => '25%',
		'12_12' => '50%',
		'34_14' => '75%'
	)));
	$product->addOption('number', 'thumbnails_columns', 3, __('Gallery thumbnails columns', 'time'), '', array('min' => 1, 'max' => 6));
	$product->addOption('group', 'captions', 'title', __('Gallery captions', 'time'), '', array('options' => array(
		''              => __('None', 'time'),
		'title'         => __('Image title', 'time'),
		'caption'       => __('Image caption', 'time'),
		'caption_title' => __('Image caption or title', 'time')
	)));
	$product->addOption('boolean', 'brands', Time::$plugins['woocommerce-brands'], __('Brand', 'time'), '', array('caption' => __('Show brand description', 'time'), 'disabled' => !Time::$plugins['woocommerce-brands']));
	$meta = $product->addGroup('meta', __('Meta', 'time'));
	$visible = $meta->addOption('boolean', 'visible', true, '', '', array('caption' => __('Visible', 'time')));
	$meta->addOption('group', 'items', Time::$plugins['woocommerce-brands'] ? array('sku', 'categories', 'tags', 'brands') : array('sku', 'categories', 'tags'), '', '', array('options' => array(
		'sku'        => __('SKU', 'time'),
		'categories' => __('Categories', 'time'),
		'tags'       => __('Tags', 'time'),
		'brands'     => __('Brands', 'time'),
	), 'disabled' => Time::$plugins['woocommerce-brands'] ? array() : array('brands'), 'indent' => true, 'multiple' => true, 'sortable' => true, 'parent' => $visible));

	$cart = $woocommerce->addGroup('cart', __('Cart icon', 'time'));
	$cart->addOption('image_group', 'icon', 'icon-cart', __('Image', 'time'), '', array('options' => $cart_icons_options));
	$cart->addOption('color', 'color', '', __('Color', 'time'), '', array('required' => false, 'on_html' => create_function(
		'$option, &$html',
		'$html->style("width: 70px;")->placeholder("'.__('default', $this->theme_name).'");'
	)));
	$cart->addOption('color', 'hover', '', __('Hover color', 'time'), '', array('required' => false, 'on_html' => create_function(
		'$option, &$html',
		'$html->style("width: 70px;")->placeholder("'.__('leading', $this->theme_name).'");'
	)));

	$onsale = $woocommerce->addGroup('onsale', __('Sale label style', 'time'));
	$custom = $onsale->addOption('boolean', 'custom', false, '', '', array('caption' => __('Custom', 'time')));
	$onsale->addOption('color', 'background', '#0587e1', __('Background', 'time'), '', array('parent' => $custom));
	$onsale->addOption('color', 'color', '#ffffff', __('Color', 'time'), '', array('parent' => $custom));

}

// -----------------------------------------------------------------------------

// Not found
$not_found = $theme_options->addGroup('not_found', __('404 page', 'time'));

require TEMPLATEPATH.'/inc/not-found-content-default.php';
$not_found->addOption('editor', 'content', $default, __('Content', 'time'));

// -----------------------------------------------------------------------------

// Contact form
$this->addThemeFeature('option-contact-form');

// -----------------------------------------------------------------------------

// Advanced
$advanced = $theme_options->addGroup('advanced', __('Advanced', 'time'));

$this->addThemeFeature('option-custom-css');

$this->addThemeFeature('option-custom-js');

// -----------------------------------------------------------------------------

// Other
$other = $theme_options->addGroup('other', __('Other', 'time'));

$this->addThemeFeature('option-tracking-code');

$this->addThemeFeature('option-feed-url');

$this->addThemeFeature('option-ogp');

// todo: usunac
/*
DroneOptionsConditionalTagOption::$custom_groups = array('General' => array('test'));
DroneOptionsConditionalTagOption::$custom_tags = array('test' => 'Test');
$other->addOption('conditional_tag', 'test_tag', 'front_page', 'Show other on');
$other->addOption('conditional_tag_array', 'test_tags', '', 'Other conf.', '', array('type' => 'text'));
$other->addOption('custom', 'custom', array('aaa' => 0, 'bbb' => ''), 'Custom', '', array('options' => array(
	'aaa' => 'number',
	'bbb' => 'image'
)));
*/

// -----------------------------------------------------------------------------

// Post options
$post_options = $this->getPostOptions('post');

$layout = $post_options->addGroup('layout', __('Layout', 'time'));
$this->addLayoutOptions($layout, $sidebar_options, $nav_menus);

// -----------------------------------------------------------------------------

// Page options
$page_options = $this->getPostOptions('page');

$layout = $page_options->addGroup('layout', __('Layout', 'time'));
$this->addLayoutOptions($layout, $sidebar_options, $nav_menus);
$layout->child('page')->addOption('list', 'hide_title', 'inherit', __('Title', 'time'), '', array('options' => array(
	'inherit' => __('Inherit', 'time'),
	''        => __('Show', 'time'),
	'true'    => __('Hide', 'time')  // it's correct
)), 'author_bio');

// -----------------------------------------------------------------------------

// Gallery options
$gallery_options = $this->getPostOptions('gallery');

$layout = $gallery_options->addGroup('layout', __('Layout', 'time'));
$this->addLayoutOptions($layout, $sidebar_options, $nav_menus);

// -----------------------------------------------------------------------------

// Portfolio options
$portfolio_options = $this->getPostOptions('portfolio');

$layout = $portfolio_options->addGroup('layout', __('Layout', 'time'));
$this->addLayoutOptions($layout, $sidebar_options, $nav_menus);

// -----------------------------------------------------------------------------

// WooCommerce product options
if (Time::$plugins['woocommerce']) {

	$product_options = $this->getPostOptions('product');

	$layout = $product_options->addGroup('layout', __('Layout', 'time'));
	$this->addLayoutOptions($layout, $sidebar_options, $nav_menus);
	$layout->deleteChild('page');

}