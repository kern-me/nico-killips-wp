<?php
/**
 * Theme functions and definitions
 *
 * @package Nico_Killips_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include custom nav walker
require get_template_directory() . '/inc/class-walker-nav-menu-aria.php';

/**
 * Define theme constants
 */
define( 'NICO_KILLIPS_VERSION', '1.0.1' );
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
 * Enqueue theme styles
 */
function nico_killips_enqueue_styles(): void {
    $theme_version = wp_get_theme()->get('Version');
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'nico-killips-styles',
        get_template_directory_uri() . '/assets/css/style.css',
        array(),
        $theme_version
    );
}
add_action('wp_enqueue_scripts', 'nico_killips_enqueue_styles');

/**
 * Enqueue theme scripts
 */
function nico_killips_enqueue_scripts(): void {
    $theme_version = wp_get_theme()->get('Version');

    // Enqueue navigation script
    wp_enqueue_script(
        'nico-killips-scripts',
        get_template_directory_uri() . '/assets/js/app.js',
        array(),
        $theme_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'nico_killips_enqueue_scripts');

/**
 * Register Portfolio sample custom post type
 */
function register_portfolio_sample_post_type() {
    $labels = array(
        'name'                  => _x('Portfolio', 'Post type general name', 'nico-killips-wp'),
        'singular_name'         => _x('Portfolio Sample', 'Post type singular name', 'nico-killips-wp'),
        'menu_name'            => _x('Portfolio', 'Admin Menu text', 'nico-killips-wp'),
        'add_new'              => __('Add New', 'nico-killips-wp'),
        'add_new_item'         => __('Add New Portfolio Sample', 'nico-killips-wp'),
        'edit_item'            => __('Edit Portfolio Sample', 'nico-killips-wp'),
        'new_item'             => __('New Portfolio Sample', 'nico-killips-wp'),
        'view_item'            => __('View Portfolio Sample', 'nico-killips-wp'),
        'search_items'         => __('Search Portfolio', 'nico-killips-wp'),
        'not_found'            => __('No portfolio samples found', 'nico-killips-wp'),
        'not_found_in_trash'   => __('No portfolio samples found in Trash', 'nico-killips-wp'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'portfolio'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => false, // Disable Gutenberg editor
    );

    register_post_type('portfolio-sample', $args);
}
add_action('init', 'register_portfolio_sample_post_type');

/**
 * Add URL meta box to portfolio-sample post type
 */
function add_portfolio_url_meta_box() {
    add_meta_box(
        'portfolio_url_meta_box', // Unique ID
        'Portfolio URL', // Box title
        'portfolio_url_meta_box_html', // Content callback, must be of type callable
        'portfolio-sample', // Post type
        'normal', // Context
        'high' // Priority
    );
}
add_action('add_meta_boxes', 'add_portfolio_url_meta_box');

/**
 * Meta box HTML
 */
function portfolio_url_meta_box_html($post) {
    $portfolio_url = get_post_meta($post->ID, '_portfolio_url', true);
    wp_nonce_field('portfolio_url_meta_box', 'portfolio_url_meta_box_nonce');
    ?>
    <p>
        <label for="portfolio_url">Project URL:</label>
        <input 
            type="url" 
            id="portfolio_url" 
            name="portfolio_url" 
            value="<?php echo esc_url($portfolio_url); ?>" 
            style="width: 100%"
            placeholder="https://example.com"
        >
    </p>
    <?php
}

/**
 * Save meta box content
 */
function save_portfolio_url_meta_box($post_id) {
    if (!isset($_POST['portfolio_url_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['portfolio_url_meta_box_nonce'], 'portfolio_url_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['portfolio_url'])) {
        $portfolio_url = esc_url_raw($_POST['portfolio_url']);
        update_post_meta($post_id, '_portfolio_url', $portfolio_url);
    }
}
add_action('save_post_portfolio-sample', 'save_portfolio_url_meta_box');

/**
 * Get portfolio URL (helper function)
 * 
 * @param int $post_id Post ID
 * @return string|false The portfolio URL or false if not set
 */
function get_portfolio_url($post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_portfolio_url', true);
}

/**
 * Force classic editor for portfolio-sample post type
 */
function disable_gutenberg_for_portfolio_samples($use_block_editor, $post_type) {
    if ($post_type === 'portfolio-sample') {
        return false;
    }
    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_portfolio_samples', 10, 2);
$portfolio_url = get_portfolio_url();
if ($portfolio_url) {
    echo '<a href="' . esc_url($portfolio_url) . '">View Project</a>';
}
/**
 * Register Portfolio sample taxonomies
 */
function register_portfolio_sample_taxonomies() {
    // Add category taxonomy
    $category_labels = array(
        'name'              => _x('Portfolio Categories', 'taxonomy general name', 'nico-killips-wp'),
        'singular_name'     => _x('Portfolio Category', 'taxonomy singular name', 'nico-killips-wp'),
        'search_items'      => __('Search Portfolio Categories', 'nico-killips-wp'),
        'all_items'         => __('All Portfolio Categories', 'nico-killips-wp'),
        'parent_item'       => __('Parent Portfolio Category', 'nico-killips-wp'),
        'parent_item_colon' => __('Parent Portfolio Category:', 'nico-killips-wp'),
        'edit_item'         => __('Edit Portfolio Category', 'nico-killips-wp'),
        'update_item'       => __('Update Portfolio Category', 'nico-killips-wp'),
        'add_new_item'      => __('Add New Portfolio Category', 'nico-killips-wp'),
        'new_item_name'     => __('New Portfolio Category Name', 'nico-killips-wp'),
        'menu_name'         => __('Categories', 'nico-killips-wp'),
    );

    register_taxonomy('portfolio-category', 'portfolio-sample', array(
        'hierarchical'      => true,
        'labels'           => $category_labels,
        'show_ui'          => true,
        'show_admin_column' => true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'portfolio-category'),
        'show_in_rest'     => false
    ));

    // Add tag taxonomy
    $tag_labels = array(
        'name'              => _x('Portfolio Tags', 'taxonomy general name', 'nico-killips-wp'),
        'singular_name'     => _x('Portfolio Tag', 'taxonomy singular name', 'nico-killips-wp'),
        'search_items'      => __('Search Portfolio Tags', 'nico-killips-wp'),
        'all_items'         => __('All Portfolio Tags', 'nico-killips-wp'),
        'edit_item'         => __('Edit Portfolio Tag', 'nico-killips-wp'),
        'update_item'       => __('Update Portfolio Tag', 'nico-killips-wp'),
        'add_new_item'      => __('Add New Portfolio Tag', 'nico-killips-wp'),
        'new_item_name'     => __('New Portfolio Tag Name', 'nico-killips-wp'),
        'menu_name'         => __('Tags', 'nico-killips-wp'),
    );

    register_taxonomy('portfolio-tag', 'portfolio-sample', array(
        'hierarchical'      => false,
        'labels'           => $tag_labels,
        'show_ui'          => true,
        'show_admin_column' => true,
        'query_var'        => true,
        'rewrite'          => array('slug' => 'portfolio-tag'),
        'show_in_rest'     => false
    ));
}
add_action('init', 'register_portfolio_sample_taxonomies');
