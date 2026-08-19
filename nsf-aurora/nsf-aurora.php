<?php
/**
 * Plugin Name: Aurora · Widgets Elementor
 * Description: Widgets Elementor, demo installer y plantillas para portal de noticias Estética diario regional — ámbar cálido, sans amigable, comunidad local.
 * Version: 1.1.0
 * Author: Connotar
 * Text Domain: nsfaurora-widgets
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NSFAURORA_VERSION', '1.1.0' );
define( 'NSFAURORA_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSFAURORA_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function () {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Aurora · Widgets Elementor requiere Elementor activo.', 'nsfaurora-widgets' ) . '</p></div>';
        } );
        return;
    }

    require_once NSFAURORA_PATH . 'includes/class-plugin.php';
    \NSFAURORA\Plugin::instance();
} );
