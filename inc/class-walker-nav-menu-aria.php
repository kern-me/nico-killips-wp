<?php
/**
 * Custom navigation walker with enhanced ARIA support
 *
 * @package Nico_Killips_WP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Class Walker_Nav_Menu_Aria
 * 
 * Extends the WordPress nav menu walker to add ARIA attributes for accessibility
 */
class Walker_Nav_Menu_Aria extends Walker_Nav_Menu {
    /**
     * Starts the list before the elements are added.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );

        // Default class.
        $classes = array( 'sub-menu' );

        /**
         * Filters the CSS class(es) applied to a menu list element.
         */
        $class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );

        $atts = array();
        $atts['class'] = $class_names;
        $atts['role'] = 'menu';

        /**
         * Filters the HTML attributes applied to a menu list element.
         */
        $atts = apply_filters( 'nav_menu_submenu_attributes', $atts, $args, $depth );
        $attributes = $this->build_atts( $atts );

        $output .= "{$n}{$indent}<ul{$attributes}>{$n}";
    }

    /**
     * Starts the element output.
     *
     * @param string   $output            Used to append additional content (passed by reference).
     * @param WP_Post  $item              Menu item data object.
     * @param int      $depth             Depth of menu item. Used for padding.
     * @param stdClass $args              An object of wp_nav_menu() arguments.
     * @param int      $id                Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

        $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add has-children class if item has children
        if ( in_array( 'menu-item-has-children', $classes ) ) {
            $classes[] = 'has-children';
        }

        /**
         * Filters the arguments for a single nav menu item.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         */
        $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

        /**
         * Filters the ID applied to a menu item's list item element.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );

        $li_atts = array();
        $li_atts['id'] = $id;
        $li_atts['class'] = $class_names;
        $li_atts['role'] = 'none'; // ARIA role for list item

        /**
         * Filters the HTML attributes applied to a menu's list item element.
         */
        $li_atts = apply_filters( 'nav_menu_item_attributes', $li_atts, $item, $args, $depth );
        $attributes = $this->build_atts( $li_atts );

        $output .= $indent . '<li' . $attributes . '>';

        // Filter title
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        // Link attributes
        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';
        $atts['role']   = 'menuitem'; // ARIA role for link

        // Add tabindex
        $atts['tabindex'] = '0';

        // Add aria-current for current page
        if ( $item->current ) {
            $atts['aria-current'] = 'page';
        }

        // Add aria-haspopup and aria-expanded for items with children
        if ( in_array( 'menu-item-has-children', $classes ) ) {
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        }

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
        $attributes = $this->build_atts( $atts );

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}
