<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      2.0
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Best_Sellers')):

class Time_WC_Widget_Best_Sellers extends WC_Widget_Best_Sellers // WooCommerce <= 2.0.20
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Cart')):

class Time_WC_Widget_Cart extends WC_Widget_Cart
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		extract($args);

		if ($instance['show_cart_icon']) {
			$output = str_replace($before_title, $before_title.Time::getInstance()->shortcodeIcon(array('name' => Time::to('woocommerce/cart/icon'), 'size' => '', 'class' => 'icon-woocommerce-cart', 'style' => 'margin: 0 0 0 -0.4em;')), $output);
		}

		echo $output;

	}

	// -------------------------------------------------------------------------

	function update($new_instance, $old_instance)
	{
		$instance = parent::update($new_instance, $old_instance);
		$instance['show_cart_icon'] = empty($new_instance['show_cart_icon']) ? 0 : 1;
		return $instance;
	}

	// -------------------------------------------------------------------------

	function form($instance)
	{
		parent::form($instance);
		$show_cart_icon = empty($instance['show_cart_icon']) ? 0 : 1;
		?>
			<p><input type="checkbox" class="checkbox" id="<?php echo esc_attr($this->get_field_id('show_cart_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('show_cart_icon')); ?>"<?php checked($show_cart_icon); ?> />
			<label for="<?php echo $this->get_field_id('show_cart_icon'); ?>"><?php _e('Display cart icon', 'woocommerce'); ?></label></p>
		<?php
	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Featured_Products')):

class Time_WC_Widget_Featured_Products extends WC_Widget_Featured_Products // WooCommerce <= 2.0.20
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Layered_Nav_Filters')):

class Time_WC_Widget_Layered_Nav_Filters extends WC_Widget_Layered_Nav_Filters
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		$footer = strpos($args['id'], 'footer-') === 0;

		$output = preg_replace('#<ul>(.*?)</ul>#i', '<p>\1</p>', $output);
		$output = preg_replace('#<li[^<>]*>(<a[^<>]*>)(.*?)(</a>)</li>#i', '<mark>\1<i class="icon-cancel"></i><i class="icon-cancel-circled"></i> \2\3</mark> ', $output);

		echo $output;

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Layered_Nav')):

class Time_WC_Widget_Layered_Nav extends WC_Widget_Layered_Nav
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseNav($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Onsale')):

class Time_WC_Widget_Onsale extends WC_Widget_Onsale // WooCommerce <= 2.0.20
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Price_Filter')):

class Time_WC_Widget_Price_Filter extends WC_Widget_Price_Filter
{
}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Product_Categories')):

class Time_WC_Widget_Product_Categories extends WC_Widget_Product_Categories
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseNav($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Product_Search')):

class Time_WC_Widget_Product_Search extends WC_Widget_Product_Search
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		$output = str_replace('<form ', '<form class="search" ', $output);
		$output = preg_replace('#<input type="submit"([^<>]*)>#i', '<button type="submit"\1><i class="icon-search"></i></button>', $output);

		echo $output;

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Product_Tag_Cloud')):

class Time_WC_Widget_Product_Tag_Cloud extends WC_Widget_Product_Tag_Cloud
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		$output = str_replace('<div class="tagcloud">', '<div class="tagcloud alt">', $output);

		echo $output;

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Products')):

class Time_WC_Widget_Products extends WC_Widget_Products
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Random_Products')):

class Time_WC_Widget_Random_Products extends WC_Widget_Random_Products // WooCommerce <= 2.0.20
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Recent_Products')):

class Time_WC_Widget_Recent_Products extends WC_Widget_Recent_Products // WooCommerce <= 2.0.20
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Recent_Reviews')):

class Time_WC_Widget_Recent_Reviews extends WC_Widget_Recent_Reviews
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Recently_Viewed')):

class Time_WC_Widget_Recently_Viewed extends WC_Widget_Recently_Viewed
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;

// -----------------------------------------------------------------------------

if (class_exists('WC_Widget_Top_Rated_Products')):

class Time_WC_Widget_Top_Rated_Products extends WC_Widget_Top_Rated_Products
{

	// -------------------------------------------------------------------------

	function widget($args, $instance)
	{

		ob_start();
		parent::widget($args, $instance);
		$output = ob_get_clean();

		echo Time::woocommerceWidgetParseList($output);

	}

}

endif;