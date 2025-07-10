<?php
/**
 * Template part for displaying posts
 *
 * @package Nico_Killips_WP
 */
?>

<section>
    <?php
        if ( is_singular() ) :
            the_content();
        else :
            the_excerpt();
        endif;
    ?>
</section>