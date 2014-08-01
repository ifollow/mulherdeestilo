<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

// Separator
$separator = '&rsaquo;'; // is_rtl() ? '&lsaquo;' : '&rsaquo;'

// bbPress
if (Time::$plugins['bbpress'] && Time::to('bbpress/breadcrumbs') && is_bbpress()) {
	$breadcrumbs_html = bbp_get_breadcrumb(array(
		'before'         => '',
		'after'          => '',
		'sep'            => $separator,
		'sep_before'     => '',
		'sep_after'      => '',
		'current_before' => '',
		'current_after'  => ''
	));
}

// WooCommerce
else if (Time::$plugins['woocommerce'] && Time::to('woocommerce/breadcrumbs') && (is_shop() || is_product_taxonomy() || is_product())) { //  || is_cart() || is_checkout() || is_order_received_page() || is_account_page()
	$breadcrumbs_html = DroneFunc::functionGetOutputBuffer('woocommerce_breadcrumb', array(
		'delimiter'   => $separator,
		'wrap_before' => '',
		'wrap_after'  => ''
	));
}

// http://wordpress.org/extend/plugins/breadcrumb-navxt/
else if (function_exists('bcn_display')) {
	$options   = get_option('bcn_options');
	$separator = $options['hseparator'];
	$breadcrumbs_html = bcn_display(true);
}

// http://wordpress.org/extend/plugins/breadcrumb-trail/
else if (function_exists('breadcrumb_trail')) {
	$breadcrumbs_html = breadcrumb_trail(array(
		'separator'   => $separator,
		'show_browse' => false,
		'echo'        => false
	));
}

// http://wordpress.org/extend/plugins/breadcrumbs/
else if (function_exists('yoast_breadcrumb')) {
	if (Time::$plugins['wpseo']) {
		$options   = get_option('wpseo_internallinks');
		$separator = $options['breadcrumbs-sep'] ? $options['breadcrumbs-sep'] : '&raquo;';
	} else {
		$options   = get_option('yoast_breadcrumbs');
		$separator = $options['sep'];
	}
	$breadcrumbs_html = yoast_breadcrumb('', '', false);
}

else {
	return;
}

// Processing breadcrumbs
$breadcrumbs = explode($separator, $breadcrumbs_html);
$breadcrumbs = array_map(create_function('$a', 'return "<li>".trim($a)."</li>";'), $breadcrumbs);

?>

<ul class="breadcrumbs alt"><?php echo implode(' ', $breadcrumbs); ?></ul>