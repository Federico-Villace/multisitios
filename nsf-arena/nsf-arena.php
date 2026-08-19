<?php
/**
 * Plugin Name: Arena · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias Estética deportiva — naranja vibrante, bold, alta energía, estilo Olé/ESPN.
 * Version: 1.1.0
 * Author: Connotar
 * Text Domain: nsfarena-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFARENA_VERSION', '1.1.0' );
define( 'NSFARENA_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFARENA_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Arena · Widgets Elementor requiere Elementor activo.', 'nsfarena-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFARENA_PATH . 'includes/class-plugin.php';
    \NSFARENA\Plugin::instance();
} );
