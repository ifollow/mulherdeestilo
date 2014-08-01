<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php if (apply_filters('time_comments_display', Time::to(array(get_post_type().'/comments', 'page/comments'))) && !post_password_required()): ?>

	<?php if (have_comments()): ?>

		<section id="comments" class="section">
			<ul class="comments">
				<?php wp_list_comments(array(
					'style'        => 'div',
					'callback'     => 'Time::callbackComment',
					'end-callback' => 'Time::callbackCommentEnd'
				)); ?>
			</ul>
			<?php get_template_part('parts/paginate-links', 'comments'); ?>
		</section>

	<?php endif; ?>

	<?php if (comments_open()): ?>
		<section class="section">
			<?php comment_form(); ?>
		</section>
	<?php endif; ?>

<?php endif; ?>