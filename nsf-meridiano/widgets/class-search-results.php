<?php
namespace NSFMERIDIANO\Widgets;

use NSFMERIDIANO\Widget_Base;
use NSFMERIDIANO\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Search_Results extends Widget_Base {
    public function get_name() { return 'nsfmeridiano_search_results'; }
    public function get_title() { return esc_html__( 'Resultados de Búsqueda', 'nsfmeridiano-widgets' ); }
    public function get_icon() { return 'eicon-search-results'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Búsqueda', 'nsfmeridiano-widgets' ) ] );
        $this->add_control( 'use_search_query', [ 'label' => esc_html__( 'Usar búsqueda actual', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'preview_term', [ 'label' => esc_html__( 'Término para previsualizar', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'description' => esc_html__( 'Sólo se usa en Elementor o si no hay búsqueda activa.', 'nsfmeridiano-widgets' ) ] );
        $this->add_control( 'posts_per_page', [ 'label' => esc_html__( 'Cantidad de resultados', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10, 'min' => 1, 'max' => 50 ] );
        $this->add_control( 'show_search_title', [ 'label' => esc_html__( 'Mostrar título de búsqueda', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'title_prefix', [ 'label' => esc_html__( 'Prefijo del título', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Resultados de la búsqueda de:', 'nsfmeridiano-widgets' ), 'condition' => [ 'show_search_title' => 'yes' ] ] );
        $this->add_control( 'layout', [ 'label' => esc_html__( 'Layout', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'list', 'options' => [ 'list' => esc_html__( 'Lista imagen derecha', 'nsfmeridiano-widgets' ), 'grid' => esc_html__( 'Grilla', 'nsfmeridiano-widgets' ), 'compact' => esc_html__( 'Compacto sin imagen', 'nsfmeridiano-widgets' ) ] ] );
        $this->add_control( 'show_category', [ 'label' => esc_html__( 'Mostrar categoría', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfmeridiano-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'empty_text', [ 'label' => esc_html__( 'Texto sin resultados', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => esc_html__( 'No se encontraron resultados.', 'nsfmeridiano-widgets' ) ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_header', [ 'label' => esc_html__( 'Título', 'nsfmeridiano-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Color', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-search-title' ] );
        $this->add_responsive_control( 'title_margin', [ 'label' => esc_html__( 'Margen título', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_items', [ 'label' => esc_html__( 'Resultados', 'nsfmeridiano-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 20, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-list' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'grid_columns', [ 'label' => esc_html__( 'Columnas grilla', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '3', 'tablet_default' => '2', 'mobile_default' => '1', 'options' => [ '1'=>'1','2'=>'2','3'=>'3','4'=>'4' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-list.layout-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ], 'condition' => [ 'layout' => 'grid' ] ] );
        $this->add_responsive_control( 'image_width', [ 'label' => esc_html__( 'Ancho imagen lista', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 80, 'max' => 520 ], '%' => [ 'min' => 20, 'max' => 60 ] ], 'default' => [ 'size' => 220, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-item.layout-list' => 'grid-template-columns: minmax(0, 1fr) {{SIZE}}{{UNIT}};' ], 'condition' => [ 'layout' => 'list' ] ] );
        $this->add_responsive_control( 'image_ratio', [ 'label' => esc_html__( 'Proporción imagen', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '1/1', 'options' => [ '1/1'=>'1:1','4/3'=>'4:3','16/9'=>'16:9','3/2'=>'3:2' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_control( 'accent_color', [ 'label' => esc_html__( 'Color categoría', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#003087', 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-kicker' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'post_title_color', [ 'label' => esc_html__( 'Color título', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-item-title, {{WRAPPER}} .nsfmeridiano-search-item-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'post_title_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-search-item-title' ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Color bajada', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-excerpt' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-search-excerpt' ] );
        $this->add_control( 'meta_color', [ 'label' => esc_html__( 'Color meta', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-meta' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-search-meta' ] );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [ 'name' => 'item_border', 'selector' => '{{WRAPPER}} .nsfmeridiano-search-item' ] );
        $this->add_responsive_control( 'item_padding', [ 'label' => esc_html__( 'Padding item', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-search-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
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
        <article class="nsfmeridiano-search-item layout-<?php echo esc_attr( $layout ); ?>">
            <div class="nsfmeridiano-search-content">
                <?php if ( 'yes' === ( $settings['show_category'] ?? '' ) && $cats ) : ?><span class="nsfmeridiano-search-kicker"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                <h3 class="nsfmeridiano-search-item-title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
                <?php if ( 'yes' === ( $settings['show_excerpt'] ?? '' ) ) : ?><p class="nsfmeridiano-search-excerpt"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $post_id ), 28 ) ); ?></p><?php endif; ?>
                <?php if ( $meta ) : ?><p class="nsfmeridiano-search-meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p><?php endif; ?>
            </div>
            <?php if ( 'compact' !== $layout ) : ?>
                <a class="nsfmeridiano-search-img" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php if ( has_post_thumbnail( $post_id ) ) { echo get_the_post_thumbnail( $post_id, 'medium_large', [ 'loading' => 'lazy' ] ); } else { echo '<span class="nsfmeridiano-dynamic-placeholder ' . esc_attr( Helpers::card_image_class( $post_id ) ) . '"></span>'; } ?>
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
        <div class="nsfmeridiano-scope nsfmeridiano-search-results"><div class="container">
            <?php if ( 'yes' === ( $s['show_search_title'] ?? 'yes' ) ) : ?>
                <h1 class="nsfmeridiano-search-title"><?php echo esc_html( $s['title_prefix'] ?? '' ); ?> <?php echo esc_html( $term ); ?></h1>
            <?php endif; ?>
            <section class="nsfmeridiano-search-list layout-<?php echo esc_attr( $s['layout'] ?? 'list' ); ?>">
                <?php if ( $q && $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post(); $this->render_item( get_the_ID(), $s ); endwhile; else : ?>
                    <p class="nsfmeridiano-search-empty"><?php echo esc_html( $s['empty_text'] ?? __( 'No se encontraron resultados.', 'nsfmeridiano-widgets' ) ); ?></p>
                <?php endif; ?>
            </section>
            <?php if ( $q && $q->max_num_pages > 1 ) : ?>
                <nav class="nsfmeridiano-search-pagination"><?php echo wp_kses_post( paginate_links( [ 'total' => $q->max_num_pages, 'current' => $paged ] ) ); ?></nav>
            <?php endif; ?>
        </div></div>
        <?php if ( ! ( is_search() && 'yes' === ( $s['use_search_query'] ?? 'yes' ) ) ) { wp_reset_postdata(); }
    }
}
