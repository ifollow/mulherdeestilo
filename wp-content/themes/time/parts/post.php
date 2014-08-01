<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
	<?php get_template_part('parts/featured', get_post_format()); ?>
	<?php
		if (is_search()) {
			the_excerpt();
		} else if (get_post_type() == 'post') {
			switch (Time::to(array('format_posts/'.(string)get_post_format().'/content', 'format_posts/standard/content'))) {
				case 'excerpt':
					the_excerpt();
					break;
				case 'excerpt_content':
					if (has_excerpt()) {
						the_excerpt();
						break;
					}
				case 'content':
					the_content(Time::to('post/readmore').'<i class="icon-forward"></i>');
					break;
			}
		} else {
			the_content(Time::to('post/readmore').'<i class="icon-forward"></i>');
		}
	?>
	<?php get_template_part('parts/social-buttons'); ?>
	<?php get_template_part('parts/meta'); ?>
</article>