<?php
/**
 * FAQ Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$faq_top_title = get_field( 'faq_top_title' );
$faq_top_bg_image = get_field( 'faq_top_bg_image' );
$faq_items = get_field( 'faq_items' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $faq_items ) ) {
	$faq_top_title = $faq_top_title ?: 'Frequently Asked Questions';
	$faq_items = array(
		array(
			'question' => 'Is therapy right for me?',
			'answer'   => '<p>Working with a therapist can help provide insight, support, and new strategies for all types of life challenges.</p>',
		),
	);
}

// Block classes
$block_classes = array( 'faq-block', 'acf-block-faq' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="faq-section" data-block-name="faq">
	<?php if ( $faq_top_title ) : ?>
		<?php
		$bg_image_url = '';
		$bg_style = '';
		if ( $faq_top_bg_image ) {
			$bg_image_id = is_array( $faq_top_bg_image ) ? ( isset( $faq_top_bg_image['ID'] ) ? $faq_top_bg_image['ID'] : ( isset( $faq_top_bg_image['id'] ) ? $faq_top_bg_image['id'] : 0 ) ) : ( is_numeric( $faq_top_bg_image ) ? $faq_top_bg_image : 0 );
			if ( $bg_image_id > 0 ) {
				$bg_image_url = wp_get_attachment_image_url( $bg_image_id, 'full' );
				if ( $bg_image_url ) {
					$bg_style = 'background-image: url(' . esc_url( $bg_image_url ) . ');';
				}
			}
		}
		?>
		<div class="faq-top-section"<?php if ( $bg_style ) : ?> style="<?php echo $bg_style; ?>"<?php endif; ?>>
			<div class="faq-top-overlay"></div>
			<div class="faq-top-container">
				<h1 class="faq-top-title"><?php echo esc_html( $faq_top_title ); ?></h1>
			</div>
		</div>
	<?php endif; ?>

	<div class="faq-content-section">
		<div class="faq-container">
			<?php if ( $faq_items && is_array( $faq_items ) ) : ?>
				<div class="faq-items">
					<?php foreach ( $faq_items as $index => $item ) : ?>
						<?php
						$item_question = isset( $item['question'] ) ? $item['question'] : '';
						$item_answer = isset( $item['answer'] ) ? $item['answer'] : '';
						$is_first = ( $index === 0 );
						$item_id = 'faq-item-' . $index;
						?>
						<?php if ( $item_question ) : ?>
							<div class="faq-item">
								<button class="faq-question" 
										type="button" 
										aria-expanded="<?php echo $is_first ? 'true' : 'false'; ?>"
										aria-controls="<?php echo esc_attr( $item_id ); ?>"
										data-faq-target="<?php echo esc_attr( $item_id ); ?>">
									<span class="faq-icon"><?php echo $is_first ? '−' : '+'; ?></span>
									<span class="faq-question-text"><?php echo esc_html( $item_question ); ?></span>
								</button>
								<div class="faq-answer<?php echo $is_first ? ' faq-answer-open' : ''; ?>" 
									 id="<?php echo esc_attr( $item_id ); ?>"
									 aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>">
									<?php if ( $item_answer ) : ?>
										<div class="faq-answer-content">
											<?php echo wp_kses_post( $item_answer ); ?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

