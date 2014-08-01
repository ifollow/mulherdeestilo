<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.2.1
 */
?>

<?php get_header(); ?>

<div class="outer-container">

	<?php get_template_part('parts/nav-secondary', 'lower'); ?>

	<?php Time::openContent(); ?>

		<section class="section">
			<?php woocommerce_content(); ?>
		</section>

	<?php Time::closeContent(); ?>

</div>

<?php get_footer(); ?>