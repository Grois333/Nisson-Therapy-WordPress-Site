<?php
/**
 * Services List Block Template
 *
 * @package NissonTherapy
 *
 * @var array $block The block settings and attributes.
 * @var string $content The block inner HTML (empty).
 * @var bool $is_preview True during backend preview render.
 * @var int $post_id The post ID the block is rendering content against.
 */

// Get block fields
$services_list_title = get_field( 'services_list_title' );
$services_list_items = get_field( 'services_list_items' );

// Use example data in preview mode if fields are empty
if ( $is_preview && empty( $services_list_items ) ) {
	$services_list_title = $services_list_title ?: 'Therapies & Practices I use to help you';
	$services_list_items = array(
		array(
			'title'       => 'Cognitive Behavioral Therapy (CBT)',
			'description' => '',
		),
		array(
			'title'       => 'Emotion Focused Therapy (EFT)',
			'description' => '',
		),
	);
}

// Block classes
$block_classes = array( 'services-list-block', 'acf-block-services-list' );
$block_classes = implode( ' ', $block_classes );
?>

<section class="<?php echo esc_attr( $block_classes ); ?>" id="services-list-section" data-block-name="services-list">
	<div class="services-list-container">
		<?php if ( $services_list_title ) : ?>
			<h2 class="services-list-title"><?php echo esc_html( $services_list_title ); ?></h2>
		<?php endif; ?>

		<?php if ( $services_list_items && is_array( $services_list_items ) ) : ?>
			<?php
			// Check if any item has description text
			$has_descriptions = false;
			foreach ( $services_list_items as $item ) {
				if ( ! empty( $item['description'] ) ) {
					$has_descriptions = true;
					break;
				}
			}
			$list_class = $has_descriptions ? 'services-list-items-with-descriptions' : 'services-list-items-no-descriptions';
			?>
			<div class="services-list-items <?php echo esc_attr( $list_class ); ?>">
				<?php
				$total_items = count( $services_list_items );
				$is_odd = ( $total_items % 2 ) === 1;
				?>
				<?php foreach ( $services_list_items as $index => $item ) : ?>
					<?php
					$item_title = isset( $item['title'] ) ? $item['title'] : '';
					$item_description = isset( $item['description'] ) ? $item['description'] : '';
					$is_last_item = ( $index === $total_items - 1 );
					$item_classes = array( 'services-list-item' );
					
					// If odd number of items, center the last item's title
					if ( $is_odd && $is_last_item ) {
						$item_classes[] = 'services-list-item-centered';
					}
					$item_class = implode( ' ', $item_classes );
					?>
					<?php if ( $item_title ) : ?>
						<div class="<?php echo esc_attr( $item_class ); ?>">
							<h3 class="services-list-item-title"><?php echo esc_html( $item_title ); ?></h3>
							<?php if ( $item_description ) : ?>
								<div class="services-list-item-description">
									<?php echo wp_kses_post( $item_description ); ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

