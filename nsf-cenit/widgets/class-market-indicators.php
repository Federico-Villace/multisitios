<?php
namespace NSFCENIT\Widgets;

use NSFCENIT\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Market_Indicators extends Widget_Base {
    public function get_name() { return 'nsfcenit_market_indicators'; }
    public function get_title() { return esc_html__( 'Indicadores dólar / riesgo país', 'nsfcenit-widgets' ); }
    public function get_icon() { return 'eicon-slider-push'; }
    public function is_reload_preview_required() { return true; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Indicadores', 'nsfcenit-widgets' ) ] );
        $this->add_control( 'items', [
            'label'       => esc_html__( 'Mostrar indicadores', 'nsfcenit-widgets' ),
            'type'        => \Elementor\Controls_Manager::SELECT2,
            'multiple'    => true,
            'default'     => [ 'oficial', 'tarjeta', 'blue', 'mep', 'ccl', 'riesgo' ],
            'options'     => [
                'oficial' => 'Dólar oficial', 'tarjeta' => 'Dólar tarjeta', 'blue' => 'Dólar blue',
                'mep' => 'Dólar MEP', 'ccl' => 'Contado con liqui', 'riesgo' => 'Riesgo país',
            ],
            'label_block' => true,
        ] );
        $this->add_control( 'layout', [
            'label' => esc_html__( 'Estilo visual', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'carousel',
            'options' => [ 'carousel' => esc_html__( 'Carrusel horizontal manual', 'nsfcenit-widgets' ), 'sidebar_grid' => esc_html__( 'Grilla sidebar', 'nsfcenit-widgets' ), 'grid' => esc_html__( 'Grilla ancha', 'nsfcenit-widgets' ), 'ticker_auto' => esc_html__( 'Cinta automática', 'nsfcenit-widgets' ), 'ticker' => esc_html__( 'Ticker compacto manual', 'nsfcenit-widgets' ) ],
        ] );
        $this->add_control( 'dolar_api_url', [ 'label' => esc_html__( 'API dólares', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'https://dolarapi.com/v1/dolares', 'label_block' => true ] );
        $this->add_control( 'risk_api_url', [ 'label' => esc_html__( 'API riesgo país', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'https://api.argentinadatos.com/v1/finanzas/indices/riesgo-pais/ultimo', 'label_block' => true ] );
        $this->add_control( 'cache_minutes', [ 'label' => esc_html__( 'Cache en minutos', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 15, 'min' => 1, 'max' => 1440 ] );
        $this->add_control( 'source_text', [ 'label' => esc_html__( 'Texto fuente', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Fuente: DolarAPI / ArgentinaDatos' ] );
        $this->add_control( 'show_source', [ 'label' => esc_html__( 'Mostrar fuente', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'ticker_speed', [ 'label' => esc_html__( 'Velocidad cinta (segundos)', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 28, 'min' => 8, 'max' => 120, 'condition' => [ 'layout' => 'ticker_auto' ] ] );
        $this->add_control( 'pause_on_hover', [ 'label' => esc_html__( 'Pausar al pasar el mouse', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'layout' => 'ticker_auto' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_cards', [ 'label' => esc_html__( 'Estilo', 'nsfcenit-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'columns', [ 'label' => esc_html__( 'Columnas grilla', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '6', 'tablet_default' => '3', 'mobile_default' => '2', 'options' => [ '1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));' ], 'condition' => [ 'layout' => [ 'grid', 'sidebar_grid' ] ] ] );
        $this->add_responsive_control( 'slide_width', [ 'label' => esc_html__( 'Ancho tarjeta carrusel', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 130, 'max' => 360 ], '%' => [ 'min' => 20, 'max' => 90 ] ], 'default' => [ 'size' => 220, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-carousel .nsfcenit-market-card' => 'flex-basis: {{SIZE}}{{UNIT}};' ], 'condition' => [ 'layout' => 'carousel' ] ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'default' => [ 'size' => 10, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-track, {{WRAPPER}} .nsfcenit-market-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'card_bg', [ 'label' => esc_html__( 'Fondo tarjeta', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-card' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'accent_color', [ 'label' => esc_html__( 'Color acento', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#e30613', 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-card::before, {{WRAPPER}} .nsfcenit-market-card.is-active' => 'border-color: {{VALUE}};', '{{WRAPPER}} .nsfcenit-market-title, {{WRAPPER}} .nsfcenit-market-meta.is-up' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'border_color', [ 'label' => esc_html__( 'Borde', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#e2e2e2', 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-card' => 'border-color: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'radius', [ 'label' => esc_html__( 'Radio', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'default' => [ 'size' => 14, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-card' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'padding', [ 'label' => esc_html__( 'Padding tarjeta', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px' ], 'default' => [ 'top' => 14, 'right' => 16, 'bottom' => 14, 'left' => 16, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
        $this->add_control( 'value_color', [ 'label' => esc_html__( 'Color valor', 'nsfcenit-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#202124', 'selectors' => [ '{{WRAPPER}} .nsfcenit-market-value' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'label' => esc_html__( 'Título', 'nsfcenit-widgets' ), 'selector' => '{{WRAPPER}} .nsfcenit-market-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'value_typo', 'label' => esc_html__( 'Valor', 'nsfcenit-widgets' ), 'selector' => '{{WRAPPER}} .nsfcenit-market-value' ] );
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $items = ! empty( $s['items'] ) ? (array) $s['items'] : [ 'oficial', 'tarjeta', 'blue', 'mep', 'ccl', 'riesgo' ];
        $data = $this->get_market_data( $s );
        $layout = $s['layout'] ?? 'carousel';
        $speed = max( 8, absint( $s['ticker_speed'] ?? 28 ) );
        $pause_class = 'yes' === ( $s['pause_on_hover'] ?? 'yes' ) ? ' is-pausable' : '';
        $render_card = function( $key ) use ( $data ) {
            if ( empty( $data[ $key ] ) ) { return; }
            $row = $data[ $key ];
            $variation = isset( $row['variation'] ) ? (float) $row['variation'] : 0;
            $down = $variation < 0;
            $flat = abs( $variation ) < 0.01;
            ?>
            <div class="nsfcenit-market-card <?php echo 'oficial' === $key ? 'is-active' : ''; ?>">
                <div class="nsfcenit-market-top"><span class="nsfcenit-market-title"><?php echo esc_html( $row['label'] ); ?></span><span class="nsfcenit-market-arrow <?php echo $down ? 'is-down' : ( $flat ? 'is-flat' : 'is-up' ); ?>"><?php echo $flat ? '═' : ( $down ? '▼' : '▲' ); ?></span></div>
                <div class="nsfcenit-market-value"><?php echo 'riesgo' === $key ? esc_html( number_format_i18n( (float) $row['value'], 0 ) ) : '$' . esc_html( number_format_i18n( (float) $row['value'], 2 ) ); ?></div>
                <div class="nsfcenit-market-meta <?php echo $down ? 'is-down' : ( $flat ? 'is-flat' : 'is-up' ); ?>"><strong><?php echo esc_html( number_format_i18n( abs( $variation ), 2 ) ); ?>%</strong> <span><?php echo esc_html__( '24 hs', 'nsfcenit-widgets' ); ?></span></div>
            </div>
            <?php
        };
        ?>
        <div class="nsfcenit-scope"><div class="nsfcenit-market-widget nsfcenit-market-layout-<?php echo esc_attr( $layout ); ?>" style="--nsfcenit-ticker-speed: <?php echo esc_attr( $speed ); ?>s;">
            <?php if ( 'ticker_auto' === $layout ) : ?>
                <div class="nsfcenit-market-tape<?php echo esc_attr( $pause_class ); ?>" aria-label="<?php echo esc_attr__( 'Indicadores financieros', 'nsfcenit-widgets' ); ?>">
                    <div class="nsfcenit-market-tape-inner">
                        <?php foreach ( $items as $key ) { $render_card( $key ); } ?>
                        <?php foreach ( $items as $key ) { $render_card( $key ); } ?>
                    </div>
                </div>
            <?php else : ?>
                <?php $track_class = in_array( $layout, [ 'grid', 'sidebar_grid' ], true ) ? 'nsfcenit-market-grid' : ( 'ticker' === $layout ? 'nsfcenit-market-ticker' : 'nsfcenit-market-track nsfcenit-market-carousel' ); ?>
                <div class="<?php echo esc_attr( $track_class ); ?>">
                    <?php foreach ( $items as $key ) { $render_card( $key ); } ?>
                </div>
            <?php endif; ?>
            <?php if ( 'yes' === ( $s['show_source'] ?? 'yes' ) ) : ?><div class="nsfcenit-market-source"><span><?php echo esc_html( $s['source_text'] ); ?></span></div><?php endif; ?>
        </div></div>
        <?php
    }

    private function get_market_data( array $settings ) {
        $cache_minutes = max( 1, absint( $settings['cache_minutes'] ?? 15 ) );
        $transient_key = 'nsfcenit_market_data_' . md5( wp_json_encode( [ $settings['dolar_api_url'] ?? '', $settings['risk_api_url'] ?? '' ] ) );
        $cached = get_transient( $transient_key );
        if ( is_array( $cached ) ) { return $cached; }
        $values = [ 'oficial' => [ 'label' => 'Dólar oficial', 'value' => 0 ], 'tarjeta' => [ 'label' => 'Dólar tarjeta', 'value' => 0 ], 'blue' => [ 'label' => 'Dólar blue', 'value' => 0 ], 'mep' => [ 'label' => 'Dólar MEP', 'value' => 0 ], 'ccl' => [ 'label' => 'Contado con liqui', 'value' => 0 ], 'riesgo' => [ 'label' => 'Riesgo país *', 'value' => 0 ] ];
        $dolar_url = esc_url_raw( $settings['dolar_api_url'] ?? 'https://dolarapi.com/v1/dolares' );
        $response = wp_remote_get( $dolar_url, [ 'timeout' => 8, 'redirection' => 2 ] );
        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
            $json = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( is_array( $json ) ) { foreach ( $json as $item ) { if ( empty( $item['casa'] ) ) { continue; } $casa = sanitize_key( $item['casa'] ); $map = [ 'oficial' => 'oficial', 'tarjeta' => 'tarjeta', 'blue' => 'blue', 'bolsa' => 'mep', 'contadoconliqui' => 'ccl' ]; if ( isset( $map[ $casa ], $values[ $map[ $casa ] ] ) ) { $values[ $map[ $casa ] ]['value'] = isset( $item['venta'] ) ? (float) $item['venta'] : (float) ( $item['compra'] ?? 0 ); } } }
        }
        $risk_url = esc_url_raw( $settings['risk_api_url'] ?? 'https://api.argentinadatos.com/v1/finanzas/indices/riesgo-pais/ultimo' );
        $risk_response = wp_remote_get( $risk_url, [ 'timeout' => 8, 'redirection' => 2 ] );
        if ( ! is_wp_error( $risk_response ) && 200 === wp_remote_retrieve_response_code( $risk_response ) ) { $risk = json_decode( wp_remote_retrieve_body( $risk_response ), true ); if ( is_array( $risk ) && isset( $risk['valor'] ) ) { $values['riesgo']['value'] = (float) $risk['valor']; } }
        $last = get_option( 'nsfcenit_market_last_values', [] ); $current_values = [];
        foreach ( $values as $key => $row ) { $current = (float) $row['value']; $previous = isset( $last[ $key ] ) ? (float) $last[ $key ] : $current; $values[ $key ]['variation'] = $previous > 0 ? ( ( $current - $previous ) / $previous ) * 100 : 0; $current_values[ $key ] = $current; }
        update_option( 'nsfcenit_market_last_values', $current_values, false ); set_transient( $transient_key, $values, $cache_minutes * MINUTE_IN_SECONDS ); return $values;
    }
}
