<?php
namespace NSFCAPITAL\Widgets;

use NSFCAPITAL\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Latest_Box extends Widget_Base {
    public function get_name() { return 'nsfcapital_latest_box'; }
    public function get_title() { return esc_html__( 'Lo Último Box', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-editor-list-ul'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfcapital-widgets' ) ] );
        $this->add_control( 'title', [ 'label' => 'Título', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Lo Último' ] );
        $this->add_control( 'section_label', [ 'label' => 'Sección', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Argentina' ] );
        $this->add_query_controls( 5 );
        $this->add_control( 'first_with_thumb', [ 'label' => 'Primera nota con imagen', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style', [ 'label' => 'Estilo', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'accent', [ 'label' => 'Color acento', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1a4731', 'selectors' => [ '{{WRAPPER}} .nsfcapital-latest-box::after' => 'background: {{VALUE}};', '{{WRAPPER}} .nsfcapital-latest-title' => 'border-color: {{VALUE}};' ] ] );
        $this->add_control( 'box_bg', [ 'label' => 'Fondo', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .nsfcapital-latest-box' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'box_border', [ 'label' => 'Borde', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#d6d6d6', 'selectors' => [ '{{WRAPPER}} .nsfcapital-latest-box' => 'border-color: {{VALUE}};', '{{WRAPPER}} .nsfcapital-latest-item' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'box_padding', [ 'label' => 'Padding', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-latest-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'head_typo', 'selector' => '{{WRAPPER}} .nsfcapital-latest-head' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'item_typo', 'selector' => '{{WRAPPER}} .nsfcapital-latest-item-title' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $q = $this->build_query( $s );
        ?>
        <div class="nsfcapital-scope"><div class="nsfcapital-latest-box">
            <div class="nsfcapital-latest-head"><strong><?php echo esc_html( $s['title'] ); ?></strong><span></span><em><?php echo esc_html( $s['section_label'] ); ?></em></div>
            <div class="nsfcapital-latest-list">
                <?php $i = 0; while ( $q->have_posts() ) : $q->the_post(); $i++; ?>
                    <article class="nsfcapital-latest-item <?php echo 1 === $i && 'yes' === ( $s['first_with_thumb'] ?? 'yes' ) ? 'has-thumb' : ''; ?>">
                        <div class="nsfcapital-latest-text"><a class="nsfcapital-latest-item-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                        <?php if ( 1 === $i && 'yes' === ( $s['first_with_thumb'] ?? 'yes' ) ) : ?>
                            <a class="nsfcapital-latest-thumb" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); } else { echo '<span class="card-img sq ' . esc_attr( \NSFCAPITAL\Helpers::card_image_class( get_the_ID() ) ) . '"></span>'; } ?></a>
                        <?php endif; ?>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div></div>
        <?php
    }
}
