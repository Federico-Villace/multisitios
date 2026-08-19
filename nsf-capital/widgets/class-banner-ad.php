<?php
namespace NSFCAPITAL\Widgets;

use NSFCAPITAL\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Banner_Ad extends Widget_Base {
    public function get_name() { return 'nsfcapital_banner_ad'; }
    public function get_title() { return esc_html__( 'Banner Publicitario', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-banner'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Anuncio', 'nsfcapital-widgets' ) ] );
        $this->add_control( 'placement', [
            'label' => esc_html__( 'Ubicación sugerida', 'nsfcapital-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'middle',
            'options' => [
                'middle'  => 'Medio de página',
                'sidebar' => 'Sidebar',
                'footer'  => 'Footer',
                'header'  => 'Header / superior',
            ],
            'prefix_class' => 'nsfcapital-ad-place-'
        ] );
        $this->add_control( 'format', [
            'label' => esc_html__( 'Formato', 'nsfcapital-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'leaderboard',
            'options' => [
                'mobile-banner' => 'Mobile 320 × 100',
                'leaderboard'   => 'Leaderboard 728 × 90',
                'billboard'     => 'Billboard 970 × 250',
                'rectangle'     => 'Sidebar 300 × 250',
                'half-page'     => 'Sidebar 300 × 600',
                'footer-wide'   => 'Footer 970 × 120',
                'super-wide'    => 'Largo superior 1200 × 280',
                'mega-wide'     => 'Largo horizontal 1140 × 180',
                'square'        => 'Cuadrado 300 × 300',
                'mobile-square' => 'Cuadrado mobile 336 × 280',
                'fluid'         => 'Fluido / responsive',
            ]
        ] );
        $this->add_control( 'mode', [
            'label' => esc_html__( 'Contenido', 'nsfcapital-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'placeholder',
            'options' => [
                'placeholder' => 'Placeholder',
                'image'       => 'Imagen + link',
                'code'        => 'HTML / shortcode / adserver',
            ]
        ] );
        $this->add_control( 'image', [
            'label' => esc_html__( 'Imagen', 'nsfcapital-widgets' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'condition' => [ 'mode' => 'image' ],
        ] );
        $this->add_control( 'url', [
            'label' => esc_html__( 'URL destino', 'nsfcapital-widgets' ),
            'type' => \Elementor\Controls_Manager::URL,
            'condition' => [ 'mode' => 'image' ],
        ] );
        $this->add_control( 'custom_code', [
            'label' => esc_html__( 'HTML / shortcode / script adserver', 'nsfcapital-widgets' ),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'rows' => 8,
            'condition' => [ 'mode' => 'code' ],
            'description' => esc_html__( 'Acepta HTML seguro y shortcodes. Para scripts de adserver usá un shortcode propio o un plugin de ads.', 'nsfcapital-widgets' ),
        ] );
        $this->add_control( 'label', [ 'label' => esc_html__( 'Etiqueta', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'PUBLICIDAD' ] );
        $this->add_control( 'placeholder_text', [ 'label' => esc_html__( 'Texto placeholder', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '' ] );
        $this->add_control( 'hide_mobile', [ 'label' => esc_html__( 'Ocultar en mobile', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => '' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_box', [ 'label' => esc_html__( 'Estilo', 'nsfcapital-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'margin', [ 'label' => 'Margin', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'padding', [ 'label' => 'Padding', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', '%', 'em' ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'bg_color', [ 'label' => 'Fondo contenedor', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-wrap' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'box_bg', [ 'label' => 'Fondo banner', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-box' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'label_color', [ 'label' => 'Color etiqueta', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-label' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'border_color', [ 'label' => 'Color borde', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-box' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'min_height', [ 'label' => 'Alto mínimo', 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', 'vh' ], 'range' => [ 'px' => [ 'min' => 40, 'max' => 800 ] ], 'selectors' => [ '{{WRAPPER}} .nsfcapital-ad-box' => 'min-height: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $format = sanitize_html_class( $s['format'] ?? 'leaderboard' );
        $mode = sanitize_key( $s['mode'] ?? 'placeholder' );
        $classes = [ 'nsfcapital-scope', 'nsfcapital-ad-widget', 'nsfcapital-ad-' . $format ];
        if ( ! empty( $s['hide_mobile'] ) ) { $classes[] = 'nsfcapital-ad-hide-mobile'; }
        $label = sanitize_text_field( $s['label'] ?? 'PUBLICIDAD' );
        $placeholder = sanitize_text_field( $s['placeholder_text'] ?? '' );
        if ( ! $placeholder ) {
            $placeholder = str_replace( [ 'mobile-banner', 'leaderboard', 'billboard', 'rectangle', 'half-page', 'footer-wide', 'super-wide', 'mega-wide', 'square', 'mobile-square', 'fluid' ], [ '320 × 100', '728 × 90', '970 × 250', '300 × 250', '300 × 600', '970 × 120', '1200 × 280', '1140 × 180', '300 × 300', '336 × 280', 'Responsive' ], $format );
        }
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="nsfcapital-ad-wrap">
                <?php if ( $label ) : ?><span class="nsfcapital-ad-label"><?php echo esc_html( $label ); ?></span><?php endif; ?>
                <div class="nsfcapital-ad-box">
                    <?php if ( 'image' === $mode && ! empty( $s['image']['url'] ) ) : ?>
                        <?php $href = ! empty( $s['url']['url'] ) ? esc_url( $s['url']['url'] ) : ''; ?>
                        <?php if ( $href ) : ?><a href="<?php echo $href; ?>" <?php echo ! empty( $s['url']['is_external'] ) ? 'target="_blank" rel="noopener"' : ''; ?>><?php endif; ?>
                            <img src="<?php echo esc_url( $s['image']['url'] ); ?>" alt="<?php echo esc_attr( $label ); ?>">
                        <?php if ( $href ) : ?></a><?php endif; ?>
                    <?php elseif ( 'code' === $mode && ! empty( $s['custom_code'] ) ) : ?>
                        <?php echo do_shortcode( wp_kses_post( $s['custom_code'] ) ); ?>
                    <?php else : ?>
                        <span><?php echo esc_html( $placeholder ); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}
