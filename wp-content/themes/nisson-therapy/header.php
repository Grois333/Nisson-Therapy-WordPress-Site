<?php
/**
 * Header template
 *
 * @package NissonTherapy
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'nisson-therapy' ); ?></a>

<header id="masthead" class="site-header" role="banner">
	<div class="header-container">
		<div class="site-branding">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-link">
					<span class="site-title"><?php bloginfo( 'name' ); ?></span>
				</a>
				<?php
			}
			?>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'nisson-therapy' ); ?>">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'nisson-therapy' ); ?></span>
				<span class="menu-icon"></span>
			</button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'menu_class'     => 'nav-menu',
				)
			);
			?>
		</nav>

		<?php
		// Get CTA button from ACF options if set
		$cta_text = get_field( 'header_cta_text', 'option' );
		$cta_link = get_field( 'header_cta_link', 'option' );
		if ( $cta_text && $cta_link ) {
			?>
			<div class="header-cta">
				<a href="<?php echo esc_url( $cta_link ); ?>" class="btn btn-primary">
					<?php echo esc_html( $cta_text ); ?>
				</a>
			</div>
			<?php
		}
		?>
	</div>
</header>

