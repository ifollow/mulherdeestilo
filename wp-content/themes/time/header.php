<?php
/**
 * @package    WordPress
 * @subpackage Time
 * @since      1.0
 */
?>
<!DOCTYPE html>
<!--[if lt IE 9]>             <html class="no-js ie lt-ie9" <?php language_attributes(); ?>"><![endif]-->
<!--[if IE 9]>                <html class="no-js ie ie9" <?php language_attributes(); ?>>   <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js no-ie" <?php language_attributes(); ?>>    <!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" />
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="<?php echo Time::get('template_uri'); ?>/data/js/selectivizr.min.js"></script>
		<![endif]-->
		<title><?php wp_title('-', true, 'right'); ?></title>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>

		<div id="top" <?php
			$class = array();
			if (Time::to('header/hide_bar'))         $class[] = 'no-bar';
			if (Time::to('header/style') == 'fixed') $class[] = 'fixed';
			if (!empty($class)) {
				printf('class="%s"', implode(' ', $class));
			}
		?>>

			<?php get_template_part('parts/background', 'boxed'); ?>

			<div class="upper-container <?php if (Time::to('header/style') == 'fixed') echo 'fixed'; ?>">

				<div class="outer-container">

					<?php if (Time::to_('header/primary')->value('mobile')): ?>
						<nav id="menu" class="mobile">
							<?php Time::navMenu('primary-mobile'); ?>
						</nav>
					<?php endif; ?>

					<?php if (Time::to_('header/search')->value('mobile')): ?>
						<nav id="search" class="mobile">
							<?php get_search_form(); ?>
						</nav>
					<?php endif; ?>

				</div>

				<div class="outer-container <?php if (Time::to('header/style') == 'blank') echo 'blank'; ?>">

					<header class="header">

						<div class="container">

							<div class="mobile-helper vertical-align">

								<?php if (Time::to_('header/primary')->value('mobile')): ?>
									<a href="#menu" class="button" title="<?php _e('Menu', 'time'); ?>"><i class="icon-menu"></i></a>
								<?php endif; ?>

								<?php if (Time::$plugins['woocommerce'] && Time::to('header/cart/enabled')): global $woocommerce; ?>
									<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="button" title="<?php _e('Cart', 'time'); ?>">
										<?php echo Time::getInstance()->shortcodeIcon(array('name' => Time::to('woocommerce/cart/icon'), 'size' => '')) ?>
										<?php if ($woocommerce->cart->cart_contents_count > 0): ?>
											<span><?php echo $woocommerce->cart->cart_contents_count; ?></span>
										<?php endif; ?>
									</a>
								<?php endif; ?>

								<?php if (Time::to_('header/search')->value('mobile')): ?>
									<a href="#search" class="button" title="<?php _e('Search', 'time'); ?>"><i class="icon-search"></i></a>
								<?php endif; ?>

							</div>

							<?php if (Time::to('header/lang') == 'short'): ?>
								<nav class="lang vertical-align">
									<ul>
										<?php foreach (icl_get_languages('skip_missing=0&orderby=code') as $lang): ?>
											<li <?php if ($lang['active']) echo 'class="current"'; ?>>
												<a href="<?php echo $lang['url']; ?>" title="<?php echo $lang['native_name']; ?>">
													<?php echo $lang['language_code']; ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								</nav>
							<?php endif; ?>

							<?php get_template_part('parts/logo'); ?>

							<?php if (Time::to_('header/primary')->value('desktop') && !Time::to('header/logo/center')): ?>
								<nav class="primary vertical-align">
									<?php Time::navMenu('primary-desktop'); ?>
								</nav>
							<?php endif; ?>

						</div>

					</header>

					<?php get_template_part('parts/nav-secondary', 'upper'); ?>

				</div>

			</div>

			<?php get_template_part('parts/banner'); ?>
			<?php get_template_part('parts/headline'); ?>