<?php
/**
 * Plugin Name: Meridiano · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias. Estética broadsheet — papel, azul marino y dorado apagado, tipografía con remates, versalitas, corondeles y capitulares.
 * Version: 2.0.0
 * Author: Connotar
 * Text Domain: nsfmeridiano-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFMERIDIANO_VERSION', '2.0.0' );
define( 'NSFMERIDIANO_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFMERIDIANO_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Meridiano · Widgets Elementor requiere Elementor activo.', 'nsfmeridiano-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFMERIDIANO_PATH . 'includes/class-plugin.php';
    \NSFMERIDIANO\Plugin::instance();
} );
