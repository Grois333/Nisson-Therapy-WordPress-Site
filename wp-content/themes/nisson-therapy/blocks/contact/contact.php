<?php
/**
 * Contact Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$contact_top_title = get_field( 'contact_top_title' );
$contact_top_bg_image = get_field( 'contact_top_bg_image' );
$contact_tagline = get_field( 'contact_tagline' );
$contact_name = get_field( 'contact_name' );
$contact_title = get_field( 'contact_title' );
$contact_specializations = get_field( 'contact_specializations' );
$contact_phone = get_field( 'contact_phone' );
$contact_email = get_field( 'contact_email' );
$contact_services = get_field( 'contact_services' );
$contact_commitment = get_field( 'contact_commitment' );
$contact_form_id = get_field( 'contact_form_id' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $contact_tagline ) ) {
	$contact_top_title = $contact_top_title ?: 'Connect With Us';
	$contact_tagline = $contact_tagline ?: 'You only live once so let\'s make it happen.';
	$contact_name = $contact_name ?: 'Mary DiOrio LCSW';
	$contact_title = $contact_title ?: 'Psychotherapist';
	$contact_specializations = $contact_specializations ?: 'Eating Disorders | Anxiety & Depression | Couples Therapy';
	$contact_phone = $contact_phone ?: '503-984-2926';
	$contact_email = $contact_email ?: 'mary@marydioriolcsw.com';
}

// Block classes
$block_classes = array( 'contact-block', 'acf-block-contact' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="contact-section" data-block-name="contact">
	<?php if ( $contact_top_title ) : ?>
		<?php
		$bg_image_url = '';
		$bg_style = '';
		if ( $contact_top_bg_image ) {
			$bg_image_id = is_array( $contact_top_bg_image ) ? ( isset( $contact_top_bg_image['ID'] ) ? $contact_top_bg_image['ID'] : ( isset( $contact_top_bg_image['id'] ) ? $contact_top_bg_image['id'] : 0 ) ) : ( is_numeric( $contact_top_bg_image ) ? $contact_top_bg_image : 0 );
			if ( $bg_image_id > 0 ) {
				$bg_image_url = wp_get_attachment_image_url( $bg_image_id, 'full' );
				if ( $bg_image_url ) {
					$bg_style = 'background-image: url(' . esc_url( $bg_image_url ) . ');';
				}
			}
		}
		?>
		<div class="contact-top-section"<?php if ( $bg_style ) : ?> style="<?php echo $bg_style; ?>"<?php endif; ?>>
			<div class="contact-top-overlay"></div>
			<div class="contact-top-container">
				<h1 class="contact-top-title"><?php echo esc_html( $contact_top_title ); ?></h1>
			</div>
		</div>
	<?php endif; ?>

	<div class="contact-content-section">
		<div class="contact-container">
			<?php if ( $contact_tagline ) : ?>
				<h2 class="contact-tagline"><?php echo esc_html( $contact_tagline ); ?></h2>
			<?php endif; ?>

			<div class="contact-wrapper">
				<div class="contact-info-column">
					<?php if ( $contact_name || $contact_title ) : ?>
						<div class="contact-name-title">
							<?php if ( $contact_name ) : ?>
								<h3 class="contact-name"><?php echo esc_html( $contact_name ); ?></h3>
							<?php endif; ?>
							<?php if ( $contact_title ) : ?>
								<p class="contact-title"><?php echo esc_html( $contact_title ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $contact_specializations ) : ?>
						<p class="contact-specializations"><?php echo esc_html( $contact_specializations ); ?></p>
					<?php endif; ?>

					<?php if ( $contact_phone ) : ?>
						<div class="contact-phone">
							<span class="contact-icon">📞</span>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
						</div>
					<?php endif; ?>

					<?php if ( $contact_email ) : ?>
						<div class="contact-email">
							<span class="contact-icon">✉️</span>
							<a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
						</div>
					<?php endif; ?>

					<?php if ( $contact_services ) : ?>
						<div class="contact-services">
							<?php echo wp_kses_post( $contact_services ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $contact_commitment ) : ?>
						<div class="contact-commitment">
							<?php echo wp_kses_post( $contact_commitment ); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="contact-form-column">
					<?php if ( $contact_form_id ) : ?>
						<div class="contact-form-wrapper">
							<?php echo do_shortcode( '[contact-form-7 id="' . esc_attr( $contact_form_id ) . '"]' ); ?>
						</div>
					<?php else : ?>
						<div class="contact-form-placeholder">
							<p>Please select a Contact Form 7 form in the block settings.</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

