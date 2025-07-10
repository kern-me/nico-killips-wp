<?php
/**
 * Template part for displaying posts
 *
 * @package Nico_Killips_WP
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header>

	<div class="entry-content">
		<?php
		if ( is_singular() ) :
			the_content();
		else :
			the_excerpt();
		endif;
		?>
	</div>
</article>