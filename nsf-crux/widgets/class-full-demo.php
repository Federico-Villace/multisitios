<?php
namespace NSFCRUX\Widgets;

use NSFCRUX\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Full_Demo extends Widget_Base {
    public function get_name() { return 'nsfcrux_full_demo'; }
    public function get_title() { return esc_html__( 'Maqueta completa HTML5', 'nsfcrux-widgets' ); }
    public function get_icon() { return 'eicon-site-identity'; }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Maqueta', 'nsfcrux-widgets' ) ] );
        $this->add_control(
            'default_view',
            [
                'label'   => esc_html__( 'Vista inicial', 'nsfcrux-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'cat',
                'options' => [
                                        'nota' => esc_html__( 'Nota', 'nsfcrux-widgets' ),
                    'cat'  => esc_html__( 'Categoría', 'nsfcrux-widgets' ),
                ],
            ]
        );
        $this->add_control(
            'show_switcher',
            [
                'label'        => esc_html__( 'Mostrar switch demo', 'nsfcrux-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $classes = [ 'nsfcrux-scope' ];
        if ( empty( $settings['show_switcher'] ) || 'yes' !== $settings['show_switcher'] ) {
            $classes[] = 'nsfcrux-hide-switcher';
        }
        $view = in_array( $settings['default_view'], [ 'nota', 'cat' ], true ) ? $settings['default_view'] : 'cat';
        echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" data-nsfcrux-default-view="' . esc_attr( $view ) . '">';
        include NSFCRUX_PATH . 'templates/full-demo.php';
        echo '</div>';
    }
}
