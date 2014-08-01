<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>

<?php
	$nav = Time::po_('layout/nav_secondary/lower');
	if (is_singular() && !is_null($nav) && !$nav->isDefault()) {
		$nav_display = (bool)$nav->value;
		$nav_menu    = is_numeric($nav->value) ? (int)$nav->value : null;
	} else {
		$nav_display = DroneFunc::wpContitionTagSwitch(Time::to_('nav/secondary/lower')->values(), false);
		$nav_menu    = null;
	}
	if ((DroneFunc::wpAssignedMenu('secondary-lower') || !is_null($nav_menu)) && apply_filters('time_nav_secondary_lower_display', $nav_display)):
?>
	<nav class="secondary">
		<div class="container">
			<?php Time::navMenu('secondary-lower', $nav_menu); ?>
		</div>
	</nav>
<?php endif; ?>