<?php
/**
 * @template name: Contact form
 * @package        WordPress
 * @subpackage     Time
 * @since          1.0
 */

/*
add_filter('the_content', create_function(
	'$content',
	'return $content.Time::getInstance()->shortcodeContact(array());'
));

get_template_part('page', 'contact-form');
*/

?>

<?php get_header(); ?>

<div class="outer-container">

	<?php get_template_part('parts/nav-secondary', 'lower'); ?>

	<?php Time::openContent(); ?>

		<?php if (have_posts()): the_post(); ?>

			<section class="section">
				<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
					<?php get_template_part('parts/post-thumbnail'); ?>
					<?php if (!Time::io('layout/page/hide_title', 'page/hide_title')) get_template_part('parts/title'); ?>
					<?php the_content(); ?>
					<?php echo Time::getInstance()->shortcodeContact(array()); // todo: zastapic hookiem ?>
					<?php get_template_part('parts/paginate-links', 'page'); ?>
				</article>
			</section>

			<?php get_template_part('parts/author-bio'); ?>
			<?php get_template_part('parts/social-buttons'); ?>
			<?php get_template_part('parts/meta'); ?>
			<?php comments_template(); ?>

		<?php endif; ?>

	<?php Time::closeContent(); ?>

</div>

<?php get_footer(); ?>