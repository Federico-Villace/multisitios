<?php
namespace NSFMERIDIANO;

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
        require_once NSFMERIDIANO_PATH . 'includes/class-helpers.php';
        require_once NSFMERIDIANO_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfmeridiano_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfmeridiano_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function register_assets() {
        $frontend_css = NSFMERIDIANO_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFMERIDIANO_PATH . 'assets/js/frontend.js';
        $css_version  = NSFMERIDIANO_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFMERIDIANO_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfmeridiano-widgets-frontend',
            NSFMERIDIANO_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfmeridiano-widgets-editor',
            NSFMERIDIANO_URL . 'assets/css/editor.css',
            [ 'nsfmeridiano-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfmeridiano-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFMERIDIANO_VERSION
            );
            wp_register_style(
                'nsfmeridiano-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFMERIDIANO_VERSION
            );
            wp_register_style(
                'nsfmeridiano-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFMERIDIANO_VERSION
            );
        }

        wp_register_script(
            'nsfmeridiano-widgets-frontend',
            NSFMERIDIANO_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfmeridiano-widgets-frontend' );
        wp_enqueue_script( 'nsfmeridiano-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfmeridiano-widgets',
            [
                'title' => esc_html__( 'Meridiano', 'nsfmeridiano-widgets' ),
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
            '_nsfmeridiano_demo'        => 'boolean',
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
        add_shortcode( 'nsfmeridiano_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfmeridiano_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfmeridiano-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfmeridiano-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfmeridiano-default-view="' . esc_attr( $view ) . '">';
        include NSFMERIDIANO_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFMERIDIANO_PATH . 'includes/class-widget-base.php';

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
            require_once NSFMERIDIANO_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFMERIDIANO\\Widgets\\Full_Demo',
            '\\NSFMERIDIANO\\Widgets\\Header_News',
            '\\NSFMERIDIANO\\Widgets\\Live_Trends',
            '\\NSFMERIDIANO\\Widgets\\Hero_News',
            '\\NSFMERIDIANO\\Widgets\\Grid_News',
            '\\NSFMERIDIANO\\Widgets\\Sidebar_Most_Read',
            '\\NSFMERIDIANO\\Widgets\\Single_Note',
            '\\NSFMERIDIANO\\Widgets\\Category_Archive',
            '\\NSFMERIDIANO\\Widgets\\Category_Nav',
            '\\NSFMERIDIANO\\Widgets\\Banner_Ad',
            '\\NSFMERIDIANO\\Widgets\\Sidebar_Ad',
            '\\NSFMERIDIANO\\Widgets\\Middle_Ad',
            '\\NSFMERIDIANO\\Widgets\\Footer_Ad',
            '\\NSFMERIDIANO\\Widgets\\Footer_News',
            '\\NSFMERIDIANO\\Widgets\\Market_Indicators',
            '\\NSFMERIDIANO\\Widgets\\Latest_Box',
            '\\NSFMERIDIANO\\Widgets\\News_Row',
            '\\NSFMERIDIANO\\Widgets\\Feature_Split',
            '\\NSFMERIDIANO\\Widgets\\Dynamic_News_Block',
            '\\NSFMERIDIANO\\Widgets\\Brand_Tiles',
            '\\NSFMERIDIANO\\Widgets\\Search_Results',
            '\\NSFMERIDIANO\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( 'Meridiano Demo', 'nsfmeridiano-widgets' ),
            esc_html__( 'Meridiano Demo', 'nsfmeridiano-widgets' ),
            'manage_options',
            'nsfmeridiano-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfmeridiano-widgets' ) );
        }

        $ids = get_option( 'nsfmeridiano_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( 'Meridiano · Instalador demo', 'nsfmeridiano-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfmeridiano_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfmeridiano_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta Meridiano: secciones, posts populares, páginas, menú y shortcodes.', 'nsfmeridiano-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfmeridiano-widgets' ); ?></strong></p>
            <code>[nsfmeridiano_demo view="cat" switcher="no"]</code><br>
            <code>[nsfmeridiano_demo view="nota" switcher="no"]</code><br>
            <code>[nsfmeridiano_demo view="cat" switcher="no"]</code><br>
            <code>[nsfmeridiano_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfmeridiano_install_demo', 'nsfmeridiano_nonce' ); ?>
                <input type="hidden" name="action" value="nsfmeridiano_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfmeridiano-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por Meridiano. ¿Continuar?', 'nsfmeridiano-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfmeridiano_delete_demo', 'nsfmeridiano_nonce' ); ?>
                <input type="hidden" name="action" value="nsfmeridiano_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfmeridiano-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfmeridiano-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfmeridiano-widgets' ) );
        }
        check_admin_referer( 'nsfmeridiano_install_demo', 'nsfmeridiano_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfmeridiano_demo_status', rawurlencode( 'Demo Meridiano instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfmeridiano-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfmeridiano-widgets' ) );
        }
        check_admin_referer( 'nsfmeridiano_delete_demo', 'nsfmeridiano_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfmeridiano_demo_status', rawurlencode( 'Contenido demo Meridiano eliminado.' ), admin_url( 'tools.php?page=nsfmeridiano-demo' ) ) );
        exit;
    }
}
