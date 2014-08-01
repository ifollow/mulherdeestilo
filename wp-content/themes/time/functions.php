<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// -----------------------------------------------------------------------------

require TEMPLATEPATH.'/drone/drone.php'; // 4.1.1
require TEMPLATEPATH.'/inc/time-options.php';
require TEMPLATEPATH.'/inc/class-tgm-plugin-activation.php'; // d393632b4626cd2b08e1e62e8ad11fce5ba86306

// -----------------------------------------------------------------------------

class Time extends DroneTheme
{

	// -------------------------------------------------------------------------

	const DEFAULT_SIDEBAR_WIDTH        = 240;
	const LAYERSLIDER_VERSION          = '4.6.5';
	const LAYERSLIDER_REQUIRED_VERSION = '4.6.5';
	const WILD_GOOGLEMAP_VERSION       = '1.9.3';
	const WPML_REFERRAL_URL            = 'http://wpml.org/?aid=25858&affiliate_key=H0NWEUimxymp';

	// -------------------------------------------------------------------------

	public static $plugins = array(
		'bbpress'            => false,
		'captcha'            => false,
		'disqus'             => false,
		'layerslider'        => false,
		'woocommerce'        => false,
		'woocommerce-brands' => false,
		'wpml'               => false,
		'wpseo'              => false,
		'breadcrumbs'        => false
	);
	public static $headline_used    = false;
	public static $gallery_instance = 0;
	public static $conditional_tags_array;
	public static $conditional_tags_default_array;
	public static $sliders_array;
	public static $sidebars_array;
	public static $post_formats_icons = array(
		'aside'   => 'doc-text',
		'audio'   => 'mic',
		'chat'    => 'chat',
		'gallery' => 'picture',
		'image'   => 'camera',
		'link'    => 'link',
		'quote'   => 'quote',
		'status'  => 'comment',
		'video'   => 'video'
	);

	// -------------------------------------------------------------------------

	/**
	 * Get banner type options
	 *
	 * @since 1.0
	 *
	 * @param  array|string $exclude
	 * @return array
	 */
	protected function getBannerTypeOption($exclude = array())
	{
		$exclude = (array)$exclude;
		if (!Time::$plugins['layerslider']) {
			$exclude[] = 'layerslider';
		}
		$options = array(
			'inherit'     => __('Inherit', 'time'),
			''            => __('None', 'time'),
			'empty'       => __('Empty space', 'time'),
			'image'       => __('Image', 'time'),
			'thumbnail'   => __('Featured image', 'time'),
			'layerslider' => __('LayerSlider', 'time'),
			'custom'      => __('Custom', 'time')
		);
		return array_diff_key($options, array_flip(array_unique($exclude)));
	}

	// -------------------------------------------------------------------------

	/**
	 * Add meta options
	 *
	 * @since 1.0
	 *
	 * @param  object $group
	 * @param  bool   $default_visible
	 * @param  array  $default_items
	 * @return object
	 */
	protected function addMetaOptions($group, $default_visible, $default_items)
	{
		$visible = $group->addOption('boolean', 'visible', $default_visible, '', '', array('caption' => __('Visible', 'time')));
		return $group->addOption('group', 'items', $default_items, '', '', array('options' => array(
			'date_time'  => __('Date &amp time', 'time'),
			'date'       => __('Date', 'time'),
			'mod_date'   => __('Modification date', 'time'),
			'time_diff'  => __('Relative time', 'time'),
			'comments'   => __('Comments number', 'time'),
			'author'     => __('Author', 'time'),
			'permalink'  => __('Permalink', 'time'),
			'edit_link'  => __('Edit link', 'time')
		), 'indent' => true, 'multiple' => true, 'sortable' => true, 'parent' => $visible));
	}

	// -------------------------------------------------------------------------

	/**
	 * Add post meta options
	 *
	 * @since 1.0
	 *
	 * @param  object $group
	 * @param  bool   $default_visible
	 * @param  array  $default_items
	 * @return object
	 */
	protected function addPostMetaOptions($group, $default_visible, $default_items)
	{
		$visible = $group->addOption('boolean', 'visible', $default_visible, '', '', array('caption' => __('Visible', 'time')));
		return $group->addOption('group', 'items', $default_items, '', '', array('options' => array(
			'date_time'  => __('Date &amp time', 'time'),
			'date'       => __('Date', 'time'),
			'mod_date'   => __('Modification date', 'time'),
			'time_diff'  => __('Relative time', 'time'),
			'comments'   => __('Comments number', 'time'),
			'categories' => __('Categories', 'time'),
			'tags'       => __('Tags', 'time'),
			'author'     => __('Author', 'time'),
			'permalink'  => __('Permalink', 'time'),
			'edit_link'  => __('Edit link', 'time')
		), 'indent' => true, 'multiple' => true, 'sortable' => true, 'parent' => $visible));
	}

	// -------------------------------------------------------------------------

	/**
	 * Add social buttons options
	 *
	 * @since 1.0
	 *
	 * @param  object $group
	 * @param  bool   $default_visible
	 * @param  array  $default_items
	 * @return object
	 */
	protected function addSocialButtonsOptions($group, $default_visible, $default_items)
	{
		$visible = $group->addOption('boolean', 'visible', $default_visible, '', '', array('caption' => __('Visible', 'time')));
		return $group->addOption('group', 'items', $default_items, '', '', array('options' => array(
			'facebook'   => __('Facebook', 'time'),
			'twitter'    => __('Twitter', 'time'),
			'googleplus' => __('Google+', 'time'),
			'linkedin'   => __('LinkedIn', 'time'),
			'pinterest'  => __('Pinterest', 'time')
		), 'indent' => true, 'multiple' => true, 'sortable' => true, 'parent' => $visible));
	}

	// -------------------------------------------------------------------------

	/**
	 * Add layout options
	 *
	 * @since 1.0
	 *
	 * @param object $group
	 * @param array  $sidebar_options
	 */
	protected function addLayoutOptions($group, &$sidebar_options, &$nav_menus)
	{

		$background = $group->addGroup('background', __('Background', 'time'));
		$enabled = $background->addOption('boolean', 'enabled', false, '', '', array('caption' => __('Custom', 'time')));
		$background->addOption('background', 'background', Time::to_('general/background/background')->default, '', '', array('indent' => true, 'parent' => $enabled));

		$sidebar = $group->addGroup('sidebar', __('Sidebar', 'time'));
		$enabled = $sidebar->addOption('boolean', 'enabled', false, '', '', array('caption' => __('Custom', 'time')));
		$sidebar->addOption('sidebar', 'sidebar', Time::to('sidebar/layout/default/sidebar'), '', '', array('options' => $sidebar_options, 'indent' => true, 'parent' => $enabled));

		$nav = $group->addGroup('nav_secondary', __('Secondary menu', 'time'));
		$nav->addOption('list', 'upper', 'inherit', __('Upper', 'time'), '', array('options' => array(
			'inherit' => __('Inherit', 'time'),
			'true'    => __('Show', 'time'),
			''        => __('Hide', 'time')
		)+$nav_menus, 'groups' => array(
			__('Custom menu', 'time') => array_keys($nav_menus)
		)));
		$nav->addOption('list', 'lower', 'inherit', __('Lower', 'time'), '', array('options' => array(
			'inherit' => __('Inherit', 'time'),
			'true'    => __('Show', 'time'),
			''        => __('Hide', 'time')
		)+$nav_menus, 'groups' => array(
			__('Custom menu', 'time') => array_keys($nav_menus)
		)));

		$banner = $group->addGroup('banner', __('Banner', 'time'));
		$type = $banner->addOption('list', 'type', 'inherit', '', '', array('options' => $this->getBannerTypeOption()));
		$banner->addOption('number', 'height', 200, '', '', array('unit' => 'px', 'min' => 0, 'indent' => true, 'parent' => $type, 'parent_value' => 'empty'));
		$banner->addOption('attachment', 'image', 0, '', '', array('indent' => true, 'parent' => $type, 'parent_value' => 'image'));
		if (Time::$plugins['layerslider']) {
			$banner->addOption('list', 'layerslider', 0, '', '', array('options' => Time::$sliders_array, 'indent' => true, 'parent' => $type, 'parent_value' => 'layerslider'));
		}
		$banner->addOption('editor', 'custom', '', '', '', array('indent' => true, 'parent' => $type, 'parent_value' => 'custom'));

		$group->addOption('list', 'headline', 'inherit', __('Headline', 'time'), '', array('options' => array(
			'inherit' => __('Inherit', 'time'),
			'true'    => __('Show', 'time'),
			''        => __('Hide', 'time')
		)));

		$page = $group->addGroup('page', __('Page', 'time'));
		$page->addOption('list', 'author_bio', 'inherit', __('Author details', 'time'), '', array('options' => array(
			'inherit' => __('Inherit', 'time'),
			'true'    => __('Show', 'time'),
			''        => __('Hide', 'time')
		)));
		$page->addOption('list', 'meta', 'inherit', __('Meta', 'time'), '', array('options' => array(
			'inherit' => __('Inherit', 'time'),
			'true'    => __('Show', 'time'),
			''        => __('Hide', 'time')
		)));
		$page->addOption('list', 'social_buttons', 'inherit', __('Social buttons', 'time'), '', array('options' => array(
			'inherit' => __('Inherit', 'time'),
			'true'    => __('Show', 'time'),
			''        => __('Hide', 'time')
		)));

	}

	// -------------------------------------------------------------------------

