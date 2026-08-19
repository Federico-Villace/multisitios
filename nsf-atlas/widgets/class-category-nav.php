<?php
namespace NSFATLAS\Widgets;

use NSFATLAS\Helpers;
use NSFATLAS\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Category_Nav extends Widget_Base {
    public function get_name() {
        return 'nsfatlas_category_nav';
    }

    public function get_title() {
        return esc_html__( 'Categorías / Subcategorías', 'nsfatlas-widgets' );
    }

    public function get_icon() {
        return 'eicon-product-categories';
    }

    public function get_keywords() {
        return [ 'nsfatlas', 'categorias', 'subcategorias', 'archive', 'theme builder' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [ 'label' => esc_html__( 'Contenido', 'nsfatlas-widgets' ) ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Mostrar título', 'nsfatlas-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => esc_html__( 'Título', 'nsfatlas-widgets' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => esc_html__( 'Secciones', 'nsfatlas-widgets' ),
                'condition' => [ 'show_title' => 'yes' ],
            ]
        );

        $this->add_control(
            'source',
            [
                'label'   => esc_html__( 'Fuente', 'nsfatlas-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'current_children',
                'options' => [
                    'current_children' => esc_html__( 'Subcategorías de la categoría actual', 'nsfatlas-widgets' ),
                    'siblings'         => esc_html__( 'Categorías hermanas', 'nsfatlas-widgets' ),
                    'top_level'        => esc_html__( 'Categorías principales', 'nsfatlas-widgets' ),
                    'all'              => esc_html__( 'Todas las categorías', 'nsfatlas-widgets' ),
                    'manual'           => esc_html__( 'Seleccionadas manualmente', 'nsfatlas-widgets' ),
                ],
            ]
        );

        $this->add_control(
            'manual_terms',
            [
                'label'       => esc_html__( 'Categorías manuales', 'nsfatlas-widgets' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => Helpers::get_categories_options(),
                'label_block' => true,
                'condition'   => [ 'source' => 'manual' ],
            ]
        );

        $this->add_control(
            'show_all',
            [
                'label'        => esc_html__( 'Mostrar botón Todas', 'nsfatlas-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'all_label',
            [
                'label'     => esc_html__( 'Texto botón Todas', 'nsfatlas-widgets' ),
                'type'      => \Elementor\Controls_Manager::TEXT,
                'default'   => esc_html__( 'Todas', 'nsfatlas-widgets' ),
                'condition' => [ 'show_all' => 'yes' ],
            ]
        );

        $this->add_control(
            'hide_empty',
            [
                'label'        => esc_html__( 'Ocultar vacías', 'nsfatlas-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label'        => esc_html__( 'Mostrar contador', 'nsfatlas-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label'        => esc_html__( 'Mostrar descripción', 'nsfatlas-widgets' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__( 'Ordenar por', 'nsfatlas-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'name',
                'options' => [
                    'name'    => esc_html__( 'Nombre', 'nsfatlas-widgets' ),
                    'count'   => esc_html__( 'Cantidad de notas', 'nsfatlas-widgets' ),
                    'id'      => esc_html__( 'ID', 'nsfatlas-widgets' ),
                    'include' => esc_html__( 'Orden manual', 'nsfatlas-widgets' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Dirección', 'nsfatlas-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC'  => 'ASC',
                    'DESC' => 'DESC',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            [ 'label' => esc_html__( 'Layout', 'nsfatlas-widgets' ) ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Diseño', 'nsfatlas-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'pills_scroll',
                'options' => [
                    'pills_scroll' => esc_html__( 'Pills con scroll horizontal', 'nsfatlas-widgets' ),
                    'pills_wrap'   => esc_html__( 'Pills en varias líneas', 'nsfatlas-widgets' ),
                    'grid'         => esc_html__( 'Grilla de tarjetas', 'nsfatlas-widgets' ),
                    'vertical'     => esc_html__( 'Lista vertical', 'nsfatlas-widgets' ),
                    'select'       => esc_html__( 'Select desplegable', 'nsfatlas-widgets' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'     => esc_html__( 'Columnas', 'nsfatlas-widgets' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => '4',
                'tablet_default' => '3',
                'mobile_default' => '2',
                'options'   => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'condition' => [ 'layout' => 'grid' ],
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-nav-list.is-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__( 'Separación', 'nsfatlas-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                    'rem' => [ 'min' => 0, 'max' => 5, 'step' => .1 ],
                ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-nav-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Título', 'nsfatlas-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [ 'show_title' => 'yes' ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .nsfatlas-cat-nav-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'nsfatlas-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-nav-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Espacio inferior', 'nsfatlas-widgets' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-nav-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__( 'Items', 'nsfatlas-widgets' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'item_typography',
                'selector' => '{{WRAPPER}} .nsfatlas-cat-nav-item',
            ]
        );

        $this->start_controls_tabs( 'item_tabs' );

        $this->start_controls_tab( 'item_normal', [ 'label' => esc_html__( 'Normal', 'nsfatlas-widgets' ) ] );
        $this->add_control( 'item_color', [ 'label' => esc_html__( 'Texto', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'item_bg', [ 'label' => esc_html__( 'Fondo', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'item_border_color', [ 'label' => esc_html__( 'Borde', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item' => 'border-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'item_hover', [ 'label' => esc_html__( 'Hover', 'nsfatlas-widgets' ) ] );
        $this->add_control( 'item_hover_color', [ 'label' => esc_html__( 'Texto', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item:hover' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'item_hover_bg', [ 'label' => esc_html__( 'Fondo', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item:hover' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'item_hover_border_color', [ 'label' => esc_html__( 'Borde', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item:hover' => 'border-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();

        $this->start_controls_tab( 'item_active', [ 'label' => esc_html__( 'Activo', 'nsfatlas-widgets' ) ] );
        $this->add_control( 'item_active_color', [ 'label' => esc_html__( 'Texto', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item.is-active' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'item_active_bg', [ 'label' => esc_html__( 'Fondo', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item.is-active' => 'background-color: {{VALUE}};' ] ] );
        $this->add_control( 'item_active_border_color', [ 'label' => esc_html__( 'Borde', 'nsfatlas-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => [ '{{WRAPPER}} .nsfatlas-cat-nav-item.is-active' => 'border-color: {{VALUE}};' ] ] );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__( 'Padding', 'nsfatlas-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'default' => [ 'top' => 8, 'right' => 16, 'bottom' => 8, 'left' => 16, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .nsfatlas-cat-nav-item',
            ]
        );

        $this->add_responsive_control(
            'item_radius',
            [
                'label' => esc_html__( 'Radio', 'nsfatlas-widgets' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem' ],
                'default' => [ 'top' => 18, 'right' => 18, 'bottom' => 18, 'left' => 18, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-nav-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .nsfatlas-cat-nav-item',
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => esc_html__( 'Color contador', 'nsfatlas-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-count' => 'color: {{VALUE}};',
                ],
                'condition' => [ 'show_count' => 'yes' ],
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__( 'Color descripción', 'nsfatlas-widgets' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nsfatlas-cat-desc' => 'color: {{VALUE}};',
                ],
                'condition' => [ 'show_description' => 'yes' ],
            ]
        );

        $this->end_controls_section();
    }

    protected function get_terms_for_display( array $settings ) {
        $source = $settings['source'] ?? 'current_children';
        $hide_empty = 'yes' === ( $settings['hide_empty'] ?? 'yes' );
        $orderby = sanitize_key( $settings['orderby'] ?? 'name' );
        $order = 'DESC' === strtoupper( $settings['order'] ?? 'ASC' ) ? 'DESC' : 'ASC';

        $args = [
            'taxonomy'   => 'category',
            'hide_empty' => $hide_empty,
            'orderby'    => $orderby,
            'order'      => $order,
        ];

        $queried = is_category() ? get_queried_object() : null;

        if ( 'manual' === $source ) {
            $include = array_filter( array_map( 'absint', (array) ( $settings['manual_terms'] ?? [] ) ) );
            if ( empty( $include ) ) {
                return [];
            }
            $args['include'] = $include;
            if ( 'include' === $orderby ) {
                $args['orderby'] = 'include';
            }
        } elseif ( 'current_children' === $source ) {
            $args['parent'] = $queried && isset( $queried->term_id ) ? absint( $queried->term_id ) : 0;
        } elseif ( 'siblings' === $source ) {
            $args['parent'] = $queried && isset( $queried->parent ) ? absint( $queried->parent ) : 0;
        } elseif ( 'top_level' === $source ) {
            $args['parent'] = 0;
        }

        $terms = get_terms( $args );
        if ( is_wp_error( $terms ) ) {
            return [];
        }

        // Fallback útil en Theme Builder: si la categoría actual no tiene hijas, mostramos hermanas.
        if ( empty( $terms ) && 'current_children' === $source && $queried && isset( $queried->parent ) ) {
            $args['parent'] = absint( $queried->parent );
            $terms = get_terms( $args );
            if ( is_wp_error( $terms ) ) {
                return [];
            }
        }

        return $terms;
    }

    protected function get_all_url( array $settings ) {
        $queried = is_category() ? get_queried_object() : null;
        $source = $settings['source'] ?? 'current_children';

        if ( $queried && 'current_children' === $source ) {
            return get_term_link( $queried );
        }

        if ( $queried && 'siblings' === $source && ! empty( $queried->parent ) ) {
            return get_term_link( (int) $queried->parent, 'category' );
        }

        $posts_page = get_option( 'page_for_posts' );
        return $posts_page ? get_permalink( $posts_page ) : home_url( '/' );
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $terms = $this->get_terms_for_display( $settings );
        $layout = sanitize_key( $settings['layout'] ?? 'pills_scroll' );
        $queried_id = is_category() ? get_queried_object_id() : 0;

        $classes = [ 'nsfatlas-cat-nav-list' ];
        if ( 'grid' === $layout ) {
            $classes[] = 'is-grid';
        } elseif ( 'vertical' === $layout ) {
            $classes[] = 'is-vertical';
        } elseif ( 'pills_wrap' === $layout ) {
            $classes[] = 'is-wrap';
        } else {
            $classes[] = 'is-scroll';
        }

        ?>
        <div class="nsfatlas-scope nsfatlas-cat-nav-widget">
            <nav class="nsfatlas-cat-nav" aria-label="<?php echo esc_attr__( 'Categorías', 'nsfatlas-widgets' ); ?>">
                <?php if ( 'yes' === ( $settings['show_title'] ?? 'no' ) && ! empty( $settings['title'] ) ) : ?>
                    <h3 class="nsfatlas-cat-nav-title"><?php echo esc_html( $settings['title'] ); ?></h3>
                <?php endif; ?>

                <?php if ( 'select' === $layout ) : ?>
                    <select class="nsfatlas-cat-nav-select" onchange="if(this.value){window.location.href=this.value;}">
                        <?php if ( 'yes' === ( $settings['show_all'] ?? 'yes' ) ) : ?>
                            <option value="<?php echo esc_url( $this->get_all_url( $settings ) ); ?>"><?php echo esc_html( $settings['all_label'] ?? __( 'Todas', 'nsfatlas-widgets' ) ); ?></option>
                        <?php endif; ?>
                        <?php foreach ( $terms as $term ) : ?>
                            <?php $url = get_term_link( $term ); if ( is_wp_error( $url ) ) { continue; } ?>
                            <option value="<?php echo esc_url( $url ); ?>" <?php selected( $queried_id, (int) $term->term_id ); ?>><?php echo esc_html( $term->name ); ?><?php echo 'yes' === ( $settings['show_count'] ?? '' ) ? ' (' . absint( $term->count ) . ')' : ''; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else : ?>
                    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
                        <?php if ( 'yes' === ( $settings['show_all'] ?? 'yes' ) ) : ?>
                            <a class="nsfatlas-cat-nav-item <?php echo $queried_id ? '' : 'is-active'; ?>" href="<?php echo esc_url( $this->get_all_url( $settings ) ); ?>">
                                <span class="nsfatlas-cat-name"><?php echo esc_html( $settings['all_label'] ?? __( 'Todas', 'nsfatlas-widgets' ) ); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php foreach ( $terms as $term ) : ?>
                            <?php $url = get_term_link( $term ); if ( is_wp_error( $url ) ) { continue; } ?>
                            <a class="nsfatlas-cat-nav-item <?php echo $queried_id === (int) $term->term_id ? 'is-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>">
                                <span class="nsfatlas-cat-name"><?php echo esc_html( $term->name ); ?></span>
                                <?php if ( 'yes' === ( $settings['show_count'] ?? '' ) ) : ?>
                                    <span class="nsfatlas-cat-count"><?php echo esc_html( number_format_i18n( (int) $term->count ) ); ?></span>
                                <?php endif; ?>
                                <?php if ( 'yes' === ( $settings['show_description'] ?? '' ) && ! empty( $term->description ) ) : ?>
                                    <small class="nsfatlas-cat-desc"><?php echo esc_html( wp_trim_words( $term->description, 12, '…' ) ); ?></small>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ( empty( $terms ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
                    <p class="nsfatlas-empty-message"><?php echo esc_html__( 'No hay categorías para mostrar con esta configuración.', 'nsfatlas-widgets' ); ?></p>
                <?php endif; ?>
            </nav>
        </div>
        <?php
    }
}
