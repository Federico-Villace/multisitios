<?php
namespace NSFPULSO;

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
        require_once NSFPULSO_PATH . 'includes/class-helpers.php';
        require_once NSFPULSO_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfpulso_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfpulso_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_assets() {
        $frontend_css = NSFPULSO_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFPULSO_PATH . 'assets/js/frontend.js';
        $css_version  = NSFPULSO_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFPULSO_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfpulso-widgets-frontend',
            NSFPULSO_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfpulso-widgets-editor',
            NSFPULSO_URL . 'assets/css/editor.css',
            [ 'nsfpulso-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfpulso-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFPULSO_VERSION
            );
            wp_register_style(
                'nsfpulso-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFPULSO_VERSION
            );
            wp_register_style(
                'nsfpulso-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFPULSO_VERSION
            );
        }

        wp_register_script(
            'nsfpulso-widgets-frontend',
            NSFPULSO_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfpulso-widgets-frontend' );
        wp_enqueue_script( 'nsfpulso-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfpulso-widgets',
            [
                'title' => esc_html__( 'Pulso', 'nsfpulso-widgets' ),
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
            '_nsfpulso_demo'        => 'boolean',
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
        add_shortcode( 'nsfpulso_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfpulso_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfpulso-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfpulso-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfpulso-default-view="' . esc_attr( $view ) . '">';
        include NSFPULSO_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFPULSO_PATH . 'includes/class-widget-base.php';

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
            require_once NSFPULSO_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFPULSO\\Widgets\\Full_Demo',
            '\\NSFPULSO\\Widgets\\Header_News',
            '\\NSFPULSO\\Widgets\\Live_Trends',
            '\\NSFPULSO\\Widgets\\Hero_News',
            '\\NSFPULSO\\Widgets\\Grid_News',
            '\\NSFPULSO\\Widgets\\Sidebar_Most_Read',
            '\\NSFPULSO\\Widgets\\Single_Note',
            '\\NSFPULSO\\Widgets\\Category_Archive',
            '\\NSFPULSO\\Widgets\\Category_Nav',
            '\\NSFPULSO\\Widgets\\Banner_Ad',
            '\\NSFPULSO\\Widgets\\Sidebar_Ad',
            '\\NSFPULSO\\Widgets\\Middle_Ad',
            '\\NSFPULSO\\Widgets\\Footer_Ad',
            '\\NSFPULSO\\Widgets\\Footer_News',
            '\\NSFPULSO\\Widgets\\Market_Indicators',
            '\\NSFPULSO\\Widgets\\Latest_Box',
            '\\NSFPULSO\\Widgets\\News_Row',
            '\\NSFPULSO\\Widgets\\Feature_Split',
            '\\NSFPULSO\\Widgets\\Dynamic_News_Block',
            '\\NSFPULSO\\Widgets\\Brand_Tiles',
            '\\NSFPULSO\\Widgets\\Search_Results',
            '\\NSFPULSO\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( 'Pulso Demo', 'nsfpulso-widgets' ),
            esc_html__( 'Pulso Demo', 'nsfpulso-widgets' ),
            'manage_options',
            'nsfpulso-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfpulso-widgets' ) );
        }

        $ids = get_option( 'nsfpulso_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Pulso · Instalador demo', 'nsfpulso-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfpulso_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfpulso_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta Pulso: secciones, posts populares, páginas, menú y shortcodes.', 'nsfpulso-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfpulso-widgets' ); ?></strong></p>
            <code>[nsfpulso_demo view="cat" switcher="no"]</code><br>
            <code>[nsfpulso_demo view="nota" switcher="no"]</code><br>
            <code>[nsfpulso_demo view="cat" switcher="no"]</code><br>
            <code>[nsfpulso_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfpulso_install_demo', 'nsfpulso_nonce' ); ?>
                <input type="hidden" name="action" value="nsfpulso_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfpulso-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por Pulso. ¿Continuar?', 'nsfpulso-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfpulso_delete_demo', 'nsfpulso_nonce' ); ?>
                <input type="hidden" name="action" value="nsfpulso_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfpulso-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfpulso-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfpulso-widgets' ) );
        }
        check_admin_referer( 'nsfpulso_install_demo', 'nsfpulso_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfpulso_demo_status', rawurlencode( 'Demo Pulso instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfpulso-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfpulso-widgets' ) );
        }
        check_admin_referer( 'nsfpulso_delete_demo', 'nsfpulso_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfpulso_demo_status', rawurlencode( 'Contenido demo Pulso eliminado.' ), admin_url( 'tools.php?page=nsfpulso-demo' ) ) );
        exit;
    }
}
