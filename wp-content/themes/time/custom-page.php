<?php
/**
 * @template name: Custom page
 * @package        WordPress
 * @subpackage     Time
 * @since          1.0
 */

add_filter('time_pagination_display', create_function(
	'$show, $type',
	'return $type == "blog" ? false : $show;'
), 10, 2);

?>

<?php get_header(); ?>

<?php
	if (have_posts()) {
		the_post();
		$content = get_the_content();
	} else {
		$content = '';
	}
?>

<?php if ($content): ?>
	<?php
		if (!preg_match('#\[content.*\].*\[/content\]#isU', $content)) {
			$content = "[content]\n\n{$content}\n\n[/content]";
		}
	?>
	<div class="outer-container">
		<?php get_template_part('parts/nav-secondary', 'lower'); ?>
		<?php echo DroneFunc::wpProcessContent($content); ?>
	</div>
<?php endif; ?>

<?php get_footer(); ?>