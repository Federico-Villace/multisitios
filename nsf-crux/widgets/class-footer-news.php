<?php
namespace NSFCRUX\Widgets;

use NSFCRUX\Widget_Base;
use NSFCRUX\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Footer_News extends Widget_Base {
    public function get_name() { return 'nsfcrux_footer_news'; }
    public function get_title() { return esc_html__( 'Footer ', 'nsfcrux-widgets' ); }
    public function get_icon() { return 'eicon-footer'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_brand', [ 'label' => esc_html__( 'Marca', 'nsfcrux-widgets' ) ] );
        $this->add_control( 'logo_type', [
            'label' => esc_html__( 'Tipo de logo', 'nsfcrux-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [ 'text' => 'Texto', 'image' => 'Imagen' ],
        ] );
        $this->add_control( 'logo_text', [ 'label' => esc_html__( 'Logo texto', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'condition' => [ 'logo_type' => 'text' ] ] );
        $this->add_control( 'logo_image', [ 'label' => esc_html__( 'Logo imagen', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => [ 'logo_type' => 'image' ] ] );
        $this->add_control( 'logo_url', [ 'label' => esc_html__( 'Enlace del logo', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => home_url( '/' ) ] );
        $this->add_control( 'description', [ 'label' => esc_html__( 'Descripción', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Periodismo independiente. Información, análisis y cobertura en tiempo real de la actualidad nacional e internacional.' ] );
        $this->add_control( 'show_socials', [ 'label' => esc_html__( 'Habilitar redes sociales', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'show_brand_socials', [
            'label' => esc_html__( 'Mostrar redes debajo del logo', 'nsfcrux-widgets' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => '',
            'description' => esc_html__( 'Activá esto sólo si querés las redes en la columna de marca. Si usás una columna con fuente Redes sociales, dejalo apagado para evitar duplicados.', 'nsfcrux-widgets' ),
            'condition' => [ 'show_socials' => 'yes' ],
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'content_socials', [ 'label' => esc_html__( 'Redes sociales', 'nsfcrux-widgets' ), 'condition' => [ 'show_socials' => 'yes' ] ] );
        $social = new \Elementor\Repeater();
        $social->add_control( 'label', [ 'label' => 'Etiqueta', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Facebook' ] );
        $social->add_control( 'icon_text', [ 'label' => 'Texto / icono simple', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'f', 'description' => 'Usá f, x, ig, yt, in, tg, etc.' ] );
        $social->add_control( 'url', [ 'label' => 'URL', 'type' => \Elementor\Controls_Manager::URL ] );
        $this->add_control( 'social_items', [
            'label' => 'Redes',
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $social->get_controls(),
            'default' => [
                [ 'label' => 'Facebook', 'icon_text' => 'f' ],
                [ 'label' => 'X', 'icon_text' => '𝕏' ],
                [ 'label' => 'Instagram', 'icon_text' => 'ig' ],
                [ 'label' => 'YouTube', 'icon_text' => '▶' ],
                [ 'label' => 'Telegram', 'icon_text' => '↗' ],
                [ 'label' => 'LinkedIn', 'icon_text' => 'in' ],
            ],
            'title_field' => '{{{ label }}}',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'content_columns', [ 'label' => esc_html__( 'Columnas', 'nsfcrux-widgets' ) ] );
        $col = new \Elementor\Repeater();
        $col->add_control( 'heading', [ 'label' => 'Título columna', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Secciones' ] );
        $col->add_control( 'source', [
            'label' => 'Fuente',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'manual',
            'options' => [
                'manual' => 'Manual',
                'menu'   => 'Menú de WordPress',
                'cats'   => 'Categorías de posts',
                'text'   => 'Texto libre',
                'socials' => 'Redes sociales',
            ],
        ] );
        $col->add_control( 'menu', [ 'label' => 'Menú', 'type' => \Elementor\Controls_Manager::SELECT, 'options' => Helpers::get_menus_options(), 'condition' => [ 'source' => 'menu' ] ] );
        $col->add_control( 'cats_limit', [ 'label' => 'Cantidad de categorías', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 8, 'min' => 1, 'max' => 30, 'condition' => [ 'source' => 'cats' ] ] );
        $col->add_control( 'manual_items', [ 'label' => 'Links manuales', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'rows' => 8, 'default' => "Argentina|#\nDeportes|#\nEconomía|#\nSociedad|#", 'description' => 'Un item por línea: Texto|URL', 'condition' => [ 'source' => 'manual' ] ] );
        $col->add_control( 'text_content', [ 'label' => 'Texto', 'type' => \Elementor\Controls_Manager::WYSIWYG, 'condition' => [ 'source' => 'text' ] ] );
        $col->add_control( 'social_note', [ 'type' => \Elementor\Controls_Manager::RAW_HTML, 'raw' => 'Usa las redes cargadas en la sección Redes sociales.', 'condition' => [ 'source' => 'socials' ] ] );
        $this->add_control( 'columns', [
            'label' => 'Columnas del footer',
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $col->get_controls(),
            'default' => [
                [ 'heading' => 'Secciones', 'source' => 'manual', 'manual_items' => "América|#\nColombia|#\nEspaña|#\nEstados Unidos|#\nMéxico|#\nPerú|#\nCentroamérica|#\nÚltimas Noticias|#\nRSS|#" ],
                [ 'heading' => 'Contáctenos', 'source' => 'manual', 'manual_items' => "Redacción|#\nEmpleo|#" ],
                [ 'heading' => 'Contacto comercial', 'source' => 'manual', 'manual_items' => "Argentina|#\nColombia|#\nEspaña|#\nMéxico|#\nPerú|#\nMedia Kit|#" ],
                [ 'heading' => 'Legales', 'source' => 'manual', 'manual_items' => "Términos y Condiciones|#\nPolítica de Privacidad|#" ],
            ],
            'title_field' => '{{{ heading }}}',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'content_bottom', [ 'label' => esc_html__( 'Barra inferior', 'nsfcrux-widgets' ) ] );
        $this->add_control( 'show_bottom', [ 'label' => 'Mostrar barra inferior', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'bottom_text', [ 'label' => 'Texto legal', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Todos los derechos reservados © {year} ', 'description' => 'Podés usar {year}.' ] );
        $this->add_control( 'bottom_links', [ 'label' => 'Links inferiores', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'rows' => 4, 'default' => "Privacidad|#\nTérminos|#\nEditorial|#", 'description' => 'Un item por línea: Texto|URL' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_layout', [ 'label' => 'Layout', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'container_width', [ 'label' => 'Ancho máximo', 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 720, 'max' => 1600 ], '%' => [ 'min' => 50, 'max' => 100 ] ], 'default' => [ 'size' => 1200, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .container' => 'max-width: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'footer_padding', [ 'label' => 'Padding footer', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'default' => [ 'top' => 48, 'right' => 20, 'bottom' => 42, 'left' => 20, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'columns_count', [ 'label' => 'Columnas desktop', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4', 'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-cols' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ] ] );
        $this->add_responsive_control( 'columns_gap', [ 'label' => 'Separación columnas', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 120 ] ], 'default' => [ 'size' => 52, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-cols' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'brand_width', [ 'label' => 'Ancho bloque marca', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 160, 'max' => 520 ] ], 'default' => [ 'size' => 240, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-brand' => 'flex-basis: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'footer_align', [ 'label' => 'Alineación general', 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => [ 'left' => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left' ], 'center' => [ 'title' => 'Centro', 'icon' => 'eicon-text-align-center' ], 'right' => [ 'title' => 'Derecha', 'icon' => 'eicon-text-align-right' ] ], 'default' => 'center', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro' => 'text-align: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_colors', [ 'label' => 'Colores', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'footer_bg', [ 'label' => 'Fondo', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#363636', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'footer_border_color', [ 'label' => 'Línea superior / divisores', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#777777', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro, {{WRAPPER}} .nsfcrux-footer-pro .footer-bottom' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'top_border_width', [ 'label' => 'Grosor línea superior', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 12 ] ], 'default' => [ 'size' => 0, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro' => 'border-top-width: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'logo_color', [ 'label' => 'Color logo texto', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .brand-logo' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'heading_color', [ 'label' => 'Color títulos columnas', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-col h5' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'link_color', [ 'label' => 'Color links', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#eeeeee', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-col a, {{WRAPPER}} .nsfcrux-footer-pro .footer-bottom a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'link_hover_color', [ 'label' => 'Color links hover', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#00d4ff', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-col a:hover, {{WRAPPER}} .nsfcrux-footer-pro .footer-bottom a:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'text_color', [ 'label' => 'Color textos', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#dddddd', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-brand p, {{WRAPPER}} .nsfcrux-footer-pro .footer-text, {{WRAPPER}} .nsfcrux-footer-pro .footer-bottom p' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'social_color', [ 'label' => 'Color iconos redes', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-socials a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'social_hover_bg', [ 'label' => 'Fondo redes hover', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#00d4ff', 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-socials a:hover' => 'background: {{VALUE}}; border-color: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_typo', [ 'label' => 'Tipografías', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'logo_typo', 'label' => 'Logo', 'selector' => '{{WRAPPER}} .nsfcrux-footer-pro .brand-logo' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'label' => 'Descripción', 'selector' => '{{WRAPPER}} .nsfcrux-footer-pro .footer-brand p' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'head_typo', 'label' => 'Títulos columnas', 'selector' => '{{WRAPPER}} .nsfcrux-footer-pro .footer-col h5' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'links_typo', 'label' => 'Links', 'selector' => '{{WRAPPER}} .nsfcrux-footer-pro .footer-col a' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'bottom_typo', 'label' => 'Barra inferior', 'selector' => '{{WRAPPER}} .nsfcrux-footer-pro .footer-bottom' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_socials', [ 'label' => 'Redes', 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => [ 'show_socials' => 'yes' ] ] );
        $this->add_responsive_control( 'social_size', [ 'label' => 'Tamaño botón', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 20, 'max' => 80 ] ], 'default' => [ 'size' => 34, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-socials a' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'social_font_size', [ 'label' => 'Tamaño icono/texto', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 8, 'max' => 32 ] ], 'default' => [ 'size' => 15, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-socials a' => 'font-size: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'social_gap', [ 'label' => 'Separación redes', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'default' => [ 'size' => 10, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-socials' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'social_radius', [ 'label' => 'Radio redes', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ], 'default' => [ 'size' => 50, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-footer-pro .footer-socials a' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    private function render_manual_links( $raw ) {
        $lines = array_filter( array_map( 'trim', explode( "\n", (string) $raw ) ) );
        echo '<ul>';
        foreach ( $lines as $line ) {
            $parts = array_map( 'trim', explode( '|', $line, 2 ) );
            $text = $parts[0] ?? '';
            $url  = $parts[1] ?? '#';
            if ( '' === $text ) { continue; }
            echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a></li>';
        }
        echo '</ul>';
    }

    private function render_menu_links( $menu_id ) {
        if ( empty( $menu_id ) ) { echo '<ul></ul>'; return; }
        $items = wp_get_nav_menu_items( absint( $menu_id ) );
        echo '<ul>';
        if ( $items ) {
            foreach ( $items as $item ) {
                echo '<li><a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a></li>';
            }
        }
        echo '</ul>';
    }

    private function render_social_links( $items ) {
        if ( empty( $items ) || ! is_array( $items ) ) { return; }
        echo '<div class="footer-socials footer-socials-inline" aria-label="' . esc_attr__( 'Redes sociales', 'nsfcrux-widgets' ) . '">';
        foreach ( $items as $social ) {
            $url = ! empty( $social['url']['url'] ) ? $social['url']['url'] : '#';
            $target = ! empty( $social['url']['is_external'] ) ? ' target="_blank"' : '';
            $nofollow = ! empty( $social['url']['nofollow'] ) ? ' rel="nofollow"' : '';
            echo '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $social['label'] ?? '' ) . '"' . $target . $nofollow . '>' . esc_html( $social['icon_text'] ?? '' ) . '</a>';
        }
        echo '</div>';
    }

    private function render_category_links( $limit ) {
        $cats = get_categories( [ 'number' => absint( $limit ), 'hide_empty' => false ] );
        echo '<ul>';
        foreach ( $cats as $cat ) {
            echo '<li><a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></li>';
        }
        echo '</ul>';
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $logo_url = ! empty( $s['logo_url']['url'] ) ? $s['logo_url']['url'] : home_url( '/' );
        $bottom_text = str_replace( '{year}', gmdate( 'Y' ), $s['bottom_text'] ?? '' );
        ?>
        <div class="nsfcrux-scope">
            <footer class="footer nsfcrux-footer-pro">
                <div class="container">
                    <div class="nsfcrux-footer-main">
                        <div class="footer-brand">
                            <a href="<?php echo esc_url( $logo_url ); ?>" class="brand-logo">
                                <?php if ( 'image' === ( $s['logo_type'] ?? 'text' ) && ! empty( $s['logo_image']['url'] ) ) : ?>
                                    <img src="<?php echo esc_url( $s['logo_image']['url'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                                <?php else : ?>
                                    <?php echo esc_html( $s['logo_text'] ?? '' ); ?>
                                <?php endif; ?>
                            </a>
                            <?php if ( ! empty( $s['description'] ) ) : ?><p><?php echo wp_kses_post( $s['description'] ); ?></p><?php endif; ?>
                            <?php if ( 'yes' === ( $s['show_socials'] ?? '' ) && 'yes' === ( $s['show_brand_socials'] ?? '' ) && ! empty( $s['social_items'] ) ) : ?>
                                <div class="footer-socials" aria-label="<?php echo esc_attr__( 'Redes sociales', 'nsfcrux-widgets' ); ?>">
                                    <?php foreach ( $s['social_items'] as $social ) :
                                        $url = ! empty( $social['url']['url'] ) ? $social['url']['url'] : '#';
                                        $target = ! empty( $social['url']['is_external'] ) ? ' target="_blank"' : '';
                                        $nofollow = ! empty( $social['url']['nofollow'] ) ? ' rel="nofollow"' : '';
                                    ?>
                                        <a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $social['label'] ?? '' ); ?>"<?php echo $target . $nofollow; ?>><?php echo esc_html( $social['icon_text'] ?? '' ); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="footer-cols">
                            <?php foreach ( (array) ( $s['columns'] ?? [] ) as $col ) : ?>
                                <div class="footer-col">
                                    <?php if ( ! empty( $col['heading'] ) ) : ?><h5><?php echo esc_html( $col['heading'] ); ?></h5><?php endif; ?>
                                    <?php
                                    $source = $col['source'] ?? 'manual';
                                    if ( 'menu' === $source ) {
                                        $this->render_menu_links( $col['menu'] ?? 0 );
                                    } elseif ( 'cats' === $source ) {
                                        $this->render_category_links( $col['cats_limit'] ?? 8 );
                                    } elseif ( 'text' === $source ) {
                                        echo '<div class="footer-text">' . wp_kses_post( $col['text_content'] ?? '' ) . '</div>';
                                    } elseif ( 'socials' === $source ) {
                                        $this->render_social_links( $s['social_items'] ?? [] );
                                    } else {
                                        $this->render_manual_links( $col['manual_items'] ?? '' );
                                    }
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ( 'yes' === ( $s['show_bottom'] ?? '' ) ) : ?>
                        <div class="footer-bottom">
                            <?php if ( $bottom_text ) : ?><p><?php echo esc_html( $bottom_text ); ?></p><?php endif; ?>
                            <?php $this->render_manual_links( $s['bottom_links'] ?? '' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </footer>
        </div>
        <?php
    }
}
