<?php
/**
 * Services Content Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$services_content_image = get_field( 'services_content_image' );
$services_content_text = get_field( 'services_content_text' );
$services_content_button = get_field( 'services_content_button' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $services_content_text ) ) {
	$services_content_text = $services_content_text ?: '<p>Mary DiOrio is a licensed psychotherapist specializing in depression and anxiety treatment...</p>';
	$services_content_button = $services_content_button ?: array(
		'url'   => '#',
		'title' => 'Schedule A Free 15 Minute Consultation',
	);
}

// Block classes
$block_classes = array( 'services-content-block', 'acf-block-services-content' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="services-content-section" data-block-name="services-content">
	<div class="services-content-container">
		<div class="services-content-wrapper">
			<?php if ( $services_content_image ) : ?>
				<div class="services-content-image-wrapper">
					<?php
					echo wp_get_attachment_image(
						$services_content_image,
						'large',
						false,
						array(
							'class' => 'services-content-image',
							'alt'   => 'Mary DiOrio',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<div class="services-content-text-wrapper">
				<?php if ( $services_content_text ) : ?>
					<div class="services-content-text">
						<?php echo wp_kses_post( $services_content_text ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $services_content_button && is_array( $services_content_button ) && ! empty( $services_content_button['url'] ) ) : ?>
					<div class="services-content-button-wrapper">
						<a href="<?php echo esc_url( $services_content_button['url'] ); ?>" 
						   class="services-content-button"
						   <?php if ( ! empty( $services_content_button['target'] ) ) : ?>target="<?php echo esc_attr( $services_content_button['target'] ); ?>"<?php endif; ?>>
							<?php echo esc_html( $services_content_button['title'] ?: $services_content_button['url'] ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

