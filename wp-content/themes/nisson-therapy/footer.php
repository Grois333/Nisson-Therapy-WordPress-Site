<?php
/**
 * Footer template
 *
 * @package NissonTherapy
 */
?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="footer-container">
		<?php
		// Get footer fields from ACF options
		// Try both field names in case of sync issues
		$footer_logo_image = get_field( 'footer_logo_image', 'option' );
		if ( ! $footer_logo_image ) {
			$footer_logo_image = get_field( 'footer_logo', 'option' ); // Fallback to old field name
		}
		$footer_links = get_field( 'footer_links', 'option' );
		$footer_phone_label = get_field( 'footer_phone_label', 'option' );
		$footer_phone_israel = get_field( 'footer_phone_israel', 'option' );
		$footer_phone_us = get_field( 'footer_phone_us', 'option' );
		$footer_location = get_field( 'footer_location', 'option' );
		$footer_confidentiality = get_field( 'footer_confidentiality', 'option' );
		
		// Debug: Check if field exists (remove after testing)
		// if ( current_user_can( 'manage_options' ) ) {
		// 	echo '<!-- Footer Logo Debug: ' . print_r( $footer_logo_image, true ) . ' -->';
		// }
		?>

		<div class="footer-content">
			<div class="footer-left">
				<?php if ( $footer_phone_label || $footer_phone_israel || $footer_phone_us || $footer_location ) : ?>
					<div class="footer-info">
						<?php if ( $footer_phone_label ) : ?>
							<div class="footer-section">
								<p class="footer-label"><?php echo esc_html( $footer_phone_label ); ?></p>
							</div>
						<?php endif; ?>

						<?php if ( $footer_phone_israel || $footer_phone_us ) : ?>
							<div class="footer-section">
								<?php if ( $footer_phone_israel ) : ?>
									<p class="footer-item">
										<span class="footer-item-label">Israel:</span>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $footer_phone_israel ) ); ?>" class="footer-link">
											<?php echo esc_html( $footer_phone_israel ); ?>
										</a>
									</p>
								<?php endif; ?>
								<?php if ( $footer_phone_us ) : ?>
									<p class="footer-item">
										<span class="footer-item-label">US:</span>
										<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $footer_phone_us ) ); ?>" class="footer-link">
											<?php echo esc_html( $footer_phone_us ); ?>
										</a>
									</p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( $footer_location ) : ?>
							<div class="footer-section">
								<p class="footer-item">
									<span class="footer-item-label">Location:</span>
									<?php echo esc_html( $footer_location ); ?>
								</p>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="footer-center">
				<?php 
				// Display footer logo - using same approach as header logo
				if ( $footer_logo_image ) {
					$logo_id = 0;
					
					// Get the attachment ID - handle all possible formats
					if ( is_numeric( $footer_logo_image ) ) {
						$logo_id = (int) $footer_logo_image;
					} elseif ( is_array( $footer_logo_image ) ) {
						if ( isset( $footer_logo_image['ID'] ) ) {
							$logo_id = (int) $footer_logo_image['ID'];
						} elseif ( isset( $footer_logo_image['id'] ) ) {
							$logo_id = (int) $footer_logo_image['id'];
						}
					}
					
					if ( $logo_id > 0 ) {
						// Verify attachment exists
						$attachment = get_post( $logo_id );
						if ( $attachment && $attachment->post_type === 'attachment' ) {
							// Get logo URL - use wp_get_attachment_url for absolute URL
							$logo_url = wp_get_attachment_url( $logo_id );
							if ( ! $logo_url ) {
								// Fallback to wp_get_attachment_image_url
								$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
							}
							
							$logo_alt = get_post_meta( $logo_id, '_wp_attachment_image_alt', true );
							if ( empty( $logo_alt ) ) {
								$logo_alt = get_bloginfo( 'name', 'display' );
							}
							
							if ( $logo_url ) {
								// Ensure absolute URL
								$logo_url = esc_url( $logo_url );
								// Check if it's an SVG file
								$file_ext = strtolower( pathinfo( $logo_url, PATHINFO_EXTENSION ) );
								$is_svg = ( $file_ext === 'svg' );
								?>
								<div class="footer-logo-wrapper">
									<img src="<?php echo $logo_url; ?>" 
										 alt="<?php echo esc_attr( $logo_alt ); ?>" 
										 class="footer-logo<?php echo $is_svg ? ' footer-logo-svg' : ''; ?>"
										 <?php if ( $is_svg ) : ?>style="display: block !important; width: auto; height: 80px; max-width: 200px; visibility: visible !important;"<?php endif; ?> />
								</div>
								<?php
							} else {
								// Fallback to wp_get_attachment_image
								?>
								<div class="footer-logo-wrapper">
									<?php echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'footer-logo', 'alt' => $logo_alt ) ); ?>
								</div>
								<?php
							}
						}
					}
				}
				?>

				<?php if ( $footer_confidentiality ) : ?>
					<div class="footer-confidentiality-wrapper">
						<p class="footer-confidentiality"><?php echo esc_html( $footer_confidentiality ); ?></p>
					</div>
				<?php endif; ?>
			</div>

			<div class="footer-right">
				<?php if ( $footer_links && is_array( $footer_links ) && ! empty( $footer_links ) ) : ?>
					<h3 class="footer-links-title">Quick Links</h3>
					<nav class="footer-links">
						<?php foreach ( $footer_links as $link_item ) : ?>
							<?php
							$link = isset( $link_item['link'] ) ? $link_item['link'] : '';
							if ( $link && is_array( $link ) && ! empty( $link['url'] ) ) {
								?>
								<a href="<?php echo esc_url( $link['url'] ); ?>" 
								   class="footer-link"
								   <?php if ( ! empty( $link['target'] ) ) : ?>target="<?php echo esc_attr( $link['target'] ); ?>"<?php endif; ?>>
									<?php echo esc_html( $link['title'] ?: $link['url'] ); ?>
								</a>
								<?php
							}
							?>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
			</div>

			<?php if ( $footer_confidentiality ) : ?>
				<div class="footer-confidentiality-wrapper footer-confidentiality-mobile">
					<p class="footer-confidentiality"><?php echo esc_html( $footer_confidentiality ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="site-info" style="text-align: center; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.875rem;">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'nisson-therapy' ); ?></p>
		</div>
	</div>
</footer>

<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'nisson-therapy' ); ?>" title="<?php esc_attr_e( 'Back to top', 'nisson-therapy' ); ?>">
	<svg aria-hidden="true" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
		<path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"></path>
	</svg>
</button>

<?php wp_footer(); ?>

</body>
</html>

