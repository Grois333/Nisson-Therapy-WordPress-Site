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
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			if ( $custom_logo_id ) {
				// Get logo URL - this works for both SVG and regular images
				$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
				$logo_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
				if ( empty( $logo_alt ) ) {
					$logo_alt = get_bloginfo( 'name', 'display' );
				}
				
				if ( $logo_url ) {
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" class="custom-logo" />
					</a>
					<?php
				} else {
					// Fallback to WordPress default
					the_custom_logo();
				}
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo-link">
					<span class="site-title"><?php bloginfo( 'name' ); ?></span>
				</a>
				<?php
			}
			?>
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'nisson-therapy' ); ?></span>
				<span class="menu-icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
		</div>

		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'nisson-therapy' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'menu_class'     => 'nav-menu',
					'link_before'    => '',
					'link_after'     => '',
				)
			);
			?>
		</nav>

		<div class="header-cta">
			<?php
			// Get CTA button from ACF options if set
			$cta_text = get_field( 'header_cta_text', 'option' );
			$cta_link = get_field( 'header_cta_link', 'option' );
			if ( $cta_text && $cta_link ) {
				?>
				<a href="<?php echo esc_url( $cta_link ); ?>" class="btn btn-primary header-cta-button">
					<?php echo esc_html( $cta_text ); ?>
				</a>
				<?php
			}
			?>
			<span class="header-hebrew-text" dir="rtl"><strong>בס"ד</strong></span>
		</div>
	</div>
</header>

