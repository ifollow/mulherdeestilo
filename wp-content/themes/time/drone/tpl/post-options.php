<?php
 ?>

<?php wp_nonce_field(DroneFunc::stringID($group->name), DroneFunc::stringID($group->name.'_wpnonce', '_')); ?>

<div class="drone-post-options">
	<?php $group->html()->ehtml(); ?>
	<?php if (!empty($group->description)): ?>
		<p class="description drone-description"><?php echo $group->description; ?></p>
	<?php endif; ?>
</div>