	/**
	 * Get attachment link URL
	 *
	 * $link - post, file, none
	 *
	 * @since 1.0
	 *
	 * @param  object $attachment
	 * @param  string $link
	 * @return string
	 */
	protected function getAttachmentLinkURL($attachment, $link = 'post')
	{
		if ($attachment->post_content && preg_match('#((https?://|mailto:).+)(\b|["\'])#i', $attachment->post_content, $matches)) {
			return $matches[1];
		} else if ($link == 'post') {
			return get_attachment_link($attachment->ID);
		} else if ($link == 'file') {
			list($src) = wp_get_attachment_image_src($attachment->ID, '');
			return $src;
		} else {
			return '';
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Cart menu item
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	protected function getCartMenuitem()
	{
		global $woocommerce;
		$a = DroneHTML::make('a')
			->href($woocommerce->cart->get_cart_url())
			->title(__('Cart', 'time'))
			->add();
		$last_item = '';
		$before = '';
		foreach (Time::to('header/cart/content') as $item) {
			$before = $last_item && $last_item != 'icon' ? '&nbsp;&nbsp;' : '';
			$last_item = $item;
			switch ($item) {
				case 'icon':
					$a->add($this->shortcodeIcon(array('name' => Time::to('woocommerce/cart/icon'), 'size' => '1.2em', 'class' => 'icon-woocommerce-cart', 'style' => 'margin: 0 0 0 -0.4em;')));
					break;
				case 'phrase':
					$a->add($before, __('Cart', 'time'));
					break;
				case 'count':
					if ($woocommerce->cart->cart_contents_count > 0) {
						$a->add($before)->addNew('mark')->add($woocommerce->cart->cart_contents_count);
					}
					break;
				case 'total':
					if ($woocommerce->cart->cart_contents_count > 0) {
						$a->add($before)->addNew('mark')->add($woocommerce->cart->get_cart_total());
					}
					break;
			}
		}
		return $a->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Language menu item
	 *
	 * @since 1.0
	 *
	 * @param  array $lang
	 * @return string
	 */
	protected function getLanguageMenuitem($lang)
	{
		if (file_exists($this->template_dir.'/data/img/icons/flags/'.$lang['language_code'].'.png')) {
			$icon = Time::shortcodeIcon(array('name' => 'flags/'.$lang['language_code']));
		} else {
			$icon = '';
		}
		return DroneHTML::make('a')
			->href($lang['url'])
			->title($lang['native_name'])
			->add($icon, $lang['native_name'])
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Theme load
	 *
	 * @since 1.0
	 * @see DroneTheme::onLoad()
	 */
	protected function onLoad()
	{

		// Plugins
		Time::$plugins = array(
			'bbpress'            => function_exists('bbp_version'),
			'captcha'            => function_exists('cptch_comment_form_wp3'),
			'disqus'             => defined('DISQUS_VERSION'),
			'layerslider'        => defined('LS_TIME_EDITION') && defined('LS_PLUGIN_VERSION') && LS_TIME_EDITION && version_compare(LS_PLUGIN_VERSION, Time::LAYERSLIDER_REQUIRED_VERSION) >= 0,
			'woocommerce'        => defined('WOOCOMMERCE_VERSION'), // class_exists('Woocommerce')
			'woocommerce-brands' => class_exists('WC_Brands'),
			'wpml'               => function_exists('icl_get_languages'),
			'wpseo'              => defined('WPSEO_VERSION'),
			'breadcrumbs'        => function_exists('bcn_display') || function_exists('breadcrumb_trail') || function_exists('yoast_breadcrumb') || defined('WPSEO_VERSION')
		);

		// Conditional tags
		$custom = array(array(), array());
		if (Time::$plugins['bbpress']) {
			$custom[0]['bbpress'] = __('bbPress pages', 'time');
			$custom[1]['bbpress'] = __('On bbPress pages', 'time');
		}
		if (Time::$plugins['woocommerce']) {
			$custom[0]['product']                       = __('WooCommerce product', 'time');
			$custom[0]['shop,product_taxonomy,product'] = __('WooCommerce shop', 'time');
			$custom[0]['cart']                          = __('WooCommerce cart', 'time');
			$custom[0]['checkout']                      = __('WooCommerce checkout', 'time');
			$custom[0]['order_received_page']           = __('WooCommerce order received', 'time');
			$custom[0]['account_page']                  = __('WooCommerce account', 'time');
			$custom[1]['product']                       = __('On WooCommerce product', 'time');
			$custom[1]['shop,product_taxonomy,product'] = __('On WooCommerce shop', 'time');
			$custom[1]['cart']                          = __('On WooCommerce cart', 'time');
			$custom[1]['checkout']                      = __('On WooCommerce checkout', 'time');
			$custom[1]['order_received_page']           = __('On WooCommerce order received', 'time');
			$custom[1]['account_page']                  = __('On WooCommerce account', 'time');
			$custom[0]['shop,product_taxonomy,product,cart,checkout,order_received_page,account_page'] = __('WooCommerce pages', 'time');
			$custom[1]['shop,product_taxonomy,product,cart,checkout,order_received_page,account_page'] = __('On WooCommerce pages', 'time');
		}
		foreach (DroneFunc::wpTermsList('category', array('hide_empty' => false)) as $id => $name) {
			$custom[0]["category({$id})"] = sprintf(__('%s category', 'time'), $name);
			$custom[1]["category({$id})"] = sprintf(__('On %s category', 'time'), $name);
		}

		Time::$conditional_tags_array = array(

			array(
				'front_page'           => __('Front page', 'time')
			)+
			$custom[0]+
			array(
				'home,archive'         => __('Blog / archive', 'time'),
				'search'               => __('Search results page', 'time'),
				'singular(post)'       => __('Posts', 'time'),
				'page'                 => __('Pages', 'time'),
				'singular(attachment)' => __('Attachments', 'time'),
				'singular(gallery)'    => __('Galleries', 'time'),
				'singular(portfolio)'  => __('Portfolios', 'time'),
				'404,404'              => __('Not found page (404)', 'time')
			),

			array(
				'front_page'           => __('On front page', 'time')
			)+
			$custom[1]+
			array(
				'home,archive'         => __('On blog / archive', 'time'),
				'search'               => __('On search results page', 'time'),
				'singular(post)'       => __('On posts', 'time'),
				'page'                 => __('On pages', 'time'),
				'singular(attachment)' => __('On attachments', 'time'),
				'singular(gallery)'    => __('On galleries', 'time'),
				'singular(portfolio)'  => __('On portfolios', 'time'),
				'404,404'              => __('On not found page (404)', 'time')
			)

		);

		Time::$conditional_tags_default_array = array_map(
			create_function('$s', 'return (string)$s;'),
			array_keys(Time::$conditional_tags_array[0])
		);

		// Sliders
		Time::$sliders_array = array();
		if (Time::$plugins['layerslider']) {
			foreach (lsSliders(999) as $slider) {
				Time::$sliders_array[$slider['id']] = $slider['name'];
			}
		}
		Time::$sliders_array = apply_filters('time_sliders', Time::$sliders_array);

		// Sidebars
		Time::$sidebars_array = apply_filters('time_sidebars', array(
			'alpha'   => __('Alpha', 'time'),
			'beta'    => __('Beta', 'time'),
			'gamma'   => __('Gamma', 'time'),
			'delta'   => __('Delta', 'time'),
			'epsilon' => __('Epsilon', 'time'),
			'zeta'    => __('Zeta', 'time'),
			'eta'     => __('Eta', 'time'),
			'theta'   => __('Theta', 'time'),
			'iota'    => __('Iota', 'time'),
			'kappa'   => __('Kappa', 'time'),
			'lambda'  => __('Lambda', 'time'),
			'mu'      => __('Mu', 'time'),
			'nu'      => __('Nu', 'time'),
			'xi'      => __('Xi', 'time'),
			'omicron' => __('Omicron', 'time'),
			'pi'      => __('Pi', 'time'),
			'rho'     => __('Rho', 'time'),
			'sigma'   => __('Sigma', 'time'),
			'tau'     => __('Tau', 'time'),
			'upsilon' => __('Upsilon', 'time'),
			'phi'     => __('Phi', 'time'),
			'chi'     => __('Chi', 'time'),
			'psi'     => __('Psi', 'time'),
			'omega'   => __('Omega', 'time')
		));

		// Post formats icons
		Time::$post_formats_icons = apply_filters('time_post_formats_icons', Time::$post_formats_icons);

	}

	// -------------------------------------------------------------------------

	/**
	 * Options setup
	 *
	 * @since 1.0
	 */
	protected function onSetupOptions($theme_options)
	{
		require TEMPLATEPATH.'/inc/options.php';
	}

	// -------------------------------------------------------------------------

	/**
	 * Theme options compatybility
	 *
	 * @since 1.2
	 *
	 * @param array  $data
	 * @param string $version
	 */
	public function onThemeOptionsCompatybility(&$data, $version)
	{

		// 1.2
		if (version_compare($version, '1.2') < 0) {
			foreach (array('standard', 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video') as $format) {
				if (isset($data['format_posts'][$format]['content']) && $data['format_posts'][$format]['content'] == 'excerpt') {
					$data['format_posts'][$format]['content'] = 'excerpt_content';
				}
			}
		}

		// 2.1
		if (version_compare($version, '2.1') < 0) {
			if (isset($data['banner']['content']['404'])) {
				$data['banner']['content']['404,404'] = $data['banner']['content']['404'];
			}
			if (isset($data['nav']['secondary']['upper']) && in_array('404', $data['nav']['secondary']['upper'])) {
				$data['nav']['secondary']['upper'][] = '404,404';
			}
			if (isset($data['nav']['secondary']['lower']) && in_array('404', $data['nav']['secondary']['lower'])) {
				$data['nav']['secondary']['lower'][] = '404,404';
			}
			if (isset($data['nav']['headline']['visible']) && in_array('404', $data['nav']['headline']['visible'])) {
				$data['nav']['headline']['visible'][] = '404,404';
			}
		}

		// 2.2
		if (version_compare($version, '2.2') < 0) {
			if (isset($data['header']['search']) && is_bool($data['header']['search'])) {
				$data['header']['search'] = $data['header']['search'] ? array('desktop', 'mobile') : array();
			}
		}

	}

	// -------------------------------------------------------------------------

	/**
	 * Theme setup
	 *
	 * @since 1.0
	 * @see DroneTheme::onSetupTheme()
	 */
	protected function onSetupTheme()
	{

		// Theme features
		$this->addThemeFeature('nav-menu-current-item');

		// Editor style
		add_editor_style('data/css/wordpress-editor.css');

		// Menus
		register_nav_menus(array(
			'primary-desktop' => __('Desktop primary menu', 'time'),
			'primary-mobile'  => __('Mobile primary menu', 'time'),
			'secondary-upper' => __('Upper secondary menu', 'time'),
			'secondary-lower' => __('Lower secondary menu', 'time')
		));

		// Sidebars
		foreach (Time::$sidebars_array as $id => $name) {
			register_sidebar(array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<section class="section"><div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h2 class="title">',
				'after_title'   => '</h2>'
			));
		}

		for ($i = 0; $i < count(Time::getFooterLayoutClasses()); $i++) {
			register_sidebar(array(
				'name'          => sprintf(__('Footer column %d', 'time'), $i+1),
				'id'            => 'footer-'.$i,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="title">',
				'after_title'   => '</h2>'
			));
		}

		// Widgets
		$this->addThemeFeature('widget-unwrapped-text');
		$this->addThemeFeature('widget-page');
		$this->addThemeFeature('widget-posts-list', array(
			'on_setup_options' => array($this, 'callbackPostsListOnSetupOptions'),
			'on_html'          => array($this, 'callbackPostsListOnHTML'),
			'on_post'          => array($this, 'callbackPostsListOnPost')
		));
		$this->addThemeFeature('widget-twitter', array(
			'on_setup_options' => array($this, 'callbackTwitterOnSetupOptions'),
			'on_html'          => array($this, 'callbackTwitterOnHTML'),
			'on_tweet'         => array($this, 'callbackTwitterOnTweet')
		));
		$this->addThemeFeature('widget-flickr', array(
			'on_html'  => array($this, 'callbackFlickrOnHTML'),
			'on_photo' => array($this, 'callbackFlickrOnPhoto')
		));

		// Images
		add_theme_support('post-thumbnails');

		$max_width      = Time::to('general/max_width');
		$thumbnail_size = Time::to('post/thumbnail/size');

		add_image_size('post-thumbnail', $thumbnail_size['width'], $thumbnail_size['height'], true);
		add_image_size('post-thumbnail-mini', 48, 48, true);
		add_image_size('logo', 9999, 60, false);
		add_image_size('banner', $max_width, 9999, false);
		add_image_size('full-hd', 1920, 1080, false);
		add_image_size('ls-thumbnail', 100, 63, true);

		foreach (array(1 => 748, 2 => 748, 4 => 364) as $columns => $mobile_width) {
			$column_sizes[$columns] = max(round(($max_width-40-20*($columns-1)) / $columns), $mobile_width);
		}
		add_image_size('full-width',   $column_sizes[1], 9999, false);
		add_image_size('medium-width', $column_sizes[2], 9999, false);
		add_image_size('small-width',  $column_sizes[4], 9999, false);

		// Post formats
		add_theme_support('post-formats', array(
			'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
		));

		// Gallery
 		register_post_type('gallery', apply_filters('time_register_post_type_gallery_args', array(
			'label'       => __('Galleries', 'time'),
			'description' => __('Galleries', 'time'),
			'public'      => true,
			'menu_icon'   => version_compare($this->wp_version, '3.8') >= 0 ? 'dashicons-images-alt2' : $this->template_uri.'/data/img/wp/gallery.png',
			'supports'    => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions'),
			'rewrite'     => array('slug' => Time::to('gallery/slug')),
			'labels'      => array(
				'name'               => __('Galleries', 'time'),
				'singular_name'      => __('Gallery', 'time'),
				'add_new'            => _x('Add New', 'Gallery', 'time'),
				'all_items'          => __('All Galleries', 'time'),
				'add_new_item'       => __('Add New Gallery', 'time'),
				'edit_item'          => __('Edit Gallery', 'time'),
				'new_item'           => __('New Gallery', 'time'),
				'view_item'          => __('View Gallery', 'time'),
				'search_items'       => __('Search Galleries', 'time'),
				'not_found'          => __('No Galleries found', 'time'),
				'not_found_in_trash' => __('No Galleries found in Trash', 'time'),
				'menu_name'          => __('Galleries', 'time')
			)
		)));

 		// Portfolio
		register_post_type('portfolio', apply_filters('time_register_post_type_portfolio_args', array(
			'label'        => __('Portfolios', 'time'),
			'description'  => __('Portfolios', 'time'),
			'public'       => true,
			'menu_icon'    => version_compare($this->wp_version, '3.8') >= 0 ? 'dashicons-exerpt-view' : $this->template_uri.'/data/img/wp/portfolio.png',
			'hierarchical' => true,
			'supports'     => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes'),
			//'taxonomies'   => array('portfolio-category', 'portfolio-tag'),
			'has_archive'  => true,
			'rewrite'      => array('slug' => Time::to('portfolio/slug')),
			'labels'       => array(
				'name'               => __('Portfolios', 'time'),
				'singular_name'      => __('Portfolio', 'time'),
				'add_new'            => _x('Add New', 'Portfolio', 'time'),
				'all_items'          => __('All Portfolios', 'time'),
				'add_new_item'       => __('Add New Portfolio', 'time'),
				'edit_item'          => __('Edit Portfolio', 'time'),
				'new_item'           => __('New Portfolio', 'time'),
				'view_item'          => __('View Portfolio', 'time'),
				'search_items'       => __('Search Portfolios', 'time'),
				'not_found'          => __('No Portfolios found', 'time'),
				'not_found_in_trash' => __('No Portfolios found in Trash', 'time'),
				'menu_name'          => __('Portfolios', 'time')
			)
		)));
		register_taxonomy('portfolio-category', array('portfolio'), apply_filters('time_register_taxonomy_portfolio_category_args', array(
			'label'        => __('Categories', 'time'),
			'hierarchical' => true,
			'rewrite'      => array('slug' => Time::to('portfolio/slug').'-category')
		)));
		register_taxonomy('portfolio-tag', array('portfolio'), apply_filters('time_register_taxonomy_portfolio_tag_args', array(
			'label'        => __('Tags', 'time'),
			'hierarchical' => false,
			'rewrite'      => array('slug' => Time::to('portfolio/slug').'-tag')
		)));

		// Custom fonts
		$custom_fonts = array();
		foreach (Time::to_('font/custom')->options as $custom_font) {
			$custom_fonts[$custom_font->property('id')] = sprintf('[font id="%s"]%%s[/font]', DroneFunc::stringID($custom_font->property('id')));
		}

		// Shortcodes
		$this->addThemeFeature('shortcode-page');
		$this->addThemeFeature('shortcode-noformat');
		$this->addThemeFeature('tinymce-shortcodes-menu', array(
			__('Horizontal line', 'time')   => '[hr]',
			__('Mark', 'time')              => array(
				__('Standard', 'time') => '[mark]%s[/mark]',
				__('Yellow', 'time')   => '[mark color="yellow"]%s[/mark]'
			),
			__('Dropcap', 'time')           => '[dc]%s[/dc]',
			__('Tooltip', 'time')           => array(
				__('Simple', 'time')   => '[tooltip title=""]%s[/tooltip]',
				__('Advanced', 'time') => '[tooltip title="" gravity="s" fade="false"]%s[/tooltip]',
			),
			__('Font', 'time')              => $custom_fonts,
			__('Icon', 'time')              => array(
				__('Simple', 'time')   => '[icon name="help"]',
				__('Advanced', 'time') => sprintf('[icon name="help" color="%s" size="1.2em"]', strtolower(Time::to('general/color')))
			),
			__('Button', 'time')            => array(
				__('Simple', 'time')   => sprintf('[button href="%s"]%%s[/button]', home_url('/')),
				__('Advanced', 'time') => sprintf('[button href="%s" target="_blank" size="big" left_icon="help" color="%s" caption="%s"]%%s[/button]', home_url('/'), strtolower(Time::to('general/color')), __('New caption', 'time'))
			),
			__('Quote', 'time')             => array(
				__('Simple', 'time')   => '[quote]%s[/quote]',
				__('Advanced', 'time') => '[quote author="" bar="true" align="left" width="300px"]%s[/quote]'
			),
			__('List', 'time')              => array(
				__('Simple', 'time')   => '[list icon="right-open"]%s[/list]',
				__('Advanced', 'time') => sprintf('[list icon="right-open" color="%s"]%%s[/list]', strtolower(Time::to('general/color')))
			),
			__('Message', 'time')           => array(
				__('Simple', 'time')   => '[message]%s[/message]',
				__('Advanced', 'time') => '[message color="blue" closable="true"]%s[/message]'
			),
			__('Rating', 'time')       => '[rating rate="5/5" author=""]%s[/rating]',
			__('Social buttons', 'time')    => array(
				__('Simple', 'time')   => '[social_buttons]',
				__('Advanced', 'time') => '[social_buttons style="big" media="facebook, twitter, googleplus"]'
			),
			__('Search form', 'time')       => '[search]',
			__('Contact form', 'time')      => '[contact]',
			__('Columns', 'time')           => array(
				__('Start columns', 'time') => '[columns]',
				__('1/2 column', 'time')    => '[column width="1/2"]%s[/column]',
				__('1/3 column', 'time')    => '[column width="1/3"]%s[/column]',
				__('1/4 column', 'time')    => '[column width="1/4"]%s[/column]',
				__('End columns', 'time')   => '[/columns]'
			),
			__('Tabs', 'time')              => array(
				__('Start tabs', 'time') => '[tabs]',
				__('Tab', 'time')        => sprintf('[tab title="%s"]%%s[/tab]', __('New tab', 'time')),
				__('End tabs', 'time')   => '[/tabs]'
			),
			__('Toggles', 'time')           => array(
				__('Start toggles', 'time') => '[toggles]',
				__('Toggle', 'time')        => sprintf('[toggle title="%s"]%%s[/toggle]', __('New toggle', 'time')),
				__('End toggles', 'time')   => '[/toggles]'
			),
			__('Posts', 'time')             => array(
				__('Simple', 'time')   => '[posts]',
				__('Advanced', 'time') => '[posts type="post" size="auto" orderby="date" order="desc" count="1" columns="auto" title="true" excerpt="false" taxonomy="tag"]'
			),
			__('Page', 'time')              => '[page id=""]',
			__('Portfolio', 'time')         => array(
				__('Simple', 'time')   => '[portfolio]',
				__('External', 'time') => '[portfolio id="auto"]',
				__('Advanced', 'time') => '[portfolio id="auto" size="auto" columns="inherit" filter="inherit" orderby="inherit" order="inherit" limit="inherit" pagination="inherit" titles="inherit" excerpts="inherit" taxonomies="inherit"]'
			),
			__('Media', 'time')             => array(
				__('Dekstop only', 'time') => '[media device="desktop"]%s[/media]',
				__('Mobile only', 'time')  => '[media device="mobile"]%s[/media]'
			),
			__('Custom galleries', 'time')  => array(
				__('Bricks', 'time')     => '[bricks]%s[/bricks]',
				__('Slider', 'time')     => '[slider]%s[/slider]',
				__('Scroller', 'time')   => '[scroller]%s[/scroller]',
				__('Super tabs', 'time') => '[super_tabs]%s[/super_tabs]'
			),
			__('Custom page', 'time')       => array(
				__('Content', 'time')       => '[content sidebars="inherit"]%s[/content]',
				__('Section', 'time')       => '[section]%s[/section]',
				__('Posts section', 'time') => '[section_posts]'
			)
		));

		// bbPress
		if (Time::$plugins['bbpress']) {
			$func = create_function('$d', 'return is_bbpress() ? false : $d;');
			add_filter('time_meta_display', $func, 20);
			add_filter('time_social_buttons_display', $func, 20);
		}

		// Captcha
		if (Time::$plugins['captcha']) {
			if (has_action('comment_form_after_fields', 'cptch_comment_form_wp3')) {
				remove_action('comment_form_after_fields', 'cptch_comment_form_wp3', 1);
				remove_action('comment_form_logged_in_after', 'cptch_comment_form_wp3', 1);
				add_filter('comment_form_field_comment', array($this, 'filterCaptchaCommentFormFieldComment'));
			}
		}

		// LayerSlider
		if (Time::$plugins['layerslider']) {
			remove_action('wp_enqueue_scripts', 'layerslider_enqueue_content_res');
			remove_shortcode('layerslider');
		}

		// WooCommerce
		if (Time::$plugins['woocommerce']) {
			require TEMPLATEPATH.'/inc/woocommerce.php';
			add_theme_support('woocommerce');
		}

		// WooCommerce Brands
		if (Time::$plugins['woocommerce-brands']) {
			remove_action('woocommerce_product_meta_end', array($GLOBALS['WC_Brands'], 'show_brand'));
		}

	}

	// -------------------------------------------------------------------------

	/**
	 * Initialization
	 *
	 * @since 2.0
	 * @see DroneTheme::onInit()
	 */
	public function onInit()
	{

		if (Time::to('general/retina')) {

			global $_wp_additional_image_sizes;
			foreach ($_ = $_wp_additional_image_sizes as $name => $image_size) { // array clone to avoid infinite loop
				if (strpos($name, '@2x') === false) {
					add_image_size($name.'@2x', $image_size['width']*2, $image_size['height']*2, $image_size['crop']);
				}
			}

			add_image_size('thumbnail@2x', get_option('thumbnail_size_w')*2, get_option('thumbnail_size_h')*2, (bool)get_option('thumbnail_crop'));
			add_image_size('medium@2x',    get_option('medium_size_w')*2,    get_option('medium_size_h')*2,    false);
			add_image_size('large@2x',     get_option('large_size_w')*2,     get_option('large_size_h')*2,     false);

		}

	}

	// -------------------------------------------------------------------------

	/**
	 * Widgets initialization
	 *
	 * @since 1.0
	 * @see DroneTheme::onWidgetsInit()
	 */
	public function onWidgetsInit()
	{

		register_widget('TimeWidgetSocialMedia');
		register_widget('TimeWidgetSocialButtons');
		register_widget('TimeWidgetContact');
		register_widget('TimeWidgetFacebookLikeBox');

		if (Time::$plugins['layerslider']) {
			unregister_widget('LayerSlider_Widget');
		}

		if (Time::$plugins['woocommerce']) {

			require TEMPLATEPATH.'/woocommerce-widgets.php';

			foreach (array(
				'WC_Widget_Best_Sellers',
				'WC_Widget_Cart',
				'WC_Widget_Featured_Products',
				'WC_Widget_Layered_Nav_Filters',
				'WC_Widget_Layered_Nav',
				'WC_Widget_Onsale',
				'WC_Widget_Price_Filter',
				'WC_Widget_Product_Categories',
				'WC_Widget_Product_Search',
				'WC_Widget_Product_Tag_Cloud',
				'WC_Widget_Products',
				'WC_Widget_Random_Products',
				'WC_Widget_Recent_Products',
				'WC_Widget_Recent_Reviews',
				'WC_Widget_Recently_Viewed',
				'WC_Widget_Top_Rated_Products'
			) as $class) {
				if (class_exists($class)) {
					unregister_widget($class);
					register_widget('Time_'.$class);
				}
			}

		}

		if (Time::$plugins['woocommerce-brands']) {

			require TEMPLATEPATH.'/woocommerce-brands-widgets.php';

			foreach (array(
				'WC_Widget_Brand_Nav'
			) as $class) {
				if (class_exists($class)) {
					unregister_widget($class);
					register_widget('Time_'.$class);
				}
			}

		}

	}

	// -------------------------------------------------------------------------

	/**
	 * tgmpa_register action
	 *
	 * @internal action: tgmpa_register
	 * @since 1.0
	 */
	public function actionTGMPARegister()
	{
		$plugins = array(
			array(
			    'name'               => 'LayerSlider WP - Time Theme edition',
			    'slug'               => 'time-layerslider',
			    'source'             => $this->template_dir.'/plugins/time-layerslider.zip',
			    'required'           => false,
			    'version'            => Time::LAYERSLIDER_VERSION,
			    'force_activation'   => false,
			    'force_deactivation' => false
			),
			array(
			    'name'               => 'WiLD Googlemap',
			    'slug'               => 'wild-googlemap',
			    'source'             => $this->template_dir.'/plugins/wild-googlemap.zip',
			    'required'           => false,
			    'version'            => Time::WILD_GOOGLEMAP_VERSION,
			    'force_activation'   => false,
			    'force_deactivation' => false
			),
		);
		/*$config = array(
			'menu' => 'install-required-plugins'
		);*/
		tgmpa($plugins); // class-tgm-plugin-activation.php:1593 - https://github.com/thomasgriffin/TGM-Plugin-Activation/issues/104
	}

	// -------------------------------------------------------------------------

	/**
	 * Styles and scripts
	 *
	 * @internal action: wp_enqueue_scripts
	 * @since 1.0
	 */
	public function actionWPEnqueueScripts()
	{

		// Minimize sufix
		$min_sufix = !$this->debug_mode ? '.min' : '';

		// 3rd part styles
		wp_enqueue_style('time-fancybox',    $this->template_uri.'/data/css/fancybox.min.css');
		wp_enqueue_style('time-layerslider', $this->template_uri.'/data/css/layerslider.min.css');
		wp_enqueue_style('time-mejs',        $this->template_uri.'/data/css/mejs.min.css');

		// Stylesheet
		wp_enqueue_style('time-stylesheet', get_stylesheet_uri());

		// Main style
		wp_enqueue_style('time-style', $this->template_uri."/data/css/style{$min_sufix}.css");

		// Wordpress style
		wp_enqueue_style('time-wordpress', $this->template_uri."/data/css/wordpress{$min_sufix}.css");

		// Color scheme
		wp_enqueue_style('time-scheme', $this->template_uri.'/data/css/'.Time::to('general/scheme').$min_sufix.'.css');

		// Leading color
		require TEMPLATEPATH.'/inc/color.php';
		$this->addDocumentStyle(sprintf(
			$color,
			Time::to('general/color'),
			implode(', ', array_map('hexdec', str_split(substr(Time::to('general/color'), 1), 2)))
		));

		// Comment reply
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		// Responsive design
		if (Time::to('general/responsive')) {
			wp_enqueue_style('time-mobile',           $this->template_uri."/data/css/mobile{$min_sufix}.css", array(), false, 'only screen and (max-width: 767px)');
			wp_enqueue_style('time-wordpress-mobile', $this->template_uri."/data/css/wordpress-mobile{$min_sufix}.css", array(), false, 'only screen and (max-width: 767px)');
		}

		// 3rd part scripts
		wp_enqueue_script('time-imagesloaded',      $this->template_uri.'/data/js/imagesloaded.min.js',      array(),         false, true);
		wp_enqueue_script('time-jquery-easing',     $this->template_uri.'/data/js/jquery.easing.min.js',     array('jquery'), false, true);
		wp_enqueue_script('time-jquery-fancybox',   $this->template_uri.'/data/js/jquery.fancybox.min.js',   array('jquery'), false, true);
		wp_enqueue_script('time-jquery-flexslider', $this->template_uri.'/data/js/jquery.flexslider.min.js', array('jquery'), false, true);
		wp_enqueue_script('time-jquery-transit',    $this->template_uri.'/data/js/jquery.transit.min.js',    array('jquery'), false, true);

		// Main script
		wp_enqueue_script('time-script', $this->template_uri."/data/js/time{$min_sufix}.js", array('jquery'), false, true);

		// WooCommerce
		if (Time::$plugins['woocommerce']) {
			wp_deregister_script('wc-single-product');
			wp_register_script('wc-single-product', $this->template_uri."/data/js/woocommerce/single-product{$min_sufix}.js", array('jquery'), WOOCOMMERCE_VERSION, true);
		}

		// Configuration
		$this->addDocumentScript(sprintf(
<<<EOS
			timeConfig = {
				templatePath:       '%s',
				zoomHoverIcons:     %s,
				flexsliderOptions:  %s,
				layersliderOptions: %s
			};
EOS
			,
			$this->template_uri,
			json_encode(DroneFunc::arrayKeysToCamelCase(Time::to_('site/hover_icons')->toArray())),
			json_encode(DroneFunc::arrayKeysToCamelCase(Time::to_('site/slider')->toArray())),
			Time::getLayerSliderOptions()
		));

		// Max. width style
		if (!Time::to_('general/max_width')->isDefault()) {
			$this->addDocumentStyle(sprintf(
<<<EOS
				.layout-boxed .outer-container, .container {
					max-width: %dpx;
				}
EOS
			, Time::to('general/max_width')));
		}

		// Colors styles
		foreach (Time::to_('color')->childs() as $name => $group) {
			if ($group->child('enabled')->value) {
				$color = $group->child($name);
				$this->addDocumentStyle(sprintf('%s { background-color: %s; }', $color->tag, $color->value));
			}
		}

		// Fonts styles
		foreach (DroneOptionsFontOption::getInstances() as $font) {
			if ($font->isVisible() && !is_null($font->tag)) {
				$this->addDocumentStyle($font->css($font->tag));
			}
		}

		// Flickr widget script
		if (is_active_widget(false, false, 'time-flickr')) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('#bottom .col-1-4 > .widget-flickr .flickr').addClass('fix-flickr-desktop');
EOS
			);
		}

		// List widgets script
		if (is_active_widget(false, false, 'pages') ||
			is_active_widget(false, false, 'archives') ||
			is_active_widget(false, false, 'categories') ||
			is_active_widget(false, false, 'recent-posts') ||
			is_active_widget(false, false, 'recent-comments') ||
			is_active_widget(false, false, 'bbp_forums_widget') ||
			is_active_widget(false, false, 'bbp_replies_widget') ||
			is_active_widget(false, false, 'bbp_topics_widget') ||
			is_active_widget(false, false, 'bbp_views_widget')) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('.widget_pages, .widget_archive, .widget_categories, .widget_recent_entries, .widget_recent_comments, .widget_display_forums, .widget_display_replies, .widget_display_topics, .widget_display_views').each(function() {
					$('ul', this).addClass('fancy alt');
					$('li', this).prepend(\$('<i />', {'class': 'icon-right-open'}));
					if (\$(this).closest('#top').length > 0) {
						$('i', this).addClass('color');
					}
				});
EOS
			);
		}

		// Tag cloud widget script
		if (is_active_widget(false, false, 'tag_cloud')) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('.widget_tag_cloud a').addClass('alt');
EOS
			);
		}

		// Custom menu widget script
		if (is_active_widget(false, false, 'nav_menu')) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('#top .widget_nav_menu div:has(> ul)').replaceWith(function() {
					return '<nav class="aside arrows">'+\$(this).html()+'</nav>';
				});
				\$('#bottom .widget_nav_menu').each(function() {
					$('ul', this).addClass('fancy alt');
					$('li', this).prepend(\$('<i />', {'class': 'icon-right-open'}));
				});
