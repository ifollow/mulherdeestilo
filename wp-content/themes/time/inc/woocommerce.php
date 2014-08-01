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

function woocommerce_output_related_products() {
	woocommerce_related_products(4, 4);
}

// -----------------------------------------------------------------------------

function woocommerce_related_products($posts_per_page = 4, $columns = 4, $orderby = 'rand') {
	woocommerce_get_template('single-product/related.php', array(
		'posts_per_page'  => $posts_per_page,
		'orderby'         => $orderby,
		'columns'         => $columns
	));
}

// -----------------------------------------------------------------------------

function woocommerce_upsell_display($posts_per_page = '-1', $columns = 4, $orderby = 'rand') {
	woocommerce_get_template('single-product/up-sells.php', array(
		'posts_per_page'  => $posts_per_page,
		'orderby'         => $orderby,
		'columns'         => $columns
	));
}

// -----------------------------------------------------------------------------

function woocommerce_get_product_thumbnail($size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0)
{

	global $post, $product;

	// Figure
	$figure = DroneHTML::make('figure')
		->class('featured full-width');

	// Hyperlink
	$a = $figure->addNew('a')
		->attr(Time::getImageAttrs('a', array('hover' => Time::to('woocommerce/shop/image_hover'))))
		->href(get_permalink());

	// Image
	if (has_post_thumbnail()) {
		$a->add(get_the_post_thumbnail($post->ID, $size));
		if (Time::to('woocommerce/shop/image_hover') == 'image') {
			$attachment_ids = $product->get_gallery_attachment_ids();
			if (isset($attachment_ids[0])) {
				$a->add(wp_get_attachment_image($attachment_ids[0], $size));
			}
		}
	} elseif (woocommerce_placeholder_img_src()) {
		$a->add(woocommerce_placeholder_img($size));
	}

	return $figure->html();

}

// -----------------------------------------------------------------------------

function woocommerce_subcategory_thumbnail($category)
{

	// Figure
	$figure = DroneHTML::make('figure')
		->class('featured full-width');

	// Hyperlink
	$a = $figure->addNew('a')
		->attr(Time::getImageAttrs('a'))
		->href(get_term_link($category->slug, 'product_cat'));

	// Thumbnail
	$thumbnail_id   = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);
	$thumbnail_size = apply_filters('single_product_small_thumbnail_size', 'shop_catalog');

	if ($thumbnail_id) {
		$a->add(wp_get_attachment_image($thumbnail_id, $thumbnail_size));
	} elseif (woocommerce_placeholder_img_src()) {
		$a->add(woocommerce_placeholder_img($thumbnail_size));
	}

	$figure->ehtml();

}