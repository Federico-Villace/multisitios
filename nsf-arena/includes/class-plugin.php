<?php
namespace NSFARENA;

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
        require_once NSFARENA_PATH . 'includes/class-helpers.php';
        require_once NSFARENA_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfarena_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfarena_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_assets() {
        $frontend_css = NSFARENA_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFARENA_PATH . 'assets/js/frontend.js';
        $css_version  = NSFARENA_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFARENA_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfarena-widgets-frontend',
            NSFARENA_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfarena-widgets-editor',
            NSFARENA_URL . 'assets/css/editor.css',
            [ 'nsfarena-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfarena-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFARENA_VERSION
            );
            wp_register_style(
                'nsfarena-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFARENA_VERSION
            );
            wp_register_style(
                'nsfarena-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFARENA_VERSION
            );
        }

        wp_register_script(
            'nsfarena-widgets-frontend',
            NSFARENA_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfarena-widgets-frontend' );
        wp_enqueue_script( 'nsfarena-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfarena-widgets',
            [
                'title' => esc_html__( '', 'nsfarena-widgets' ),
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
            '_nsfarena_demo'        => 'boolean',
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
        add_shortcode( 'nsfarena_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfarena_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfarena-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfarena-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfarena-default-view="' . esc_attr( $view ) . '">';
        include NSFARENA_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFARENA_PATH . 'includes/class-widget-base.php';

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
            require_once NSFARENA_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFARENA\\Widgets\\Full_Demo',
            '\\NSFARENA\\Widgets\\Header_News',
            '\\NSFARENA\\Widgets\\Live_Trends',
            '\\NSFARENA\\Widgets\\Hero_News',
            '\\NSFARENA\\Widgets\\Grid_News',
            '\\NSFARENA\\Widgets\\Sidebar_Most_Read',
            '\\NSFARENA\\Widgets\\Single_Note',
            '\\NSFARENA\\Widgets\\Category_Archive',
            '\\NSFARENA\\Widgets\\Category_Nav',
            '\\NSFARENA\\Widgets\\Banner_Ad',
            '\\NSFARENA\\Widgets\\Sidebar_Ad',
            '\\NSFARENA\\Widgets\\Middle_Ad',
            '\\NSFARENA\\Widgets\\Footer_Ad',
            '\\NSFARENA\\Widgets\\Footer_News',
            '\\NSFARENA\\Widgets\\Market_Indicators',
            '\\NSFARENA\\Widgets\\Latest_Box',
            '\\NSFARENA\\Widgets\\News_Row',
            '\\NSFARENA\\Widgets\\Feature_Split',
            '\\NSFARENA\\Widgets\\Dynamic_News_Block',
            '\\NSFARENA\\Widgets\\Brand_Tiles',
            '\\NSFARENA\\Widgets\\Search_Results',
            '\\NSFARENA\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( ' Demo', 'nsfarena-widgets' ),
            esc_html__( ' Demo', 'nsfarena-widgets' ),
            'manage_options',
            'nsfarena-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfarena-widgets' ) );
        }

        $ids = get_option( 'nsfarena_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( ' · Instalador demo', 'nsfarena-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfarena_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfarena_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta : secciones, posts populares, páginas, menú y shortcodes.', 'nsfarena-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfarena-widgets' ); ?></strong></p>
            <code>[nsfarena_demo view="cat" switcher="no"]</code><br>
            <code>[nsfarena_demo view="nota" switcher="no"]</code><br>
            <code>[nsfarena_demo view="cat" switcher="no"]</code><br>
            <code>[nsfarena_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfarena_install_demo', 'nsfarena_nonce' ); ?>
                <input type="hidden" name="action" value="nsfarena_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfarena-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por . ¿Continuar?', 'nsfarena-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfarena_delete_demo', 'nsfarena_nonce' ); ?>
                <input type="hidden" name="action" value="nsfarena_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfarena-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfarena-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfarena-widgets' ) );
        }
        check_admin_referer( 'nsfarena_install_demo', 'nsfarena_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfarena_demo_status', rawurlencode( 'Demo  instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfarena-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfarena-widgets' ) );
        }
        check_admin_referer( 'nsfarena_delete_demo', 'nsfarena_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfarena_demo_status', rawurlencode( 'Contenido demo  eliminado.' ), admin_url( 'tools.php?page=nsfarena-demo' ) ) );
        exit;
    }
}
