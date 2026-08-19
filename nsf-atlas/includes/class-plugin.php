<?php
namespace NSFATLAS;

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
        require_once NSFATLAS_PATH . 'includes/class-helpers.php';
        require_once NSFATLAS_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfatlas_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfatlas_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_assets() {
        $frontend_css = NSFATLAS_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFATLAS_PATH . 'assets/js/frontend.js';
        $css_version  = NSFATLAS_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFATLAS_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfatlas-widgets-frontend',
            NSFATLAS_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfatlas-widgets-editor',
            NSFATLAS_URL . 'assets/css/editor.css',
            [ 'nsfatlas-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfatlas-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFATLAS_VERSION
            );
            wp_register_style(
                'nsfatlas-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFATLAS_VERSION
            );
            wp_register_style(
                'nsfatlas-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFATLAS_VERSION
            );
        }

        wp_register_script(
            'nsfatlas-widgets-frontend',
            NSFATLAS_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfatlas-widgets-frontend' );
        wp_enqueue_script( 'nsfatlas-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfatlas-widgets',
            [
                'title' => esc_html__( '', 'nsfatlas-widgets' ),
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
            '_nsfatlas_demo'        => 'boolean',
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
        add_shortcode( 'nsfatlas_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfatlas_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfatlas-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfatlas-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfatlas-default-view="' . esc_attr( $view ) . '">';
        include NSFATLAS_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFATLAS_PATH . 'includes/class-widget-base.php';

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
            require_once NSFATLAS_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFATLAS\\Widgets\\Full_Demo',
            '\\NSFATLAS\\Widgets\\Header_News',
            '\\NSFATLAS\\Widgets\\Live_Trends',
            '\\NSFATLAS\\Widgets\\Hero_News',
            '\\NSFATLAS\\Widgets\\Grid_News',
            '\\NSFATLAS\\Widgets\\Sidebar_Most_Read',
            '\\NSFATLAS\\Widgets\\Single_Note',
            '\\NSFATLAS\\Widgets\\Category_Archive',
            '\\NSFATLAS\\Widgets\\Category_Nav',
            '\\NSFATLAS\\Widgets\\Banner_Ad',
            '\\NSFATLAS\\Widgets\\Sidebar_Ad',
            '\\NSFATLAS\\Widgets\\Middle_Ad',
            '\\NSFATLAS\\Widgets\\Footer_Ad',
            '\\NSFATLAS\\Widgets\\Footer_News',
            '\\NSFATLAS\\Widgets\\Market_Indicators',
            '\\NSFATLAS\\Widgets\\Latest_Box',
            '\\NSFATLAS\\Widgets\\News_Row',
            '\\NSFATLAS\\Widgets\\Feature_Split',
            '\\NSFATLAS\\Widgets\\Dynamic_News_Block',
            '\\NSFATLAS\\Widgets\\Brand_Tiles',
            '\\NSFATLAS\\Widgets\\Search_Results',
            '\\NSFATLAS\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( ' Demo', 'nsfatlas-widgets' ),
            esc_html__( ' Demo', 'nsfatlas-widgets' ),
            'manage_options',
            'nsfatlas-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfatlas-widgets' ) );
        }

        $ids = get_option( 'nsfatlas_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( ' · Instalador demo', 'nsfatlas-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfatlas_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfatlas_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta : secciones, posts populares, páginas, menú y shortcodes.', 'nsfatlas-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfatlas-widgets' ); ?></strong></p>
            <code>[nsfatlas_demo view="cat" switcher="no"]</code><br>
            <code>[nsfatlas_demo view="nota" switcher="no"]</code><br>
            <code>[nsfatlas_demo view="cat" switcher="no"]</code><br>
            <code>[nsfatlas_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfatlas_install_demo', 'nsfatlas_nonce' ); ?>
                <input type="hidden" name="action" value="nsfatlas_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfatlas-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por . ¿Continuar?', 'nsfatlas-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfatlas_delete_demo', 'nsfatlas_nonce' ); ?>
                <input type="hidden" name="action" value="nsfatlas_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfatlas-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfatlas-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfatlas-widgets' ) );
        }
        check_admin_referer( 'nsfatlas_install_demo', 'nsfatlas_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfatlas_demo_status', rawurlencode( 'Demo  instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfatlas-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfatlas-widgets' ) );
        }
        check_admin_referer( 'nsfatlas_delete_demo', 'nsfatlas_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfatlas_demo_status', rawurlencode( 'Contenido demo  eliminado.' ), admin_url( 'tools.php?page=nsfatlas-demo' ) ) );
        exit;
    }
}
