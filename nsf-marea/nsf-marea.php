<?php
/**
 * Plugin Name: Marea · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias Estética editorial progresista — violeta, serif italic, estilo Página/12.
 * Version: 1.1.0
 * Author: Connotar
 * Text Domain: nsfmarea-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFMAREA_VERSION', '1.1.0' );
define( 'NSFMAREA_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFMAREA_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Marea · Widgets Elementor requiere Elementor activo.', 'nsfmarea-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFMAREA_PATH . 'includes/class-plugin.php';
    \NSFMAREA\Plugin::instance();
} );
