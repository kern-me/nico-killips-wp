<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Nico_Killips_WP
 */
?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'nico-killips-wp' ); ?></h1>
	</header>

	<div class="page-content">
		<?php
		if ( is_search() ) :
			?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'nico-killips-wp' ); ?></p>
			<?php
			get_search_form();
		else :
			?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'nico-killips-wp' ); ?></p>
			<?php
			get_search_form();
		endif;
		?>
	</div>
</section>