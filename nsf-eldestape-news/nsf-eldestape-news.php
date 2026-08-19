<?php
/**
 * Plugin Name: El Destape News · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias con estética El Destape News.
 * Version: 1.1.0
 * Author: Connotar
 * Text Domain: nsfeldestape-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFELDESTAPE_VERSION', '1.1.0' );
define( 'NSFELDESTAPE_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFELDESTAPE_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'El Destape News · Widgets Elementor requiere Elementor activo.', 'nsfeldestape-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFELDESTAPE_PATH . 'includes/class-plugin.php';
    \NSFELDESTAPE\Plugin::instance();
} );
