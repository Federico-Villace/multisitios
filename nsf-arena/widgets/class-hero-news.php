<?php
namespace NSFARENA\Widgets;

use NSFARENA\Widget_Base;
use NSFARENA\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Hero_News extends Widget_Base {
    public function get_name() { return 'nsfarena_hero_news'; }
    public function get_title() { return esc_html__( 'Hero Noticias', 'nsfarena-widgets' ); }
    public function get_icon() { return 'eicon-posts-ticker'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Consulta', 'nsfarena-widgets' ) ] );
        $this->add_query_controls( 1 );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfarena-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'secondary_layout', [ 'label' => esc_html__( 'Notas secundarias', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'side', 'options' => [ 'side' => esc_html__( 'Al costado', 'nsfarena-widgets' ), 'bottom' => esc_html__( 'Debajo', 'nsfarena-widgets' ) ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style', [ 'label' => esc_html__( 'Estilo', 'nsfarena-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'hero_gap', [ 'label' => esc_html__( 'Separación', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 24, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfarena-hero-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .hero-title, {{WRAPPER}} .nsfarena-hero-main-title' ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Color título', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .hero-title, {{WRAPPER}} .hero-title a, {{WRAPPER}} .nsfarena-hero-main-title, {{WRAPPER}} .nsfarena-hero-main-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'secondary_title_typography', 'label' => esc_html__( 'Título secundarias', 'nsfarena-widgets' ), 'selector' => '{{WRAPPER}} .nsfarena-hero-side-title' ] );
        $this->add_control( 'secondary_title_color', [ 'label' => esc_html__( 'Color títulos secundarios', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfarena-hero-side-title, {{WRAPPER}} .nsfarena-hero-side-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typography', 'selector' => '{{WRAPPER}} .hero-sub' ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Color bajada', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .hero-sub' => 'color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'hero_image_ratio', [ 'label' => esc_html__( 'Proporción imagen principal', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4/3', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '3/2' => '3:2', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .hero-img, {{WRAPPER}} .hero-img-real, {{WRAPPER}} .nsfarena-hero-main-media' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'side_image_ratio', [ 'label' => esc_html__( 'Proporción imágenes secundarias', 'nsfarena-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '16/9', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '3/2' => '3:2', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .nsfarena-hero-side-media' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->end_controls_section();
    }

    private function media( $post_id, $class ) {
        if ( has_post_thumbnail( $post_id ) ) {
            echo get_the_post_thumbnail( $post_id, 'large', [ 'class' => $class . ' wp-post-image', 'loading' => 'lazy' ] );
        } else {
            echo '<div class="' . esc_attr( $class . ' hero-img ' . Helpers::card_image_class( $post_id ) ) . '" aria-hidden="true"></div>';
        }
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $q = $this->build_query( $s );
        if ( ! $q->have_posts() ) { return; }
        $posts = $q->posts;
        $count = count( $posts );
        $main = $posts[0];
        $author_byline = Helpers::author_byline( $main->ID, $s['author_prefix'] ?? __( 'Por', 'nsfarena-widgets' ) );
        $layout_class = ( $count > 1 ) ? ' nsfarena-hero-has-secondary nsfarena-hero-secondary-' . sanitize_html_class( $s['secondary_layout'] ?? 'side' ) : ' nsfarena-hero-single';
        ?>
        <div class="nsfarena-scope nsfarena-hero-news<?php echo esc_attr( $layout_class ); ?>">
            <div class="container">
                <?php if ( 1 === $count ) : ?>
                    <article class="hero">
                        <div>
                            <h1 class="hero-title"><a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>"><?php echo esc_html( get_the_title( $main->ID ) ); ?></a></h1>
                            <?php if ( 'yes' === ( $s['show_excerpt'] ?? '' ) ) : ?><p class="hero-sub"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $main->ID ), 34 ) ); ?></p><?php endif; ?>
                            <?php if ( 'yes' === ( $s['show_author'] ?? '' ) && $author_byline ) : ?><p class="hero-byline"><?php echo esc_html( $author_byline ); ?></p><?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>" class="nsfarena-hero-main-media-link"><?php $this->media( $main->ID, 'hero-img-real' ); ?></a>
                    </article>
                <?php else : ?>
                    <div class="nsfarena-hero-grid">
                        <article class="nsfarena-hero-main">
                            <a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>" class="nsfarena-hero-main-media-link"><?php $this->media( $main->ID, 'nsfarena-hero-main-media' ); ?></a>
                            <div class="nsfarena-hero-main-content">
                                <h1 class="nsfarena-hero-main-title"><a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>"><?php echo esc_html( get_the_title( $main->ID ) ); ?></a></h1>
                                <?php if ( 'yes' === ( $s['show_excerpt'] ?? '' ) ) : ?><p class="hero-sub"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $main->ID ), 28 ) ); ?></p><?php endif; ?>
                                <?php if ( 'yes' === ( $s['show_author'] ?? '' ) && $author_byline ) : ?><p class="hero-byline"><?php echo esc_html( $author_byline ); ?></p><?php endif; ?>
                            </div>
                        </article>
                        <div class="nsfarena-hero-side">
                            <?php foreach ( array_slice( $posts, 1 ) as $side_post ) : ?>
                                <article class="nsfarena-hero-side-card">
                                    <a href="<?php echo esc_url( get_permalink( $side_post->ID ) ); ?>" class="nsfarena-hero-side-media-link"><?php $this->media( $side_post->ID, 'nsfarena-hero-side-media' ); ?></a>
                                    <h3 class="nsfarena-hero-side-title"><a href="<?php echo esc_url( get_permalink( $side_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $side_post->ID ) ); ?></a></h3>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    }
}
