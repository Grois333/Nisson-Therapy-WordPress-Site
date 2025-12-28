<?php
/**
 * Services Page Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$services_top_title = get_field( 'services_top_title' );
$services_top_bg_image = get_field( 'services_top_bg_image' );
$services_content = get_field( 'services_content' );
$services_button = get_field( 'services_button' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $services_top_title ) ) {
	$services_top_title = $services_top_title ?: 'Learn to tame Anxiety';
	$services_content = $services_content ?: '<h2>Learn to understand your emotions, soothe your nervous system, and move through life with greater confidence and clarity.</h2><p>Anxiety can make even the simplest moments feel overwhelming. If you\'re navigating a major life transition, feeling stuck, or simply want to get out of your own way, I\'m here to help you find steadiness, perspective, and relief.</p>';
}

// Block classes
$block_classes = array( 'services-page-block', 'acf-block-services-page' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="services-page-section" data-block-name="services-page">
	<?php if ( $services_top_title ) : ?>
		<?php
		$bg_image_url = '';
		$bg_style = '';
		if ( $services_top_bg_image ) {
			$bg_image_id = is_array( $services_top_bg_image ) ? ( isset( $services_top_bg_image['ID'] ) ? $services_top_bg_image['ID'] : ( isset( $services_top_bg_image['id'] ) ? $services_top_bg_image['id'] : 0 ) ) : ( is_numeric( $services_top_bg_image ) ? $services_top_bg_image : 0 );
			if ( $bg_image_id > 0 ) {
				$bg_image_url = wp_get_attachment_image_url( $bg_image_id, 'full' );
				if ( $bg_image_url ) {
					$bg_style = 'background-image: url(' . esc_url( $bg_image_url ) . ');';
				}
			}
		}
		?>
		<div class="services-page-top-section"<?php if ( $bg_style ) : ?> style="<?php echo $bg_style; ?>"<?php endif; ?>>
			<div class="services-page-top-overlay"></div>
			<div class="services-page-top-container">
				<h1 class="services-page-top-title"><?php echo esc_html( $services_top_title ); ?></h1>
			</div>
		</div>
	<?php endif; ?>

	<div class="services-page-content-section">
		<div class="services-page-container">
			<?php if ( $services_content ) : ?>
				<div class="services-page-content">
					<?php echo wp_kses_post( $services_content ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $services_button && is_array( $services_button ) && ! empty( $services_button['url'] ) ) : ?>
				<div class="services-page-button-wrapper">
					<a href="<?php echo esc_url( $services_button['url'] ); ?>" 
					   class="services-page-button"
					   <?php if ( ! empty( $services_button['target'] ) ) : ?>target="<?php echo esc_attr( $services_button['target'] ); ?>"<?php endif; ?>>
						<?php echo esc_html( $services_button['title'] ?: $services_button['url'] ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

