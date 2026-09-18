<?php
namespace NSFMAREA;

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
        require_once NSFMAREA_PATH . 'includes/class-helpers.php';
        require_once NSFMAREA_PATH . 'includes/class-demo-installer.php';

        add_action( 'init', [ $this, 'register_post_meta' ] );
        add_action( 'init', [ $this, 'register_shortcodes' ] );
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_post_nsfmarea_install_demo', [ $this, 'handle_install_demo' ] );
        add_action( 'admin_post_nsfmarea_delete_demo', [ $this, 'handle_delete_demo' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ], 5 );
        add_filter( 'wp_resource_hints', [ $this, 'resource_hints' ], 10, 2 );
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_assets' ] );
        add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_assets' ] );
        add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function resource_hints( $hints, $relation ) {
        if ( 'preconnect' === $relation && wp_style_is( 'nsfmarea-fonts', 'enqueued' ) ) {
            $hints[] = [ 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' ];
        }
        return $hints;
    }

    public function register_assets() {
        $frontend_css = NSFMAREA_PATH . 'assets/css/frontend.css';
        $frontend_js  = NSFMAREA_PATH . 'assets/js/frontend.js';
        $css_version  = NSFMAREA_VERSION . '-' . ( file_exists( $frontend_css ) ? filemtime( $frontend_css ) : time() );
        $js_version   = NSFMAREA_VERSION . '-' . ( file_exists( $frontend_js ) ? filemtime( $frontend_js ) : time() );

        /* Tipografía de titulares: una grotesca ANCHA y muy negra, que es
           lo que devuelve el logo entintado de Noticias del Conurbano.
           Es deliberadamente lo opuesto a la condensada que usa el
           plugin hermano, para que los dos sitios no se confundan.

           Se registra como dependencia de la hoja del plugin: WordPress
           la pide sola donde hay un widget y no en el resto del sitio.
           El cuerpo va en serif del sistema, así que no hace falta
           cargar una segunda familia. */
        wp_register_style(
            'nsfmarea-fonts',
            'https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap',
            [],
            null
        );

        wp_register_style(
            'nsfmarea-widgets-frontend',
            NSFMAREA_URL . 'assets/css/frontend.css',
            [ 'nsfmarea-fonts' ],
            $css_version
        );

        wp_register_style(
            'nsfmarea-widgets-editor',
            NSFMAREA_URL . 'assets/css/editor.css',
            [ 'nsfmarea-widgets-frontend' ],
            $css_version
        );


        if ( defined( 'ELEMENTOR_ASSETS_URL' ) ) {
            wp_register_style(
                'nsfmarea-fa-solid',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/solid.min.css',
                [],
                NSFMAREA_VERSION
            );
            wp_register_style(
                'nsfmarea-fa-brands',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/brands.min.css',
                [],
                NSFMAREA_VERSION
            );
            wp_register_style(
                'nsfmarea-fa-fontawesome',
                ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/fontawesome.min.css',
                [],
                NSFMAREA_VERSION
            );
        }

        wp_register_script(
            'nsfmarea-widgets-frontend',
            NSFMAREA_URL . 'assets/js/frontend.js',
            [],
            $js_version,
            true
        );
    }

    public function enqueue_assets() {
        $this->register_assets();
        wp_enqueue_style( 'nsfmarea-widgets-frontend' );
        wp_enqueue_script( 'nsfmarea-widgets-frontend' );
    }

    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'nsfmarea-widgets',
            [
                'title' => esc_html__( '', 'nsfmarea-widgets' ),
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
            '_nsfmarea_demo'        => 'boolean',
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
        add_shortcode( 'nsfmarea_demo', [ $this, 'shortcode_demo' ] );
    }

    public function shortcode_demo( $atts ) {
        $atts = shortcode_atts(
            [
                'view'     => 'cat',
                'switcher' => 'no',
            ],
            $atts,
            'nsfmarea_demo'
        );

        $view = in_array( $atts['view'], [ 'nota', 'cat' ], true ) ? $atts['view'] : 'cat';
        $show_switcher = in_array( strtolower( (string) $atts['switcher'] ), [ 'yes', 'si', 'sí', 'true', '1' ], true );

        $this->enqueue_assets();

        ob_start();
        $classes = [ 'nsfmarea-scope' ];
        if ( ! $show_switcher ) {
            $classes[] = 'nsfmarea-hide-switcher';
        }
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfmarea-default-view="' . esc_attr( $view ) . '">';
        include NSFMAREA_PATH . 'templates/full-demo.php';
        echo '</div>';
        return ob_get_clean();
    }

    public function register_widgets( $widgets_manager ) {
        require_once NSFMAREA_PATH . 'includes/class-widget-base.php';

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
            require_once NSFMAREA_PATH . 'widgets/class-' . $file . '.php';
        }

        $classes = [
            '\\NSFMAREA\\Widgets\\Full_Demo',
            '\\NSFMAREA\\Widgets\\Header_News',
            '\\NSFMAREA\\Widgets\\Live_Trends',
            '\\NSFMAREA\\Widgets\\Hero_News',
            '\\NSFMAREA\\Widgets\\Grid_News',
            '\\NSFMAREA\\Widgets\\Sidebar_Most_Read',
            '\\NSFMAREA\\Widgets\\Single_Note',
            '\\NSFMAREA\\Widgets\\Category_Archive',
            '\\NSFMAREA\\Widgets\\Category_Nav',
            '\\NSFMAREA\\Widgets\\Banner_Ad',
            '\\NSFMAREA\\Widgets\\Sidebar_Ad',
            '\\NSFMAREA\\Widgets\\Middle_Ad',
            '\\NSFMAREA\\Widgets\\Footer_Ad',
            '\\NSFMAREA\\Widgets\\Footer_News',
            '\\NSFMAREA\\Widgets\\Market_Indicators',
            '\\NSFMAREA\\Widgets\\Latest_Box',
            '\\NSFMAREA\\Widgets\\News_Row',
            '\\NSFMAREA\\Widgets\\Feature_Split',
            '\\NSFMAREA\\Widgets\\Dynamic_News_Block',
            '\\NSFMAREA\\Widgets\\Brand_Tiles',
            '\\NSFMAREA\\Widgets\\Search_Results',
            '\\NSFMAREA\\Widgets\\Category_Grid_Posts',
        ];

        foreach ( $classes as $class ) {
            if ( class_exists( $class ) ) {
                $widgets_manager->register( new $class() );
            }
        }
    }

    public function admin_menu() {
        add_management_page(
            esc_html__( ' Demo', 'nsfmarea-widgets' ),
            esc_html__( ' Demo', 'nsfmarea-widgets' ),
            'manage_options',
            'nsfmarea-demo',
            [ $this, 'render_demo_page' ]
        );
    }

    public function render_demo_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para acceder a esta página.', 'nsfmarea-widgets' ) );
        }

        $ids = get_option( 'nsfmarea_demo_ids', [] );
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__( ' · Instalador demo', 'nsfmarea-widgets' ); ?></h1>
            <?php if ( isset( $_GET['nsfmarea_demo_status'] ) ) : ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html( sanitize_text_field( wp_unslash( $_GET['nsfmarea_demo_status'] ) ) ); ?></p></div>
            <?php endif; ?>
            <p><?php echo esc_html__( 'Crea contenido demo para probar la maqueta : secciones, posts populares, páginas, menú y shortcodes.', 'nsfmarea-widgets' ); ?></p>
            <p><strong><?php echo esc_html__( 'Shortcodes:', 'nsfmarea-widgets' ); ?></strong></p>
            <code>[nsfmarea_demo view="cat" switcher="no"]</code><br>
            <code>[nsfmarea_demo view="nota" switcher="no"]</code><br>
            <code>[nsfmarea_demo view="cat" switcher="no"]</code><br>
            <code>[nsfmarea_demo view="cat" switcher="yes"]</code>

            <hr>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:12px;">
                <?php wp_nonce_field( 'nsfmarea_install_demo', 'nsfmarea_nonce' ); ?>
                <input type="hidden" name="action" value="nsfmarea_install_demo">
                <?php submit_button( esc_html__( 'Instalar / actualizar demo', 'nsfmarea-widgets' ), 'primary', 'submit', false ); ?>
            </form>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;" onsubmit="return confirm('<?php echo esc_js( __( 'Esto eliminará sólo el contenido demo creado por . ¿Continuar?', 'nsfmarea-widgets' ) ); ?>');">
                <?php wp_nonce_field( 'nsfmarea_delete_demo', 'nsfmarea_nonce' ); ?>
                <input type="hidden" name="action" value="nsfmarea_delete_demo">
                <?php submit_button( esc_html__( 'Eliminar demo', 'nsfmarea-widgets' ), 'delete', 'submit', false ); ?>
            </form>

            <hr>
            <h2><?php echo esc_html__( 'Contenido demo registrado', 'nsfmarea-widgets' ); ?></h2>
            <pre style="background:#fff;border:1px solid #ccd0d4;padding:12px;max-width:760px;overflow:auto;"><?php echo esc_html( wp_json_encode( $ids, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ); ?></pre>
        </div>
        <?php
    }

    public function handle_install_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfmarea-widgets' ) );
        }
        check_admin_referer( 'nsfmarea_install_demo', 'nsfmarea_nonce' );

        $installer = new Demo_Installer();
        $installer->install();

        wp_safe_redirect( add_query_arg( 'nsfmarea_demo_status', rawurlencode( 'Demo  instalado / actualizado correctamente.' ), admin_url( 'tools.php?page=nsfmarea-demo' ) ) );
        exit;
    }

    public function handle_delete_demo() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'No tenés permisos para ejecutar esta acción.', 'nsfmarea-widgets' ) );
        }
        check_admin_referer( 'nsfmarea_delete_demo', 'nsfmarea_nonce' );

        $installer = new Demo_Installer();
        $installer->delete();

        wp_safe_redirect( add_query_arg( 'nsfmarea_demo_status', rawurlencode( 'Contenido demo  eliminado.' ), admin_url( 'tools.php?page=nsfmarea-demo' ) ) );
        exit;
    }
}
