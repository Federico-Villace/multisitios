<?php
namespace NSFMAREA\Widgets;

use NSFMAREA\Widget_Base;
use NSFMAREA\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Header_News extends Widget_Base {
    public function get_name() { return 'nsfmarea_header_news'; }
    public function get_title() { return esc_html__( 'Header ', 'nsfmarea-widgets' ); }
    public function get_icon() { return 'eicon-header'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfmarea-widgets' ) ] );
        $this->add_control( 'logo_type', [ 'label' => esc_html__( 'Tipo de logo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'text', 'options' => [ 'text' => esc_html__( 'Texto', 'nsfmarea-widgets' ), 'image' => esc_html__( 'Imagen / SVG', 'nsfmarea-widgets' ) ] ] );
        $this->add_control( 'logo_text', [ 'label' => esc_html__( 'Logo texto', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'condition' => [ 'logo_type' => 'text' ] ] );
        $this->add_control( 'logo_image', [ 'label' => esc_html__( 'Logo imagen / SVG', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => [ 'logo_type' => 'image' ] ] );
        $this->add_control( 'logo_slogan', [ 'label' => esc_html__( 'Slogan', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'FIRME CON LA GENTE' ] );
        $this->add_control( 'home_url', [ 'label' => esc_html__( 'URL del logo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::URL ] );
        $this->add_control( 'show_user', [ 'label' => esc_html__( 'Mostrar icono usuario', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'user_url', [ 'label' => esc_html__( 'URL usuario', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_user' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_live', [ 'label' => esc_html__( 'Mostrar En vivo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'live_text', [ 'label' => esc_html__( 'Texto En vivo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'En vivo', 'condition' => [ 'show_live' => 'yes' ] ] );
        $this->add_control( 'live_url', [ 'label' => esc_html__( 'URL En vivo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_live' => 'yes' ] ] );
        $this->add_control( 'show_club', [ 'label' => esc_html__( 'Mostrar Club', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'club_text', [ 'label' => esc_html__( 'Texto Club', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'UNITE\nAL CLUB', 'condition' => [ 'show_club' => 'yes' ] ] );
        $this->add_control( 'club_url', [ 'label' => esc_html__( 'URL Club', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_club' => 'yes' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'drawer_content', [ 'label' => esc_html__( 'Menú canvas', 'nsfmarea-widgets' ) ] );
        $this->add_control( 'drawer_title', [ 'label' => esc_html__( 'Título del canvas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '' ] );
        $this->add_control( 'drawer_menu', [ 'label' => esc_html__( 'Menú de WordPress', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'options' => Helpers::get_menus_options(), 'default' => '' ] );
        $this->add_control( 'fallback_items', [ 'label' => esc_html__( 'Ítems fallback', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'rows' => 5, 'default' => "Argentina\nPolítica\nEconomía\nDeportes\nSociedad\nMundo\nEspectáculos\nTecnología\nNewsletters" ] );
        $this->add_control( 'show_drawer_ad', [ 'label' => esc_html__( 'Mostrar banner superior en canvas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'drawer_ad_text', [ 'label' => esc_html__( 'Texto banner canvas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '320 × 50', 'condition' => [ 'show_drawer_ad' => 'yes' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'search_content', [ 'label' => esc_html__( 'Buscador / Ivory Search', 'nsfmarea-widgets' ) ] );
        $this->add_control( 'search_mode', [
            'label' => esc_html__( 'Acción del botón buscar', 'nsfmarea-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'ivory_modal',
            'options' => [
                'ivory_modal' => esc_html__( 'Abrir modal propio con Ivory Search', 'nsfmarea-widgets' ),
                'trigger_ivory' => esc_html__( 'Disparar icono/popup existente de Ivory Search', 'nsfmarea-widgets' ),
                'drawer' => esc_html__( 'Abrir canvas y enfocar búsqueda', 'nsfmarea-widgets' ),
            ],
        ] );
        $this->add_control( 'ivory_shortcode', [
            'label' => esc_html__( 'Shortcode Ivory Search', 'nsfmarea-widgets' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '[ivory-search id="0" title="Default Search Form"]',
            'description' => esc_html__( 'Pegá acá el shortcode real de Ivory Search. Ej: [ivory-search id="123" title="AJAX Search"]', 'nsfmarea-widgets' ),
            'condition' => [ 'search_mode' => 'ivory_modal' ],
        ] );
        $this->add_control( 'search_placeholder', [ 'label' => esc_html__( 'Placeholder búsqueda canvas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Buscar en todo el sitio' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style', [ 'label' => esc_html__( 'Estilo', 'nsfmarea-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'accent_color', [ 'label' => esc_html__( 'Color rojo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#6b2d8b', 'selectors' => [ '{{WRAPPER}} .nsfmarea-scope' => '--c-red: {{VALUE}};' ] ] );
        $this->add_control( 'header_bg', [ 'label' => esc_html__( 'Fondo header', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .site-header' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'drawer_bg', [ 'label' => esc_html__( 'Fondo canvas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .drawer' => 'background-color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'logo_typography', 'selector' => '{{WRAPPER}} .brand-logo, {{WRAPPER}} .drawer-logo' ] );
        $this->add_responsive_control( 'logo_width', [ 'label' => esc_html__( 'Ancho logo imagen', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 40, 'max' => 420 ], '%' => [ 'min' => 10, 'max' => 100 ] ], 'default' => [ 'size' => 180, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .brand-logo-img' => 'width: {{SIZE}}{{UNIT}};' ] ] );

        $this->add_responsive_control( 'header_height', [ 'label' => esc_html__( 'Alto header', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 42, 'max' => 140 ] ], 'selectors' => [ '{{WRAPPER}} .site-header-inner' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'logo_text_size', [ 'label' => esc_html__( 'Tamaño logo texto', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', 'rem' ], 'range' => [ 'px' => [ 'min' => 18, 'max' => 96 ], 'rem' => [ 'min' => 1, 'max' => 6, 'step' => .1 ] ], 'selectors' => [ '{{WRAPPER}} .brand-logo' => 'font-size: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'logo_image_max_height', [ 'label' => esc_html__( 'Alto máximo logo imagen', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 20, 'max' => 120 ] ], 'selectors' => [ '{{WRAPPER}} .brand-logo-img' => 'max-height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'icon_box_size', [ 'label' => esc_html__( 'Tamaño botones header', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 28, 'max' => 72 ] ], 'selectors' => [ '{{WRAPPER}} .header-icon-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'icon_size', [ 'label' => esc_html__( 'Tamaño íconos header', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 12, 'max' => 42 ] ], 'selectors' => [ '{{WRAPPER}} .header-icon-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'header_left_width', [ 'label' => esc_html__( 'Ancho columna izquierda', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 40, 'max' => 320 ], '%' => [ 'min' => 10, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .site-header-inner' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr auto;' ] ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'drawer_typography', 'selector' => '{{WRAPPER}} .drawer-nav a' ] );
        $this->add_responsive_control( 'header_padding', [ 'label' => esc_html__( 'Padding header', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'selectors' => [ '{{WRAPPER}} .site-header-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    private function render_drawer_menu( $s ) {
        if ( ! empty( $s['drawer_menu'] ) ) {
            wp_nav_menu([
                'menu'           => absint( $s['drawer_menu'] ),
                'container'      => false,
                'menu_class'     => '',
                'fallback_cb'    => false,
                'depth'          => 2,
                'items_wrap'     => '<ul>%3$s</ul>',
            ]);
            return;
        }

        $items = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) ( $s['fallback_items'] ?? '' ) ) ) );
        echo '<ul>';
        foreach ( $items as $item ) {
            echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( $item ) . '</a></li>';
        }
        echo '</ul>';
    }

    private function render_logo( $s, $url, $class = 'brand' ) {
        $logo_type = $s['logo_type'] ?? 'text';
        $has_image = 'image' === $logo_type && ! empty( $s['logo_image']['url'] );
        echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">';
        if ( $has_image ) {
            echo '<img class="brand-logo-img" src="' . esc_url( $s['logo_image']['url'] ) . '" alt="' . esc_attr( $s['logo_text'] ?? '' ) . '">';
        } else {
            echo '<span class="brand-logo">' . esc_html( $s['logo_text'] ?: '' ) . '</span>';
        }
        if ( ! empty( $s['logo_slogan'] ) ) {
            echo '<span class="brand-slogan">' . esc_html( $s['logo_slogan'] ) . '</span>';
        }
        echo '</a>';
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $url = ! empty( $s['home_url']['url'] ) ? $s['home_url']['url'] : home_url( '/' );
        $uid = 'nsfmarea-header-' . $this->get_id();
        $user_tag = ! empty( $s['user_url']['url'] ) ? 'a' : 'button';
        $user_attrs = ! empty( $s['user_url']['url'] ) ? ' href="' . esc_url( $s['user_url']['url'] ) . '"' . ( ! empty( $s['user_url']['is_external'] ) ? ' target="_blank" rel="noopener"' : '' ) : ' type="button"';
        $search_mode = sanitize_key( $s['search_mode'] ?? 'ivory_modal' );
        $live_url = ! empty( $s['live_url']['url'] ) ? $s['live_url']['url'] : home_url( '/' );
        $club_url = ! empty( $s['club_url']['url'] ) ? $s['club_url']['url'] : home_url( '/' );
        $club_text = nl2br( esc_html( $s['club_text'] ?? "UNITE\nAL CLUB" ) );
        ?>
        <div class="nsfmarea-scope nsfmarea-header-widget" id="<?php echo esc_attr( $uid ); ?>" data-search-mode="<?php echo esc_attr( $search_mode ); ?>">
            <div class="drawer-backdrop" data-nsfmarea-backdrop aria-hidden="true"></div>
            <aside class="drawer" data-nsfmarea-drawer aria-label="<?php echo esc_attr__( 'Menú principal', 'nsfmarea-widgets' ); ?>" aria-hidden="true">
                <header class="drawer-top">
                    <button class="drawer-close" type="button" data-nsfmarea-close><?php echo esc_html__( 'Cerrar', 'nsfmarea-widgets' ); ?></button>
                    <a class="drawer-logo" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $s['drawer_title'] ?: ( $s['logo_text'] ?: '' ) ); ?></a>
                    <span aria-hidden="true" style="width:60px"></span>
                </header>
                <nav class="drawer-list" aria-label="<?php echo esc_attr__( 'Categorías', 'nsfmarea-widgets' ); ?>">
                    <?php $this->render_drawer_menu( $s ); ?>
                </nav>
                <?php if ( 'yes' === ( $s['show_live'] ?? 'yes' ) ) : ?>
                    <a href="<?php echo esc_url( $live_url ); ?>" class="drawer-live-cta"><span class="dot" aria-hidden="true"></span><?php echo esc_html( $s['live_text'] ?? 'En vivo' ); ?> · EL DESTAPE NEWS HD</a>
                <?php endif; ?>
                <div class="drawer-search">
                    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="drawer-search-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.5 14h-.8l-.3-.3c1-1.1 1.6-2.6 1.6-4.2C16 5.9 13.1 3 9.5 3S3 5.9 3 9.5 5.9 16 9.5 16c1.6 0 3.1-.6 4.2-1.6l.3.3v.8l5 5 1.5-1.5-5-5zm-6 0C7 14 5 12 5 9.5S7 5 9.5 5 14 7 14 9.5 12 14 9.5 14z"/></svg>
                        <input type="search" name="s" placeholder="<?php echo esc_attr( $s['search_placeholder'] ?? 'Buscar en el sitio…' ); ?>" aria-label="<?php echo esc_attr__( 'Buscar', 'nsfmarea-widgets' ); ?>">
                    </form>
                </div>
            </aside>

            <?php if ( 'ivory_modal' === $search_mode ) : ?>
                <div class="nsfmarea-search-modal" data-nsfmarea-search-modal aria-hidden="true">
                    <div class="nsfmarea-search-modal__backdrop" data-nsfmarea-search-close></div>
                    <div class="nsfmarea-search-modal__panel" role="dialog" aria-modal="true" aria-label="<?php echo esc_attr__( 'Buscar', 'nsfmarea-widgets' ); ?>">
                        <button type="button" class="nsfmarea-search-modal__close" data-nsfmarea-search-close aria-label="<?php echo esc_attr__( 'Cerrar buscador', 'nsfmarea-widgets' ); ?>">×</button>
                        <?php
                        $shortcode = trim( (string) ( $s['ivory_shortcode'] ?? '' ) );
                        if ( $shortcode ) { echo do_shortcode( wp_kses_post( $shortcode ) ); } else { get_search_form(); }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <header class="site-header">
                <div class="container">
                    <div class="site-header-inner">
                        <div class="header-icons">
                            <button class="header-icon-btn is-burger" type="button" data-nsfmarea-open aria-label="<?php echo esc_attr__( 'Abrir menú', 'nsfmarea-widgets' ); ?>" aria-expanded="false"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="6" width="18" height="2" rx="1"/><rect x="3" y="11" width="18" height="2" rx="1"/><rect x="3" y="16" width="18" height="2" rx="1"/></svg></button>
                            <button class="header-icon-btn" type="button" data-nsfmarea-search-open aria-label="<?php echo esc_attr__( 'Buscar', 'nsfmarea-widgets' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.5 14h-.8l-.3-.3c1-1.1 1.6-2.6 1.6-4.2C16 5.9 13.1 3 9.5 3S3 5.9 3 9.5 5.9 16 9.5 16c1.6 0 3.1-.6 4.2-1.6l.3.3v.8l5 5 1.5-1.5-5-5zm-6 0C7 14 5 12 5 9.5S7 5 9.5 5 14 7 14 9.5 12 14 9.5 14z"/></svg></button>
                            <button class="header-icon-btn" type="button" aria-label="<?php echo esc_attr__( 'Notificaciones', 'nsfmarea-widgets' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg></button>
                        </div>
                        <?php $this->render_logo( $s, $url, 'brand' ); ?>
                        <div class="header-right">
                            <?php if ( 'yes' === ( $s['show_date'] ?? 'yes' ) ) : ?><span class="header-date"><?php echo esc_html( wp_date( 'l, j \d\e F \d\e Y' ) ); ?></span><?php endif; ?>
                            <?php if ( 'yes' === ( $s['show_live'] ?? 'yes' ) ) : ?><a href="<?php echo esc_url( $live_url ); ?>" class="live-pill"><span class="dot" aria-hidden="true"></span><?php echo esc_html( $s['live_text'] ?: 'En vivo' ); ?></a><?php endif; ?>
                            <?php if ( 'yes' === ( $s['show_club'] ?? 'yes' ) ) : ?><a href="<?php echo esc_url( $club_url ); ?>" class="club-pill"><span class="club-badge">club</span><span class="club-text"><?php echo $club_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></a><?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>
        </div>
        <?php
    }
}