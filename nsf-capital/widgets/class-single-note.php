<?php
namespace NSFCAPITAL\Widgets;

use NSFCAPITAL\Widget_Base;
use NSFCAPITAL\Helpers;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Single_Note extends Widget_Base {
    public function get_name() { return 'nsfcapital_single_note'; }
    public function get_title() { return esc_html__( 'Página de Nota', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-single-post'; }

    protected function register_controls() {
        $this->start_controls_section( 'content', [ 'label' => esc_html__( 'Contenido', 'nsfcapital-widgets' ) ] );
        $this->add_control( 'show_breadcrumb', [ 'label' => esc_html__( 'Mostrar breadcrumb/categoría', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'breadcrumb_home_text', [ 'label' => esc_html__( 'Texto inicio', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Inicio', 'nsfcapital-widgets' ), 'condition' => [ 'show_breadcrumb' => 'yes' ] ] );
        $this->add_control( 'show_author', [ 'label' => esc_html__( 'Mostrar autor', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'author_prefix', [ 'label' => esc_html__( 'Prefijo autor', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => esc_html__( 'Por', 'nsfcapital-widgets' ), 'description' => esc_html__( 'Dejalo vacío para mostrar sólo el nombre. Nunca imprime el prefijo si no hay autor.', 'nsfcapital-widgets' ), 'condition' => [ 'show_author' => 'yes' ] ] );
        $this->add_control( 'show_date', [ 'label' => esc_html__( 'Mostrar fecha', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_share', [ 'label' => esc_html__( 'Mostrar compartir', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes' ] );
        $this->add_control( 'show_save', [ 'label' => esc_html__( 'Mostrar guardar', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes', 'condition' => [ 'show_share' => 'yes' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_main', [ 'label' => esc_html__( 'Estilo', 'nsfcapital-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
        $this->add_responsive_control( 'content_width', [ 'label' => esc_html__( 'Ancho contenido', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'size_units' => [ 'px', '%' ], 'range' => [ 'px' => [ 'min' => 480, 'max' => 1200 ], '%' => [ 'min' => 40, 'max' => 100 ] ], 'selectors' => [ '{{WRAPPER}} .note-main' => 'max-width: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'title_typo', 'label' => esc_html__( 'Título', 'nsfcapital-widgets' ), 'selector' => '{{WRAPPER}} .note-title' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'subtitle_typo', 'label' => esc_html__( 'Bajada', 'nsfcapital-widgets' ), 'selector' => '{{WRAPPER}} .note-subtitle' ] );
        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'body_typo', 'label' => esc_html__( 'Cuerpo', 'nsfcapital-widgets' ), 'selector' => '{{WRAPPER}} .note-body' ] );
        $this->add_control( 'title_color', [ 'label' => esc_html__( 'Color título', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .note-title' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'accent_color', [ 'label' => esc_html__( 'Color acento', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1a4731', 'selectors' => [ '{{WRAPPER}} .note-breadcrumb a, {{WRAPPER}} .author-name strong, {{WRAPPER}} .note-body a' => 'color: {{VALUE}};', '{{WRAPPER}} .share-btn:hover' => 'border-color: {{VALUE}};' ] ] );
        $this->end_controls_section();

        $this->start_controls_section( 'style_share', [ 'label' => esc_html__( 'Compartir', 'nsfcapital-widgets' ), 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => [ 'show_share' => 'yes' ] ] );
        $this->add_responsive_control( 'share_size', [ 'label' => esc_html__( 'Tamaño botón', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'px' => [ 'min' => 24, 'max' => 70 ] ], 'default' => [ 'size' => 38, 'unit' => 'px' ], 'selectors' => [ '{{WRAPPER}} .share-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};' ] ] );
        $this->add_control( 'share_color', [ 'label' => esc_html__( 'Color iconos', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'selectors' => [ '{{WRAPPER}} .share-btn, {{WRAPPER}} .share-save' => 'color: {{VALUE}};' ] ] );
        $this->add_control( 'share_hover_bg', [ 'label' => esc_html__( 'Fondo hover', 'nsfcapital-widgets' ), 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#1a4731', 'selectors' => [ '{{WRAPPER}} .share-btn:hover' => 'background: {{VALUE}}; color: #fff; border-color: {{VALUE}};' ] ] );
        $this->end_controls_section();
    }

    private function icon_svg( $name ) {
        $icons = [
            'facebook' => '<svg viewBox="0 0 320 512" aria-hidden="true"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06H297V6.26S260.43 0 225.36 0C152.14 0 104.09 44.38 104.09 124.72v70.62H22.89V288h81.2v224h100.36V288z"/></svg>',
            'whatsapp' => '<svg viewBox="0 0 448 512" aria-hidden="true"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32 101 32 1.3 131.7 1.3 254.6c0 39.2 10.2 77.5 29.6 111.3L0 480l116.7-30.6c32.4 17.7 68.9 27 107.2 27h.1c122.8 0 222.6-99.7 222.6-222.6 0-59.4-23.1-115.2-65.7-157.7zM224 438.7h-.1c-34.1 0-67.5-9.2-96.6-26.6l-6.9-4.1-69.2 18.2 18.5-67.4-4.5-7.1c-18.5-29.4-28.2-63.3-28.2-98 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 35.4 35.3 54.9 82.1 54.9 131.7-.1 101.8-82.9 184.7-184.9 184.7z"/></svg>',
            'x' => '<svg viewBox="0 0 512 512" aria-hidden="true"><path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48h145.6l100.5 132.9L389.2 48zm-24.8 373.8h39.1L151.1 88h-42L364.4 421.8z"/></svg>',
            'linkedin' => '<svg viewBox="0 0 448 512" aria-hidden="true"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>',
            'bookmark' => '<svg viewBox="0 0 384 512" aria-hidden="true"><path d="M0 48C0 21.5 21.5 0 48 0h288c26.5 0 48 21.5 48 48v464L192 400 0 512z"/></svg>',
        ];
        return $icons[ $name ] ?? '';
    }

    protected function render() {
        if ( ! is_singular( 'post' ) && ! get_the_ID() ) { return; }
        $s = $this->get_settings_for_display();
        $post_id = get_the_ID();
        $cats = get_the_category( $post_id );
        $main_cat = ! empty( $cats ) ? $cats[0] : null;
        $subtitle = Helpers::post_subtitle( $post_id );
        $author_byline = Helpers::author_byline( $post_id, $s['author_prefix'] ?? __( 'Por', 'nsfcapital-widgets' ) );
        $author_id = (int) get_post_field( 'post_author', $post_id );
        $author_name = trim( (string) get_the_author_meta( 'display_name', $author_id ) );
        $share_url = rawurlencode( get_permalink( $post_id ) );
        $share_title = rawurlencode( get_the_title( $post_id ) );
        ?>
        <div class="nsfcapital-scope"><div class="container"><article class="note-wrap"><div class="note-main">
            <?php if ( 'yes' === ( $s['show_breadcrumb'] ?? 'yes' ) ) : ?>
                <nav class="note-breadcrumb" aria-label="<?php echo esc_attr__( 'Migas', 'nsfcapital-widgets' ); ?>">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $s['breadcrumb_home_text'] ?: __( 'Inicio', 'nsfcapital-widgets' ) ); ?></a>
                    <?php if ( $main_cat ) : ?><span class="sep">›</span><a href="<?php echo esc_url( get_category_link( $main_cat->term_id ) ); ?>"><?php echo esc_html( $main_cat->name ); ?></a><?php endif; ?>
                </nav>
            <?php endif; ?>
            <h1 class="note-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
            <?php if ( $subtitle ) : ?><p class="note-subtitle"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
            <?php if ( 'yes' === ( $s['show_author'] ?? 'yes' ) && '' !== $author_byline ) : ?>
                <div class="note-author-row"><div class="author-block"><div class="author-avatar"><?php echo esc_html( mb_substr( $author_name, 0, 2 ) ); ?></div><span class="author-name"><?php echo esc_html( $author_byline ); ?></span></div></div>
            <?php endif; ?>
            <?php if ( 'yes' === ( $s['show_date'] ?? 'yes' ) ) : ?><p class="note-date"><?php echo esc_html( get_the_date( 'j M, Y · H:i', $post_id ) ); ?></p><?php endif; ?>
            <?php if ( 'yes' === ( $s['show_share'] ?? '' ) ) : ?>
                <div class="share-row">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $share_url ); ?>" class="share-btn" aria-label="Facebook" target="_blank" rel="noopener"><?php echo $this->icon_svg( 'facebook' ); ?></a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $share_title . '%20' . $share_url ); ?>" class="share-btn" aria-label="WhatsApp" target="_blank" rel="noopener"><?php echo $this->icon_svg( 'whatsapp' ); ?></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&text=<?php echo esc_attr( $share_title ); ?>" class="share-btn" aria-label="X" target="_blank" rel="noopener"><?php echo $this->icon_svg( 'x' ); ?></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_attr( $share_url ); ?>" class="share-btn" aria-label="LinkedIn" target="_blank" rel="noopener"><?php echo $this->icon_svg( 'linkedin' ); ?></a>
                    <span class="share-divider" aria-hidden="true"></span>
                    <?php if ( 'yes' === ( $s['show_save'] ?? 'yes' ) ) : ?><a href="#" class="share-save"><?php echo $this->icon_svg( 'bookmark' ); ?> <?php echo esc_html__( 'Guardar', 'nsfcapital-widgets' ); ?></a><?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if ( has_post_thumbnail( $post_id ) ) : ?><figure class="note-figure"><?php echo get_the_post_thumbnail( $post_id, 'large' ); ?><?php $cap = get_post_meta( $post_id, '_news_caption', true ); if ( $cap ) : ?><figcaption class="note-figcaption"><?php echo esc_html( $cap ); ?></figcaption><?php endif; ?></figure><?php endif; ?>
            <div class="note-body"><?php the_content(); ?></div>
        </div></article></div></div>
        <?php
    }
}
