<?php
namespace NSFATLAS\Widgets;

use NSFATLAS\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Live_Trends extends Widget_Base {
    public function get_name() { return 'nsfatlas_live_trends'; }
    public function get_title() { return esc_html__( 'Live Bar + Trends', 'nsfatlas-widgets' ); }
    public function get_icon() { return 'eicon-flash'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_live', [ 'label' => esc_html__( 'Live Bar', 'nsfatlas-widgets' ) ] );
        $this->add_control( 'show_live_bar', [ 'label' => 'Mostrar Live Bar', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'fm_brand', [ 'label' => 'Marca FM', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->add_control( 'fm_small', [ 'label' => 'Texto chico FM', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'FM 102.3', 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->add_control( 'fm_url', [ 'label' => 'Enlace del badge FM', 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->add_control( 'live_label', [ 'label' => 'Texto vivo', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'EN VIVO', 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->add_control( 'live_title', [ 'label' => esc_html__( 'Título vivo', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Liga Profesional · Estudio Central', 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->add_control( 'live_time', [ 'label' => 'Tiempo', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Hace 5 minutos', 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->add_control( 'live_url', [ 'label' => 'Enlace de la barra', 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_live_bar' => 'yes' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'content_trends', [ 'label' => esc_html__( 'Trends', 'nsfatlas-widgets' ) ] );
        $this->add_control( 'show_trends_bar', [ 'label' => 'Mostrar Trends', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes' ] );
        $this->add_control( 'trends_label', [ 'label' => 'Etiqueta', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Trends', 'condition' => [ 'show_trends_bar' => 'yes' ] ] );
        $this->add_control( 'trends_source', [ 'label' => 'Fuente', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'manual', 'options' => [ 'manual' => 'Manual', 'google' => 'Google Trends RSS' ], 'condition' => [ 'show_trends_bar' => 'yes' ] ] );
        $this->add_control( 'trends', [ 'label' => esc_html__( 'Trends manuales separados por coma', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Mundial 2026,VIVO Liga Profesional,Inteligencia Artificial,Dólar,Vaca Muerta,Reforma previsional', 'condition' => [ 'trends_source' => 'manual' ] ] );
        $this->add_control( 'google_geo', [ 'label' => 'País Google Trends', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'AR', 'options' => [ 'AR' => 'Argentina', 'US' => 'Estados Unidos', 'UY' => 'Uruguay', 'CL' => 'Chile', 'BR' => 'Brasil', 'MX' => 'México', 'ES' => 'España' ], 'condition' => [ 'trends_source' => 'google' ] ] );
        $this->add_control( 'google_limit', [ 'label' => 'Cantidad de trends', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 8, 'min' => 1, 'max' => 20, 'condition' => [ 'trends_source' => 'google' ] ] );
        $this->add_control( 'cache_minutes', [ 'label' => 'Cache Google Trends (minutos)', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 30, 'min' => 5, 'max' => 360, 'condition' => [ 'trends_source' => 'google' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_live', [ 'label' => 'Estilo Live Bar', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'live_bg', [ 'label' => 'Fondo', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .live-bar' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'live_border', [ 'label' => 'Borde', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .live-bar' => 'border-bottom-color: {{VALUE}};' ] ] );
        $this->add_control( 'fm_bg', [ 'label' => 'Fondo FM', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .fm-badge' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'fm_color', [ 'label' => 'Color FM', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .fm-badge' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'live_accent', [ 'label' => 'Color vivo', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .live-status, {{WRAPPER}} .live-time' => 'color: {{VALUE}};', '{{WRAPPER}} .live-status .dot' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'live_title_color', [ 'label' => 'Color título vivo', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .live-title' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'live_typo', 'selector' => '{{WRAPPER}} .live-bar-inner' ] );
        $this->add_responsive_control( 'live_height', [ 'label' => 'Alto barra', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 28, 'max' => 90 ] ], 'selectors' => [ '{{WRAPPER}} .live-bar-inner' => 'height: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_trends', [ 'label' => 'Estilo Trends', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'trends_bg', [ 'label' => 'Fondo', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .trends-bar' => 'background: {{VALUE}};' ] ] );
        $this->add_control( 'trends_border', [ 'label' => 'Borde', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .trends-bar' => 'border-bottom-color: {{VALUE}};' ] ] );
        $this->add_control( 'trends_label_color', [ 'label' => 'Color etiqueta', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .trends-label' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'trends_bolt_color', [ 'label' => 'Color rayo', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .trends-label .bolt' => 'fill: {{VALUE}};' ] ] );
        $this->add_control( 'trends_item_color', [ 'label' => 'Color ítems', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .trends-items a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'trends_item_hover', [ 'label' => 'Color hover', 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .trends-items a:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'trends_typo', 'selector' => '{{WRAPPER}} .trends-bar-inner' ] );
        $this->add_responsive_control( 'trends_height', [ 'label' => 'Alto barra', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 28, 'max' => 90 ] ], 'selectors' => [ '{{WRAPPER}} .trends-bar-inner' => 'height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'trends_gap', [ 'label' => 'Separación trends', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 4, 'max' => 60 ] ], 'selectors' => [ '{{WRAPPER}} .trends-items' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();
    }

    private function get_google_trends_items( $geo, $limit, $cache_minutes ) {
        $geo = preg_replace( '/[^A-Z]/', '', strtoupper( (string) $geo ) );
        if ( ! $geo ) { $geo = 'AR'; }
        $limit = max( 1, min( 20, absint( $limit ) ) );
        $cache_key = 'nsfatlas_google_trends_' . strtolower( $geo ) . '_' . $limit;
        $cached = get_transient( $cache_key );
        if ( is_array( $cached ) ) { return $cached; }

        $url = add_query_arg( [ 'geo' => $geo, 'hl' => 'es-419' ], 'https://trends.google.com/trending/rss' );
        $response = wp_remote_get( $url, [ 'timeout' => 8, 'redirection' => 3, 'user-agent' => 'WordPress/nsfatlas-widgets' ] );
        $items = [];
        if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
            $body = wp_remote_retrieve_body( $response );
            $xml = @simplexml_load_string( $body );
            if ( $xml && isset( $xml->channel->item ) ) {
                foreach ( $xml->channel->item as $item ) {
                    $title = trim( wp_strip_all_tags( (string) $item->title ) );
                    $link  = esc_url_raw( (string) $item->link );
                    if ( $title ) { $items[] = [ 'title' => $title, 'url' => $link ?: 'https://trends.google.com/trending?geo=' . $geo ]; }
                    if ( count( $items ) >= $limit ) { break; }
                }
            }
        }
        if ( empty( $items ) ) {
            $items = [
                [ 'title' => 'Mundial 2026', 'url' => '#' ],
                [ 'title' => 'Dólar', 'url' => '#' ],
                [ 'title' => 'Inteligencia Artificial', 'url' => '#' ],
                [ 'title' => 'Vaca Muerta', 'url' => '#' ],
            ];
        }
        set_transient( $cache_key, $items, max( 5, absint( $cache_minutes ) ) * MINUTE_IN_SECONDS );
        return $items;
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        if ( 'google' === ( $s['trends_source'] ?? 'manual' ) ) {
            $items = $this->get_google_trends_items( $s['google_geo'] ?? 'AR', $s['google_limit'] ?? 8, $s['cache_minutes'] ?? 30 );
        } else {
            $items = [];
            foreach ( array_filter( array_map( 'trim', explode( ',', (string) ( $s['trends'] ?? '' ) ) ) ) as $item ) {
                $items[] = [ 'title' => $item, 'url' => '#' ];
            }
        }
        $fm_url = ! empty( $s['fm_url']['url'] ) ? $s['fm_url']['url'] : '';
        $live_url = ! empty( $s['live_url']['url'] ) ? $s['live_url']['url'] : '';
        ?>
        <div class="nsfatlas-scope nsfatlas-live-trends-widget">
            <?php if ( 'yes' === ( $s['show_live_bar'] ?? 'yes' ) ) : ?>
            <div class="live-bar"><div class="container"><div class="live-bar-inner">
                <?php $badge = '<span class="fm-badge">' . esc_html( $s['fm_brand'] ?: '' ) . '<small>' . esc_html( $s['fm_small'] ?: 'FM 102.3' ) . '</small></span>'; echo $fm_url ? '<a href="' . esc_url( $fm_url ) . '">' . $badge . '</a>' : $badge; ?>
                <?php $live_content = '<span class="live-status"><span class="dot" aria-hidden="true"></span>' . esc_html( $s['live_label'] ?: 'EN VIVO' ) . '</span><span class="live-title">' . esc_html( $s['live_title'] ) . '</span><span class="live-time">' . esc_html( $s['live_time'] ?: '' ) . '</span><span class="live-chevron" aria-hidden="true">›</span>'; echo $live_url ? '<a class="nsfatlas-live-main-link" href="' . esc_url( $live_url ) . '">' . $live_content . '</a>' : $live_content; ?>
            </div></div></div>
            <?php endif; ?>
            <?php if ( 'yes' === ( $s['show_trends_bar'] ?? 'yes' ) ) : ?>
            <div class="trends-bar"><div class="container"><div class="trends-bar-inner"><span class="trends-label"><svg class="bolt" viewBox="0 0 24 24" aria-hidden="true"><path d="M13 2L4 14h7l-2 8 9-12h-7l2-8z"/></svg><?php echo esc_html( $s['trends_label'] ?: 'Trends' ); ?></span><div class="trends-items"><?php foreach ( $items as $item ) : $title = is_array( $item ) ? $item['title'] : (string) $item; $url = is_array( $item ) ? $item['url'] : '#'; ?><a href="<?php echo esc_url( $url ?: '#' ); ?>" class="<?php echo false !== stripos( $title, 'vivo' ) ? 'live-item' : ''; ?>"><?php echo esc_html( $title ); ?></a><?php endforeach; ?></div></div></div></div>
            <?php endif; ?>
        </div>
        <?php
    }
}
