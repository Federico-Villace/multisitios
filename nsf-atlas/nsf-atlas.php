<?php
/**
 * Plugin Name: Atlas · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias Estética internacional minimalista — gris carbón, serif, estilo BBC/Le Monde.
 * Version: 1.1.0
 * Author: Connotar
 * Text Domain: nsfatlas-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFATLAS_VERSION', '1.1.0' );
define( 'NSFATLAS_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFATLAS_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Atlas · Widgets Elementor requiere Elementor activo.', 'nsfatlas-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFATLAS_PATH . 'includes/class-plugin.php';
    \NSFATLAS\Plugin::instance();
} );
