<?php
/**
 * Intro Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$intro_name = get_field( 'intro_name' );
$intro_quote = get_field( 'intro_quote' );
$intro_image = get_field( 'intro_image' );
$intro_content = get_field( 'intro_content' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $intro_name ) ) {
	$intro_name = $intro_name ?: 'Mary DiOrio, Therapist';
	$intro_quote = $intro_quote ?: 'Seeing yourself differently is often the first step to living the life you truly want to live.';
	$intro_content = $intro_content ?: '<p>You may be functioning on the outside, but inside it might feel noisy, tense, or fragmented.</p><p>Parts of you want relief, parts want control, parts want to shut things down, and other parts are just exhausted.</p>';
}

// Block classes
$block_classes = array( 'intro-block', 'acf-block-intro' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="intro-section" data-block-name="intro">
	<div class="intro-container">
		<?php if ( $intro_name ) : ?>
			<p class="intro-name"><?php echo esc_html( $intro_name ); ?></p>
		<?php endif; ?>

		<?php if ( $intro_quote ) : ?>
			<p class="intro-quote"><?php echo esc_html( $intro_quote ); ?></p>
		<?php endif; ?>

		<div class="intro-content-wrapper">
			<?php if ( $intro_image ) : ?>
				<div class="intro-image-wrapper">
					<?php
					echo wp_get_attachment_image(
						$intro_image,
						'large',
						false,
						array(
							'class' => 'intro-image',
							'alt'   => $intro_name ? esc_attr( $intro_name ) : '',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<?php if ( $intro_content ) : ?>
				<div class="intro-text-wrapper">
					<div class="intro-text">
						<?php echo wp_kses_post( $intro_content ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

