<?php
/**
 * Approach Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$approach_title = get_field( 'approach_title' );
$approach_subtitle = get_field( 'approach_subtitle' );
$approach_cards = get_field( 'approach_cards' );
$approach_footer_text = get_field( 'approach_footer_text' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $approach_title ) ) {
	$approach_title = $approach_title ?: 'My Approach';
	$approach_subtitle = $approach_subtitle ?: 'Inside each of us are different "parts": protective, wounded, or reactive, each doing its best to help. Symptoms are signals, not flaws. At your core is a calm, confident Self. Therapy helps you meet that Self, trust it, and let it lead.';
	$approach_cards = $approach_cards ?: array(
		array(
			'title'       => 'Identify and understand internal parts',
			'description' => 'We work together to recognize and understand the different parts within you.',
		),
		array(
			'title'       => 'Help internal conflict and self-criticism soften',
			'description' => 'We create space for internal conflicts to resolve and self-criticism to ease.',
		),
		array(
			'title'       => 'Heal emotional wounds at a sustainable pace',
			'description' => 'We address emotional wounds with care and respect for your pace.',
		),
		array(
			'title'       => 'Strengthen Self-leadership and internal trust',
			'description' => 'We help you develop trust in your core Self and strengthen your leadership.',
		),
	);
}

// Block classes
$block_classes = array( 'approach-block', 'acf-block-approach' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="approach-section" data-block-name="approach">
	<?php if ( $approach_title || $approach_subtitle ) : ?>
		<div class="approach-top-section">
			<div class="approach-top-container">
				<?php if ( $approach_title ) : ?>
					<h2 class="approach-title"><?php echo esc_html( $approach_title ); ?></h2>
				<?php endif; ?>
				<?php if ( $approach_subtitle ) : ?>
					<p class="approach-subtitle"><?php echo esc_html( $approach_subtitle ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $approach_cards && is_array( $approach_cards ) && ! empty( $approach_cards ) ) : ?>
		<div class="approach-cards-section">
			<div class="approach-cards-container">
				<div class="approach-cards-grid">
					<?php
					// Default icons array
					$default_icons = array(
						'identify' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="#326281"/></svg>',
						'help' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z" fill="#326281"/></svg>',
						'heal' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="#326281"/></svg>',
						'strengthen' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="#326281" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>',
					);
					$icon_keys = array_keys( $default_icons );
					?>
					<?php foreach ( $approach_cards as $index => $card ) : ?>
						<?php
						$card_title = isset( $card['title'] ) ? $card['title'] : '';
						$card_description = isset( $card['description'] ) ? $card['description'] : '';
						$card_icon_key = isset( $card['icon'] ) ? $card['icon'] : '';
						
						// Auto-assign icon based on card index if no icon selected
						if ( empty( $card_icon_key ) ) {
							$icon_index = $index % count( $icon_keys );
							$card_icon_key = $icon_keys[ $icon_index ];
						}
						
						// Get the icon SVG
						$card_icon = isset( $default_icons[ $card_icon_key ] ) ? $default_icons[ $card_icon_key ] : $default_icons[ $icon_keys[0] ];
						?>
						<?php if ( $card_title || $card_description ) : ?>
							<div class="approach-card">
								<?php if ( $card_icon ) : ?>
									<div class="approach-card-icon">
										<?php echo $card_icon; ?>
									</div>
								<?php endif; ?>
								<?php if ( $card_title ) : ?>
									<h3 class="approach-card-title"><?php echo esc_html( $card_title ); ?></h3>
								<?php endif; ?>
								<?php if ( $card_description ) : ?>
									<div class="approach-card-description">
										<?php echo wp_kses_post( wpautop( $card_description ) ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<?php if ( $approach_footer_text ) : ?>
					<div class="approach-footer-text">
						<p><?php echo esc_html( $approach_footer_text ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>

