<?php
/**
 * Font handling class for the theme
 *
 * @package Nico_Killips_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class for handling fonts in the theme
 */
class Nico_Killips_Fonts {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_barlow_font' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_barlow_font' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_barlow_font' ) );
        add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
    }

    /**
     * Enqueue Barlow font
     */
    public function enqueue_barlow_font() {
        // Use local hosting for better performance
        wp_enqueue_style(
            'nico-killips-barlow-font',
            get_template_directory_uri() . '/assets/fonts/barlow/barlow.css',
            array(),
            NICO_KILLIPS_VERSION
        );
    }

    /**
     * Add preconnect for Google Fonts as fallback
     *
     * @param array  $urls          URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed.
     * @return array URLs to print for resource hints.
     */
    public function resource_hints( $urls, $relation_type ) {
        if ( 'preconnect' === $relation_type ) {
            // Add Google Fonts domain as preconnect for fallback
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }

        return $urls;
    }
}

// Initialize the fonts class
new Nico_Killips_Fonts();
