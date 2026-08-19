<?php
namespace NSFMAREA\Widgets;

use NSFMAREA\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Brand_Tiles extends Widget_Base {
    public function get_name() { return 'nsfmarea_brand_tiles'; }
    public function get_title() { return esc_html__( 'Cuadros Footer / Marcas', 'nsfmarea-widgets' ); }
    public function get_icon() { return 'eicon-gallery-grid'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Cuadros', 'nsfmarea-widgets' ) ] );
        $rep = new \Elementor\Repeater();
        $rep->add_control( 'label', [ 'label' => esc_html__( 'Texto', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'marca' ] );
        $rep->add_control( 'url', [ 'label' => esc_html__( 'Enlace', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::URL, 'placeholder' => 'https://...' ] );
        $rep->add_control( 'style', [ 'label' => esc_html__( 'Estilo', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'red2', 'options' => [ 'green'=>'Verde', 'pink'=>'Rosa', 'red'=>'Rojo', 'orange'=>'Revista', 'blue'=>'Azul', 'purple'=>'Violeta', 'black'=>'Negro', 'red2'=>'Rojo 2', 'custom'=>'Personalizado' ] ] );
        $rep->add_control( 'bg', [ 'label' => esc_html__( 'Fondo personalizado', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'condition' => [ 'style' => 'custom' ] ] );
        $rep->add_control( 'color', [ 'label' => esc_html__( 'Color texto personalizado', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'condition' => [ 'style' => 'custom' ] ] );
        $this->add_control( 'items', [ 'label' => esc_html__( 'Marcas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $rep->get_controls(), 'default' => [
            [ 'label' => 'deport', 'style' => 'green' ], [ 'label' => 'showtiempo', 'style' => 'pink' ], [ 'label' => 'mercado', 'style' => 'red' ], [ 'label' => 'REVISTA 27', 'style' => 'orange' ], [ 'label' => 'la voz sur', 'style' => 'blue' ], [ 'label' => 'mx música', 'style' => 'purple' ], [ 'label' => 'FM TROPICAL', 'style' => 'black' ], [ 'label' => 'tiendaB', 'style' => 'red2' ],
        ], 'title_field' => '{{{ label }}}' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_grid', [ 'label' => esc_html__( 'Estilo', 'nsfmarea-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'columns', [ 'label' => esc_html__( 'Columnas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '4', 'tablet_default' => '2', 'mobile_default' => '2', 'options' => [ '1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-brand-tiles' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ] ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ], 'default' => [ 'size' => 10, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-brand-tiles' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'height', [ 'label' => esc_html__( 'Alto', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 36, 'max' => 160 ] ], 'default' => [ 'size' => 64, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .brand-tile' => 'height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'radius', [ 'label' => esc_html__( 'Radio', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'default' => [ 'size' => 2, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .brand-tile' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'typo', 'label' => esc_html__( 'Tipografía', 'nsfmarea-widgets' ), 'selector' => '{{WRAPPER}} .brand-tile' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $items = (array) ( $s['items'] ?? [] );
        if ( empty( $items ) ) { return; }
        ?>
        <div class="nsfmarea-scope"><div class="nsfmarea-brand-tiles footer-brands-grid">
            <?php foreach ( $items as $item ) :
                $url = ! empty( $item['url']['url'] ) ? $item['url']['url'] : '#';
                $target = ! empty( $item['url']['is_external'] ) ? ' target="_blank"' : '';
                $rel = ! empty( $item['url']['nofollow'] ) ? ' rel="nofollow"' : '';
                $style = sanitize_html_class( $item['style'] ?? 'red2' );
                $inline = '';
                if ( 'custom' === $style ) {
                    $bits = [];
                    if ( ! empty( $item['bg'] ) ) { $bits[] = 'background:' . sanitize_hex_color( $item['bg'] ); }
                    if ( ! empty( $item['color'] ) ) { $bits[] = 'color:' . sanitize_hex_color( $item['color'] ); }
                    $inline = $bits ? ' style="' . esc_attr( implode( ';', $bits ) ) . '"' : '';
                }
                ?>
                <a class="brand-tile tile-<?php echo esc_attr( $style ); ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $target . $rel . $inline; ?>><?php echo esc_html( $item['label'] ?? '' ); ?></a>
            <?php endforeach; ?>
        </div></div>
        <?php
    }
}
