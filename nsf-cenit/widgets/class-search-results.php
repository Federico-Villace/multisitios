<?php
namespace NSFCENIT\Widgets;

use NSFCENIT\Widget_Base;
use NSFCENIT\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Search_Results extends Widget_Base {
    public function get_name() { return 'nsfcenit_search_results'; }
    public function get_title() { return esc_html__( 'Resultados de Búsqueda', 'nsfcenit-widgets' ); }
    public function get_icon() { return 'eicon-search-results'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Búsqueda', 'nsfcenit-widgets' ) ] );
        $this->add_control( 'use_search_query', [ 'label' => esc_html__( 'Usar búsqueda actual', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'preview_term', [ 'label' => esc_html__( 'Término para previsualizar', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'description' => esc_html__( 'Sólo se usa en Elementor o si no hay búsqueda activa.', 'nsfcenit-widgets' ) ] );
        $this->add_control( 'posts_per_page', [ 'label' => esc_html__( 'Cantidad de resultados', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10, 'min' => 1, 'max' => 50 ] );
        $this->add_control( 'show_search_title', [ 'label' => esc_html__( 'Mostrar título de búsqueda', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'title_prefix', [ 'label' => esc_html__( 'Prefijo del título', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Resultados de la búsqueda de:', 'nsfcenit-widgets' ), 'condition' => [ 'show_search_title' => 'yes' ] ] );
        $this->add_control( 'layout', [ 'label' => esc_html__( 'Layout', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'list', 'options' => [ 'list' => esc_html__( 'Lista imagen derecha', 'nsfcenit-widgets' ), 'grid' => esc_html__( 'Grilla', 'nsfcenit-widgets' ), 'compact' => esc_html__( 'Compacto sin imagen', 'nsfcenit-widgets' ) ] ] );
        $this->add_control( 'show_category', [ 'label' => esc_html__( 'Mostrar categoría', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfcenit-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'empty_text', [ 'label' => esc_html__( 'Texto sin resultados', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => esc_html__( 'No se encontraron resultados.', 'nsfcenit-widgets' ) ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_header', [ 'label' => esc_html__( 'Título', 'nsfcenit-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Color', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .nsfcenit-search-title' ] );
        $this->add_responsive_control( 'title_margin', [ 'label' => esc_html__( 'Margen título', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_items', [ 'label' => esc_html__( 'Resultados', 'nsfcenit-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 20, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-list' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'grid_columns', [ 'label' => esc_html__( 'Columnas grilla', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '3', 'tablet_default' => '2', 'mobile_default' => '1', 'options' => [ '1'=>'1','2'=>'2','3'=>'3','4'=>'4' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-list.layout-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ], 'condition' => [ 'layout' => 'grid' ] ] );
        $this->add_responsive_control( 'image_width', [ 'label' => esc_html__( 'Ancho imagen lista', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 80, 'max' => 520 ], '%' => [ 'min' => 20, 'max' => 60 ] ], 'default' => [ 'size' => 220, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-item.layout-list' => 'grid-template-columns: minmax(0, 1fr) {{SIZE}}{{UNIT}};' ], 'condition' => [ 'layout' => 'list' ] ] );
        $this->add_responsive_control( 'image_ratio', [ 'label' => esc_html__( 'Proporción imagen', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '1/1', 'options' => [ '1/1'=>'1:1','4/3'=>'4:3','16/9'=>'16:9','3/2'=>'3:2' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_control( 'accent_color', [ 'label' => esc_html__( 'Color categoría', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#e30613', 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-kicker' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'post_title_color', [ 'label' => esc_html__( 'Color título', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-item-title, {{WRAPPER}} .nsfcenit-search-item-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'post_title_typo', 'selector' => '{{WRAPPER}} .nsfcenit-search-item-title' ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Color bajada', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-excerpt' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .nsfcenit-search-excerpt' ] );
        $this->add_control( 'meta_color', [ 'label' => esc_html__( 'Color meta', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-meta' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .nsfcenit-search-meta' ] );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [ 'name' => 'item_border', 'selector' => '{{WRAPPER}} .nsfcenit-search-item' ] );
        $this->add_responsive_control( 'item_padding', [ 'label' => esc_html__( 'Padding item', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-search-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    private function render_item( $post_id, $settings ) {
        $layout = $settings['layout'] ?? 'list';
        $cats = get_the_category( $post_id );
        $meta = [];
        if ( 'yes' === ( $settings['show_author'] ?? '' ) ) {
            $byline = Helpers::author_byline( $post_id, $settings['author_prefix'] ?? '' );
            if ( $byline ) { $meta[] = $byline; }
        }
        if ( 'yes' === ( $settings['show_date'] ?? '' ) ) { $meta[] = get_the_date( '', $post_id ); }
        ?>
        <article class="nsfcenit-search-item layout-<?php echo esc_attr( $layout ); ?>">
            <div class="nsfcenit-search-content">
                <?php if ( 'yes' === ( $settings['show_category'] ?? '' ) && $cats ) : ?><span class="nsfcenit-search-kicker"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                <h3 class="nsfcenit-search-item-title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
                <?php if ( 'yes' === ( $settings['show_excerpt'] ?? '' ) ) : ?><p class="nsfcenit-search-excerpt"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $post_id ), 28 ) ); ?></p><?php endif; ?>
                <?php if ( $meta ) : ?><p class="nsfcenit-search-meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p><?php endif; ?>
            </div>
            <?php if ( 'compact' !== $layout ) : ?>
                <a class="nsfcenit-search-img" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php if ( has_post_thumbnail( $post_id ) ) { echo get_the_post_thumbnail( $post_id, 'medium_large', [ 'loading' => 'lazy' ] ); } else { echo '<span class="nsfcenit-dynamic-placeholder ' . esc_attr( Helpers::card_image_class( $post_id ) ) . '"></span>'; } ?>
                </a>
            <?php endif; ?>
        </article>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $term = '';
        if ( 'yes' === ( $s['use_search_query'] ?? 'yes' ) && is_search() ) {
            $term = get_search_query();
        }
        if ( '' === $term ) { $term = sanitize_text_field( $s['preview_term'] ?? '' ); }
        $paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => absint( $s['posts_per_page'] ?? 10 ),
            'paged' => $paged,
            's' => $term,
            'ignore_sticky_posts' => true,
        ];
        $q = ( is_search() && 'yes' === ( $s['use_search_query'] ?? 'yes' ) ) ? $GLOBALS['wp_query'] : new \WP_Query( $args );
        ?>
        <div class="nsfcenit-scope nsfcenit-search-results"><div class="container">
            <?php if ( 'yes' === ( $s['show_search_title'] ?? 'yes' ) ) : ?>
                <h1 class="nsfcenit-search-title"><?php echo esc_html( $s['title_prefix'] ?? '' ); ?> <?php echo esc_html( $term ); ?></h1>
            <?php endif; ?>
            <section class="nsfcenit-search-list layout-<?php echo esc_attr( $s['layout'] ?? 'list' ); ?>">
                <?php if ( $q && $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post(); $this->render_item( get_the_ID(), $s ); endwhile; else : ?>
                    <p class="nsfcenit-search-empty"><?php echo esc_html( $s['empty_text'] ?? __( 'No se encontraron resultados.', 'nsfcenit-widgets' ) ); ?></p>
                <?php endif; ?>
            </section>
            <?php if ( $q && $q->max_num_pages > 1 ) : ?>
                <nav class="nsfcenit-search-pagination"><?php echo wp_kses_post( paginate_links( [ 'total' => $q->max_num_pages, 'current' => $paged ] ) ); ?></nav>
            <?php endif; ?>
        </div></div>
        <?php if ( ! ( is_search() && 'yes' === ( $s['use_search_query'] ?? 'yes' ) ) ) { wp_reset_postdata(); }
    }
}
