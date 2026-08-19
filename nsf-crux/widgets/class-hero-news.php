<?php
namespace NSFCRUX\Widgets;

use NSFCRUX\Widget_Base;
use NSFCRUX\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Hero_News extends Widget_Base {
    public function get_name() { return 'nsfcrux_hero_news'; }
    public function get_title() { return esc_html__( 'Hero Noticias', 'nsfcrux-widgets' ); }
    public function get_icon() { return 'eicon-posts-ticker'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Consulta', 'nsfcrux-widgets' ) ] );
        $this->add_query_controls( 1 );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfcrux-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'secondary_layout', [ 'label' => esc_html__( 'Notas secundarias', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'side', 'options' => [ 'side' => esc_html__( 'Al costado', 'nsfcrux-widgets' ), 'bottom' => esc_html__( 'Debajo', 'nsfcrux-widgets' ) ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style', [ 'label' => esc_html__( 'Estilo', 'nsfcrux-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'hero_gap', [ 'label' => esc_html__( 'Separación', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 24, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-hero-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typography', 'selector' => '{{WRAPPER}} .hero-title, {{WRAPPER}} .nsfcrux-hero-main-title' ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Color título', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .hero-title, {{WRAPPER}} .hero-title a, {{WRAPPER}} .nsfcrux-hero-main-title, {{WRAPPER}} .nsfcrux-hero-main-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'secondary_title_typography', 'label' => esc_html__( 'Título secundarias', 'nsfcrux-widgets' ), 'selector' => '{{WRAPPER}} .nsfcrux-hero-side-title' ] );
        $this->add_control( 'secondary_title_color', [ 'label' => esc_html__( 'Color títulos secundarios', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcrux-hero-side-title, {{WRAPPER}} .nsfcrux-hero-side-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typography', 'selector' => '{{WRAPPER}} .hero-sub' ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Color bajada', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .hero-sub' => 'color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'hero_image_ratio', [ 'label' => esc_html__( 'Proporción imagen principal', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4/3', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '3/2' => '3:2', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .hero-img, {{WRAPPER}} .hero-img-real, {{WRAPPER}} .nsfcrux-hero-main-media' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'side_image_ratio', [ 'label' => esc_html__( 'Proporción imágenes secundarias', 'nsfcrux-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '16/9', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '3/2' => '3:2', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .nsfcrux-hero-side-media' => 'aspect-ratio: {{VALUE}};' ] ] );
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
        $author_byline = Helpers::author_byline( $main->ID, $s['author_prefix'] ?? __( 'Por', 'nsfcrux-widgets' ) );
        $layout_class = ( $count > 1 ) ? ' nsfcrux-hero-has-secondary nsfcrux-hero-secondary-' . sanitize_html_class( $s['secondary_layout'] ?? 'side' ) : ' nsfcrux-hero-single';
        ?>
        <div class="nsfcrux-scope nsfcrux-hero-news<?php echo esc_attr( $layout_class ); ?>">
            <div class="container">
                <?php if ( 1 === $count ) : ?>
                    <article class="hero">
                        <div>
                            <h1 class="hero-title"><a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>"><?php echo esc_html( get_the_title( $main->ID ) ); ?></a></h1>
                            <?php if ( 'yes' === ( $s['show_excerpt'] ?? '' ) ) : ?><p class="hero-sub"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $main->ID ), 34 ) ); ?></p><?php endif; ?>
                            <?php if ( 'yes' === ( $s['show_author'] ?? '' ) && $author_byline ) : ?><p class="hero-byline"><?php echo esc_html( $author_byline ); ?></p><?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>" class="nsfcrux-hero-main-media-link"><?php $this->media( $main->ID, 'hero-img-real' ); ?></a>
                    </article>
                <?php else : ?>
                    <div class="nsfcrux-hero-grid">
                        <article class="nsfcrux-hero-main">
                            <a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>" class="nsfcrux-hero-main-media-link"><?php $this->media( $main->ID, 'nsfcrux-hero-main-media' ); ?></a>
                            <div class="nsfcrux-hero-main-content">
                                <h1 class="nsfcrux-hero-main-title"><a href="<?php echo esc_url( get_permalink( $main->ID ) ); ?>"><?php echo esc_html( get_the_title( $main->ID ) ); ?></a></h1>
                                <?php if ( 'yes' === ( $s['show_excerpt'] ?? '' ) ) : ?><p class="hero-sub"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $main->ID ), 28 ) ); ?></p><?php endif; ?>
                                <?php if ( 'yes' === ( $s['show_author'] ?? '' ) && $author_byline ) : ?><p class="hero-byline"><?php echo esc_html( $author_byline ); ?></p><?php endif; ?>
                            </div>
                        </article>
                        <div class="nsfcrux-hero-side">
                            <?php foreach ( array_slice( $posts, 1 ) as $side_post ) : ?>
                                <article class="nsfcrux-hero-side-card">
                                    <a href="<?php echo esc_url( get_permalink( $side_post->ID ) ); ?>" class="nsfcrux-hero-side-media-link"><?php $this->media( $side_post->ID, 'nsfcrux-hero-side-media' ); ?></a>
                                    <h3 class="nsfcrux-hero-side-title"><a href="<?php echo esc_url( get_permalink( $side_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $side_post->ID ) ); ?></a></h3>
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
