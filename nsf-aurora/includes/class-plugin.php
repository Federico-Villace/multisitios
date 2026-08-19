<?php
namespace NSFAURORA;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Plugin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        require_once NSFAURORA_PATH . 'includes/class-helpers.php';
        require_once NSFAURORA_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfaurora_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfaurora_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_assets() {
        $frontend_css = NSFAURORA_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFAURORA_PATH . 'assets/js/frontend.js';
        $css_version  = NSFAURORA_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFAURORA_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfaurora-widgets-frontend',
            NSFAURORA_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfaurora-widgets-editor',
            NSFAURORA_URL . 'assets/css/editor.css',
            [ 'nsfaurora-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfaurora-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFAURORA_VERSION
            );
            wp_register_style(
                'nsfaurora-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFAURORA_VERSION
            );
            wp_register_style(
                'nsfaurora-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFAURORA_VERSION
            );
        }

        wp_register_script(
            'nsfaurora-widgets-frontend',
            NSFAURORA_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfaurora-widgets-frontend' );
        wp_enqueue_script( 'nsfaurora-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfaurora-widgets',
            [
                'title' => esc_html__( '', 'nsfaurora-widgets' ),
                'icon'  => 'eicon-posts-grid',
            ]
        );
    }

    public function register_post_meta() {
        $metas = [
            '_news_urgent'       => 'boolean',
            '_news_subtitle'     => 'string',
            '_news_caption'      => 'string',
            '_news_video_url'    => 'string',
            '_news_duration'     => 'string',
            '_nsfaurora_demo'        => 'boolean',
            'post_views_count'   => 'integer',
        ];

        foreach ( $metas as $key => $type ) {
            register_post_meta(
                'post',
                $key,
                [
                    'type'              => $type,
                    'single'            => true,
                    'show_in_rest'      => true,
                    'sanitize_callback' => $type === 'integer' ? 'absint' : ( $type === 'boolean' ? 'rest_sanitize_boolean' : 'sanitize_text_field' ),
                    'auth_callback'     => function () {
                        return current_user_can( 'edit_posts' );
                    },
                ]
            );
        }
    }

    public function register_shortcodes() {
        add_shortcode( 'nsfaurora_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfaurora_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfaurora-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfaurora-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfaurora-default-view="' . esc_attr( $view ) . '">';
        include NSFAURORA_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFAURORA_PATH . 'includes/class-widget-base.php';

        $widgets = [
            'full-demo',
            'header-news',
            'live-trends',
            'hero-news',
            'grid-news',
            'sidebar-most-read',
            'single-note',
            'category-archive',
            'category-nav',
            'banner-ad',
            'sidebar-ad',
            'middle-ad',
            'footer-ad',
            'footer-news',
            'market-indicators',
            'latest-box',
            'news-row',
            'feature-split',
            'dynamic-news-block',
            'brand-tiles',
            'search-results',
            'category-grid-posts',
        ];

        foreach ( $widgets as $file ) {
            require_once NSFAURORA_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFAURORA\\Widgets\\Full_Demo',
            '\\NSFAURORA\\Widgets\\Header_News',
            '\\NSFAURORA\\Widgets\\Live_Trends',
            '\\NSFAURORA\\Widgets\\Hero_News',
            '\\NSFAURORA\\Widgets\\Grid_News',
            '\\NSFAURORA\\Widgets\\Sidebar_Most_Read',
            '\\NSFAURORA\\Widgets\\Single_Note',
            '\\NSFAURORA\\Widgets\\Category_Archive',
            '\\NSFAURORA\\Widgets\\Category_Nav',
            '\\NSFAURORA\\Widgets\\Banner_Ad',
            '\\NSFAURORA\\Widgets\\Sidebar_Ad',
            '\\NSFAURORA\\Widgets\\Middle_Ad',
            '\\NSFAURORA\\Widgets\\Footer_Ad',
            '\\NSFAURORA\\Widgets\\Footer_News',
            '\\NSFAURORA\\Widgets\\Market_Indicators',
            '\\NSFAURORA\\Widgets\\Latest_Box',
            '\\NSFAURORA\\Widgets\\News_Row',
            '\\NSFAURORA\\Widgets\\Feature_Split',
            '\\NSFAURORA\\Widgets\\Dynamic_News_Block',
            '\\NSFAURORA\\Widgets\\Brand_Tiles',
            '\\NSFAURORA\\Widgets\\Search_Results',
            '\\NSFAURORA\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( ' Demo', 'nsfaurora-widgets' ),
            esc_html__( ' Demo', 'nsfaurora-widgets' ),
            'manage_options',
            'nsfaurora-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfaurora-widgets' ) );
        }

        $ids = get_option( 'nsfaurora_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( ' · Instalador demo', 'nsfaurora-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfaurora_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfaurora_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta : secciones, posts populares, páginas, menú y shortcodes.', 'nsfaurora-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfaurora-widgets' ); ?></strong></p>
            <code>[nsfaurora_demo view="cat" switcher="no"]</code><br>
            <code>[nsfaurora_demo view="nota" switcher="no"]</code><br>
            <code>[nsfaurora_demo view="cat" switcher="no"]</code><br>
            <code>[nsfaurora_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfaurora_install_demo', 'nsfaurora_nonce' ); ?>
                <input type="hidden" name="action" value="nsfaurora_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfaurora-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por . ¿Continuar?', 'nsfaurora-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfaurora_delete_demo', 'nsfaurora_nonce' ); ?>
                <input type="hidden" name="action" value="nsfaurora_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfaurora-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfaurora-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfaurora-widgets' ) );
        }
        check_admin_referer( 'nsfaurora_install_demo', 'nsfaurora_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfaurora_demo_status', rawurlencode( 'Demo  instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfaurora-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfaurora-widgets' ) );
        }
        check_admin_referer( 'nsfaurora_delete_demo', 'nsfaurora_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfaurora_demo_status', rawurlencode( 'Contenido demo  eliminado.' ), admin_url( 'tools.php?page=nsfaurora-demo' ) ) );
        exit;
    }
}
