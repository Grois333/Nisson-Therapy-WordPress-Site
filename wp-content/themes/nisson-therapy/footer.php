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

<?php wp_footer(); ?>

</body>
</html>

