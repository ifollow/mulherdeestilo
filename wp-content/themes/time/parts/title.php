<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php if (Time::$plugins['bbpress'] && is_bbpress()): ?>
	<?php if (!Time::$headline_used): ?><h1 class="title"><?php the_title(); ?></h1><?php endif; ?>

<?php elseif (!is_singular()): ?>
	<h1 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(Time::getPostIcon()); ?></a></h1>

<?php elseif (!Time::$headline_used): ?>
	<h1 class="title"><?php the_title(Time::getPostIcon()); ?></h1>

<?php endif; ?>