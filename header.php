<?php
/**
 * The header template file
 *
 * @package Nico_Killips_WP
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
        <a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <?php bloginfo( 'name' ); ?><p><?php bloginfo( 'description' ); ?></p>
        </a>
        <nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
            ]);
            ?>
        </nav>
    </header>