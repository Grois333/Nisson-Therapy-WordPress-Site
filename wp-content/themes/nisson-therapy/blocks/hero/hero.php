<?php
/**
 * Hero Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$subheadline = get_field( 'subheadline' );
$headline    = get_field( 'headline' );
$highlighted_text = get_field( 'highlighted_text' );
$button_link = get_field( 'button_link' );
$philosophy_line = get_field( 'philosophy_line' );
$background_image = get_field( 'background_image' );
$enable_parallax = get_field( 'enable_parallax' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $headline ) ) {
	$subheadline = $subheadline ?: 'Telehealth for New York, New Jersey, Florida, and Oregon';
	$headline = $headline ?: 'Time to See Yourself In A Different';
	$highlighted_text = $highlighted_text ?: 'Light?';
	if ( empty( $button_link ) ) {
		$button_link = array(
			'url'    => '#',
			'title'  => 'Learn more',
			'target' => '',
		);
	}
	$philosophy_line = $philosophy_line ?: 'Explore your inner world safely, with curiosity and compassion for all parts of you.';
}

// Block classes
$block_classes = array( 'hero-block', 'acf-block-hero' );
if ( $enable_parallax ) {
	$block_classes[] = 'hero-parallax';
}
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="hero-section" data-block-name="hero">
	<?php if ( $background_image ) : ?>
		<div class="hero-background" data-parallax="<?php echo $enable_parallax ? 'true' : 'false'; ?>">
			<?php
			echo wp_get_attachment_image(
				$background_image,
				'full',
				false,
				array(
					'class' => 'hero-bg-image',
					'alt'   => $subheadline ? esc_attr( $subheadline ) : '',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="hero-overlay"></div>

	<div class="hero-content">
		<div class="hero-container">
			<?php if ( $subheadline ) : ?>
				<p class="hero-subheadline"><?php echo esc_html( $subheadline ); ?></p>
			<?php endif; ?>

			<?php if ( $headline ) : ?>
				<h1 class="hero-headline">
					<?php
					// Only split if highlighted text is provided and exists in headline
					if ( ! empty( $highlighted_text ) && strpos( $headline, $highlighted_text ) !== false ) {
						$headline_parts = explode( $highlighted_text, $headline, 2 );
						if ( count( $headline_parts ) === 2 ) {
							echo esc_html( $headline_parts[0] );
							?>
							<span class="hero-highlight">
								<?php echo esc_html( $highlighted_text ); ?>
								<svg class="hero-highlight-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none" aria-hidden="true">
									<path class="hero-highlight-path" d="M325,18C228.7-8.3,118.5,8.3,78,21C22.4,38.4,4.6,54.6,5.6,77.6c1.4,32.4,52.2,54,142.6,63.7 c66.2,7.1,212.2,7.5,273.5-8.3c64.4-16.6,104.3-57.6,33.8-98.2C386.7-4.9,179.4-1.4,126.3,20.7 Z" fill="none" stroke="#2c3e50" stroke-width="2"/>
								</svg>
							</span>
							<?php
							echo esc_html( $headline_parts[1] );
						} else {
							echo esc_html( $headline );
						}
					} else {
						echo esc_html( $headline );
					}
					?>
				</h1>
			<?php endif; ?>

			<?php if ( $philosophy_line ) : ?>
				<p class="hero-philosophy-line"><?php echo esc_html( $philosophy_line ); ?></p>
			<?php endif; ?>

			<?php if ( $button_link && is_array( $button_link ) && ! empty( $button_link['url'] ) ) : ?>
				<div class="hero-cta">
					<a href="<?php echo esc_url( $button_link['url'] ); ?>" 
					   class="btn btn-hero" 
					   <?php if ( ! empty( $button_link['target'] ) ) : ?>target="<?php echo esc_attr( $button_link['target'] ); ?>"<?php endif; ?>>
						<span class="btn-text"><?php echo esc_html( $button_link['title'] ?: 'Learn more' ); ?></span>
						<svg aria-hidden="true" class="btn-icon" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg">
							<path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"></path>
						</svg>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

