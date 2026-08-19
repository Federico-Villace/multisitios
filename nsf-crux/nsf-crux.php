<?php
/**
 * Plugin Name: Crux · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias Estética tech futurista — negro total, cyan eléctrico, estilo Wired.
 * Version: 1.3.0
 * Author: Connotar
 * Text Domain: nsfcrux-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFCRUX_VERSION', '1.3.0' );
define( 'NSFCRUX_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFCRUX_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Crux · Widgets Elementor requiere Elementor activo.', 'nsfcrux-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFCRUX_PATH . 'includes/class-plugin.php';
    \NSFCRUX\Plugin::instance();
} );
