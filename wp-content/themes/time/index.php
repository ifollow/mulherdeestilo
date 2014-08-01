<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php get_header(); ?>

<div class="outer-container">

	<?php get_template_part('parts/nav-secondary', 'lower'); ?>

	<?php Time::openContent(); ?>

		<?php get_template_part('parts/posts'); ?>

	<?php Time::closeContent(); ?>

</div>

<?php get_footer(); ?>