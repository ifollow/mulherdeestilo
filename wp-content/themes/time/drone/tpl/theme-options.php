<?php
 ?>

<div class="wrap">

	<?php screen_icon('themes'); ?>
	<h2><?php echo get_admin_page_title(); ?></h2>

	<?php settings_errors('general'); ?>

	<?php if ($this->plugin_page == self::SYSINFO_SLUG) require $this->drone_dir.'/tpl/sysinfo.php'; ?>

	<form method="post" action="<?php echo self::WP_OPTIONS_URL; ?>">
		<?php settings_fields($this->theme->id); ?>
		<div class="drone-theme-options">
			<?php $group->html()->ehtml(); ?>
			<p class="submit">
				<input id="submit" name="submit" type="submit" value="<?php _e('Save Changes', $this->domain); ?>" class="button-primary" disabled />
			</p>
		</div>
	</form>

</div>