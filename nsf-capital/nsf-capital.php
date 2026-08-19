<?php
/**
 * Plugin Name: Capital · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias Estética finanzas y negocios — verde oscuro, sans limpia, estilo Bloomberg/El Cronista.
 * Version: 1.1.0
 * Author: Connotar
 * Text Domain: nsfcapital-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFCAPITAL_VERSION', '1.1.0' );
define( 'NSFCAPITAL_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFCAPITAL_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Capital · Widgets Elementor requiere Elementor activo.', 'nsfcapital-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFCAPITAL_PATH . 'includes/class-plugin.php';
    \NSFCAPITAL\Plugin::instance();
} );
