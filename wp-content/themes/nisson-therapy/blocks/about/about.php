<?php
/**
 * About Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$about_top_title = get_field( 'about_top_title' );
$about_top_bg_image = get_field( 'about_top_bg_image' );
$about_title = get_field( 'about_title' );
$about_subtitle = get_field( 'about_subtitle' );
$about_content = get_field( 'about_content' );
$about_image = get_field( 'about_image' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $about_top_title ) ) {
	$about_top_title = $about_top_title ?: 'Are You Ready for Change?';
	$about_title = $about_title ?: 'About Mary DiOrio (She, Her, Her\'s)';
	$about_subtitle = $about_subtitle ?: 'Licensed Psychotherapist • Online Therapy in NY, NJ, OR & FL';
	$about_content = $about_content ?: '<p><strong>I wasn\'t always a therapist.</strong></p><p>My first degree was a BA in Business and I worked for the City of Portland.</p>';
}

// Block classes
$block_classes = array( 'about-block', 'acf-block-about' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="about-section" data-block-name="about">
	<?php if ( $about_top_title ) : ?>
		<?php
		$bg_image_url = '';
		$bg_style = '';
		if ( $about_top_bg_image ) {
			$bg_image_id = is_array( $about_top_bg_image ) ? ( isset( $about_top_bg_image['ID'] ) ? $about_top_bg_image['ID'] : ( isset( $about_top_bg_image['id'] ) ? $about_top_bg_image['id'] : 0 ) ) : ( is_numeric( $about_top_bg_image ) ? $about_top_bg_image : 0 );
			if ( $bg_image_id > 0 ) {
				$bg_image_url = wp_get_attachment_image_url( $bg_image_id, 'full' );
				if ( $bg_image_url ) {
					$bg_style = 'background-image: url(' . esc_url( $bg_image_url ) . ');';
				}
			}
		}
		?>
		<div class="about-top-section"<?php if ( $bg_style ) : ?> style="<?php echo $bg_style; ?>"<?php endif; ?>>
			<div class="about-top-overlay"></div>
			<div class="about-top-container">
				<h1 class="about-top-title"><?php echo esc_html( $about_top_title ); ?></h1>
			</div>
		</div>
	<?php endif; ?>

	<div class="about-content-section">
		<div class="about-container">
			<?php if ( $about_title ) : ?>
				<h2 class="about-title"><?php echo esc_html( $about_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $about_subtitle ) : ?>
				<p class="about-subtitle"><?php echo esc_html( $about_subtitle ); ?></p>
			<?php endif; ?>

			<div class="about-content-wrapper">
				<?php if ( $about_content ) : ?>
					<div class="about-text-wrapper">
						<div class="about-text">
							<?php echo wp_kses_post( $about_content ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( $about_image ) : ?>
					<div class="about-image-wrapper">
						<?php
						echo wp_get_attachment_image(
							$about_image,
							'large',
							false,
							array(
								'class' => 'about-image',
								'alt'   => $about_title ? esc_attr( $about_title ) : '',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

