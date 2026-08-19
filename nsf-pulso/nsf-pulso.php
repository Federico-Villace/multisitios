<?php
/**
 * Plugin Name: Pulso · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias. Estética tecno-berlinesa — brutalista, rojo señal sobre blanco y negro, retículas visibles y tipografía monoespaciada.
 * Version: 2.0.0
 * Author: Connotar
 * Text Domain: nsfpulso-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFPULSO_VERSION', '2.0.0' );
define( 'NSFPULSO_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFPULSO_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Pulso · Widgets Elementor requiere Elementor activo.', 'nsfpulso-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFPULSO_PATH . 'includes/class-plugin.php';
    \NSFPULSO\Plugin::instance();
} );
