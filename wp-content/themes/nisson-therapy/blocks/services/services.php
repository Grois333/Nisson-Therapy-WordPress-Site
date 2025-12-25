<?php
/**
 * Services Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$services_cards = get_field( 'services_cards' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $services_cards ) ) {
	$services_cards = array(
		array(
			'title'       => 'Learn to tame Anxiety',
			'description' => 'I also specialize in treating anxiety that often stems from difficult life transitions such as divorce, bereavement, sudden career changes, and many cumulative causes.',
			'link'        => array(
				'url'   => '#',
				'title' => 'Learn more',
			),
		),
	);
}

// Block classes
$block_classes = array( 'services-block', 'acf-block-services' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="services-section" data-block-name="services">
	<div class="services-container">
		<?php if ( $services_cards && is_array( $services_cards ) ) : ?>
			<div class="services-cards">
				<?php foreach ( $services_cards as $index => $card ) : ?>
					<?php
					$card_title = isset( $card['title'] ) ? $card['title'] : '';
					$card_description = isset( $card['description'] ) ? $card['description'] : '';
					$card_bg_image = isset( $card['background_image'] ) ? $card['background_image'] : '';
					$card_link = isset( $card['link'] ) ? $card['link'] : '';
					?>
					<div class="service-card" data-card-index="<?php echo esc_attr( $index ); ?>">
						<?php if ( $card_bg_image ) : ?>
							<div class="service-card-bg">
								<?php
								echo wp_get_attachment_image(
									$card_bg_image,
									'large',
									false,
									array(
										'class' => 'service-bg-image',
										'alt'   => $card_title ? esc_attr( $card_title ) : '',
									)
								);
								?>
							</div>
						<?php endif; ?>
						<div class="service-card-overlay"></div>
						<div class="service-card-content">
							<?php if ( $card_title ) : ?>
								<h2 class="service-card-title">
									<?php echo esc_html( $card_title ); ?>
								</h2>
							<?php endif; ?>
							<?php if ( $card_description ) : ?>
								<div class="service-card-description"><?php echo wp_kses_post( $card_description ); ?></div>
							<?php endif; ?>
							<?php if ( $card_link && is_array( $card_link ) && ! empty( $card_link['url'] ) ) : ?>
								<div class="service-card-cta">
									<a href="<?php echo esc_url( $card_link['url'] ); ?>" 
									   class="btn btn-service" 
									   <?php if ( ! empty( $card_link['target'] ) ) : ?>target="<?php echo esc_attr( $card_link['target'] ); ?>"<?php endif; ?>>
										<?php echo esc_html( $card_link['title'] ?: 'Learn more' ); ?>
										<span class="btn-arrow">></span>
									</a>
								</div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

