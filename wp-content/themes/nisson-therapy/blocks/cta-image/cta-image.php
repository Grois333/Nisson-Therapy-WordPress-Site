<?php
/**
 * CTA Image Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$cta_image_title = get_field( 'cta_image_title' );
$cta_image_button = get_field( 'cta_image_button' );
$cta_image_bg = get_field( 'cta_image_bg' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $cta_image_title ) ) {
	$cta_image_title = $cta_image_title ?: 'Ready to see yourself in a different light?';
	$cta_image_button = $cta_image_button ?: array(
		'url'   => '#',
		'title' => 'Schedule A Free 15 Minute Consultation',
		'target' => '',
	);
}

// Get background image URL
$bg_image_url = '';
$bg_style = '';
if ( $cta_image_bg ) {
	$bg_image_id = is_array( $cta_image_bg ) ? ( isset( $cta_image_bg['ID'] ) ? $cta_image_bg['ID'] : ( isset( $cta_image_bg['id'] ) ? $cta_image_bg['id'] : 0 ) ) : ( is_numeric( $cta_image_bg ) ? $cta_image_bg : 0 );
	if ( $bg_image_id > 0 ) {
		$bg_image_url = wp_get_attachment_image_url( $bg_image_id, 'full' );
		if ( $bg_image_url ) {
			$bg_style = 'background-image: url(' . esc_url( $bg_image_url ) . ');';
		}
	}
}

// Block classes
$block_classes = array( 'cta-image-block', 'acf-block-cta-image' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="cta-image-section" data-block-name="cta-image">
	<div class="cta-image-wrapper"<?php if ( $bg_style ) : ?> style="<?php echo $bg_style; ?>"<?php endif; ?>>
		<div class="cta-image-overlay"></div>
		<div class="cta-image-container">
			<?php if ( $cta_image_title ) : ?>
				<h2 class="cta-image-title"><?php echo esc_html( $cta_image_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $cta_image_button && is_array( $cta_image_button ) && ! empty( $cta_image_button['url'] ) ) : ?>
				<a href="<?php echo esc_url( $cta_image_button['url'] ); ?>" 
				   class="cta-image-button"
				   <?php if ( ! empty( $cta_image_button['target'] ) ) : ?>target="<?php echo esc_attr( $cta_image_button['target'] ); ?>"<?php endif; ?>>
					<?php echo esc_html( $cta_image_button['title'] ?: $cta_image_button['url'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>

