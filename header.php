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
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
        <a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <p class="home-link--name"><?php bloginfo( 'name' ); ?></p>
            <p class="home-link--description"><?php bloginfo( 'description' ); ?></p>
        </a>
        <nav id="site-navigation" class="main-navigation">
            <button class="menu-toggle" aria-controls="primary-menu-container" aria-expanded="false" aria-label="Toggle menu">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
            <div id="primary-menu-container" class="menu-container">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'nav-menu',
                    'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                    'walker'         => new Walker_Nav_Menu_Aria(),
                ]);
                ?>
            </div>
        </nav>
    </header>