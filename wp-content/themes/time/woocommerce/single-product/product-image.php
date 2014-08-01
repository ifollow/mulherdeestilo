<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.0
 * @version    2.0.14
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

global $post, $woocommerce, $product;

if (has_post_thumbnail()) {

	$image_title = esc_attr(Time::woocommerceGetThumbnailCaption(get_post_thumbnail_id()));
	$image_link  = wp_get_attachment_url(get_post_thumbnail_id());
	$image       = get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', Time::getImageSize(2)), array(
		'title' => $image_title
	));

	echo apply_filters('woocommerce_single_product_image_html', sprintf('<figure class="full-width"><a href="%s" itemprop="image" data-fancybox-title="%s" rel="fb[product-gallery]" '.DroneFunc::arraySerialize(Time::getImageAttrs('a'), 'html').'>%s</a></figure>', $image_link, $image_title, $image), $post->ID);

} else {

	echo apply_filters('woocommerce_single_product_image_html', sprintf('<figure class="full-width"><div '.DroneFunc::arraySerialize(Time::getImageAttrs('div'), 'html').'><img src="%s" alt="Placeholder" /></div></figure>', woocommerce_placeholder_img_src()), $post->ID);

}

do_action('woocommerce_product_thumbnails');