<?php
 ?>

<h3><?php _e('Informations', $this->domain); ?></h3>
<table class="widefat">
	<colgroup>
		<col width="25%" />
		<col width="75%" />
	</colgroup>
	<thead>
		<tr>
			<th><?php _e('Name', $this->domain); ?></th>
			<th><?php _e('Value', $this->domain); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php _e('PHP version', $this->domain); ?></td>
			<td><?php echo PHP_VERSION; ?></td>
		</tr>
		<tr>
			<td><?php _e('MySQL version', $this->domain); ?></td>
			<td><?php if (function_exists('mysql_get_server_info')) echo esc_html(mysql_get_server_info()); ?></td>
		</tr>
		<tr>
			<td><?php _e('WordPress version', $this->domain); ?></td>
			<td><?php echo esc_html($this->wp_version); ?></td>
		</tr>
		<?php if (!is_null($this->parent_theme)): ?>
			<tr>
				<td><?php _e('Parent theme version', $this->domain); ?></td>
				<td><?php echo esc_html($this->parent_theme->version); ?></td>
			</tr>
		<?php endif; ?>
		<tr>
			<td><?php _e('Theme version', $this->domain); ?></td>
			<td><?php echo esc_html($this->theme->version); ?></td>
		</tr>
	</tbody>
</table>

<h3><?php _e('Configuration', $this->domain); ?></h3>
<table class="widefat">
	<colgroup>
		<col width="25%" />
		<col width="75%" />
	</colgroup>
	<thead>
		<tr>
			<th><?php _e('Name', $this->domain); ?></th>
			<th><?php _e('Value', $this->domain); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php _e('PHP max. execution time', $this->domain); ?></td>
			<td><?php if (function_exists('ini_get')) echo ini_get('max_execution_time'); ?>s</td>
		</tr>
		<tr>
			<td><?php _e('PHP memory limit', $this->domain); ?></td>
			<td><?php if (function_exists('ini_get')) echo ini_get('memory_limit').'B'; ?></td>
		</tr>
		<tr>
			<td><?php _e('WordPress memory limit', $this->domain); ?></td>
			<td><?php echo WP_MEMORY_LIMIT; ?>B</td>
		</tr>
	</tbody>
</table>

<h3><?php _e('Paths', $this->domain); ?></h3>
<table class="widefat">
	<colgroup>
		<col width="25%" />
		<col width="75%" />
	</colgroup>
	<thead>
		<tr>
			<th><?php _e('Name', $this->domain); ?></th>
			<th><?php _e('Value', $this->domain); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php _e('Home URL', $this->domain); ?></td>
			<td class="code"><?php echo home_url(); ?></td>
		</tr>
		<tr>
			<td><?php _e('Site URL', $this->domain); ?></td>
			<td class="code"><?php echo site_url(); ?></td>
		</tr>
		<tr>
			<td><?php _e('Template URL', $this->domain); ?></td>
			<td class="code"><?php echo get_template_directory_uri(); ?></td>
		</tr>
		<tr>
			<td><?php _e('Stylesheet URL', $this->domain); ?></td>
			<td class="code"><?php echo get_stylesheet_directory_uri(); ?></td>
		</tr>
		<tr>
			<td><?php _e('Template directory', $this->domain); ?></td>
			<td class="code"><?php echo get_template_directory(); ?></td>
		</tr>
		<tr>
			<td><?php _e('Stylesheet directory', $this->domain); ?></td>
			<td class="code"><?php echo get_stylesheet_directory(); ?></td>
		</tr>
	</tbody>
</table>

<h3><?php _e('Settings', $this->domain); ?></h3>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
	<p><?php _e('Export Theme Options settings to file.', $this->domain); ?></p>
	<p><input class="button" type="submit" value="<?php _e('Export settings', $this->domain) ?>" name="settings-export" /></p>
</form>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data">
	<p>
		<label for="settings-file"><?php _e('Choose a file from your computer', $this->domain); ?>:</label>
		<input type="file" name="settings-import-file" />
	</p>
	<p><input class="button" type="submit" value="<?php _e('Import settings', $this->domain) ?>" name="settings-import" /></p>
</form>

<h3><?php _e('Options', $this->domain); ?></h3>