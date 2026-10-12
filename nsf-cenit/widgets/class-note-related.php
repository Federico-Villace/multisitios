<?php
namespace NSFCENIT\Widgets;

use NSFCENIT\Widget_Base;
use NSFCENIT\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Bloque de notas para colocar DEBAJO de una nota.
 *
 * Hace el mismo trabajo que el Sidebar Más Leídas, pero pensado para
 * ocupar el ancho completo al pie del artículo: en vez de una lista
 * vertical angosta, reparte las notas en columnas con la numeración
 * como ancla visual.
 *
 * Excluye la nota que se está leyendo. Un bloque de recomendados que te
 * ofrece la nota en la que ya estás es el error más común de estos
 * módulos y el que más rápido quema la confianza del lector.
 */
class Note_Related extends Widget_Base {
    public function get_name() { return 'nsfcenit_note_related'; }
    public function get_title() { return esc_html__( 'Más Leídas bajo la Nota', 'nsfcenit-widgets' ); }
    public function get_icon() { return 'eicon-post-list'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfcenit-widgets' ) ] );
        $this->add_control( 'title', [
            'label'   => esc_html__( 'Título del bloque', 'nsfcenit-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Lo más leído', 'nsfcenit-widgets' ),
        ] );
        $this->add_query_controls( 4 );
        $this->add_control( 'same_category', [
            'label'       => esc_html__( 'Sólo de la misma categoría', 'nsfcenit-widgets' ),
            'type'        => \Elementor\Controls_Manager::SWITCHER,
            'return_value'=> 'yes',
            'default'     => '',
            'description' => esc_html__( 'Si se activa, ignora las categorías elegidas arriba y usa las de la nota actual.', 'nsfcenit-widgets' ),
        ] );
        $this->add_control( 'show_numbers', [
            'label' => esc_html__( 'Mostrar numeración', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes',
        ] );
        $this->add_control( 'show_image', [
            'label' => esc_html__( 'Mostrar imagen', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes',
        ] );
        $this->add_control( 'show_kicker', [
            'label' => esc_html__( 'Mostrar categoría', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes',
        ] );
        $this->add_control( 'show_date', [
            'label' => esc_html__( 'Mostrar fecha', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'layout', [ 'label' => esc_html__( 'Layout', 'nsfcenit-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'columns', [
            'label' => esc_html__( 'Columnas', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'min' => 1, 'max' => 6,
            'default' => 4, 'tablet_default' => 2, 'mobile_default' => 1,
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-grid' => '--px-rel-cols: {{VALUE}};' ],
        ] );
        $this->add_responsive_control( 'gap', [
            'label' => esc_html__( 'Separación', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
            'default' => [ 'size' => 28, 'unit' => 'px' ],
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-grid' => 'gap: {{SIZE}}{{UNIT}};' ],
        ] );
        $this->add_control( 'image_ratio', [
            'label' => esc_html__( 'Proporción imagen', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => '4/3',
            'options' => [ '4/3' => '4:3', '3/2' => '3:2', '16/9' => '16:9', '1/1' => '1:1' ],
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-img' => 'aspect-ratio: {{VALUE}};' ],
            'condition' => [ 'show_image' => 'yes' ],
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'colors', [ 'label' => esc_html__( 'Colores', 'nsfcenit-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'head_color', [
            'label' => esc_html__( 'Color del título', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#2f6f5e',
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-head' => 'color: {{VALUE}}; border-bottom-color: currentColor;' ],
        ] );
        $this->add_control( 'num_color', [
            'label' => esc_html__( 'Color de la numeración', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#2f6f5e',
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-num' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'item_title_color', [
            'label' => esc_html__( 'Color del titular', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#191d1c',
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-title, {{WRAPPER}} .nsfcenit-related-title a' => 'color: {{VALUE}};' ],
        ] );
        $this->add_control( 'item_hover_color', [
            'label' => esc_html__( 'Titular al pasar', 'nsfcenit-widgets' ),
            'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#2f6f5e',
            'selectors' => [ '{{WRAPPER}} .nsfcenit-related-title a:hover' => 'color: {{VALUE}};' ],
        ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name' => 'item_title_typo', 'label' => esc_html__( 'Titular', 'nsfcenit-widgets' ),
            'selector' => '{{WRAPPER}} .nsfcenit-related-title',
        ] );
        $this->end_controls_section();
    }

    /**
     * Arma la consulta excluyendo la nota en curso.
     *
     * `build_query` no sabe nada del contexto, así que se reaprovechan sus
     * argumentos y se le agregan la exclusión y, si corresponde, las
     * categorías de la nota actual. Se pide una nota de más para que al
     * descartar la actual el bloque no quede corto.
     */
    private function build_related_query( $s ) {
        $current = get_the_ID();
        $base    = $this->build_query( $s );
        $args    = $base->query_vars;
        wp_reset_postdata();

        $wanted = max( 1, absint( $s['posts_per_page'] ?? 4 ) );
        $args['posts_per_page'] = $wanted + 1;
        if ( $current ) { $args['post__not_in'] = [ (int) $current ]; }

        if ( 'yes' === ( $s['same_category'] ?? '' ) && $current ) {
            $cats = wp_get_post_categories( $current );
            unset( $args['category__in'] );
            if ( $cats ) { $args['category__in'] = array_map( 'absint', $cats ); }
        }
        return [ new \WP_Query( $args ), $wanted ];
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        [ $q, $wanted ] = $this->build_related_query( $s );
        if ( ! $q->have_posts() ) { return; }

        $ids = [];
        while ( $q->have_posts() ) { $q->the_post(); $ids[] = get_the_ID(); }
        wp_reset_postdata();
        $ids = array_slice( $ids, 0, $wanted );
        if ( ! $ids ) { return; }

        $n = 0;
        ?>
        <div class="nsfcenit-scope nsfcenit-px nsfcenit-related-widget">
            <section class="nsfcenit-related">
                <?php if ( ! empty( $s['title'] ) ) : ?>
                    <h2 class="nsfcenit-related-head"><?php echo esc_html( $s['title'] ); ?></h2>
                <?php endif; ?>
                <div class="nsfcenit-related-grid">
                    <?php foreach ( $ids as $id ) : $n++; $cats = get_the_category( $id ); ?>
                        <article class="nsfcenit-related-item">
                            <?php if ( 'yes' === ( $s['show_image'] ?? 'yes' ) ) : ?>
                                <a class="nsfcenit-related-img" href="<?php echo esc_url( get_permalink( $id ) ); ?>" aria-hidden="true" tabindex="-1">
                                    <?php
                                    if ( has_post_thumbnail( $id ) ) {
                                        echo get_the_post_thumbnail( $id, 'medium_large', [ 'loading' => 'lazy' ] );
                                    } else {
                                        echo '<span class="card-img ' . esc_attr( Helpers::card_image_class( $id ) ) . '"></span>';
                                    }
                                    ?>
                                </a>
                            <?php endif; ?>
                            <div class="nsfcenit-related-body">
                                <?php if ( 'yes' === ( $s['show_numbers'] ?? 'yes' ) ) : ?>
                                    <span class="nsfcenit-related-num" aria-hidden="true"><?php echo esc_html( str_pad( (string) $n, 2, '0', STR_PAD_LEFT ) ); ?></span>
                                <?php endif; ?>
                                <?php if ( 'yes' === ( $s['show_kicker'] ?? 'yes' ) && $cats ) : ?>
                                    <span class="nsfcenit-related-kicker"><?php echo esc_html( $cats[0]->name ); ?></span>
                                <?php endif; ?>
                                <h3 class="nsfcenit-related-title"><a href="<?php echo esc_url( get_permalink( $id ) ); ?>"><?php echo esc_html( get_the_title( $id ) ); ?></a></h3>
                                <?php if ( 'yes' === ( $s['show_date'] ?? '' ) ) : ?>
                                    <span class="nsfcenit-related-date"><?php echo esc_html( get_the_date( '', $id ) ); ?></span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
        <?php
    }
}
