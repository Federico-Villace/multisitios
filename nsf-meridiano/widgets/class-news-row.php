<?php
namespace NSFMERIDIANO\Widgets;

use NSFMERIDIANO\Widget_Base;
use NSFMERIDIANO\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class News_Row extends Widget_Base {
    public function get_name() { return 'nsfmeridiano_news_row'; }
    public function get_title() { return esc_html__( 'Fila de notas editorial', 'nsfmeridiano-widgets' ); }
    public function get_icon() { return 'eicon-posts-group'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => 'Contenido' ] );
        $this->add_query_controls( 4 );
        $this->add_control( 'show_image', [ 'label' => 'Mostrar imagen', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'show_author', [ 'label' => 'Mostrar autor', 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'author_prefix', [ 'label' => 'Prefijo autor', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Por', 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_opinion_badge', [ 'label' => 'Mostrar badge opinión en segunda nota', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => '' ] );
        $this->end_controls_section();
        $this->start_controls_section( 'style', [ 'label' => 'Estilo', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'columns', [ 'label' => 'Columnas', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4', 'tablet_default' => '2', 'mobile_default' => '1', 'options' => [ '1'=>'1','2'=>'2','3'=>'3','4'=>'4' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-news-row-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0,1fr));' ] ] );
        $this->add_responsive_control( 'gap', [ 'label' => 'Gap', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ], 'default' => [ 'size' => 24, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-news-row-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'selector' => '{{WRAPPER}} .nsfmeridiano-news-row-title' ] );
        $this->add_control( 'title_color', [ 'label' => 'Color título', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#202124', 'selectors' => [ '{{WRAPPER}} .nsfmeridiano-news-row-title' => 'color: {{VALUE}};' ] ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $q = $this->build_query( $s );
        ?>
        <div class="nsfmeridiano-scope"><div class="nsfmeridiano-news-row-grid">
            <?php $i=0; while ( $q->have_posts() ) : $q->the_post(); $i++; ?>
                <article class="nsfmeridiano-news-row-card">
                    <h3 class="nsfmeridiano-news-row-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php if ( 'yes' === ( $s['show_author'] ?? 'yes' ) ) : ?>
                        <p class="nsfmeridiano-news-row-author">
                            <?php if ( 2 === $i && 'yes' === ( $s['show_opinion_badge'] ?? '' ) ) : ?><span class="nsfmeridiano-opinion-dot"></span><strong>OPINIÓN</strong><br><?php endif; ?>
                            <?php $byline = Helpers::author_byline( get_the_ID(), $s['author_prefix'] ?? __( 'Por', 'nsfmeridiano-widgets' ) ); if ( $byline ) { echo esc_html( $byline ); } ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( 'yes' === ( $s['show_image'] ?? 'yes' ) ) : ?>
                        <a class="nsfmeridiano-news-row-img" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large' ); } else { echo '<span class="card-img ' . esc_attr( \NSFMERIDIANO\Helpers::card_image_class( get_the_ID() ) ) . '"></span>'; } ?></a>
                    <?php endif; ?>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div></div>
        <?php
    }
}