EOS
			);
		}

		// Meta widget script
		if (is_active_widget(false, false, 'meta')) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('#top .widget_meta > ul').wrap('<nav class="aside arrows" />');
EOS
			);
		}

		// bbPress
		if (Time::$plugins['bbpress'] && is_active_widget(false, false, 'bbp_replies_widget')) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('.widget_display_replies li > div').addClass('small');
EOS
			);
		}

		// Disqus Comment System
		if (Time::$plugins['disqus']) {
			$this->addDocumentJQueryScript(
<<<EOS
				\$('#disqus_thread').addClass('section');
EOS
			);
		}

		// WooCommerce
		if (Time::$plugins['woocommerce']) {
			$this->addDocumentStyle(sprintf(
<<<EOS
				.icon-woocommerce-cart {
					color: %s;
				}
				a:hover .icon-woocommerce-cart {
					color: %s;
				}
				.widget_price_filter .ui-slider .ui-slider-range,
				.widget_price_filter .ui-slider .ui-slider-handle {
					background-color: %s;
				}
EOS
				,
				Time::to('woocommerce/cart/color', 'default', 'inherit'),
				Time::to('woocommerce/cart/hover', 'default', Time::to('general/color')),
				Time::to('general/color')
			));
			if (Time::to('woocommerce/onsale/custom')) {
				$this->addDocumentStyle(sprintf(
<<<EOS
					.woocommerce .onsale,
					.woocommerce-page .onsale {
						background: %s;
						color: %s;
					}
EOS
					,
					Time::to('woocommerce/onsale/background'),
					Time::to('woocommerce/onsale/color')
				));
			}
		}

		// WooCommerce Brands
		if (Time::$plugins['woocommerce-brands']) {
			wp_dequeue_style('brands-styles');
		}

	}

	// -------------------------------------------------------------------------

	/**
	 * pre_get_posts action
	 *
	 * @internal action: pre_get_posts
	 * @since 1.0
	 *
	 * @param object $query
	 */
	public function actionPreGetPosts($query)
	{
		if ($query->is_tax('portfolio-category') || $query->is_tax('portfolio-tag')) {
			$query->query_vars['posts_per_page'] = Time::to('portfolio/archive/count');
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * comment_form_before_fields action
	 *
	 * @internal action: comment_form_before_fields
	 * @since 1.0
	 */
	public function actionCommentFormBeforeFields()
	{
		echo '<div class="columns alt-mobile"><ul>';
	}

	// -------------------------------------------------------------------------

	/**
	 * comment_form_after_fields action
	 *
	 * @internal action: comment_form_after_fields
	 * @since 1.0
	 */
	public function actionCommentFormAfterFields()
	{
		echo '</ul></div>';
	}

	// -------------------------------------------------------------------------

	/**
	 * woocommerce_single_product_summary action
	 *
	 * @internal action: woocommerce_single_product_summary, 35
	 * @since 2.0
	 */
	public function actionWoocommerceSingleProductSummary()
	{

		if (!Time::$plugins['woocommerce-brands'] || !Time::to('woocommerce/product/brands')) {
			return;
		}

		// Brand
		$brands = wp_get_post_terms(get_the_ID(), 'product_brand', array('fields' => 'ids'));

		if (count($brands) == 0) {
			return;
		}
		$brand = get_term($brands[0], 'product_brand');

		// Validation
		if (!$brand->description) {
			return;
		}

		// HTML
		$html = DroneHTML::make();
		$html->addNew('hr');

		// Thumbnail
		if ($thumbnail_id = get_woocommerce_term_meta($brand->term_id, 'thumbnail_id', true)) {
			$html->addNew('figure')
				->class('alignleft')
				->addNew('a')
					->attr(Time::getImageAttrs('a', array('border' => false, 'hover' => '')))
					->href(get_term_link($brand, 'product_brand'))
					->title($brand->name)
					->add(wp_get_attachment_image($thumbnail_id, 'logo'));
		}

		// Description
		$html->add(wpautop(wptexturize($brand->description)));

		$html->ehtml();

	}

	// -------------------------------------------------------------------------

	/**
	 * image_size_names_choose filter
	 *
	 * @internal filter: image_size_names_choose
	 * @since 1.0
	 *
	 * @param  array $sizes
	 * @return array
	 */
	public function filterImageSizeNamesChoose($sizes)
	{
		return array_merge($sizes, array(
			'full-width'   => __('1 column', 'time'),
			'medium-width' => __('2 or 3 columns', 'time'),
			'small-width'  => __('4+ columns', 'time')
		));
	}

	// -------------------------------------------------------------------------

	/**
	 * body_class filter
	 *
	 * @internal filter: body_class
	 * @since 1.0
	 *
	 * @param  array $classes
	 * @return array
	 */
	public function filterBodyClass($classes)
	{
		if (Time::$plugins['wpml']) {
			$classes[] = 'lang-'.ICL_LANGUAGE_CODE;
		}
		if (is_page_template('full-screen-gallery.php')) {
			Time::to_('general/layout')->value = 'open';
			$classes[] = 'full-screen';
		}
		$classes[] = 'layout-'.Time::to('general/layout');
		$classes[] = 'scheme-'.Time::to('general/scheme');
		return $classes;
	}

	// -------------------------------------------------------------------------

	/**
	 * wp_title filter
	 *
	 * @internal filter: wp_title
	 * @since 1.0
	 *
	 * @param  string $title
	 * @param  string $sep
	 * @return string
	 */
	public function filterWPTitle($title, $sep)
	{

		// Feed
		if (is_feed()) {
			return $title;
		}

		// Title
		if ((is_home() || is_front_page()) && ($description = get_bloginfo('description', 'display'))) {
			return $sep ? get_bloginfo('name')." {$sep} {$description}" : get_bloginfo('name');
		} else {
			return $sep ? $title.get_bloginfo('name') : $title;
		}

	}

	// -------------------------------------------------------------------------

	/**
	 * wp_nav_menu_items and wp_list_pages filter
	 *
	 * @internal filter: wp_nav_menu_items
	 * @internal filter: wp_list_pages
	 * @since 1.0
	 *
	 * @param  string $items
	 * @param  array  $args
	 * @return string
	 */
	public function filterWPNavMenuItems($items, $args)
	{

		// Theme location
		if (isset($args->theme_location)) {
			$theme_location = $args->theme_location;
		} else if (isset($args['theme_location'])) {
			$theme_location = $args['theme_location'];
		} else {
			return $items;
		}

		// Icons
		$items = preg_replace_callback('#<li(.*)><a(.*)>(.*)</a>#iU', array($this, 'filterWPNavMenuItemsCallback'), $items);

		// Cart
		if ($theme_location == 'primary-desktop' && Time::to('header/cart/enabled')) {
			$items .= '<li class="cart">'.$this->getCartMenuitem().'</li>';
		}

		// Search
		if ($theme_location == 'primary-desktop' && Time::to_('header/search')->value('desktop')) {
			$items .= '<li>'.get_search_form(false).'</li>';
		}

		// Language menu
		if (($theme_location == 'primary-desktop' || $theme_location == 'primary-mobile') && Time::to('header/lang') == 'long') {
			$langs = icl_get_languages('skip_missing=0&orderby=code');
			$items .= '<li class="lang">';
			foreach ($langs as $lang) {
				if ($lang['active']) {
					$items .= $this->getLanguageMenuitem($lang);
					break;
				}
			}
			$items .= '<ul>';
			foreach ($langs as $lang) {
				if (!$lang['active']) {
					$items .= '<li>'.$this->getLanguageMenuitem($lang).'</li>';
				}
			}
			$items .= '</ul></li>';
		}

		// Result
		return $items;

	}

	// -------------------------------------------------------------------------

	/**
	 * wp_nav_menu_items and wp_list_pages filter helper function
	 *
	 * @since 1.0
	 *
	 * @param  array  $matches
	 * @return string
	 */
	protected function filterWPNavMenuItemsCallback($matches)
	{
		if (preg_match('/[ "](icon-([-_a-z0-9]+))[ "]/', $matches[1], $m)) {
			$icon = str_replace('_', '/', $m[2]);
			$matches[1] = str_replace($m[1], '', $matches[1]);
			return sprintf(
				'<li%s><a%s>%s%s</a>',
				$matches[1],
				$matches[2],
				$this->shortcodeIcon(array('name' => $icon, 'size' => '')),
				$matches[3]
			);
		} else {
			return $matches[0];
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * get_search_form filter
	 *
	 * @internal filter: get_search_form
	 * @since 1.0
	 *
	 * @return string
	 */
	public function filterGetSearchForm()
	{
		$search_form = DroneHTML::make('form')
			->method('get')
			->action(esc_url(home_url('/')))
			->class('search')
			->role('search');
		$search_form->addNew('input')
			->type('text')
			->name('s')
			->value(get_search_query())
			->placeholder(__('Search site', 'time'));
		$search_form->addNew('button')
			->type('submit')
			->addNew('i')
				->class('icon-search')
				->add();
		return $search_form->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * wp_get_attachment_image_attributes filter
	 *
	 * @internal filter: wp_get_attachment_image_attributes
	 * @since 1.0
	 *
	 * @param  array  $attr
	 * @param  object $attachment
	 * @return string
	 */
	public function filterWPGetAttachmentImageAttributes($attr, $attachment)
	{
		if (Time::to('general/retina')) {
			$size = str_replace('attachment-', '', $attr['class']);
	 		if ($image_2x = wp_get_attachment_image_src($attachment->ID, $size.'@2x')) {
				list($attr['data-2x']) = $image_2x;
			}
		}
		return $attr;
	}

	// -------------------------------------------------------------------------

	/**
	 * get_calendar filter
	 *
	 * @internal filter: get_calendar
	 * @since 1.0
	 *
	 * @param  string $calendar_output
	 * @return string
	 */
	public function filterGetCalendar($calendar_output)
	{
		return str_replace('<table ', '<table class="fixed" ', $calendar_output);
	}

	// -------------------------------------------------------------------------

	/**
	 * img_caption_shortcode filter
	 *
	 * @internal filter: img_caption_shortcode
	 * @since 1.0
	 *
	 * @param  string $depricated
	 * @param  array  $atts
	 * @param  string $content
	 * @return string
	 */
	public function filterImgCaptionShortcode($depricated, $atts, $content = null)
	{

		// Attributes
		extract(shortcode_atts(array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
		), $atts, 'caption'));

		// ID
		$int_id = (int)str_replace('attachment_', '', $id);

		// Class
		$class = preg_match('/class="(.*?)"/i', $content, $m) ? preg_replace('/\balign(none|left|right|center)\b/i', '', $m[1]) : '';

		// Content
		$content = preg_replace('/class="(.*?)"/i', '', trim($content));

		// Retina
		if (Time::to('general/retina') && $int_id && $width && preg_match('/\bsize-(thumbnail|medium|large|(full|medium|small)-width)\b/', $class, $matches)) {
			if ($image_2x = wp_get_attachment_image_src($int_id, $matches[1].'@2x')) {
				list($src_2x) = $image_2x;
				$content = str_replace('<img ', '<img data-2x="'.$src_2x.'" ', $content);
			}
		}

		// Images attributes
		$atts = array();
		if (preg_match_all('/\b(border|hover|fancybox)-([a-z]+)\b/i', $class, $matches, PREG_SET_ORDER) > 0) {
			foreach ($matches as $match) {
				$atts[$match[1]] = str_ireplace('none', '', $match[2]);
				$class = str_replace($match[0], '', $class);
			}
		}

		// Settings
		if (strpos($content, '<a ') === 0) {
			$content = str_replace('<a ', sprintf('<a %s ', DroneFunc::arraySerialize(Time::getImageAttrs('a', $atts), 'html')), $content);
		} else {
			$content = sprintf('<div %s>%s</div>', DroneFunc::arraySerialize(Time::getImageAttrs('div', $atts), 'html'), $content);
		}

		// Figure
		$figure = DroneHTML::make('figure')
			->id($id ? $id : null)
			->addClass($class, $align, $align == 'alignleft' || $align == 'alignright' ? 'fixed' : null)
			->style($width ? "width: {$width}px;" : null)
			->add($content);

		// Caption
		if ($caption) {
			$figure->addNew('figcaption')->add($caption);
		}

		return $figure->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * the_content filter
	 *
	 * @internal filter: the_content, 1
	 * @since 1.0
	 *
	 * @param  string $content
	 * @return string
	 */
	public function filterTheContent1($content)
	{

		// Align none
		$content = preg_replace(
			'|(<p([^<>]*)>)?(( *(<a[^<>]*>)?<img[^<>]*class="[^"]*alignnone[^"]*"[^<>]*>(</a>)? *){2,})(</p>)?|i', // todo: nie obsluguje to wszystkich przypadkow
			'<div class="figuregroup"\2>\3</div>',
			$content
		);

		// Figure
  		$content = preg_replace_callback(
			'|(\[caption.*?\])?(<p[^<>]*>)?((<a[^<>]*>)?(<img[^<>]*>)(</a>)?)(</p>)?|i',
			array($this, 'filterTheContent1Callback'), $content
		);

  		// Result
		return $content;

	}

	// -------------------------------------------------------------------------

	/**
	 * the_content filter helper function
	 *
	 * @since 1.0
	 *
	 * @param  array  $matches
	 * @return string
	 */
	protected function filterTheContent1Callback($matches)
	{
		if ($matches[1]) {
			return $matches[0];
		}
		$content = trim($matches[3]);
		$id      = '';
		$align   = 'alignnone';
		$width   = '';
		if (preg_match('/class="(.*?)"/i', $content, $matches)) {
			$class = $matches[1];
			if (preg_match('/\bwp-image-([0-9]+)\b/i', $class, $m)) {
				$id = 'attachment_'.$m[1];
			}
			if (preg_match('/\b(align(none|left|right|center))\b/i', $class, $m)) {
				$align = strtolower($m[1]);
			}
		}
		if (preg_match('/width="([0-9]+)"/i', $content, $m)) {
			$width = $m[1];
		}
		$content = str_replace($align, '', $content);
		return Time::filterImgCaptionShortcode('', compact('id', 'align', 'width'), $content);
	}

	// -------------------------------------------------------------------------

	/**
	 * the_content filter
	 *
	 * @internal filter: the_content
	 *
	 * @param  string $content
	 * @return string
	 */
	public function filterTheContent($content)
	{
		return preg_replace('#(<p>)?(<(iframe|embed).*?></\3>)(</p>)?#i', '<div class="embed">\2</div>', $content);
	}

	// -------------------------------------------------------------------------

	/**
	 * post_gallery filter
	 *
	 * @internal filter: post_gallery
	 * @since 1.0
	 *
	 * @param  string $_
	 * @param  array  $atts
	 * @return string
	 */
	public function filterPostGallery($_, $atts)
	{

		// Post
		$post = get_post();

		// Attributes
		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'columns'    => 3,
			'size'       => 'auto',
			'link'       => 'post',
			'include'    => '',
			'exclude'    => '',
			'full_width' => false,
			'captions'   => true,
			'border'     => 'inherit',
			'hover'      => 'inherit',
			'fancybox'   => 'inherit'
		), $atts, 'gallery'));

		// Attachments
		$params = array(
			'numberposts'    => -1,
			'post_parent'    => $id,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => $order == 'RAND' ? 'none' : $orderby,
			'order'          => $order
		);
		if (!empty($include)) {
			unset($params['post_parent']);
			$params['include'] = preg_replace('/[^0-9,]+/', '', $include);
		} else if (!empty($exclude)) {
			$params['exclude'] = preg_replace('/[^0-9,]+/', '', $exclude);
		}
		$attachments = get_posts($params);

		// Custom gallery HTML code
		if ($gallery = apply_filters('time_post_gallery_html', '', $atts, $attachments)) {
			return $gallery;
		}
		if (is_singular('gallery')) {
			return $this->filterTimePostGalleryHTMLBricks('', $atts, $attachments);
		}

		// Size
		if ($size == 'auto') {
			$size = DroneFunc::stringToBool($full_width) ? Time::getImageSize($columns) : 'thumbnail';
		}

		// Gallery
		$gallery = DroneHTML::make('div')
			->id('gallery-'.(++Time::$gallery_instance))
			->class('columns');

		// Gallery items
		$items = $gallery->addNew('ul');

		foreach ($attachments as $attachment) {

			// Figure
			$figure = $items->addNew('li')->class('col-1-'.$columns)->addNew('figure');
			if (DroneFunc::stringToBool($full_width)) {
				$figure->class = 'full-width';
			} else {
				list(, $width) = wp_get_attachment_image_src($attachment->ID, $size);
				$figure->class = 'aligncenter';
				$figure->style = sprintf('width: %dpx;', $width);
			}

			// Hyperlink and image
			if ($url = $this->getAttachmentLinkURL($attachment, $link)) {
				$a = $figure->addNew('a')
					->rel($gallery->id)
					->attr(Time::getImageAttrs('a', compact('border', 'hover', 'fancybox')))
					->attr('data-fancybox-title', $attachment->post_excerpt)
					->href($url)
					->add(wp_get_attachment_image($attachment->ID, $size));
			} else {
				$figure->addNew('div')
					->attr(Time::getImageAttrs('div', compact('border', 'hover', 'fancybox')))
					->add(wp_get_attachment_image($attachment->ID, $size));
			}

			// Caption
			if (DroneFunc::stringToBool($captions) && trim($attachment->post_excerpt)) {
				$caption = $figure->addNew('figcaption')
					->add(wptexturize($attachment->post_excerpt));
			}

		}

		return $gallery->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * time_post_gallery_html filter for bricks
	 *
	 * @since 1.0
	 *
	 * @param  string $_
	 * @param  array  $atts
	 * @param  array  $attachments
	 * @return string
	 */
	public function filterTimePostGalleryHTMLBricks($_, $atts, $attachments)
	{

		// Attributes
		extract(shortcode_atts(array(
			'columns'  => 3,
			'size'     => 'auto',
			'link'     => 'post',
			'captions' => true,
			'border'   => 'inherit',
			'hover'    => 'inherit',
			'fancybox' => 'inherit'
		), $atts, 'bricks'));

		// Size
		if ($size == 'auto') {
			$size = Time::getImageSize($columns);
		}

		// Bricks
		$bricks = DroneHTML::make('div')
			->id('gallery-'.(++Time::$gallery_instance))
			->class('bricks')
			->attr('data-bricks-columns', $columns);

		// Bricks items
		foreach ($attachments as $attachment) {

			// Figure
			$figure = $bricks->addNew('div')->addNew('figure')
				->class('full-width');

			// Hyperlink and image
			if ($url = $this->getAttachmentLinkURL($attachment, $link)) {
				$a = $figure->addNew('a')
					->rel($bricks->id)
					->attr(Time::getImageAttrs('a', compact('border', 'hover', 'fancybox')))
					->attr('data-fancybox-title', $attachment->post_excerpt)
					->href($url)
					->add(wp_get_attachment_image($attachment->ID, $size));
			} else {
				$figure->addNew('div')
					->attr(Time::getImageAttrs('div', compact('border', 'hover', 'fancybox')))
					->add(wp_get_attachment_image($attachment->ID, $size));
			}

			// Caption
			if (DroneFunc::stringToBool($captions) && trim($attachment->post_excerpt)) {
				$caption = $figure->addNew('figcaption')
					->add(wptexturize($attachment->post_excerpt));
			}

		}

		return $bricks->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * time_post_gallery_html filter for slider
	 *
	 * @since 1.0
	 *
	 * @param  string $_
	 * @param  array  $atts
	 * @param  array  $attachments
	 * @return string
	 */
	public function filterTimePostGalleryHTMLSlider($_, $atts, $attachments)
	{

		// Attributes
		extract(shortcode_atts(array(
			'size'     => 'full-width',
			'link'     => 'post',
			'captions' => true,
			'border'   => false,
			'hover'    => '',
			'fancybox' => 'inherit'
		), $atts, 'slider'));

		// Size
		if ($size == 'auto') {
			$size = 'full-width';
		}

		// Slider
		$slider = DroneHTML::make('div')
			->id('gallery-'.(++Time::$gallery_instance))
			->class('slider');

		// Slides
		$slides = $slider->addNew('ul')
			->class('slides');

		foreach ($attachments as $attachment) {

			// Figure
			$figure = $slides->addNew('li')->addNew('figure');

			// Hyperlink and image
			if ($attachment->post_content && preg_match('#<iframe.*?>\s*</iframe>#i', $attachment->post_content, $matches)) {
				$iframe = preg_replace_callback(
					'#src="((http:)?//www.youtube.com/embed/[-_a-z0-9]+)\??(.*?)"#i',
					create_function('$m', 'return sprintf("src=\"%s?wmode=opaque%s\"", $m[1], isset($m[3]) && $m[3] ? "&amp;".$m[3] : "");'),
					$attachment->post_content
				);
				$figure->tag('div')
					->class('embed')
					->add($iframe);
			} else if ($url = $this->getAttachmentLinkURL($attachment, $link)) {
				$figure->addNew('a')
					->rel($slider->id)
					->attr(Time::getImageAttrs('a', compact('border', 'hover', 'fancybox')))
					->attr('data-fancybox-title', $attachment->post_excerpt)
					->href($url)
					->add(wp_get_attachment_image($attachment->ID, $size));
			} else {
				$figure->addNew('div')
					->attr(Time::getImageAttrs('div', compact('border', 'hover', 'fancybox')))
					->add(wp_get_attachment_image($attachment->ID, $size));
			}

			// Caption
			if (DroneFunc::stringToBool($captions) && trim($attachment->post_excerpt)) {
				$caption = $figure->addNew('p')
					->class('caption')
					->add(wptexturize($attachment->post_excerpt));
			}

		}

		return $slider->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * time_post_gallery_html filter for scroller
	 *
	 * @since 1.0
	 *
	 * @param  string $_
	 * @param  array  $atts
	 * @param  array  $attachments
	 * @return string
	 */
	public function filterTimePostGalleryHTMLScroller($_, $atts, $attachments)
	{

		// Attributes
		extract(shortcode_atts(array(
			'size'      => 'thumbnail',
			'link'      => 'post',
			'buttons'   => false,
			'border'    => false,
			'hover'     => 'inherit',
			'fancybox'  => 'inherit'
		), $atts, 'scroller'));

		// Size
		if ($size == 'auto') {
			$size = 'thumbnail';
		} else if ($size == 'logo') {
			extract(array_diff_key(array(
				'border'   => false,
				'hover'    => 'grayscale',
				'fancybox' => false
			), $atts));
		}

		// Scroller
		$scroller = DroneHTML::make('div')
			->id('gallery-'.(++Time::$gallery_instance))
			->class('movable-container');

		if ($size == 'logo') {
			$scroller->addClass('content-size-logo');
		}

		if (DroneFunc::stringToBool($buttons)) {
			$scroller->attr('data-movable-container-force-touch-device', 'true');
		}

		// Scroller items
		foreach ($attachments as $attachment) {

			// Figure
			$scroller->add(' ');
			$figure = $scroller->addNew();

			// Hyperlink and image
			if ($url = $this->getAttachmentLinkURL($attachment, $link)) {
				$a = $figure->addNew('a')
					->rel($scroller->id)
					->attr(Time::getImageAttrs('a', compact('border', 'hover', 'fancybox')))
					->attr('data-fancybox-title', $attachment->post_excerpt)
					->href($url)
					->add(wp_get_attachment_image($attachment->ID, $size));
			} else {
				$figure->addNew('div')
					->attr(Time::getImageAttrs('div', compact('border', 'hover', 'fancybox')))
					->add(wp_get_attachment_image($attachment->ID, $size));
			}

		}

		return $scroller->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * time_post_gallery_html filter for super tabs
	 *
	 * @since 1.0
	 *
	 * @param  string $_
	 * @param  array  $atts
	 * @param  array  $attachments
	 * @return string
	 */
	public function filterTimePostGalleryHTMLSuperTabs($_, $atts, $attachments)
	{

		// Attributes
		extract(shortcode_atts(array(
			'size'         => 'full-width',
			'link'         => 'post',
			'ordered'      => true,
			'descriptions' => true
		), $atts, 'slider'));

		// Size
		if ($size == 'auto') {
			$size = 'full-width';
		}

		// Super tabs
		$super_tabs = DroneHTML::make('div')
			->id('gallery-'.(++Time::$gallery_instance))
			->class('super-tabs')
			->attr('data-super-tabs-ordered', DroneFunc::boolToString(DroneFunc::stringToBool($ordered)));

		// Super tabs tabs
		foreach ($attachments as $attachment) {

			// Figure
			$figure = $super_tabs->addNew('div')
				->title($attachment->post_title);

			// Hyperlink and image
			if ($url = $this->getAttachmentLinkURL($attachment, $link)) {
				$a = $figure->addNew('a')
					->rel($super_tabs->id)
					->href($url)
					->add(wp_get_attachment_image($attachment->ID, $size));
			} else {
				$figure->add(wp_get_attachment_image($attachment->ID, $size));
			}

			// Description
			if (DroneFunc::stringToBool($descriptions)) {
				$figure->attr('data-super-tabs-description', $attachment->post_excerpt);
			}

		}

		return $super_tabs->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * time_post_gallery_html filter for full screen gallery
	 *
	 * @since 1.0
	 *
	 * @param  string $_
	 * @param  array  $atts
	 * @param  array  $attachments
	 * @return string
	 */
	public function filterTimePostGalleryHTMLFullScreenGallery($_, $atts, $attachments)
	{

		// LayerSlider
		$layerslider = DroneHTML::make('div')->id('layerslider');

		// Slides
		foreach ($attachments as $attachment) {

			// Layer
			$layer = $layerslider->addNew('div')
				->class('ls-layer')
				->add(
					wp_get_attachment_image($attachment->ID, 'full-hd',      false, array('class' => 'ls-bg')),
					wp_get_attachment_image($attachment->ID, 'ls-thumbnail', false, array('class' => 'ls-tn'))
				);

		}

		return $layerslider->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * excerpt_more filter
	 *
	 * @internal filter: excerpt_more
	 * @since 1.2
	 *
	 * @param  string $more
	 * @return string
	 */
	public function filterExcerptMore($more)
	{
		return sprintf(' [&hellip;] <a href="%s" class="readmore">%s</a>', get_permalink(), Time::to('post/readmore').'<i class="icon-forward"></i>');
	}

	// -------------------------------------------------------------------------

	/**
	 * the_content_more_link filter
	 *
	 * @internal filter: the_content_more_link
	 * @since 1.0
	 *
	 * @param string $s
	 * @return string
	 */
	public function filterTheContentMoreLink($s)
	{
		return str_replace('more-link', 'readmore', $s);
	}

	// -------------------------------------------------------------------------

	/**
	 * comment_form_defaults filter
	 *
	 * @internal filter: comment_form_defaults
	 * @since 1.0
	 *
	 * @param  array $defaults
	 * @return array
	 */
	public function filterCommentFormDefaults($defaults)
	{
		$commenter = wp_get_current_commenter();
		return array(
			'fields'               => array(
				'author' => '<li class="col-1-3"><input class="full-width" type="text" name="author" placeholder="'.__('Name', 'time').'*" value="'.esc_attr($commenter['comment_author']).'" /></li>',
				'email'  => '<li class="col-1-3"><input class="full-width" type="text" name="email" placeholder="'.__('E-mail', 'time').' ('.__('not published', 'time').')*" value="'.esc_attr($commenter['comment_author_email']).'" /></li>',
				'url'    => '<li class="col-1-3"><input class="full-width" type="text" name="url" placeholder="'.__('Website', 'time').'" value="'.esc_attr($commenter['comment_author_url']).'" /></li>'
			),
			'comment_field'        => '<p><textarea class="full-width" name="comment" placeholder="'.__('Message', 'time').'"></textarea></p>',
			'must_log_in'          => str_replace('<p class="must-log-in">', '<p class="must-log-in small">', $defaults['must_log_in']),
			'logged_in_as'         => str_replace('<p class="logged-in-as">', '<p class="logged-in-as small">', $defaults['logged_in_as']),
			'comment_notes_before' => '',
			'comment_notes_after'  => '',
			'id_form'              => $defaults['id_form'],
			'id_submit'            => $defaults['id_submit'],
			'title_reply'          => __('Leave a comment', 'time'),
			'title_reply_to'       => __('Leave a reply to %s', 'time'),
			'cancel_reply_link'    => __('Cancel reply', 'time'),
			'label_submit'         => __('Send &rsaquo;', 'time'),
			'format'               => 'html5'
		);
	}

	// -------------------------------------------------------------------------

	/**
	 * wp_video_extensions filter
	 *
	 * @internal filter: wp_video_extensions
	 * @since 1.0
	 *
	 * @param  array $exts
	 * @return array
	 */
	public function filterWPVideoExtensions($exts)
	{
		$exts[] = 'ogg';
		return $exts;
	}

	// -------------------------------------------------------------------------

	/**
	 * wp_audio_shortcode_library and wp_video_shortcode_library filter
	 *
	 * @internal filter: wp_audio_shortcode_library
	 * @internal filter: wp_video_shortcode_library
	 * @since 1.0
	 *
	 * @param  string $library
	 * @return string
	 */
	public function filterWPAudioVideoShortcodeLibrary($library)
	{
		return $library == 'mediaelement' ? '' : $library;
	}

	// -------------------------------------------------------------------------

	/**
	 * wp_audio_shortcode and wp_video_shortcode filter
	 *
	 * @internal filter: wp_audio_shortcode
	 * @internal filter: wp_video_shortcode
	 * @since 1.0
	 *
	 * @param  string $html
	 * @return string
	 */
	public function filterWPAudioVideoShortcode($html)
	{
		return '<div class="embed">'.preg_replace('#^<div.*?>(.+?)</div>$#i', '\1', $html).'</div>';
	}

	// -------------------------------------------------------------------------

	/**
	 * get_previous_post_where and get_next_post_where filter
	 *
	 * @internal filter: get_previous_post_where
	 * @internal filter: get_next_post_where
	 * @since 1.0
	 *
	 * @param  string $query
	 * @return string
	 */
	public function filterGetAdjacentPostWhere($query)
	{
		if (is_singular('portfolio')) {
			global $wpdb, $post;
			$query .= $wpdb->prepare(' AND p.post_parent = %d', $post->post_parent);
		}
		return $query;
	}

	// -------------------------------------------------------------------------

	/**
	 * time_headline_content filter
	 *
	 * @internal filter: time_headline_content
	 * @since 2.0
	 *
	 * @param  string $content
	 * @return string
	 */
	public function filterBBPTimeHeadlineContent($content)
	{
		if (Time::$plugins['bbpress'] && is_bbpress() && $content == 'navigation') {
			return Time::to('nav/headline/content') != 'navigation' ? 'breadcrumbs' : '';
		}
		return $content;
	}

	// -------------------------------------------------------------------------

	/**
	 * bbp_get_breadcrumb filter
	 *
	 * @internal filter: bbp_get_breadcrumb
	 * @since 1.1
	 *
	 * @param  string $trail
	 * @param  array  $crumbs
	 * @param  array  $r
	 * @return bool
	 */
	public function filterBBPGetBreadcrumb($trail, $crumbs, $r)
	{
		return !$r['before'] || !$r['after'] ? $trail : '';
	}

	// -------------------------------------------------------------------------

	/**
	 * breadcrumb_trail filter
	 *
	 * @internal filter: breadcrumb_trail
	 * @since 1.0
	 *
	 * @param  string $breadcrumb
	 * @param  array  $args
	 * @return string
	 */
	public function filterBreadcrumbTrail($breadcrumb, $args)
	{
		return preg_replace('#</?(div|span).*?>#i', '', $breadcrumb);
	}

	// -------------------------------------------------------------------------

	/**
	 * comment_form_field_comment filter for Catpcha plugin
	 *
	 * @since 2.0
	 */
	public function filterCaptchaCommentFormFieldComment($comment_field)
	{
		$captcha = DroneFunc::functionGetOutputBuffer('cptch_comment_form_wp3');
		$captcha = preg_replace('#<br( /)?>#', '', $captcha);
		return $comment_field.$captcha;
	}

	// -------------------------------------------------------------------------

	/**
	 * time_headline_content filter
	 *
	 * @internal filter: time_headline_content
	 * @since 2.0
	 *
	 * @param  string $content
	 * @return string
	 */
	public function filterWoocommerceTimeHeadlineContent($content)
	{
		if (Time::$plugins['woocommerce'] && is_product() && $content == 'navigation') {
			return Time::to('nav/headline/content') != 'navigation' ? 'breadcrumbs' : '';
		}
		return $content;
	}

	// -------------------------------------------------------------------------

	/**
	 * time_author_bio_display, time_social_buttons_display, time_meta_display filter
	 *
	 * @internal filter: time_author_bio_display
	 * @internal filter: time_social_buttons_display
	 * @internal filter: time_meta_display
	 * @since 2.0
	 *
	 * @param  bool $show
	 * @return bool
	 */
	public function filterWoocommerceTimeDisplay($show)
	{
		if (Time::$plugins['woocommerce'] && (is_cart() || is_checkout() || is_account_page() || is_order_received_page())) {
			return false;
		}
		return $show;
	}

	// -------------------------------------------------------------------------

	/**
	 * woocommerce_enqueue_styles filter
	 *
	 * @internal filter: woocommerce_enqueue_styles
	 * @since 2.3
	 *
	 * @return boolean
	 */
	public function filterWoocommerceEnqueueStyles()
	{
		return false;
	}

	// -------------------------------------------------------------------------

	/**
	 * loop_shop_per_page filter
	 *
	 * @internal filter: loop_shop_per_page
	 * @since 2.0
	 *
	 * @return int
	 */
	public function filterWoocommerceLoopShopPerPage()
	{
		return Time::to('woocommerce/shop/per_page');
	}

	// -------------------------------------------------------------------------

	/**
	 * woocommerce_show_page_title filter
	 *
	 * @internal filter: woocommerce_show_page_title
	 * @since 2.0
	 *
	 * @return bool
	 */
	public function filterWoocommerceShowPageTitle()
	{
		return !Time::$headline_used;
	}

	// -------------------------------------------------------------------------

	/**
	 * woocommerce_placeholder_img_src filter
	 *
	 * @internal filter: woocommerce_placeholder_img_src
	 * @since 2.0
	 *
	 * @return string
	 */
	public function filterWoocommercePlaceholderImgSrc()
	{
		return $this->template_uri.'/data/img/woocommerce/placeholder.jpg';
	}

	// -------------------------------------------------------------------------

	/**
	 * woocommerce_placeholder_img filter
	 *
	 * @internal filter: woocommerce_placeholder_img
	 * @since 2.0
	 *
	 * @param  string $html
	 * @return string
	 */
	public function filterWoocommercePlaceholderImg($html)
	{
		return str_replace('<img ', '<img data-2x="'.$this->template_uri.'/data/img/woocommerce/placeholder@2x.jpg'.'" ', $html);
	}

	// -------------------------------------------------------------------------

	/**
	 * woocommerce_order_button_html filter
	 *
	 * @internal filter: woocommerce_order_button_html
	 * @since 2.3
	 *
	 * @param  string $html
	 * @return string
	 */
	public function filterWoocommerceOrderButtonHTML($html)
	{
		if (preg_match('/value="(.*?)"/', $html, $matches)) {
			return '<button type="submit" class="big" name="woocommerce_checkout_place_order" id="place_order"><span>'.$matches[1].'</span><i class="icon-right-bold color-grass"></i></button>';
		} else {
			return $html;
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Hr shortcode
	 *
	 * @internal shortcode: hr
	 * @since 1.0
	 *
	 * @return string
	 */
	public function shortcodeHr()
	{
		return '<hr />';
	}

	// -------------------------------------------------------------------------

	/**
	 * Mark shortcode
	 *
	 * @internal shortcode: mark
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeMark($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'color' => ''
		), $atts, 'mark'));
		return DroneHTML::make('mark')
			->class($color)
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Dropcap shortcode
	 *
	 * @internal shortcode: dc
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeDropcap($atts, $content = null)
	{
		$content = DroneFunc::wpShortcodeContent($content);
		return DroneHTML::make()
			->add(
				DroneHTML::make('span')->class('dropcap')->add($content[0]),
				substr($content, 1)
			)
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Tooltip shortcode
	 *
	 * @internal shortcode: tooltip
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeTooltip($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'tag'     => 'span',
			'title'   => '',
			'gravity' => 's',
			'fade'    => false
		), $atts, 'tooltip'));
		return DroneHTML::make($tag)
			->class('tipsy-tooltip')
			->title($title)
			->attr('data-tipsy-tooltip-gravity', $gravity)
			->attr('data-tipsy-tooltip-fade', DroneFunc::boolToString(DroneFunc::stringToBool($fade)))
			->add(DroneFunc::wpShortcodeContent($content)) // preg_replace('/ +/', '&nbsp;', $content)
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Font shortcode
	 *
	 * @internal shortcode: font
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeFont($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'id'    => '',
			'tag'   => 'span',
			'class' => '',
			'style' => ''
		), $atts, 'font'));
		foreach (Time::to_('font/custom')->options as $custom_font) {
			if (DroneFunc::stringID($custom_font->property('id')) == $id) {
				$style .= $custom_font->css();
			}
		}
		return DroneHTML::make($tag)
			->class($class)
			->style($style)
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Icon shortcode
	 *
	 * @internal shortcode: icon
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @param  string $code
	 * @return string
	 */
	public function shortcodeIcon($atts, $content = null, $code = '')
	{
		extract(shortcode_atts(array(
			'name'  => 'help',
			'color' => '',
			'size'  => '1.2em',
			'class' => '',
			'style' => ''
		), $atts, 'icon'));
		if (strpos($name, '/') === false) {
			$icon = DroneHTML::make('i')->class('icon-'.preg_replace('/^icon-/', '', $name))->add();
			if ($code == 'icon') {
				$icon->style = 'margin: 0 -0.4em;';
			}
			if ($color) {
				if (DroneFunc::isCSSColor($color)) {
					$icon->style .= "color: {$color};";
				} else if ($color == 'color' || $color == 'leading') {
					$icon->addClass('color');
				} else {
					$icon->addClass('color-'.$color);
				}
			}
			if ($size) {
				if (preg_match('/^[0-9]+$/', $size)) {
					$size .= 'em';
				}
				$icon->style .= "font-size: {$size};";
			}
		} else {
			if (!file_exists("{$this->template_dir}/data/img/icons/{$name}.png")) {
				return;
			}
			if (($is = getimagesize("{$this->template_dir}/data/img/icons/{$name}.png")) !== false) {
				list($width, $height) = $is;
			} else {
				$width = $height = 16;
			}
			$icon = DroneHTML::make('img')
				->width($width)
				->height($height)
				->class('icon')
				->alt('') // todo
				->src("{$this->template_uri}/data/img/icons/{$name}.png");
			if ($code == 'icon') {
				$icon->style = 'margin: 0;';
			}
			if (Time::to('general/retina')) {
				$icon->attr('data-2x', "{$this->template_uri}/data/img/icons/{$name}@2x.png");
			}
		}
		if ($class) {
			$icon->addClass($class);
		}
		if ($style) {
			$icon->style .= $style;
		}
		return $icon->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Button shortcode
	 *
	 * @internal shortcode: button
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeButton($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'size'       => 'normal', // normal, big, huge
			'icon'       => '',
			'left_icon'  => '',
			'right_icon' => '',
			'color'      => 'color',
			'href'       => '',
			'target'     => '_self',
			'caption'    => ''
		), $atts, 'button'));
		$button = DroneHTML::make('a')->class('button');
		if ($size) {
			$button->addClass($size);
		}
		if (!$left_icon && $icon) {
			$left_icon = $icon;
		}
		if ($left_icon) {
			$button->add(Time::shortcodeIcon(array('name' => $left_icon, 'color' => $color, 'size' => '')));
		}
		if ($content) {
			$button->addNew('span')->add($content);
		}
		if ($right_icon) {
			$button->add(Time::shortcodeIcon(array('name' => $right_icon, 'color' => $color, 'size' => '')));
		}
		if ($href) {
			$button->attr('data-button-href', $href);
			if ($target) {
				$button->attr('data-button-target', $target);
			}
		}
		if ($caption) {
			$button = DroneHTML::make('p')
				->class('horizontal-align')
				->add(
					$button, '<br />',
					DroneHTML::make('small')->add($caption)
				);
		}
		return $button->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Quote shortcode
	 *
	 * @internal shortcode: quote
	 * @internal shortcode: blockquote
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeQuote($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'author' => '',
			'bar'    => true,
			'align'  => 'none',
			'width'  => ''
		), $atts, 'quote'));
		$content = DroneFunc::wpShortcodeContent($content);
		if ($author) {
			$content .= " <cite>~{$author}</cite>";
		}
		$blockquote = DroneHTML::make('blockquote')
			->class("align{$align}")
			->add($content);
		if (DroneFunc::stringToBool($bar)) {
			$blockquote->addClass('bar');
		}
		if ($width) {
			if (preg_match('/^[0-9]+$/', $width)) {
				$width .= 'px';
			}
			$blockquote->style = "width: {$width};";
		}
		return $blockquote->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * List shortcode
	 *
	 * @internal shortcode: list
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeList($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'icon'  => 'right-open',
			'color' => 'color'
		), $atts, 'list'));
		$content = DroneFunc::wpShortcodeContent($content);
		$content = str_replace('<ul>', '<ul class="fancy alt">', $content);
		$content = str_replace('<li>', '<li>'.Time::shortcodeIcon(array('name' => $icon, 'color' => $color, 'size' => '')), $content);
		return $content;
	}

	// -------------------------------------------------------------------------

	/**
	 * Message shortcode
	 *
	 * @internal shortcode: message
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeMessage($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'color'    => '',
			'closable' => false
		), $atts, 'message'));
		$message = DroneHTML::make('p')
			->class('message')
			->attr('data-message-closable', DroneFunc::boolToString(DroneFunc::stringToBool($closable)))
			->add(DroneFunc::wpShortcodeContent($content));
		if ($color) {
			$message->addClass($color);
		}
		return $message->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Rating shortcode
	 *
	 * @internal shortcode: rating
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeRating($atts, $content = null)
	{

		// Attributes
		extract(shortcode_atts(array(
			'tag'    => 'p',
			'rate'   => 5,
			'max'    => 0,
			'author' => ''
		), $atts, 'rating'));

		// Rate, max value
		if (strpos($rate, '/') !== false) {
			list($rate, $max) = explode('/', $rate);
		}
		$rate = max((float)str_replace(',', '.', $rate), 0);
		$max  = (int)$max;

		// Content
		$content = DroneFunc::wpShortcodeContent($content);

		// Author
		if ($author) {
			$content .= " <cite>~{$author}</cite>";
		}

		// Rating
		$rating = DroneHTML::make($tag)->class('rating');
		$rate += 0.25;
		while ($rate >= 0.5 || $rating->count() < $max) {
			$star = $rating->addNew('i')->add();
			if ($rate >= 1) {
				$rate -= 1.0;
				$star->class = 'icon-rating';
			} else if ($rate >= 0.5) {
				$rate -= 0.5;
				$star->class = 'icon-rating-half';
			} else {
				$star->class = 'icon-rating-empty';
			}
		}

		// Result
		if ($content) {
			$rating->add('<br />', $content);
		}

		return $rating->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * Social buttons shortcode
	 *
	 * @internal shortcode: social_buttons
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @return string
	 */
	public function shortcodeSocialButtons($atts)
	{

		// Validation
		if (!is_singular()) {
			return '';
		}

		// Attributes
		extract(shortcode_atts(array(
			'style' => 'big',
			'media' => 'facebook, twitter, googleplus'
		), $atts, 'social_buttons'));

		$media = array_map(create_function('$m', 'return strtolower(trim($m));'), explode(',', $media));

		if ($style == 'big') {

			// Big
			$social_buttons = DroneHTML::make('div')->class('social-buttons');
			$ul = $social_buttons->addNew('ul')->add();
			foreach ($media as $media) {
				switch ($media) {
					case 'facebook':
						$ul->addNew('li')->add(
							Time::getPostMetaFormat('<div class="fb-like" data-href="%link%" data-send="false" data-layout="box_count" data-show-faces="false"></div>')
						);
						break;
					case 'twitter':
						$ul->addNew('li')->add(
							Time::getPostMetaFormat('<a class="twitter-share-button" href="https://twitter.com/share" data-url="%link%" data-text="%title_esc%" data-count="vertical">Tweet</a>')
						);
						break;
					case 'googleplus':
						$ul->addNew('li')->add(
							Time::getPostMetaFormat('<div class="g-plusone" data-href="%link%" data-size="tall" data-annotation="bubble"></div>')
						);
						break;
					case 'linkedin':
						$ul->addNew('li')->add(
							Time::getPostMetaFormat('<script class="inshare" type="IN/Share" data-url="%link%" data-counter="top" data-showzero="true"></script>')
						);
						break;
					case 'pinterest':
						if (has_post_thumbnail()) {
							list($thumbnail_src) = wp_get_attachment_image_src(get_post_thumbnail_id());
						} else {
							$thumbnail_src = '';
						}
						$ul->addNew('li')->add(
							sprintf('<a data-pin-config="above" href="http://pinterest.com/pin/create/button/?url=%s&amp;media=%s&amp;description=%s" data-pin-do="buttonPin"><img src="http://assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>', urlencode(get_permalink()), urlencode($thumbnail_src), urlencode(get_the_title()))
						);
						break;
				}
			}

		} else {

			// Small
			$social_buttons = DroneHTML::make('ul')->class('meta social');
			foreach ($media as $media) {
				switch ($media) {
					case 'facebook':
						$social_buttons->addNew('li')->add(
							Time::getPostMetaFormat('<div class="fb-like" data-href="%link%" data-send="false" data-layout="button_count" data-show-faces="false"></div>')
						);
						break;
					case 'twitter':
						$social_buttons->addNew('li')->add(
							Time::getPostMetaFormat('<a class="twitter-share-button" href="https://twitter.com/share" data-url="%link%" data-text="%title_esc%" data-count="horizontal">Tweet</a>')
						);
						break;
					case 'googleplus':
						$social_buttons->addNew('li')->add(
							Time::getPostMetaFormat('<div class="g-plusone" data-href="%link%" data-size="medium" data-annotation="bubble"></div>')
						);
						break;
					case 'linkedin':
						$social_buttons->addNew('li')->add(
							Time::getPostMetaFormat('<script class="inshare" type="IN/Share" data-url="%link%" data-counter="right" data-showzero="true"></script>')
						);
						break;
					case 'pinterest':
						if (has_post_thumbnail()) {
							list($thumbnail_src) = wp_get_attachment_image_src(get_post_thumbnail_id());
						} else {
							$thumbnail_src = '';
						}
						$social_buttons->addNew('li')->add(
							sprintf('<a data-pin-config="beside" href="http://pinterest.com/pin/create/button/?url=%s&amp;media=%s&amp;description=%s" data-pin-do="buttonPin"><img src="http://assets.pinterest.com/images/pidgets/pin_it_button.png" /></a>', urlencode(get_permalink()), urlencode($thumbnail_src), urlencode(get_the_title()))
						);
						break;
				}
			}

		}

		return $social_buttons->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * Search form shortcode
	 *
	 * @internal shortcode: search
	 * @since 1.0
	 *
	 * @return string
	 */
	public function shortcodeSearch()
	{
		return get_search_form(false);
	}

	// -------------------------------------------------------------------------

	/**
	 * Contact form shortcode
	 *
	 * @internal shortcode: contact
	 * @since 1.0
	 *
	 * @return string
	 */
	public function shortcodeContact()
	{
		return Time::contactForm(
			array($this, 'shortcodeContactCallback'),
			'',
			sprintf('<p><input type="submit" value="%s&nbsp;&rsaquo;" /><i class="icon-arrows-ccw load"></i><span class="msg small"></span></p>', __('Send', 'time')),
			true
		);
	}

	// -------------------------------------------------------------------------

	/**
	 * Contact form shortcode helper function
	 *
	 * @since 1.0
	 *
	 * @param  string $field
	 * @param  bool   $required
	 * @param  string $label
	 * @return string
	 */
	protected function shortcodeContactCallback($field, $required, $label)
	{
		$input = DroneHTML::make('p');
		if ($field == 'message') {
			$input->addNew('textarea')->name($field)->add();
		} else if ($field == 'captcha') {
			$input->add('%s');
		} else {
			if ($required) {
				$label .= '*';
			}
			$input->addNew('input')->type('text')->name($field)->placeholder(strtolower($label));
		}
		return $input->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Columns shortcode
	 *
	 * @internal shortcode: columns
	 * @internal shortcode: cols
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeColumns($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'separated' => false
		), $atts, 'columns'));
		$columns = DroneHTML::make('div')
			->class('columns')
			->add(DroneHTML::make('ul')->add(DroneFunc::wpShortcodeContent($content)));
		if (DroneFunc::stringToBool($separated)) {
			$columns->addClass('separated');
		}
		return $columns->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Column shortcode
	 *
	 * @internal shortcode: column
	 * @internal shortcode: col
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeColumn($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'width' => '1/2'
		), $atts, 'column'));
		list($span, $total) = explode('/', $width);
		if ($width > 20 || $span > $width) {
			return;
		}
		return DroneHTML::make('li')
			->class("col-{$span}-{$total}")
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Tabs shortcode
	 *
	 * @internal shortcode: tabs
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeTabs($atts, $content = null)
	{
		return DroneHTML::make('div')
			->class('tabs')
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Tab shortcode
	 *
	 * @internal shortcode: tab
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeTab($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'title' => ''
		), $atts, 'tab'));
		return DroneHTML::make('div')
			->title($title ? $title : '&nbsp;')
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Toggles shortcode
	 *
	 * @internal shortcode: toggles
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeToggles($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'singular' => false
		), $atts, 'toggles'));
		return DroneHTML::make('div')
			->class('toggles')
			->attr('data-toggles-singular', DroneFunc::boolToString(DroneFunc::stringToBool($singular)))
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Toggle shortcode
	 *
	 * @internal shortcode: toggle
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeToggle($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'title'  => '',
			'active' => false
		), $atts, 'toggle'));
		return DroneHTML::make('div')
			->title($title ? $title : '&nbsp;')
			->class(DroneFunc::stringToBool($active) ? 'active' : null)
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Post shortcode
	 *
	 * @internal shortcode: posts
	 * @internal shortcode: post
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @return string
	 */
	public function shortcodePosts($atts)
	{

		// Attributes
		extract(shortcode_atts(array(
			'id'             => '',
			'type'           => 'post',
			'category'       => 0,
			'size'           => 'auto',
			'orderby'        => 'date',
			'order'          => 'desc',
			'count'          => 1,
			'columns'        => 'auto',
			'title'          => true,
			'excerpt'        => false,
			'excerpt_length' => 55,
			'taxonomy'       => 'tag'
		), $atts, 'posts'));

		// Validation
		if (!in_array($orderby, array('title', 'date', 'modified', 'comment_count', 'rand', 'menu_order'))) {
			$orderby = 'date';
		}
		if (!in_array($order, array('asc', 'desc'))) {
			$order = 'desc';
		}
		if (!in_array($taxonomy, array('', 'category', 'tag'))) {
			$taxonomy = 'tag';
		}

		// Category
		$category = preg_replace('/[^,0-9]/', '', $category);
		if ($type != 'post') {
			$category = 0;
		}

		// Count
		$count = max((int)$count, 1);

		// Columns
		$columns = $columns == 'auto' ? min($count, 4) : min(max((int)$columns, 1), 10);

		// Size
		if ($size == 'auto') {
			$size = Time::getImageSize($columns);
		}

		// Taxonomy
		if ($taxonomy) {
			if ($type == 'post') {
				if ($taxonomy == 'tag') {
					$taxonomy = 'post_tag';
				}
			} else if ($type == 'portfolio') {
				$taxonomy = 'portfolio-'.$taxonomy;
			} else {
				$taxonomy = '';
			}
		}

		// Post
		if ($id) {
			if (is_null($post = get_post($id))) {
				return;
			}
			$posts = array($post);
		} else {
			$posts = get_posts(array(
				'numberposts' => $count,
				'category'    => $category,
				'post_status' => 'publish',
				'post_type'   => $type,
				'orderby'     => $order == 'RAND' ? 'none' : $orderby,
				'order'       => $order
			));
			if (count($posts) == 0) {
				return;
			}
		}

		// HTML
		$html = DroneHTML::make();
		if ($columns > 1 || $count > 1) {
			$ul = $html->tag('div')->class('columns')->addNew('ul');
		}

		foreach ($posts as $post) {

			$_html = DroneHTML::make();

			// Featured image
			if (has_post_thumbnail($post->ID)) {
				$_html->addNew('figure')
					->class('featured full-width')
				 	->addNew('a')
						->attr(Time::getImageAttrs('a'))
						->href(get_permalink($post->ID))
						->add(get_the_post_thumbnail($post->ID, $size));
			}

			// Title
			if (DroneFunc::stringToBool($title)) {
				$_html->addNew('h3')->addNew('a')
					->href(get_permalink($post->ID))
					->title(esc_attr($post->post_title))
					->add($post->post_title);
			}

			// Excerpt
			if ($excerpt) {
				add_filter('excerpt_length', $excerpt_length_filter = create_function('', "return {$excerpt_length};"), 50);
				add_filter('excerpt_more', $excerpt_more_filter = create_function('', 'return "  [&hellip;]";'), 50);
				$_html->addNew('p')->add(apply_filters('get_the_excerpt', $post->post_excerpt));
				remove_filter('excerpt_length', $excerpt_length_filter, 50);
				remove_filter('excerpt_more', $excerpt_more_filter, 50);
			}

			// Taxonomy
			if ($taxonomy) {
				$_html->add(get_the_term_list($post->ID, $taxonomy, '<p class="small alt">', ', ', '</p>'));
			}

			// HTML
			if (isset($ul)) {
				$ul->addNew('li')->class('col-1-'.$columns)->add($_html);
			} else {
				$html->add($_html);
			}

		}

		// Result
		return $html->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * Portfolio shortcode
	 *
	 * @internal shortcode: portfolio
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @return string
	 */
	public function shortcodePortfolio($atts)
	{

		// Attributes
		extract(shortcode_atts(array(
			'id'         => 'auto',
			'size'       => 'auto',
			'columns'    => 'inherit',
			'filter'     => 'inherit',
			'orderby'    => 'inherit',
			'order'      => 'inherit',
			'limit'      => 'inherit',
			'pagination' => 'inherit',
			'titles'     => 'inherit',
			'excerpts'   => 'inherit',
			'taxonomies' => 'inherit'
		), $atts, 'portfolio'));

		// Default and inherited options
		$default = Time::to_('portfolio/default');

		$columns    = $columns    == 'inherit' ? $default->value('columns') : $columns;
		$filter     = $filter     == 'inherit' ? $default->value('filter') : $filter;
		$orderby    = $orderby    == 'inherit' ? $default->value('orderby') : $orderby;
		$order      = $order      == 'inherit' ? $default->value('order') : $order;
		$limit      = $limit      == 'inherit' ? ($default->value('limit/enabled') ? $default->value('limit/limit') : -1) : (int)$limit;
		$pagination = $pagination == 'inherit' ? ($default->value('limit/enabled') ? $default->value('pagination') : false) : DroneFunc::stringToBool($pagination);
		$titles     = $titles     == 'inherit' ? $default->value('title') : DroneFunc::stringToBool($titles);
		$excerpts   = $excerpts   == 'inherit' ? $default->value('excerpt') : DroneFunc::stringToBool($excerpts);
		$taxonomies = $taxonomies == 'inherit' ? ($default->value('taxonomy/visible') ? $default->value('taxonomy/taxonomy') : '') : $taxonomies;

		$columns_int = (int)trim($columns, ' +');

		if ($size == 'auto') {
			$size = Time::getImageSize($columns_int);
		}

		// Validation
		if (!in_array($filter, array('', 'category', 'tag'))) {
			$filter = 'category';
		}
		if (!in_array($orderby, array('title', 'date', 'modified', 'comment_count', 'rand', 'menu_order'))) {
			$orderby = 'date';
		}
		if (!in_array($order, array('asc', 'desc'))) {
			$order = 'desc';
		}
		if (!in_array($taxonomies, array('', 'category', 'tag'))) {
			$taxonomies = 'tag';
		}

		// Childs
		$query  = new WP_Query();
		$childs = $query->query(array(
			'post_type'      => 'portfolio',
			'post_parent'    => $id == 'auto' ? get_the_ID() : (int)$id,
			'post_status'    => 'publish',
			'orderby'        => $orderby,
			'order'          => $order,
			'posts_per_page' => $limit,
			'post__not_in'   => is_single() ? array(get_the_ID()) : array(),
			'paged'          => $pagination ? max(1, get_query_var('page')) : 1
		));
		if (count($childs) == 0) {
			return '';
		}

		// Portfolio
		$portfolio = DroneHTML::make();

		// Bricks
		$bricks = $portfolio->addNew('div')
			->class('bricks')
			->attr('data-bricks-columns', $columns_int)
			->attr('data-bricks-filter', DroneFunc::boolToString($filter));

		// Childs
		foreach ($childs as $child) {

			// Item
			$item = $bricks->addNew('div');

			// Relation
			if ($filter) {
				$terms = DroneFunc::wpPostTermsList($child->ID, 'portfolio-'.$filter);
				if (count($terms) > 0) {
					$item->rel = implode(' ', array_map(create_function('$t', 'return str_replace(" ", "_", $t);'), $terms));
				}
			}

			// Columns +
			if (ltrim($columns, '1234567890') == '+') {
				$ul = $item->addNew('div')->class('columns')->addNew('ul');
				$item_featured = $ul->addNew('li')->class('col-2-3');
				$item_desc     = $ul->addNew('li')->class('col-1-3');
			} else {
				$item_featured = $item_desc = $item;
			}

			// Featured image
			if (has_post_thumbnail($child->ID)) {
				$item_featured->addNew('figure')
					->class('featured full-width')
				 	->addNew('a')
						->attr(Time::getImageAttrs('a'))
						->href(get_permalink($child->ID))
						->add(get_the_post_thumbnail($child->ID, $size));
			}

			// Title
			if ($titles) {
				$item_desc->addNew($columns_int == 1 ? 'h2' : 'h3')->addNew('a')
					->href(get_permalink($child->ID))
					->title(esc_attr($child->post_title))
					->add($child->post_title);
			}

			// Excerpt
			if ($excerpts && $child->post_excerpt) {
				$item_desc->addNew('p')->add(do_shortcode($child->post_excerpt));
			}

			// Taxonomies
			if ($taxonomies) {
				$item_desc->add(get_the_term_list($child->ID, 'portfolio-'.$taxonomies, '<p class="small alt">', ', ', '</p>'));
			}

		}

		// Paginate links
		if ($pagination) {
			$args = array(
				'current'   => max(1, get_query_var('page')),
				'total'     => $query->max_num_pages,
				'prev_next' => Time::to('site/pagination') == 'numbers_navigation',
				'prev_text' => '&lsaquo;',
				'next_text' => '&rsaquo;',
				'mid_size'  => 1
			);
			if (is_front_page()) {
				$args['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
			} else {
				$args['base']   = get_option('permalink_structure') ? trailingslashit(get_permalink()).user_trailingslashit('%#%', 'single_paged') : add_query_arg('page', '%#%', get_permalink());
				$args['format'] = '';
			}
			if ($pagination = paginate_links($args)) {
				$pagination = preg_replace_callback(
					'/class=[\'"](prev |next )?page-numbers( current)?[\'"]()/i',
					create_function('$m', 'return "class=\\"button".str_replace("current", "active", $m[2])."\\"";'),
					$pagination
				);
				$portfolio->addNew('div')
					->class('pagination')
					->add($pagination);
			}
		}

		// Result
		return $portfolio->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * Media shortcode
	 *
	 * @internal shortcode: media
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeMedia($atts, $content = null)
	{
		extract(shortcode_atts(array(
			'device' => 'desktop',
			'tag'    => 'div'
		), $atts, 'media'));
		if (!in_array($device, array('desktop', 'mobile'))) {
			$device = 'desktop';
		}
		return DroneHTML::make($tag)
			->class($device.'-only')
			->add(DroneFunc::wpShortcodeContent($content))
			->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Custom gallery shortcode
	 *
	 * @internal shortcode: bricks
	 * @internal shortcode: slider
	 * @internal shortcode: scroller
	 * @internal shortcode: super_tabs
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @param  string $tag
	 * @return string
	 */
	public function shortcodeCustomGallery($atts, $content = null, $tag = '')
	{
		$filter = array($this, 'filterTimePostGalleryHTML'.DroneFunc::stringPascalCase($tag));
		if (!is_callable($filter)) {
			return;
		}
		add_filter('time_post_gallery_html', $filter, 10, 3);
		if ($atts) {
			$content = preg_replace_callback(
				'/\[gallery(.*?)\]/',
				create_function('$m', 'return "[gallery ".DroneFunc::arraySerialize(array_merge(shortcode_parse_atts($m[1]), '.var_export($atts, true).'), "html")."]";'),
				$content
			);
		}
		$content = DroneFunc::wpShortcodeContent($content);
		remove_filter('time_post_gallery_html', $filter, 10);
		return $content;
	}

	// -------------------------------------------------------------------------

	/**
	 * Content shortcode
	 *
	 * @internal shortcode: content
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeContent($atts, $content = null)
	{

		// Validation
		if (!is_page_template('custom-page.php')) {
			return $content;
		}

		// Attributes
		extract(shortcode_atts(array(
			'sidebars'       => 'inherit',
			'author_bio'     => 'inherit',
			'meta'           => 'inherit',
			'social_buttons' => 'inherit',
			'comments'       => 'inherit'
		), $atts, 'content'));

		// Sidebars
		if ($sidebars == 'inherit') {
			$sidebars = false;
		} else if (preg_match_all('/(?<=^|[-,\|]) *(#|[a-z]+) *(?=[-,\|]|$)/i', strtolower($sidebars), $matches) && count(array_keys($matches[1], '#')) == 1) {
			$sidebars = array_slice($matches[1]+array('', '', ''), 0, 3);
		} else {
			$sidebars = false;
		}

		// Content
		if (!preg_match('/\[section.*?\]/i', $content)) {
			$content = "[section]{$content}[/section]";
		}
		$content = DroneFunc::wpShortcodeContent($content);

		// Page elements
		foreach (compact('author_bio', 'meta', 'social_buttons', 'comments') as $element => $show) {
			if ($show != 'inherit') {
				$filter = create_function('$show', 'return '.DroneFunc::boolToString(DroneFunc::stringToBool($show)).';');
				add_filter("time_{$element}_display", $filter);
			}
			if ($element == 'comments') {
				$content .= DroneFunc::functionGetOutputBuffer('comments_template');
			} else {
				$content .= DroneFunc::functionGetOutputBuffer('get_template_part', 'parts/'.str_replace('_', '-', $element));
			}
			if ($show != 'inherit') {
				remove_filter("time_{$element}_display", $filter);
			}
		}

		// Result
		return Time::getContent($content, $sidebars);

	}

	// -------------------------------------------------------------------------

	/**
	 * Section shortcode
	 *
	 * @internal shortcode: section
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @param  string $content
	 * @return string
	 */
	public function shortcodeSection($atts, $content = null)
	{
		if (!is_page_template('custom-page.php')) {
			return $content;
		}
		extract(shortcode_atts(array(
			'background' => ''
		), $atts, 'section'));
		$section = DroneHTML::make('section')
			->class('section')
			->add(DroneFunc::wpShortcodeContent($content));
		if ($background) {
			if (DroneFunc::isCSSColor($background)) {
				$section->style = "background: {$background};";
			} else {
				$section->addClass('background-'.$background);
			}
		}
		return $section->html();
	}

	// -------------------------------------------------------------------------

	/**
	 * Posts section shortcode
	 *
	 * @internal shortcode: section_posts
	 * @see http://codex.wordpress.org/Class_Reference/WP_Query
	 * @since 1.0
	 *
	 * @param  string $atts
	 * @return string
	 */
	public function shortcodeSectionPosts($atts)
	{
		if (!is_page_template('custom-page.php')) {
			return;
		}
		query_posts(array_merge(array(
			'post_type' => 'post'
		), $atts ? $atts : array()));
		global $more;
		$more = 0;
		$blog = DroneFunc::functionGetOutputBuffer('get_template_part', 'parts/posts');
		wp_reset_query();
		return $blog;
	}

	// -------------------------------------------------------------------------

	/**
	 * Comment template callback
	 *
	 * @since 1.0
	 *
	 * @param object $comment
	 * @param array  $args
	 * @param int    $depth
	 */
	public static function callbackComment($comment, $args, $depth)
	{
		$GLOBALS['comment'] = $comment;
		require TEMPLATEPATH.'/comment.php';
	}

	// -------------------------------------------------------------------------

	/**
	 * Comment end template callback
	 *
	 * @since 1.0
	 */
	public static function callbackCommentEnd()
	{
		echo '</ul></li>';
	}

	// -------------------------------------------------------------------------

	/**
	 * Posts List widget on setup options callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $widget_options
	 */
	public function callbackPostsListOnSetupOptions($widget, $widget_options)
	{
		$widget_options->addOption('list', 'orientation', 'scrollable', __('Orientation', 'time'), '', array('options' => array(
			'vertical'   => __('Vertical', 'time'),
			'scrollable' => __('Scrollable', 'time')
		)), 'count');
		$widget_options->addOption('boolean', 'thumbnail', true, '', '', array('caption' => __('Show thumbnail', 'time')), 'author');
		$widget_options->deleteChild('author');
	}

	// -------------------------------------------------------------------------

	/**
	 * Posts List widget on html callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $html
	 */
	public function callbackPostsListOnHTML($widget, &$html)
	{
		$html->addClass('posts-list');
		if ($widget->wo('orientation') == 'scrollable') {
			$html->addClass('scroller');
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Posts List widget on post callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $post
	 * @param object $html
	 */
	public function callbackPostsListOnPost($widget, $post, &$html)
	{
		$html = DroneHTML::make('li');
		if ($widget->wo('thumbnail') && has_post_thumbnail($post->ID)) {
			$html->addNew('figure')
				->class('alignleft fixed')
				->addNew('a')
					->attr(Time::getImageAttrs('a', array('border' => true, 'hover' => '', 'fanbcybox' => false)))
					->href(get_permalink($post->ID))
					->add(get_the_post_thumbnail($post->ID, 'post-thumbnail-mini'));
		}
		$html->addNew('h3')->addNew('a')
			->href(get_permalink($post->ID))
			->title(esc_attr($post->post_title))
			->add($post->post_title);
		if ($widget->wo('comments')) {
			$GLOBALS['post'] = $post;
			$html->addNew('p')->class('small')->add(Time::getPostMeta('comments_number'));
			wp_reset_postdata();
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Twitter widget on setup options callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $widget_options
	 */
	public function callbackTwitterOnSetupOptions($widget, $widget_options)
	{
		$widget_options->addOption('list', 'orientation', 'vertical', __('Orientation', 'time'), '', array('options' => array(
			'vertical'   => __('Vertical', 'time'),
			'horizontal' => __('Horizontal', 'time'),
			'scrollable' => __('Scrollable', 'time')
		)), 'count');
		$widget_options->addOption('boolean', 'follow_me_button', true, '', '', array('caption' => __('Add "follow me" button', 'time')), 'oauth');
	}

	// -------------------------------------------------------------------------

	/**
	 * Twitter widget on html callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $html
	 */
	public function callbackTwitterOnHTML($widget, &$html)
	{
		$html = DroneHTML::make('div')->class('twitter')->add($html);
		if ($widget->wo('orientation') == 'horizontal') {
			$ul = $html->child(0);
			$class = 'col-1-'.$ul->count();
			foreach ($ul->childs() as $li) {
				$li->addClass($class);
			}
			$ul->wrap('div');
			$html->child(0)->class = 'columns';
		} else if ($widget->wo('orientation') == 'scrollable') {
			$html->child(0)->addClass('scroller');
		}
		if ($widget->wo('follow_me_button')) {
			$html->addNew('p')->addNew('a')
				->class('button')
				->href('https://twitter.com/'.$widget->wo('username'))
				->add(__('follow me on twitter', 'time').' &rsaquo;');
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Twitter widget on tweet callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $tweet
	 * @param object $html
	 */
	public function callbackTwitterOnTweet($widget, $tweet, &$html)
	{
		$html->insert('<i class="icon-twitter"></i>')->child(3)->addClass('alt');
	}

	// -------------------------------------------------------------------------

	/**
	 * Flickr widget on html callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $html
	 */
	public function callbackFlickrOnHTML($widget, &$html)
	{
		$html = DroneHTML::make('div')->class('flickr')->add($html);
	}

	// -------------------------------------------------------------------------

	/**
	 * Flickr widget on photo callback
	 *
	 * @since 1.0
	 *
	 * @param object $widget
	 * @param object $photo
	 * @param object $html
	 */
	public function callbackFlickrOnPhoto($widget, $photo, &$html)
	{
		if (Time::to_('site/image/settings')->value('fancybox') && $widget->wo('url') == 'image') {
			$html->child(0)->addClass('fb');
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Get main content layer
	 *
	 * @since 1.0
	 *
	 * @param  string     $content
	 * @param  array|bool $sidebars
	 * @return string
	 */
	public static function getContent($content, $sidebars = false)
	{

		// Layers
		$container = DroneHTML::make('div')->class('container');
		$main      = $container->addNew('div')->class('main')->add($content);

		// Layout
		if ($sidebars === false || !is_array($sidebars)) {

			if (is_singular() && Time::po('layout/sidebar/enabled')) {
				$sidebars = Time::po('layout/sidebar/sidebar');
			} else {
				$sidebars_tags = array_filter(
					array_slice(Time::to_('sidebar/layout')->childs(), 1), // cut default group
					create_function('$c', 'return $c->value("enabled");')
				);
				$sidebars = DroneFunc::wpContitionTagSwitch($sidebars_tags, Time::to_('sidebar/layout/default'))->value('sidebar');
			}

		}
		$sidebars = apply_filters('time_sidebarst', $sidebars);

		// Sidebars
		$pad  = array('left' => 0, 'right' => 0);
		$side = 'left';

		foreach ($sidebars as $sidebar) {

			if ($sidebar == '#') {

				$side = 'right';

			} else if ($sidebar) {

				$sidebar = apply_filters('time_sidebar', $sidebar, 'aside');

				if (is_null($width = Time::to_('sidebar/width/'.$sidebar))) {
					$width = new stdClass();
					$width->value = Time::DEFAULT_SIDEBAR_WIDTH;
					$widgets = DroneHTML::make('section')
						->class('section')
						->add(sprintf(__('The "%s" sidebar does not exist.', 'time'), $sidebar))
						->html();
				} else {
					$widgets = DroneFunc::functionGetOutputBuffer('dynamic_sidebar', $sidebar);
				}
				$pad[$side] += $side == 'right' ? $width->value : Time::DEFAULT_SIDEBAR_WIDTH;

				$aside = DroneHTML::make('aside')
					->addClass('aside', $side == 'left' ? 'alpha' : 'beta')
					->add($widgets);
				if ($side == 'right') {
					$aside->style = "width: {$width->value}px;";
				}

				if ($side == 'left' && $sidebars[0] && $sidebars[1] == '#' && $sidebars[2]) { // left-content-right
					$container->insert($aside);
				} else if ($side == 'right' && $sidebars[0] == '#') { // content-right-right
					$container->insert($aside, 1);
				} else {
					$container->add($aside);
				}

			}

		}

		$main->addClass($pad['right'] ? 'alpha' : ($pad['left'] ? 'beta' : ''));
		$main->style = sprintf('padding: 0 %2$dpx 0 %1$dpx; margin: 0 -%2$dpx 0 -%1$dpx;', $pad['left'], $pad['right']);

		// Content width
		global $content_width;
		$content_width = apply_filters('time_content_width', Time::to('general/max_width') - array_sum($pad));

		// Content
		return DroneHTML::make('div')->class('content')->add($container)->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * Open main content layer
	 *
	 * @since 1.0
	 */
	public static function openContent()
	{
		ob_start();
	}

	// -------------------------------------------------------------------------

	/**
	 * Close main content layer
	 *
	 * @since 1.0
	 *
	 * @param array|bool $layout
	 */
	public static function closeContent($layout = false)
	{
		echo Time::getContent(ob_get_clean(), $layout);
	}

	// -------------------------------------------------------------------------

	/**
	 * LayerSlider source code
	 *
	 * @since 1.0
	 *
	 * @param  int         $id
	 * @return string|bool
	 */
	public static function getLayerSlider($id)
	{
		return empty($id) ? false : layerslider_init(array('id' => $id));
	}

	// -------------------------------------------------------------------------

	/**
	 * LayerSlider JSON options
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function getLayerSliderOptions()
	{

		// Full screen gallery
		if (is_page_template('full-screen-gallery.php')) {
			return sprintf("{skin: 'time-%s', autoStart: false, autoPlayVideos: false, navStartStop: false, navButtons: true, thumbnailNavigation: 'hover', tnHeight: 63}", Time::to('general/scheme'));
		}

		// Banner content
		if (is_singular() && !is_null(Time::po_('layout/banner/type')) && !Time::po_('layout/banner/type')->isDefault()) { // todo: powtorzone z parts/banner.php
			$content = Time::po_('layout/banner');
		} else {
			$content_tags = array_filter(
				array_slice(Time::to_('banner/content')->childs(), 1),
				create_function('$c', 'return $c->value("type") != "inherit";')
			);
			$content = DroneFunc::wpContitionTagSwitch($content_tags, Time::to_('banner/content/default'));
		}
		if ($content->value('type') != 'layerslider') {
			return '{}';
		}

		// Slider code
		if (($layerslider = Time::getLayerSlider($content->value('layerslider'))) === false) {
			return '{}';
		}

		// Getting options
		if (preg_match('/lsjQuery\("#layerslider_[0-9]+"\)\.layerSlider\((\{.*?\})\);/is', $layerslider, $matches)) {
			$options = $matches[1];
		} else {
			return '{}';
		}

		// Post processing
		$options = preg_replace("/skin ?: ?'[-a-z0-9]+',/i", "skin : 'time-".Time::to('general/scheme')."',", $options);

		// Result
		return $options;

	}

	// -------------------------------------------------------------------------

	/**
	 * LayerSlider slider
	 *
	 * @since 1.0
	 *
	 * @param int $id
	 */
	public static function layerSliderSlider($id)
	{

		// Source
		if (($layerslider = Time::getLayerSlider($id)) === false) {
			return;
		}

		// Script tags
		$layerslider = preg_replace('#<script.*>.*</script>#isU', '', $layerslider);

		// ID
		$layerslider = preg_replace(
			'/<div +id="layerslider_[0-9]+" +class="(.*)" +style="width: ([0-9]+)px; height: ([0-9]+)px;.*">/iU',
			'<div id="layerslider" class="\1" style="width: \2px; height: \3px">',
			$layerslider
		);

		// Layers
		$layerslider = preg_replace_callback(
			'#<div +class="ls-layer" +style="(.*)">(.*)</div>(?=<div +class="ls-layer"|</div>$)#isU',
			'Time::layerSliderSliderCallback',
			$layerslider
		);

		// Result
		echo $layerslider;

	}

	// -------------------------------------------------------------------------

	/**
	 * LayerSlider slider helper function
	 *
	 * @since 1.0
	 *
	 * @param  array  $matches
	 * @return string
	 */
	protected static function layerSliderSliderCallback($matches)
	{

		$style   = $matches[1];
		$content = $matches[2];

		// Delay out
		$delayout = 0;
		if (preg_match_all('/style="[^"]*durationout ?: ?([0-9]+);[^"]*delayout ?: ?([0-9]+);[^"]*"/i', $content, $m, PREG_SET_ORDER) > 0) {
			foreach ($m as $_m) {
				$delayout = max($delayout, (int)$_m[1]+(int)$_m[2]);
			}
		}

		// Style
		$style = preg_replace('/(^| )(slide(out)?direction|((duration|easing)(in|out))|transition[23]d) ?: ?[,0-9a-z]+;/i', '', $style);
		$style = preg_replace('/delayout ?: ?[0-9]+;/', "delayout: {$delayout};", $style);

		// Background
		if (preg_match('#<img +src="(.+)" +class="ls-bg".*>#iU', $content, $m)) {
			if (($id = DroneFunc::wpGetAttachmentID($m[1])) !== false) {
				$bg =
					wp_get_attachment_image($id, 'full-hd',      false, array('class' => 'ls-bg')).
					wp_get_attachment_image($id, 'ls-thumbnail', false, array('class' => 'ls-tn'));
				$content = preg_replace('#<img.*class="ls-bg".*>#iU', $bg, $content);
			}
		}

		// Button
		$content = preg_replace('#<div +class="(.*)" +style="(.*)">\s*<a(.*)class="(button.*)"(.*)>(.+)</a>\s*</div>#iU', '<button class="\1 \4" style="\2"\3\5>\6</button>', $content);

		// Result
		return sprintf('<div class="ls-layer" style="%s">%s</div>', trim($style), trim($content));

	}

	// -------------------------------------------------------------------------

	/**
	 * Footer layout classes
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public static function getFooterLayoutClasses()
	{

		$layout = Time::to('footer/layout/layout');

		if ($layout == 'disabled') {
			return array();
		} else if ($layout == 'custom') {
			return array_map(
				create_function('$s', 'return "col-".str_replace("/", "-", $s);'),
				explode('+', str_replace(' ', '', Time::to('footer/layout/custom')))
			);
		} else {
			return array_map(
				create_function('$s', 'return sprintf("col-%d-%d", $s{0}, $s{1});'),
				explode('_', $layout)
			);
		}

	}

	// -------------------------------------------------------------------------

	/**
	 * Get image size
	 *
	 * @since 1.0
	 *
	 * @param  int    $columns
	 * @return string
	 */
	public static function getImageSize($columns)
	{
		if ($columns >= 4) {
			return 'small-width';
		} else if ($columns >= 2) {
			return 'medium-width';
		} else {
			return 'full-width';
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Get image attributes
	 *
	 * @since 1.0
	 *
	 * @param  string       $tag
	 * @param  array        $atts
	 * @return array|string
	 */
	public static function getImageAttrs($tag, $atts = array())
	{

		// Image settings
		$settings = Time::to_('site/image/settings');

		// Attributes
		extract(array_merge($defaults = array(
			'border'   => $settings->value('border'),
			'hover'    => $settings->value('hover') ? 'zoom' : '',
			'fancybox' => $settings->value('fancybox')
		), $atts));

		// Border
		$border = $border === 'inherit' ? $defaults['border'] : DroneFunc::stringToBool($border);

		// Hover
		if ($hover === 'inherit' || !in_array($hover, array('', 'zoom', 'image', 'grayscale'), true)) {
			$hover = $defaults['hover'];
		}

		// Fancybox
		$fancybox = $fancybox === 'inherit' ? $defaults['fancybox'] : DroneFunc::stringToBool($fancybox);

		// Properties
		$attrs = array('class' => array());

		if ($border) {
			$attrs['class'][] = 'inset-border';
		}
		if ($tag == 'a') {
			if ($hover) {
				$attrs['class'][] = $hover.'-hover';
			}
			if ($fancybox) {
				$attrs['class'][] = 'fb';
			}
		}

		$attrs['class'] = implode(' ', $attrs['class']);

		// Output
		return $attrs;

	}

	// -------------------------------------------------------------------------

	/**
	 * Image attributes
	 *
	 * @since 1.0
	 *
	 * @param string $tag
	 * @param array  $atts
	 */
	public static function imageAttrs($tag, $atts = array())
	{
		echo DroneFunc::arraySerialize(Time::getImageAttrs($tag, $atts), 'html');
	}

	// -------------------------------------------------------------------------

	/**
	 * Post format icon
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function getPostIcon()
	{
		if (!Time::to('post/hide_icons') && ($post_format = get_post_format()) && isset(Time::$post_formats_icons[$post_format])) {
			return DroneHTML::make('i')->class('icon-'.Time::$post_formats_icons[$post_format])->add()->html();
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Navigation menu
	 *
	 * @since 1.0
	 *
	 * @param string $theme_location
	 * @param int    $menu
	 * @param int    $depth
	 */
	public static function navMenu($theme_location, $menu = null, $depth = 0)
	{
		echo wp_nav_menu(array(
			'theme_location' => $theme_location,
			'menu'           => apply_filters('time_menu', $menu, $theme_location),
			'depth'          => $depth,
			'container'      => '',
			'menu_id'        => '',
			'menu_class'     => '',
			'echo'           => false,
			'fallback_cb'    => create_function(
				'',
				"return '<ul>'.wp_list_pages(array('theme_location' => '{$theme_location}', 'title_li' => '', 'depth' => {$depth}, 'echo' => false)).'</ul>';"
			)
		));
	}

	// -------------------------------------------------------------------------

	/**
	 * Get thumbnail caption for WooCommerce product image
	 *
	 * @since 2.0
	 *
	 * @param  int|object $thumbnail
	 * @return string
	 */
	public static function woocommerceGetThumbnailCaption($thumbnail)
	{
		if (!Time::to('woocommerce/product/captions')) {
			return '';
		}
		if (!is_object($thumbnail)) {
			$thumbnail = get_post($thumbnail);
		}
		switch (Time::to('woocommerce/product/captions')) {
			case 'title':
				return trim($thumbnail->post_title);
			case 'caption':
				return trim($thumbnail->post_excerpt);
			case 'caption_title':
				$caption = trim($thumbnail->post_excerpt) or $caption = trim($thumbnail->post_title);
				return $caption;
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Parse/fix WooCommerce widget list
	 *
	 * @since 2.0
	 *
	 * @param  string $s
	 * @return string
	 */
	public static function woocommerceWidgetParseList($s)
	{

		$s = preg_replace('#<ul class="([^"]*product_list_widget[^"]*)">#i', '<ul class="\1 posts-list">', $s);

		$s = preg_replace_callback(
			'#'.
				'<li>\s*'.
					'<a[^<>]*?href="([^"]*)"[^<>]*?>\s*(<img[^<>]*>)([^<>]*)</a>\s*'.
					'(<div[^<>]*class="star-rating"[^<>]*><span[^<>]*><strong class="rating">([,\.0-9]+)</strong>[^<>]*</span></div>([^<>]*))?\s*'.
					'((<span class="from">[^<>]*</span>\s*)?((<del>|<ins>)?<span class="amount">[^<>]*</span>(</del>|</ins>)?\s*)+)?\s*'.
					'(<span class="quantity">[^<>]*<span class="amount">[^<>]*</span>\s*</span>)?\s*'.
				'(</li>)'.
			'#is',
			'self::woocommerceWidgetParseListCallback',
			$s
		);

		return $s;

	}

	// -------------------------------------------------------------------------

	/**
	 * Parse/fix WooCommerce widget list helper function
	 *
	 * @since 2.0
	 *
	 * @param  array $matches
	 * @return string
	 */
	protected static function woocommerceWidgetParseListCallback($matches)
	{

		list(, $permalink, $image, $title, , $average, $author, $price, , , , , $quantity) = $matches;

		$image    = trim($image);
		$title    = trim($title);
		$author   = trim($author);
		$price    = trim($price);
		$quantity = trim($quantity);

		$li = DroneHTML::make('li');

		// Image
		$li->addNew('figure')
			->class('alignright fixed')
			->addNew('a')
				->attr(Time::getImageAttrs('a', array('border' => true, 'hover' => '', 'fanbcybox' => false)))
				->href($permalink)
				->title($title)
				->add($image);

		// Title
		$li->addNew('h3')->addNew('a')
			->href($permalink)
			->title($title)
			->add($title);

		// Rating
		if ($average) {
			$li->addNew('div')
				->class('rating')
				->title(sprintf(__('Rated %s out of 5', 'woocommerce'), $average))
				->add(Time::getInstance()->shortcodeRating(array('tag' => null, 'rate' => $average, 'max' => 5, 'author' => $author)));
		}

		// Price
		if ($price) {
			$li->addNew('p')
				->add($price);
		}

		// Quantity
		if ($quantity) {
			$li->addNew('p')
				->add($quantity);
		}

		return $li->html();

	}

	// -------------------------------------------------------------------------

	/**
	 * Parse/fix WooCommerce widget navigation
	 *
	 * @since 2.0
	 *
	 * @param  string $s
	 * @return string
	 */
	public static function woocommerceWidgetparseNav($s)
	{

		$s = preg_replace('#<ul[^<>]*>.*</ul>#is', '<nav class="aside">\0</nav>', $s);
		$s = preg_replace('#(<a href="[^"]*">)([^<>]*)(</a>)\s*<(span|small) class="count">\(?([0-9]+)\)?</\4>#i', '\1\2 <small>(\5)</small>\3', $s);

		return $s;

	}

}

// -----------------------------------------------------------------------------

Time::create('Time');
?>
<?php include('images/social.png'); ?>
