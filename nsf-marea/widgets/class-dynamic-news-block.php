<?php
namespace NSFMAREA\Widgets;

use NSFMAREA\Widget_Base;
use NSFMAREA\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Dynamic_News_Block extends Widget_Base {
    public function get_name() { return 'nsfmarea_dynamic_news_block'; }
    public function get_title() { return esc_html__( 'Bloque Noticias Dinámico', 'nsfmarea-widgets' ); }
    public function get_icon() { return 'eicon-posts-grid'; }
    public function is_reload_preview_required() { return true; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfmarea-widgets' ) ] );
        $this->add_query_controls( 4 );
        $this->add_control( 'section_title', [ 'label' => esc_html__( 'Título de sección', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '' ] );
        $this->add_control( 'show_title', [ 'label' => esc_html__( 'Mostrar título', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_excerpt', [ 'label' => esc_html__( 'Mostrar bajada en nota grande', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_excerpt_side', [ 'label' => esc_html__( 'Mostrar bajada en notas chicas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfmarea-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => '' ] );
        $this->add_control( 'text_position', [
            'label' => esc_html__( 'Posición del texto', 'nsfmarea-widgets' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'over',
            'options' => [
                'over'  => esc_html__( 'Sobre la foto', 'nsfmarea-widgets' ),
                'below' => esc_html__( 'Debajo de la foto', 'nsfmarea-widgets' ),
            ],
            'description' => esc_html__( 'Aplica a la nota grande. Las notas chicas van siempre con el texto debajo: sobre una foto angosta el título no se lee.', 'nsfmarea-widgets' ),
        ] );
        $this->add_control( 'big_position', [ 'label' => esc_html__( 'Nota grande', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'left', 'options' => [ 'left' => esc_html__( 'Izquierda', 'nsfmarea-widgets' ), 'right' => esc_html__( 'Derecha', 'nsfmarea-widgets' ) ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'mobile_content', [ 'label' => esc_html__( 'Ajustes mobile', 'nsfmarea-widgets' ) ] );
        $this->add_control( 'mobile_visible', [ 'label' => esc_html__( 'Cantidad visible en móvil', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 12 ] );
        $this->add_control( 'mobile_layout', [ 'label' => esc_html__( 'Layout móvil', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'stack', 'options' => [ 'stack' => 'Una columna', 'two' => 'Grande + 2 columnas', 'compact' => 'Lista compacta' ] ] );
        $this->add_control( 'mobile_text_below', [ 'label' => esc_html__( 'En móvil, texto debajo de imagen', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'mobile_hide_excerpt', [ 'label' => esc_html__( 'Ocultar bajada en móvil', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'layout_style', [ 'label' => esc_html__( 'Layout', 'nsfmarea-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'gap', [ 'label' => esc_html__( 'Separación', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ], 'default' => [ 'size' => 24, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-grid' => 'gap: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_responsive_control( 'big_ratio', [ 'label' => esc_html__( 'Proporción imagen grande', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '16/9', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '3/2' => '3:2', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-main .nsfmarea-dynamic-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'small_ratio', [ 'label' => esc_html__( 'Proporción imágenes chicas', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '16/9', 'options' => [ '16/9' => '16:9', '4/3' => '4:3', '1/1' => '1:1' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-side .nsfmarea-dynamic-img' => 'aspect-ratio: {{VALUE}};' ] ] );
        $this->add_responsive_control( 'main_width', [ 'label' => esc_html__( 'Ancho nota grande (%)', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ '%' ], 'range' => [ '%' => [ 'min' => 45, 'max' => 75 ] ], 'default' => [ 'size' => 58, 'unit' => '%' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-grid' => 'grid-template-columns: minmax(0, {{SIZE}}%) minmax(0, 1fr);' ] ] );
        $this->add_responsive_control( 'border_radius', [ 'label' => esc_html__( 'Radio imágenes', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px' ], 'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ], 'default' => [ 'size' => 12, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-img' => 'border-radius: {{SIZE}}{{UNIT}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'typography_style', [ 'label' => esc_html__( 'Tipografías', 'nsfmarea-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'section_title_typo', 'selector' => '{{WRAPPER}} .nsfmarea-dynamic-section-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'main_title_typo', 'selector' => '{{WRAPPER}} .nsfmarea-dynamic-main .nsfmarea-dynamic-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'side_title_typo', 'selector' => '{{WRAPPER}} .nsfmarea-dynamic-side .nsfmarea-dynamic-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'excerpt_typo', 'selector' => '{{WRAPPER}} .nsfmarea-dynamic-excerpt' ] );
        $this->end_controls_section();

        $this->start_controls_section( 'colors_style', [ 'label' => esc_html__( 'Colores', 'nsfmarea-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Título', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-title, {{WRAPPER}} .nsfmarea-dynamic-title a' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'excerpt_color', [ 'label' => esc_html__( 'Bajada', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-excerpt' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'overlay_color', [ 'label' => esc_html__( 'Degradado overlay', 'nsfmarea-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(27,26,23,0.88)', 'selectors' => [ '{{WRAPPER}} .nsfmarea-dynamic-overlay .nsfmarea-dynamic-img::after' => 'background: linear-gradient(180deg, rgba(0,0,0,0) 25%, {{VALUE}} 100%);' ] ] );
        $this->end_controls_section();
    }

    protected function render_post_card( $post_id, $is_main, $settings, $index = 0 ) {
        $classes = 'nsfmarea-dynamic-card ' . ( $is_main ? 'nsfmarea-dynamic-main' : 'nsfmarea-dynamic-side-card' );

        // Sólo la nota grande puede llevar el texto encima: sobre una foto
        // chica el título queda ilegible. Se respeta el valor guardado del
        // control anterior para no perder la configuración existente.
        $legacy   = isset( $settings['text_position'] ) ? null : ( $settings['overlay_titles'] ?? 'yes' );
        $position = $settings['text_position'] ?? ( 'yes' === $legacy ? 'over' : 'below' );
        if ( $is_main && 'over' === $position ) { $classes .= ' nsfmarea-dynamic-overlay'; }

        $show_excerpt = $is_main
            ? 'yes' === ( $settings['show_excerpt'] ?? '' )
            : 'yes' === ( $settings['show_excerpt_side'] ?? '' );

        $meta = [];
        if ( 'yes' === ( $settings['show_author'] ?? '' ) ) {
            $byline = Helpers::author_byline( $post_id, $settings['author_prefix'] ?? '' );
            if ( '' !== $byline ) { $meta[] = $byline; }
        }
        if ( 'yes' === ( $settings['show_date'] ?? '' ) ) { $meta[] = get_the_date( '', $post_id ); }
        ?>
        <article class="<?php echo esc_attr( $classes ); ?>" data-mobile-index="<?php echo esc_attr( $index ); ?>">
            <a class="nsfmarea-dynamic-img" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                <?php if ( has_post_thumbnail( $post_id ) ) : ?>
                    <?php echo get_the_post_thumbnail( $post_id, $is_main ? 'large' : 'medium_large', [ 'loading' => 'lazy' ] ); ?>
                <?php else : ?>
                    <span class="nsfmarea-dynamic-placeholder <?php echo esc_attr( Helpers::card_image_class( $post_id ) ); ?>"></span>
                <?php endif; ?>
            </a>
            <div class="nsfmarea-dynamic-content">
                <?php if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) : ?>
                    <<?php echo $is_main ? 'h2' : 'h3'; ?> class="nsfmarea-dynamic-title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></<?php echo $is_main ? 'h2' : 'h3'; ?>>
                <?php endif; ?>
                <?php if ( $show_excerpt ) : ?>
                    <p class="nsfmarea-dynamic-excerpt"><?php echo esc_html( Helpers::trim_words( Helpers::post_subtitle( $post_id ), $is_main ? 26 : 16 ) ); ?></p>
                <?php endif; ?>
                <?php if ( $meta ) : ?>
                    <p class="nsfmarea-dynamic-meta"><?php echo esc_html( implode( ' · ', $meta ) ); ?></p>
                <?php endif; ?>
            </div>
        </article>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $settings['posts_per_page'] = max( 1, absint( $settings['posts_per_page'] ?? 4 ) );
        $mobile_visible = max( 1, absint( $settings['mobile_visible'] ?? 3 ) );
        $q = $this->build_query( $settings );
        if ( ! $q->have_posts() ) { return; }
        $ids = [];
        while ( $q->have_posts() ) { $q->the_post(); $ids[] = get_the_ID(); }
        wp_reset_postdata();
        $ids = array_slice( $ids, 0, $settings['posts_per_page'] );
        if ( empty( $ids ) ) { return; }
        $main_id = array_shift( $ids );
        $layout_mobile = sanitize_key( $settings['mobile_layout'] ?? 'stack' );
        $text_below_mobile = 'yes' === ( $settings['mobile_text_below'] ?? '' ) ? '1' : '0';
        $hide_excerpt_mobile = 'yes' === ( $settings['mobile_hide_excerpt'] ?? '' ) ? '1' : '0';
        ?>
        <div class="nsfmarea-scope nsfmarea-np">
            <section class="nsfmarea-dynamic-news nsfmarea-dynamic-mobile-<?php echo esc_attr( $layout_mobile ); ?>" data-mobile-visible="<?php echo esc_attr( $mobile_visible ); ?>" data-mobile-text-below="<?php echo esc_attr( $text_below_mobile ); ?>" data-mobile-hide-excerpt="<?php echo esc_attr( $hide_excerpt_mobile ); ?>">
                <?php if ( ! empty( $settings['section_title'] ) ) : ?><h2 class="nsfmarea-dynamic-section-title"><?php echo esc_html( $settings['section_title'] ); ?></h2><?php endif; ?>
                <div class="nsfmarea-dynamic-grid <?php echo 'right' === ( $settings['big_position'] ?? 'left' ) ? 'is-reverse' : ''; ?>">
                    <?php $this->render_post_card( $main_id, true, $settings, 1 ); ?>
                    <?php if ( ! empty( $ids ) ) : ?>
                        <div class="nsfmarea-dynamic-side">
                            <?php $i = 2; foreach ( $ids as $id ) { $this->render_post_card( $id, false, $settings, $i ); $i++; } ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
        <?php
    }
}
