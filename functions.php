<?php
/**
 * Theme functions and definitions
 *
 * @package Nico_Killips_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Define theme constants
 */
define( 'NICO_KILLIPS_VERSION', '1.0.0' );
define( 'NICO_KILLIPS_TEMPLATE_DIR', get_template_directory() );
define( 'NICO_KILLIPS_TEMPLATE_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features
 */
function nico_killips_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Enable support for HTML5 markup
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ] );

    // Register nav menus
    register_nav_menus([
        'primary' => esc_html__( 'Primary Menu', 'nico-killips-wp' ),
    ]);
}
add_action( 'after_setup_theme', 'nico_killips_setup' );

/**
 * Enqueue scripts and styles
 */
function nico_killips_scripts() {
    // Enqueue main theme stylesheet
    wp_enqueue_style( 
        'nico-killips-style',
        get_stylesheet_uri(),
        [],
        NICO_KILLIPS_VERSION
    );

    // Enqueue compiled CSS if it exists
    if ( file_exists( NICO_KILLIPS_TEMPLATE_DIR . '/assets/css/style.css' ) ) {
        wp_enqueue_style(
            'nico-killips-compiled-style',
            NICO_KILLIPS_TEMPLATE_URI . '/assets/css/style.css',
            ['nico-killips-style'],
            NICO_KILLIPS_VERSION
        );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'nico_killips_scripts' );
