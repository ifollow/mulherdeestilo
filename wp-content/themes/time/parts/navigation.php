<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if (!$prev_post && !$next_post) {
	return;
}

?>

<div class="nav">

	<?php if ($prev_post): ?>
		<a class="button" href="<?php echo esc_url(get_permalink($prev_post)); ?>" title="<?php echo esc_attr($prev_post->post_title); ?>">
			&lsaquo;&nbsp;<?php _e('previous', 'time') ?>
		</a>
	<?php else: ?>
		<a class="button disabled">&lsaquo;&nbsp;<?php _e('previous', 'time') ?></a>
	<?php endif; ?>

	<?php if ($next_post): ?>
		<a class="button" href="<?php echo esc_url(get_permalink($next_post)); ?>" title="<?php echo esc_attr($next_post->post_title); ?>">
			<?php _e('next', 'time') ?>&nbsp;&rsaquo;
		</a>
	<?php else: ?>
		<a class="button disabled"><?php _e('next', 'time') ?>&nbsp;&rsaquo;</a>
	<?php endif; ?>

</div>