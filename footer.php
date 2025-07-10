<?php
/**
 * The footer template file
 *
 * @package Nico_Killips_WP
 */
?>

<footer id="colophon" class="site-footer">
	<div class="site-info">
		<?php
		printf(
			esc_html__( '© %1$s %2$s', 'nico-killips-wp' ),
			date( 'Y' ),
			get_bloginfo( 'name' )
		);
		?>
	</div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>