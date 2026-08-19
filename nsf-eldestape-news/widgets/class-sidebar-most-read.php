<?php
namespace NSFELDESTAPE\Widgets;

use NSFELDESTAPE\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Sidebar_Most_Read extends Widget_Base {
    public function get_name() { return 'nsfeldestape_sidebar_most_read'; }
    public function get_title() { return esc_html__( 'Sidebar Más Leídas', 'nsfeldestape-widgets' ); }
    public function get_icon() { return 'eicon-number-field'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Consulta', 'nsfeldestape-widgets' ) ] );
        $this->add_control( 'title', [ 'label' => esc_html__( 'Título', 'nsfeldestape-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Lo más leído' ] );
        $this->add_query_controls( 5 );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $q = $this->build_query( $s );
        $i = 1;
        ?>
        <div class="nsfeldestape-scope"><aside class="home-side"><div class="side-block"><h3 class="side-head"><?php echo esc_html( $s['title'] ); ?></h3><ol class="rank-list">
        <?php while ( $q->have_posts() ) : $q->the_post(); ?>
            <li class="rank-item"><span class="rank-num"><?php echo esc_html( $i++ ); ?></span><div><h4 class="card-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h4><p class="card-byline"><?php $cats = get_the_category(); echo esc_html( $cats ? $cats[0]->name : get_the_date() ); ?></p></div></li>
        <?php endwhile; wp_reset_postdata(); ?>
        </ol></div></aside></div>
        <?php
    }
}
