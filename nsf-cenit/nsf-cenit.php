<?php
/**
 * Plugin Name: Cenit · Widgets Elementor
 * Description: Identidad Cenit — estructura moderna con rastros de 8 bits: logo bitmap, acento verdigris y sombras duras en las fotos jerárquicas. Tipografía grande y mucho aire.
 * Version: 2.1.0
 * Author: Connotar
 * Text Domain: nsfcenit-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFCENIT_VERSION', '2.1.0' );
define( 'NSFCENIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFCENIT_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Cenit · Widgets Elementor requiere Elementor activo.', 'nsfcenit-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFCENIT_PATH . 'includes/class-plugin.php';
    \NSFCENIT\Plugin::instance();
} );
