<?php
/**
 * Template part for displaying posts
 */
?>



<?php
if ( get_post_type() === 'portfolio-sample' ) {
	// portfolio
}
?>

<div class="entry-content">
	<?php
	if ( is_singular() ) :
		the_content();
	else :
		the_excerpt();
	endif;
	?>
</div>