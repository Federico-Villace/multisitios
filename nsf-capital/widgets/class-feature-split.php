<?php
namespace NSFCAPITAL\Widgets;

use NSFCAPITAL\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Feature_Split extends Widget_Base {
    public function get_name() { return 'nsfcapital_feature_split'; }
    public function get_title() { return esc_html__( 'Destacado imagen + texto', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-image-rollover'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => 'Contenido' ] );
        $this->add_control( 'source', [ 'label' => 'Fuente', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'manual', 'options' => [ 'manual' => 'Manual', 'post' => 'Último post' ] ] );
        $this->add_query_controls( 1 );
        $this->add_control( 'image', [ 'label' => 'Imagen manual', 'type' => \Elementor\Controls_Manager::MEDIA, 'condition' => [ 'source' => 'manual' ] ] );
        $this->add_control( 'title', [ 'label' => 'Título', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Armá tu lista de 26 convocados a la selección argentina para el Mundial 2026', 'condition' => [ 'source' => 'manual' ] ] );
        $this->add_control( 'text', [ 'label' => 'Texto', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => ' lanzó un simulador interactivo para que los hinchas elijan su nómina ideal antes del 30 de mayo. Jugá, compartilo y competí con tus amigos', 'condition' => [ 'source' => 'manual' ] ] );
        $this->add_control( 'url', [ 'label' => 'Enlace', 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'source' => 'manual' ] ] );
        $this->add_control( 'reverse_layout', [ 'label' => 'Imagen a la derecha', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => '' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_layout', [ 'label' => 'Layout', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'image_width', [ 'label' => 'Ancho imagen %', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ '%' => [ 'min' => 30, 'max' => 75 ] ], 'default' => [ 'size' => 60, 'unit' => '%' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-media' => 'flex-basis: {{SIZE}}%;' ] ] );
        $this->add_responsive_control( 'min_height', [ 'label' => 'Alto mínimo imagen', 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', 'vh' ], 'range' => [ 'px' => [ 'min' => 160, 'max' => 760 ], 'vh' => [ 'min' => 20, 'max' => 90 ] ], 'default' => [ 'size' => 310, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-media img, {{WRAPPER}} .nsfcapital-feature-media .card-img' => 'min-height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'copy_padding', [ 'label' => 'Padding texto', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-copy' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'text_align', [ 'label' => 'Alineación del texto', 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => [ 'left' => [ 'title' => 'Izquierda', 'icon' => 'eicon-text-align-left' ], 'center' => [ 'title' => 'Centro', 'icon' => 'eicon-text-align-center' ], 'right' => [ 'title' => 'Derecha', 'icon' => 'eicon-text-align-right' ] ], 'default' => 'center', 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-copy' => 'text-align: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'vertical_align', [ 'label' => 'Alineación vertical', 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => [ 'flex-start' => [ 'title' => 'Arriba', 'icon' => 'eicon-v-align-top' ], 'center' => [ 'title' => 'Centro', 'icon' => 'eicon-v-align-middle' ], 'flex-end' => [ 'title' => 'Abajo', 'icon' => 'eicon-v-align-bottom' ] ], 'default' => 'center', 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-copy' => 'justify-content: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'gap', [ 'label' => 'Separación', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-split' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_colors', [ 'label' => 'Colores y bordes', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'bg', [ 'label' => 'Fondo texto', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#f4f4f4', 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-copy' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'border_color', [ 'label' => 'Líneas superior/inferior', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#9f9f9f', 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-split' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'border_width', [ 'label' => 'Grosor líneas', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 8 ] ], 'default' => [ 'size' => 2, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-split' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'radius', [ 'label' => 'Radio de bordes', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-split, {{WRAPPER}} .nsfcapital-feature-media, {{WRAPPER}} .nsfcapital-feature-copy, {{WRAPPER}} .nsfcapital-feature-media img' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'title_color', [ 'label' => 'Color título', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-title' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'text_color', [ 'label' => 'Color texto', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-feature-text' => 'color: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_typo', [ 'label' => 'Tipografías', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .nsfcapital-feature-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'text_typo', 'selector' => '{{WRAPPER}} .nsfcapital-feature-text' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $title = $s['title'] ?? '';
        $text = $s['text'] ?? '';
        $url = ! empty( $s['url']['url'] ) ? $s['url']['url'] : '#';
        $img_html = '';

        if ( 'post' === ( $s['source'] ?? 'manual' ) ) {
            $q = $this->build_query( $s );
            if ( $q->have_posts() ) { $q->the_post(); $title = get_the_title(); $text = get_the_excerpt(); $url = get_permalink(); if ( has_post_thumbnail() ) { $img_html = get_the_post_thumbnail( get_the_ID(), 'large' ); } wp_reset_postdata(); }
        } elseif ( ! empty( $s['image']['url'] ) ) {
            $img_html = '<img src="' . esc_url( $s['image']['url'] ) . '" alt="">';
        }
        if ( ! $img_html ) { $img_html = '<span class="card-img s5"></span>'; }
        $classes = 'nsfcapital-feature-split';
        if ( ! empty( $s['reverse_layout'] ) && 'yes' === $s['reverse_layout'] ) { $classes .= ' is-reversed'; }
        ?>
        <div class="nsfcapital-scope"><a class="<?php echo esc_attr( $classes ); ?>" href="<?php echo esc_url( $url ); ?>">
            <div class="nsfcapital-feature-media"><?php echo $img_html; ?></div>
            <div class="nsfcapital-feature-copy"><h2 class="nsfcapital-feature-title"><?php echo esc_html( $title ); ?></h2><?php if ( $text ) : ?><p class="nsfcapital-feature-text"><?php echo esc_html( $text ); ?></p><?php endif; ?></div>
        </a></div>
        <?php
    }
}
