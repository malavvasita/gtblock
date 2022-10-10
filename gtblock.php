<?php 

/*
Plugin Name: Show Latest Posts / Products
Description: Admin will choose if posts / products need to show to the front-end.
Version: 1.0.0
Author: Malav V
Author URI: https://malavvasita.github.io/
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class GTBlock{
    function __construct() {
        add_action( 'init', array( $this, 'admin_assets' ) );

        add_action( 'enqueue_block_editor_assets', function() {
            wp_enqueue_style( 
                'gtblock-editor-css', 
                plugin_dir_url( __FILE__ ) . 'src/css/editor.css' 
            );
        } );
    }

    function admin_assets() {
        
        wp_register_script( 
            'gtblock-index', 
            plugin_dir_url( __FILE__ ) . 'build/index.js',
            array( 
                'wp-blocks',
                'wp-element',
                'wp-components',
                'wp-block-editor',
                'wp-i18n'
            )
        );

        register_block_type(
            "gtblock/get-posts",
            array(
                "editor_script" => "gtblock-index",
                "render_callback" => array( $this, "theHTML" )
            )
        );
    }

    function theHTML( $attributes ){

        if( !isset( $attributes['postType'] ) ){
            return false;
        }

        global $wpdb;

        $postType = sanitize_text_field( $attributes['postType'] );
        if ( 'products' == $postType && ! class_exists( 'WooCommerce' ) ) {
            return "<div class='gtblock-error'>" 
                . __( "No products available yet!" ) . 
            "</div>";
        }
        
        $args = array(  
            'post_type' => $postType,
            'post_status' => 'publish',
            'posts_per_page' => 5, 
            'orderby' => 'title', 
            'order' => 'ASC', 
        );

        $result = new WP_Query( $args ); 

        $html = "<div class='gtblock-result'>";
        while( $result->have_posts() ) : $result->the_post();
                
            $html .= "<div class='list-items'>
                        <h3><a href='#'>" . get_the_title() . "</a></h3>
                        <h6>" . get_the_date() . "</h6>
                        <p>" . get_the_excerpt() . "</p>
                        <hr/>
                    </div>";
        endwhile;
        $html .= "</div>"; 

        wp_reset_postdata(); 

        return $html;
    }
}

$gtBlock = new GTBlock();