<?php
namespace NSFARENA\Widgets;

use NSFARENA\Widget_Base;
use NSFARENA\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Category_Archive extends Widget_Base {
    public function get_name() { return 'nsfarena_category_archive'; }
    public function get_title() { return esc_html__( 'Archivo de Notas por Categoría', 'nsfarena-widgets' ); }
    public function get_icon() { return 'eicon-archive-posts'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Archivo', 'nsfarena-widgets' ) ] );
        $this->add_control( 'use_archive_query', [ 'label' => esc_html__( 'Usar categoría actual del Archive', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_query_controls( 10 );
        $this->add_control( 'show_header', [ 'label' => esc_html__( 'Mostrar encabezado', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_featured', [ 'label' => esc_html__( 'Primera nota destacada', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'layout', [ 'label' => esc_html__( 'Layout del resto', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'list', 'options' => [ 'list' => esc_html__( 'Lista con imagen derecha', 'nsfarena-widgets' ), 'grid' => esc_html__( 'Grilla', 'nsfarena-widgets' ), 'compact' => esc_html__( 'Compacto sin imagen', 'nsfarena-widgets' ) ] ] );
        $this->add_control( 'show_category', [ 'label' => esc_html__( 'Mostrar categoría', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfarena-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_header', [ 'label' => esc_html__( 'Encabezado', 'nsfarena-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'cat_title_typo', 'selector' => '{{WRAPPER}} .cat-title' ] );
        $this->add_control( 'cat_title_color', [ 'label' => esc_html__( 'Color título', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .cat-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'cat_desc_typo', 'selector' => '{{WRAPPER}} .cat-desc' ] );
        $this->add_control( 'cat_accent_color', [ 'label' => esc_html__( 'Color acento', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#e85d04', 'selectors' => [ '{{WRAPPER}} .cat-kicker, {{WRAPPER}} .nsfarena-archive-kicker' => 'color: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_items', [ 'label' => esc_html__( 'Notas', 'nsfarena-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'item_gap', [ 'label' => esc_html__( 'Separación', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 18, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cat-list' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'image_width', [ 'label' => esc_html__( 'Ancho imagen lista', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 80, 'max' => 520 ], '%' => [ 'min' => 20, 'max' => 60 ] ], 'default' => [ 'size' => 220, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .cat-item' => 'grid-template-columns: minmax(0, 1fr) {{SIZE}}{{UNIT}};' ], 'condition' => [ 'layout' => 'list' ] ] );
        $this->add_responsive_control( 'image_ratio', [ 'label' => esc_html__( 'Proporción imagen', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '1/1', 'options' => [ '1/1' => '1:1', '4/3' => '4:3', '16/9' => '16:9', '3/2' => '3:2' ], 'selectors' => [ '{{WRAPPER}} .nsfarena-archive-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'grid_columns', [ 'label' => esc_html__( 'Columnas grilla', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'min' => 1, 'max' => 5, 'default' => 2, 'selectors' => [ '{{WRAPPER}} .cat-list.layout-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ], 'condition' => [ 'layout' => 'grid' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'item_title_typo', 'selector' => '{{WRAPPER}} .card-title' ] );
        $this->add_control( 'item_title_color', [ 'label' => esc_html__( 'Color título', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .card-title, {{WRAPPER}} .card-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'item_title_hover_color', [ 'label' => esc_html__( 'Título hover', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#e85d04', 'selectors' => [ '{{WRAPPER}} .card-title a:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .card-byline' ] );
        $this->add_control( 'meta_color', [ 'label' => esc_html__( 'Color autor/fecha', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .card-byline' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Border::get_type(), [ 'name' => 'item_border', 'selector' => '{{WRAPPER}} .cat-item' ] );
        $this->end_controls_section();
    }

    private function render_item( $post_id, $settings, $featured = false ) {
        $cats = get_the_category( $post_id );
        $meta = [];
        if ( 'yes' === ( $settings['show_author'] ?? '' ) ) {
            $byline = Helpers::author_byline( $post_id, $settings['author_prefix'] ?? __( 'Por', 'nsfarena-widgets' ) );
            if ( '' !== $byline ) { $meta[] = $byline; }
        }
        if ( 'yes' === ( $settings['show_date'] ?? '' ) ) { $meta[] = get_the_date( '', $post_id ); }
        $show_image = ( $settings['layout'] ?? 'list' ) !== 'compact' || $featured;
        ?>
        <article class="cat-item <?php echo $featured ? 'is-featured-archive' : ''; ?>">
            <div class="nsfarena-archive-content">
                <?php if ( 'yes' === ( $settings['show_category'] ?? '' ) && $cats ) : ?><span class="kicker nsfarena-archive-kicker"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                <h3 class="card-title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></h3>
                <?php if ( 'yes' === ( $settings['show_excerpt'] ?? '' ) ) : ?><p class="cat-desc nsfarena-archive-excerpt"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $post_id ), $featured ? 28 : 20 ) ); ?></p><?php endif; ?>
                <?php if ( $meta ) : ?><p class="card-byline"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p><?php endif; ?>
            </div>
            <?php if ( $show_image ) : ?>
                <a class="nsfarena-archive-img" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php if ( has_post_thumbnail( $post_id ) ) { echo get_the_post_thumbnail( $post_id, $featured ? 'large' : 'medium_large', [ 'loading' => 'lazy' ] ); } else { echo '<span class="nsfarena-dynamic-placeholder ' . esc_attr( Helpers::card_image_class( $post_id ) ) . '"></span>'; } ?>
                </a>
            <?php endif; ?>
        </article>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        global $wp_query;
        $query = ( 'yes' === ( $s['use_archive_query'] ?? '' ) && ( is_archive() || is_home() ) ) ? $wp_query : $this->build_query( $s );
        $term = is_category() ? get_queried_object() : null;
        $ids = [];
        if ( $query && $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post(); $ids[] = get_the_ID(); }
        }
        if ( $query !== $wp_query ) { wp_reset_postdata(); }
        ?>
        <div class="nsfarena-scope"><div class="container">
            <?php if ( 'yes' === ( $s['show_header'] ?? '' ) ) : ?>
                <header class="cat-header"><span class="cat-kicker"><?php echo esc_html__( 'Sección', 'nsfarena-widgets' ); ?></span><h1 class="cat-title"><?php echo esc_html( $term ? $term->name : __( 'Noticias', 'nsfarena-widgets' ) ); ?></h1><?php if ( $term && ! empty( $term->description ) ) : ?><p class="cat-desc"><?php echo esc_html( $term->description ); ?></p><?php endif; ?></header>
            <?php endif; ?>
            <section class="cat-list layout-<?php echo esc_attr( $s['layout'] ?? 'list' ); ?>">
                <?php if ( $ids ) : ?>
                    <?php if ( 'yes' === ( $s['show_featured'] ?? '' ) ) { $first = array_shift( $ids ); $this->render_item( $first, $s, true ); } ?>
                    <?php foreach ( $ids as $id ) { $this->render_item( $id, $s, false ); } ?>
                <?php else : ?>
                    <p><?php echo esc_html__( 'No hay notas para mostrar.', 'nsfarena-widgets' ); ?></p>
                <?php endif; ?>
            </section>
        </div></div>
        <?php
    }
}
