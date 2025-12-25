<?php
/**
 * CTA Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$cta_title = get_field( 'cta_title' );
$cta_subtitle = get_field( 'cta_subtitle' );
$cta_description = get_field( 'cta_description' );
$cta_button = get_field( 'cta_button' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $cta_title ) ) {
	$cta_title = $cta_title ?: 'Schedule a Free Consultation';
	$cta_subtitle = $cta_subtitle ?: 'A brief call to see if we are a good fit.';
	$cta_description = $cta_description ?: 'All conversations are confidential and handled with the utmost care.';
}

// Block classes
$block_classes = array( 'cta-block', 'acf-block-cta' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="cta-section" data-block-name="cta">
	<div class="cta-container">
		<?php if ( $cta_title ) : ?>
			<h2 class="cta-title"><?php echo esc_html( $cta_title ); ?></h2>
		<?php endif; ?>

		<?php if ( $cta_subtitle ) : ?>
			<p class="cta-subtitle"><strong><?php echo esc_html( $cta_subtitle ); ?></strong></p>
		<?php endif; ?>

		<?php if ( $cta_description ) : ?>
			<p class="cta-description"><?php echo esc_html( $cta_description ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_button && is_array( $cta_button ) && ! empty( $cta_button['url'] ) ) : ?>
			<div class="cta-button-wrapper">
				<a href="<?php echo esc_url( $cta_button['url'] ); ?>" 
				   class="btn btn-cta" 
				   <?php if ( ! empty( $cta_button['target'] ) ) : ?>target="<?php echo esc_attr( $cta_button['target'] ); ?>"<?php endif; ?>>
					<?php echo esc_html( $cta_button['title'] ?: 'Get In Touch' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>

