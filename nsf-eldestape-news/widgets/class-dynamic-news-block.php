<?php
namespace NSFELDESTAPE\Widgets;

use NSFELDESTAPE\Widget_Base;
use NSFELDESTAPE\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dynamic_News_Block extends Widget_Base {
    public function get_name() { return 'nsfeldestape_dynamic_news_block'; }
    public function get_title() { return esc_html__( 'Bloque Noticias Dinámico', 'nsfeldestape-widgets' ); }
    public function get_icon() { return 'eicon-posts-grid'; }
    public function is_reload_preview_required() { return true; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfeldestape-widgets' ) ] );
        $this->add_query_controls( 4 );
        $this->add_control( 'section_title', [ 'label' => esc_html__( 'Título de sección', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '' ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada en nota grande', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfeldestape-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'overlay_titles', [ 'label' => esc_html__( 'Título sobre la foto en desktop', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'big_position', [ 'label' => esc_html__( 'Nota grande', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'left', 'options' => [ 'left' => esc_html__( 'Izquierda', 'nsfeldestape-widgets' ), 'right' => esc_html__( 'Derecha', 'nsfeldestape-widgets' ) ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'mobile_content', [ 'label' => esc_html__( 'Ajustes mobile', 'nsfeldestape-widgets' ) ] );
        $this->add_control( 'mobile_visible', [ 'label' => esc_html__( 'Cantidad visible en móvil', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 12 ] );
        $this->add_control( 'mobile_layout', [ 'label' => esc_html__( 'Layout móvil', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'stack', 'options' => [ 'stack' => 'Una columna', 'two' => 'Grande + 2 columnas', 'compact' => 'Lista compacta' ] ] );
        $this->add_control( 'mobile_text_below', [ 'label' => esc_html__( 'En móvil, texto debajo de imagen', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'mobile_hide_excerpt', [ 'label' => esc_html__( 'Ocultar bajada en móvil', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'layout_style', [ 'label' => esc_html__( 'Layout', 'nsfeldestape-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 24, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'big_ratio', [ 'label' => esc_html__( 'Proporción imagen grande', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '16/9', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '3/2' => '3:2', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-main .nsfeldestape-dynamic-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'small_ratio', [ 'label' => esc_html__( 'Proporción imágenes chicas', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '16/9', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-side .nsfeldestape-dynamic-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'main_width', [ 'label' => esc_html__( 'Ancho nota grande (%)', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ '%' ], 'range' => [ '%' => [ 'min' => 45, 'max' => 75 ] ], 'default' => [ 'size' => 66, 'unit' => '%' ], 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-grid' => 'grid-template-columns: minmax(0, {{SIZE}}%) minmax(0, 1fr);' ] ] );
        $this->add_responsive_control( 'border_radius', [ 'label' => esc_html__( 'Radio imágenes', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-img' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'typography_style', [ 'label' => esc_html__( 'Tipografías', 'nsfeldestape-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'section_title_typo', 'selector' => '{{WRAPPER}} .nsfeldestape-dynamic-section-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'main_title_typo', 'selector' => '{{WRAPPER}} .nsfeldestape-dynamic-main .nsfeldestape-dynamic-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'side_title_typo', 'selector' => '{{WRAPPER}} .nsfeldestape-dynamic-side .nsfeldestape-dynamic-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .nsfeldestape-dynamic-excerpt' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'colors_style', [ 'label' => esc_html__( 'Colores', 'nsfeldestape-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Título', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-title, {{WRAPPER}} .nsfeldestape-dynamic-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Bajada', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-excerpt' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'overlay_color', [ 'label' => esc_html__( 'Degradado overlay', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(0,0,0,.62)', 'selectors' => [ '{{WRAPPER}} .nsfeldestape-dynamic-overlay .nsfeldestape-dynamic-img::after' => 'background: linear-gradient(180deg, rgba(0,0,0,0) 25%, {{VALUE}} 100%);' ] ] );
        $this->end_controls_section();
    }

    protected function render_post_card( $post_id, $is_main, $settings, $index = 0 ) {
        $classes = 'nsfeldestape-dynamic-card ' . ( $is_main ? 'nsfeldestape-dynamic-main' : 'nsfeldestape-dynamic-side-card' );
        if ( 'yes' === ( $settings['overlay_titles'] ?? '' ) ) { $classes .= ' nsfeldestape-dynamic-overlay'; }
        ?>
        <article class="<?php echo esc_attr( $classes ); ?>" data-mobile-index="<?php echo esc_attr( $index ); ?>">
            <a class="nsfeldestape-dynamic-img" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                <?php if ( has_post_thumbnail( $post_id ) ) : ?>
                    <?php echo get_the_post_thumbnail( $post_id, $is_main ? 'large' : 'medium_large', [ 'loading' => 'lazy' ] ); ?>
                <?php else : ?>
                    <span class="nsfeldestape-dynamic-placeholder <?php echo esc_attr( Helpers::card_image_class( $post_id ) ); ?>"></span>
                <?php endif; ?>
            </a>
            <div class="nsfeldestape-dynamic-content">
                <<?php echo $is_main ? 'h2' : 'h3'; ?> class="nsfeldestape-dynamic-title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></<?php echo $is_main ? 'h2' : 'h3'; ?>>
                <?php if ( $is_main && 'yes' === ( $settings['show_excerpt'] ?? '' ) ) : ?>
                    <p class="nsfeldestape-dynamic-excerpt"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $post_id ), 26 ) ); ?></p>
                <?php endif; ?>
            </div>
        </article>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $settings['posts_per_page'] = max( 1, absint( $settings['posts_per_page'] ?? 4 ) );
        $mobile_visible = max( 1, absint( $settings['mobile_visible'] ?? 3 ) );
        $q = $this->build_query( $settings );
        if ( ! $q->have_posts() ) { return; }
        $ids = [];
        while ( $q->have_posts() ) { $q->the_post(); $ids[] = get_the_ID(); }
        wp_reset_postdata();
        $ids = array_slice( $ids, 0, $settings['posts_per_page'] );
        if ( empty( $ids ) ) { return; }
        $main_id = array_shift( $ids );
        $layout_mobile = sanitize_key( $settings['mobile_layout'] ?? 'stack' );
        $text_below_mobile = 'yes' === ( $settings['mobile_text_below'] ?? '' ) ? '1' : '0';
        $hide_excerpt_mobile = 'yes' === ( $settings['mobile_hide_excerpt'] ?? '' ) ? '1' : '0';
        ?>
        <div class="nsfeldestape-scope">
            <section class="nsfeldestape-dynamic-news nsfeldestape-dynamic-mobile-<?php echo esc_attr( $layout_mobile ); ?>" data-mobile-visible="<?php echo esc_attr( $mobile_visible ); ?>" data-mobile-text-below="<?php echo esc_attr( $text_below_mobile ); ?>" data-mobile-hide-excerpt="<?php echo esc_attr( $hide_excerpt_mobile ); ?>">
                <?php if ( ! empty( $settings['section_title'] ) ) : ?><h2 class="nsfeldestape-dynamic-section-title"><?php echo esc_html( $settings['section_title'] ); ?></h2><?php endif; ?>
                <div class="nsfeldestape-dynamic-grid <?php echo 'right' === ( $settings['big_position'] ?? 'left' ) ? 'is-reverse' : ''; ?>">
                    <?php $this->render_post_card( $main_id, true, $settings, 1 ); ?>
                    <?php if ( ! empty( $ids ) ) : ?>
                        <div class="nsfeldestape-dynamic-side">
                            <?php $i = 2; foreach ( $ids as $id ) { $this->render_post_card( $id, false, $settings, $i ); $i++; } ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <?php
    }
}
