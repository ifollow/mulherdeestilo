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

if (class_exists('WC_Widget_Brand_Nav')):

class Time_WC_Widget_Brand_Nav extends WC_Widget_Brand_Nav
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