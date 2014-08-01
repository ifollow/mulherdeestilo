<?php
/**
 * @package    WooCommerce/Templates
 * @subpackage Time
 * @since      2.0
 * @version    1.6.4
 */

// -----------------------------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}

// -----------------------------------------------------------------------------

if (Time::$headline_used) {
	return;
}

?>

<h1 itemprop="name"><?php the_title(); ?></h1>