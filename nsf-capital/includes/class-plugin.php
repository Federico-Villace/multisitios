<?php
namespace NSFCAPITAL;

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
        require_once NSFCAPITAL_PATH . 'includes/class-helpers.php';
        require_once NSFCAPITAL_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfcapital_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfcapital_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_assets() {
        $frontend_css = NSFCAPITAL_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFCAPITAL_PATH . 'assets/js/frontend.js';
        $css_version  = NSFCAPITAL_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFCAPITAL_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfcapital-widgets-frontend',
            NSFCAPITAL_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfcapital-widgets-editor',
            NSFCAPITAL_URL . 'assets/css/editor.css',
            [ 'nsfcapital-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfcapital-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFCAPITAL_VERSION
            );
            wp_register_style(
                'nsfcapital-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFCAPITAL_VERSION
            );
            wp_register_style(
                'nsfcapital-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFCAPITAL_VERSION
            );
        }

        wp_register_script(
            'nsfcapital-widgets-frontend',
            NSFCAPITAL_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfcapital-widgets-frontend' );
        wp_enqueue_script( 'nsfcapital-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfcapital-widgets',
            [
                'title' => esc_html__( '', 'nsfcapital-widgets' ),
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
            '_nsfcapital_demo'        => 'boolean',
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
        add_shortcode( 'nsfcapital_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfcapital_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfcapital-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfcapital-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfcapital-default-view="' . esc_attr( $view ) . '">';
        include NSFCAPITAL_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFCAPITAL_PATH . 'includes/class-widget-base.php';

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
            require_once NSFCAPITAL_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFCAPITAL\\Widgets\\Full_Demo',
            '\\NSFCAPITAL\\Widgets\\Header_News',
            '\\NSFCAPITAL\\Widgets\\Live_Trends',
            '\\NSFCAPITAL\\Widgets\\Hero_News',
            '\\NSFCAPITAL\\Widgets\\Grid_News',
            '\\NSFCAPITAL\\Widgets\\Sidebar_Most_Read',
            '\\NSFCAPITAL\\Widgets\\Single_Note',
            '\\NSFCAPITAL\\Widgets\\Category_Archive',
            '\\NSFCAPITAL\\Widgets\\Category_Nav',
            '\\NSFCAPITAL\\Widgets\\Banner_Ad',
            '\\NSFCAPITAL\\Widgets\\Sidebar_Ad',
            '\\NSFCAPITAL\\Widgets\\Middle_Ad',
            '\\NSFCAPITAL\\Widgets\\Footer_Ad',
            '\\NSFCAPITAL\\Widgets\\Footer_News',
            '\\NSFCAPITAL\\Widgets\\Market_Indicators',
            '\\NSFCAPITAL\\Widgets\\Latest_Box',
            '\\NSFCAPITAL\\Widgets\\News_Row',
            '\\NSFCAPITAL\\Widgets\\Feature_Split',
            '\\NSFCAPITAL\\Widgets\\Dynamic_News_Block',
            '\\NSFCAPITAL\\Widgets\\Brand_Tiles',
            '\\NSFCAPITAL\\Widgets\\Search_Results',
            '\\NSFCAPITAL\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( ' Demo', 'nsfcapital-widgets' ),
            esc_html__( ' Demo', 'nsfcapital-widgets' ),
            'manage_options',
            'nsfcapital-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfcapital-widgets' ) );
        }

        $ids = get_option( 'nsfcapital_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( ' · Instalador demo', 'nsfcapital-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfcapital_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfcapital_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta : secciones, posts populares, páginas, menú y shortcodes.', 'nsfcapital-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfcapital-widgets' ); ?></strong></p>
            <code>[nsfcapital_demo view="cat" switcher="no"]</code><br>
            <code>[nsfcapital_demo view="nota" switcher="no"]</code><br>
            <code>[nsfcapital_demo view="cat" switcher="no"]</code><br>
            <code>[nsfcapital_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfcapital_install_demo', 'nsfcapital_nonce' ); ?>
                <input type="hidden" name="action" value="nsfcapital_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfcapital-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por . ¿Continuar?', 'nsfcapital-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfcapital_delete_demo', 'nsfcapital_nonce' ); ?>
                <input type="hidden" name="action" value="nsfcapital_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfcapital-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfcapital-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfcapital-widgets' ) );
        }
        check_admin_referer( 'nsfcapital_install_demo', 'nsfcapital_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfcapital_demo_status', rawurlencode( 'Demo  instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfcapital-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfcapital-widgets' ) );
        }
        check_admin_referer( 'nsfcapital_delete_demo', 'nsfcapital_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfcapital_demo_status', rawurlencode( 'Contenido demo  eliminado.' ), admin_url( 'tools.php?page=nsfcapital-demo' ) ) );
        exit;
    }
}
