<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.0
 * @version    2.0.0
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

global $wp_query;

// Pagination
$pagination = paginate_links(apply_filters('woocommerce_pagination_args', array(
	'base'      => str_replace(999999999, '%#%', get_pagenum_link(999999999)),
	'format'    => '',
	'current'   => max(1, get_query_var('paged')),
	'total'     => $wp_query->max_num_pages,
	'prev_next' => Time::to('woocommerce/shop/pagination') == 'numbers_navigation',
	'prev_text' => '&lsaquo;',
	'next_text' => '&rsaquo;',
	'mid_size'  => 1
)));

if (!$pagination) {
	return;
}

// Post processing
$pagination = preg_replace_callback(
	'/class=[\'"](prev |next )?page-numbers( current)?[\'"]()/i',
	create_function('$m', 'return "class=\\"button".str_replace("current", "active", $m[2])."\\"";'),
	$pagination
);

// Filters
if (!apply_filters('time_pagination_display', true, 'woocommerce')) {
	return;
}

?>

<hr />
<div class="woocommerce-pagination pagination"><?php echo $pagination; ?></div>