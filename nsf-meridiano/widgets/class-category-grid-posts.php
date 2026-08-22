<?php
namespace NSFMERIDIANO\Widgets;

use NSFMERIDIANO\Widget_Base;
use NSFMERIDIANO\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Category_Grid_Posts extends Widget_Base {
    public function get_name() { return 'nsfmeridiano_category_grid_posts'; }
    public function get_title() { return esc_html__( 'Categoría con Notas en Grilla', 'nsfmeridiano-widgets' ); }
    public function get_icon() { return 'eicon-posts-grid'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfmeridiano-widgets' ) ] );
        $this->add_control( 'source', [ 'label' => esc_html__( 'Fuente', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'current', 'options' => [ 'current' => esc_html__( 'Categoría actual del archive', 'nsfmeridiano-widgets' ), 'manual' => esc_html__( 'Categoría seleccionada', 'nsfmeridiano-widgets' ), 'query' => esc_html__( 'Query personalizada', 'nsfmeridiano-widgets' ) ] ] );
        $this->add_control( 'category_id', [ 'label' => esc_html__( 'Categoría', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT2, 'options' => Helpers::get_categories_options(), 'condition' => [ 'source' => 'manual' ] ] );
        $this->add_query_controls( 9 );
        $this->add_control( 'show_header', [ 'label' => esc_html__( 'Mostrar título de categoría', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'custom_title', [ 'label' => esc_html__( 'Título personalizado', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'condition' => [ 'show_header' => 'yes' ] ] );
        $this->add_control( 'show_description', [ 'label' => esc_html__( 'Mostrar descripción', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'show_featured', [ 'label' => esc_html__( 'Primera nota destacada', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'show_kicker', [ 'label' => esc_html__( 'Mostrar categoría/kicker', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfmeridiano-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'layout_style', [ 'label' => esc_html__( 'Layout', 'nsfmeridiano-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'columns', [ 'label' => esc_html__( 'Columnas', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '3', 'tablet_default' => '2', 'mobile_default' => '1', 'options' => [ '1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .posts-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ] ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 28, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .posts-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'image_ratio', [ 'label' => esc_html__( 'Proporción imagen', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4/3', 'options' => [ '1/1'=>'Cuadrada 1:1','4/3'=>'4:3','16/9'=>'16:9','3/2'=>'3:2','2/3'=>'Vertical 2:3' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .post-card-img, {{WRAPPER}} .nsfmeridiano-category-grid-posts .post-card-img img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'card_padding', [ 'label' => esc_html__( 'Padding card', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .post-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'card_bg', [ 'label' => esc_html__( 'Fondo card', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .post-card' => 'background: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'card_radius', [ 'label' => esc_html__( 'Radio card', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .post-card' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;' ] ] );
        $this->add_responsive_control( 'image_radius', [ 'label' => esc_html__( 'Radio imagen', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-category-grid-posts .post-card-img' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_header', [ 'label' => esc_html__( 'Título de categoría', 'nsfmeridiano-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'header_align', [ 'label' => esc_html__( 'Alineación', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::CHOOSE, 'options' => [ 'left'=>[ 'title'=>'Izquierda','icon'=>'eicon-text-align-left' ], 'center'=>[ 'title'=>'Centro','icon'=>'eicon-text-align-center' ], 'right'=>[ 'title'=>'Derecha','icon'=>'eicon-text-align-right' ] ], 'default' => 'left', 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-cat-grid-header' => 'text-align: {{VALUE}};' ] ] );
        $this->add_control( 'header_bg', [ 'label' => esc_html__( 'Fondo encabezado', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-cat-grid-header' => 'background: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'header_padding', [ 'label' => esc_html__( 'Padding', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-cat-grid-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'cat_title_color', [ 'label' => esc_html__( 'Color título', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#0b2545', 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-cat-grid-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'cat_title_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-cat-grid-title' ] );
        $this->add_control( 'desc_color', [ 'label' => esc_html__( 'Color descripción', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-cat-grid-desc' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'desc_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-cat-grid-desc' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_card', [ 'label' => esc_html__( 'Texto de notas', 'nsfmeridiano-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'kicker_color', [ 'label' => esc_html__( 'Color kicker', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#a8823a', 'selectors' => [ '{{WRAPPER}} .post-card-kicker' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'kicker_typo', 'selector' => '{{WRAPPER}} .post-card-kicker' ] );
        $this->add_control( 'post_title_color', [ 'label' => esc_html__( 'Color título', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .post-card-title, {{WRAPPER}} .post-card-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'post_title_hover_color', [ 'label' => esc_html__( 'Título hover', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#a8823a', 'selectors' => [ '{{WRAPPER}} .post-card:hover .post-card-title, {{WRAPPER}} .post-card:hover .post-card-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'post_title_typo', 'selector' => '{{WRAPPER}} .post-card-title' ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Color bajada', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .post-card-excerpt' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .post-card-excerpt' ] );
        $this->add_control( 'meta_color', [ 'label' => esc_html__( 'Color meta', 'nsfmeridiano-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .post-card-date' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'meta_typo', 'selector' => '{{WRAPPER}} .post-card-date' ] );
        $this->end_controls_section();
    }

    private function get_query_and_term( $s ) {
        $term = null;
        $args = [ 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => absint( $s['posts_per_page'] ?? 9 ), 'ignore_sticky_posts' => true ];
        if ( ( $s['source'] ?? 'current' ) === 'current' && is_category() ) {
            $term = get_queried_object();
            $args['cat'] = (int) $term->term_id;
        } elseif ( ( $s['source'] ?? '' ) === 'manual' && ! empty( $s['category_id'] ) ) {
            $term = get_term( absint( $s['category_id'] ), 'category' );
            $args['cat'] = absint( $s['category_id'] );
        } else {
            if ( ! empty( $s['categories'] ) ) { $args['category__in'] = array_map( 'absint', (array) $s['categories'] ); }
        }
        switch ( $s['orderby'] ?? 'date' ) {
            case 'views': $args['meta_key'] = 'post_views_count'; $args['orderby'] = 'meta_value_num'; $args['order'] = 'DESC'; break;
            case 'comments': $args['orderby'] = 'comment_count'; $args['order'] = 'DESC'; break;
            case 'rand': $args['orderby'] = 'rand'; break;
            default: $args['orderby'] = 'date'; $args['order'] = 'DESC';
        }
        return [ new \WP_Query( $args ), $term ];
    }

    private function render_post_card( $post_id, $s, $featured = false ) {
        $cats = get_the_category( $post_id );
        $meta = [];
        if ( 'yes' === ( $s['show_author'] ?? '' ) ) { $byline = Helpers::author_byline( $post_id, $s['author_prefix'] ?? '' ); if ( $byline ) { $meta[] = $byline; } }
        if ( 'yes' === ( $s['show_date'] ?? '' ) ) { $meta[] = get_the_date( '', $post_id ); }
        ?>
        <article class="post-card <?php echo $featured ? 'feat' : ''; ?>">
            <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $post_id ) ); ?>">
                <div class="post-card-img <?php echo esc_attr( Helpers::card_image_class( $post_id ) ); ?>">
                    <?php if ( has_post_thumbnail( $post_id ) ) { echo get_the_post_thumbnail( $post_id, $featured ? 'large' : 'medium_large', [ 'loading' => 'lazy' ] ); } ?>
                </div>
            </a>
            <div class="post-card-body">
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php if ( 'yes' === ( $s['show_kicker'] ?? '' ) && $cats ) : ?><span class="post-card-kicker"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                    <h3 class="post-card-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h3>
                </a>
                <?php if ( 'yes' === ( $s['show_excerpt'] ?? '' ) ) : ?><p class="post-card-excerpt"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $post_id ), 18 ) ); ?></p><?php endif; ?>
                <?php if ( $meta ) : ?><span class="post-card-date"><?php echo esc_html( implode( ' · ', $meta ) ); ?></span><?php endif; ?>
            </div>
        </article>
        <?php
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        [ $q, $term ] = $this->get_query_and_term( $s );
        $title = trim( (string) ( $s['custom_title'] ?? '' ) );
        if ( '' === $title ) { $title = $term && ! is_wp_error( $term ) ? $term->name : __( 'Noticias', 'nsfmeridiano-widgets' ); }
        ?>
        <div class="nsfmeridiano-scope nsfmeridiano-category-grid-posts nsfmeridiano-brd"><div class="container">
            <?php if ( 'yes' === ( $s['show_header'] ?? '' ) ) : ?>
                <header class="nsfmeridiano-cat-grid-header">
                    <h2 class="nsfmeridiano-cat-grid-title"><?php echo esc_html( $title ); ?></h2>
                    <?php if ( 'yes' === ( $s['show_description'] ?? '' ) && $term && ! empty( $term->description ) ) : ?><p class="nsfmeridiano-cat-grid-desc"><?php echo esc_html( $term->description ); ?></p><?php endif; ?>
                </header>
            <?php endif; ?>
            <section class="posts-grid">
                <?php if ( $q->have_posts() ) : $i = 0; while ( $q->have_posts() ) : $q->the_post(); $this->render_post_card( get_the_ID(), $s, $i === 0 && 'yes' === ( $s['show_featured'] ?? '' ) ); $i++; endwhile; else : ?>
                    <p><?php echo esc_html__( 'No hay notas para mostrar.', 'nsfmeridiano-widgets' ); ?></p>
                <?php endif; wp_reset_postdata(); ?>
            </section>
        </div></div>
        <?php
    }
}
