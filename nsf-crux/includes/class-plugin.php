<?php
namespace NSFCRUX;

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
        require_once NSFCRUX_PATH . 'includes/class-helpers.php';
        require_once NSFCRUX_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfcrux_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfcrux_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_filter( 'get_the_archive_title', [ $this, 'translate_archive_title' ] );
    }

    /**
     * Traduce los títulos de archivo/categoría/búsqueda del core de WordPress,
     * que quedan en inglés cuando la traducción del theme activo está incompleta.
     */
    public function translate_archive_title( $title ) {
        if ( is_category() ) {
            $title = 'Categoría: ' . single_cat_title( '', false );
        } elseif ( is_tag() ) {
            $title = 'Etiqueta: ' . single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = 'Autor: ' . get_the_author();
        } elseif ( is_search() ) {
            $title = 'Resultados de la búsqueda para: ' . get_search_query();
        } elseif ( is_404() ) {
            $title = 'Página no encontrada';
        }
        return $title;
    }

    public function register_assets() {
        $frontend_css = NSFCRUX_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFCRUX_PATH . 'assets/js/frontend.js';
        $css_version  = NSFCRUX_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFCRUX_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        wp_register_style(
            'nsfcrux-widgets-frontend',
            NSFCRUX_URL . 'assets/css/frontend.css',
            [],
            $css_version
        );

        wp_register_style(
            'nsfcrux-widgets-editor',
            NSFCRUX_URL . 'assets/css/editor.css',
            [ 'nsfcrux-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfcrux-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFCRUX_VERSION
            );
            wp_register_style(
                'nsfcrux-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFCRUX_VERSION
            );
            wp_register_style(
                'nsfcrux-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFCRUX_VERSION
            );
        }

        wp_register_script(
            'nsfcrux-widgets-frontend',
            NSFCRUX_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfcrux-widgets-frontend' );
        wp_enqueue_script( 'nsfcrux-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfcrux-widgets',
            [
                'title' => esc_html__( '', 'nsfcrux-widgets' ),
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
            '_nsfcrux_demo'        => 'boolean',
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
        add_shortcode( 'nsfcrux_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfcrux_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfcrux-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfcrux-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfcrux-default-view="' . esc_attr( $view ) . '">';
        include NSFCRUX_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFCRUX_PATH . 'includes/class-widget-base.php';

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
            require_once NSFCRUX_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFCRUX\\Widgets\\Full_Demo',
            '\\NSFCRUX\\Widgets\\Header_News',
            '\\NSFCRUX\\Widgets\\Live_Trends',
            '\\NSFCRUX\\Widgets\\Hero_News',
            '\\NSFCRUX\\Widgets\\Grid_News',
            '\\NSFCRUX\\Widgets\\Sidebar_Most_Read',
            '\\NSFCRUX\\Widgets\\Single_Note',
            '\\NSFCRUX\\Widgets\\Category_Archive',
            '\\NSFCRUX\\Widgets\\Category_Nav',
            '\\NSFCRUX\\Widgets\\Banner_Ad',
            '\\NSFCRUX\\Widgets\\Sidebar_Ad',
            '\\NSFCRUX\\Widgets\\Middle_Ad',
            '\\NSFCRUX\\Widgets\\Footer_Ad',
            '\\NSFCRUX\\Widgets\\Footer_News',
            '\\NSFCRUX\\Widgets\\Market_Indicators',
            '\\NSFCRUX\\Widgets\\Latest_Box',
            '\\NSFCRUX\\Widgets\\News_Row',
            '\\NSFCRUX\\Widgets\\Feature_Split',
            '\\NSFCRUX\\Widgets\\Dynamic_News_Block',
            '\\NSFCRUX\\Widgets\\Brand_Tiles',
            '\\NSFCRUX\\Widgets\\Search_Results',
            '\\NSFCRUX\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( ' Demo', 'nsfcrux-widgets' ),
            esc_html__( ' Demo', 'nsfcrux-widgets' ),
            'manage_options',
            'nsfcrux-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfcrux-widgets' ) );
        }

        $ids = get_option( 'nsfcrux_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( ' · Instalador demo', 'nsfcrux-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfcrux_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfcrux_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta : secciones, posts populares, páginas, menú y shortcodes.', 'nsfcrux-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfcrux-widgets' ); ?></strong></p>
            <code>[nsfcrux_demo view="cat" switcher="no"]</code><br>
            <code>[nsfcrux_demo view="nota" switcher="no"]</code><br>
            <code>[nsfcrux_demo view="cat" switcher="no"]</code><br>
            <code>[nsfcrux_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfcrux_install_demo', 'nsfcrux_nonce' ); ?>
                <input type="hidden" name="action" value="nsfcrux_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfcrux-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por . ¿Continuar?', 'nsfcrux-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfcrux_delete_demo', 'nsfcrux_nonce' ); ?>
                <input type="hidden" name="action" value="nsfcrux_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfcrux-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfcrux-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfcrux-widgets' ) );
        }
        check_admin_referer( 'nsfcrux_install_demo', 'nsfcrux_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfcrux_demo_status', rawurlencode( 'Demo  instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfcrux-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfcrux-widgets' ) );
        }
        check_admin_referer( 'nsfcrux_delete_demo', 'nsfcrux_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfcrux_demo_status', rawurlencode( 'Contenido demo  eliminado.' ), admin_url( 'tools.php?page=nsfcrux-demo' ) ) );
        exit;
    }
}
