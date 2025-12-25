<?php
/**
 * Footer template
 *
 * @package NissonTherapy
 */
?>

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="footer-container">
		<?php
		// Footer content can be added via ACF blocks or widgets
		if ( is_active_sidebar( 'footer-1' ) ) {
			?>
			<div class="footer-widgets">
				<?php dynamic_sidebar( 'footer-1' ); ?>
			</div>
			<?php
		}
		?>

		<div class="site-info">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'nisson-therapy' ); ?></p>
		</div>
	</div>
</footer>

<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'nisson-therapy' ); ?>" title="<?php esc_attr_e( 'Back to top', 'nisson-therapy' ); ?>">
	<svg aria-hidden="true" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
		<path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"></path>
	</svg>
</button>

<?php wp_footer(); ?>

</body>
</html>

