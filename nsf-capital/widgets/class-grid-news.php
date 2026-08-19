<?php
namespace NSFCAPITAL\Widgets;

use NSFCAPITAL\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Grid_News extends Widget_Base {
    public function get_name() { return 'nsfcapital_grid_news'; }
    public function get_title() { return esc_html__( 'Grilla Noticias', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-posts-grid'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfcapital-widgets' ) ] );
        $this->add_control( 'section_title', [ 'label' => esc_html__( 'Título de sección', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Últimas noticias' ] );
        $this->add_control( 'show_header', [ 'label' => 'Mostrar encabezado', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'show_more', [ 'label' => 'Mostrar “Ver más”', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'condition' => [ 'show_header' => 'yes' ] ] );
        $this->add_control( 'more_text', [ 'label' => 'Texto “Ver más”', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Ver más', 'condition' => [ 'show_more' => 'yes' ] ] );
        $this->add_control( 'more_link_mode', [ 'label' => 'Destino “Ver más”', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'auto_category', 'options' => [ 'auto_category' => 'Categoría seleccionada', 'manual' => 'URL manual', 'posts_page' => 'Página de entradas' ], 'condition' => [ 'show_more' => 'yes' ] ] );
        $this->add_control( 'more_url', [ 'label' => 'URL “Ver más”', 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_more' => 'yes', 'more_link_mode' => 'manual' ] ] );
        $this->add_query_controls( 4 );
        $this->add_control( 'show_author', [ 'label' => 'Mostrar autor', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'show_date', [ 'label' => 'Mostrar fecha', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => '' ] );
        $this->add_control( 'show_image', [ 'label' => 'Mostrar imagen', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'layout_style', [ 'label' => 'Layout', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'columns', [ 'label' => 'Columnas', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4', 'tablet_default' => '3', 'mobile_default' => '2', 'options' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .dual-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ] ] );
        $this->add_responsive_control( 'gap', [ 'label' => 'Separación', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 70 ] ], 'default' => [ 'size' => 24, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .dual-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'image_ratio', [ 'label' => 'Proporción imagen', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '1/1', 'options' => [ '1/1' => 'Cuadrada 1:1', '4/3' => '4:3', '16/9' => '16:9', '3/2' => '3:2', '2/3' => 'Vertical 2:3' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news' => '--nsfcapital-grid-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'card_padding', [ 'label' => 'Padding card', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'card_bg', [ 'label' => 'Fondo card', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card' => 'background: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'card_radius', [ 'label' => 'Radio card', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card' => 'border-radius: {{SIZE}}{{UNIT}}; overflow:hidden;' ] ] );
        $this->add_responsive_control( 'image_radius', [ 'label' => 'Radio imagen', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card > a:first-child, {{WRAPPER}} .nsfcapital-grid-news .card-img-real, {{WRAPPER}} .nsfcapital-grid-news .card-img' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'header_style', [ 'label' => 'Encabezado', 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => [ 'show_header' => 'yes' ] ] );
        $this->add_control( 'header_border_color', [ 'label' => 'Línea', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .section-head' => 'border-bottom-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'header_border_width', [ 'label' => 'Grosor línea', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 8 ] ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .section-head' => 'border-bottom-width: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'title_color', [ 'label' => 'Color título', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .section-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'section_title_typo', 'selector' => '{{WRAPPER}} .nsfcapital-grid-news .section-title' ] );
        $this->add_control( 'more_color', [ 'label' => 'Color ver más', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .section-link' => 'color: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'card_style', [ 'label' => 'Contenido card', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'title_spacing', [ 'label' => 'Espacio imagen/título', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ], 'default' => [ 'size' => 8, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card-title' => 'margin-top: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'post_title_color', [ 'label' => 'Color título nota', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card-title, {{WRAPPER}} .nsfcapital-grid-news .card-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'post_title_hover_color', [ 'label' => 'Color título hover', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card:hover .card-title, {{WRAPPER}} .nsfcapital-grid-news .card:hover .card-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'post_title_typo', 'selector' => '{{WRAPPER}} .nsfcapital-grid-news .card-title' ] );
        $this->add_control( 'meta_color', [ 'label' => 'Color meta', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-grid-news .card-byline' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .nsfcapital-grid-news .card-byline' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $q = $this->build_query( $s );
        $more_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' );
        if ( 'manual' === ( $s['more_link_mode'] ?? '' ) && ! empty( $s['more_url']['url'] ) ) {
            $more_url = $s['more_url']['url'];
        } elseif ( 'auto_category' === ( $s['more_link_mode'] ?? 'auto_category' ) && ! empty( $s['categories'] ) ) {
            $cat_id = absint( is_array( $s['categories'] ) ? reset( $s['categories'] ) : $s['categories'] );
            $cat_link = $cat_id ? get_category_link( $cat_id ) : '';
            if ( $cat_link && ! is_wp_error( $cat_link ) ) { $more_url = $cat_link; }
        }
        ?>
        <div class="nsfcapital-scope nsfcapital-grid-news"><div class="container"><section class="section-block">
            <?php if ( 'yes' === ( $s['show_header'] ?? 'yes' ) ) : ?>
                <header class="section-head"><h2 class="section-title"><?php echo esc_html( $s['section_title'] ); ?></h2><?php if ( 'yes' === ( $s['show_more'] ?? 'yes' ) ) : ?><a href="<?php echo esc_url( $more_url ); ?>" class="section-link"><?php echo esc_html( $s['more_text'] ?: 'Ver más' ); ?></a><?php endif; ?></header>
            <?php endif; ?>
            <section class="dual-grid">
            <?php while ( $q->have_posts() ) : $q->the_post(); $this->render_card( get_the_ID(), [ 'square' => true, 'image' => 'yes' === ( $s['show_image'] ?? 'yes' ), 'show_author' => 'yes' === ( $s['show_author'] ?? 'yes' ), 'show_date' => 'yes' === ( $s['show_date'] ?? '' ) ] ); endwhile; wp_reset_postdata(); ?>
            </section>
        </section></div></div>
        <?php
    }
}
