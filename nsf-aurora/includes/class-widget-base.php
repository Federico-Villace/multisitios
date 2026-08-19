<?php
namespace NSFAURORA;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Widget_Base extends \Elementor\Widget_Base {
    public function get_categories() {
        return [ 'nsfaurora-widgets' ];
    }

    public function get_style_depends() {
        return [ 'nsfaurora-widgets-frontend' ];
    }

    public function get_script_depends() {
        return [ 'nsfaurora-widgets-frontend' ];
    }

    public function is_reload_preview_required() {
        return true;
    }

    protected function add_query_controls( $default = 4 ) {
        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__( 'Cantidad de notas', 'nsfaurora-widgets' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => $default,
                'min'     => 1,
                'max'     => 24,
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__( 'Categorías', 'nsfaurora-widgets' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => Helpers::get_categories_options(),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__( 'Ordenar por', 'nsfaurora-widgets' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'     => esc_html__( 'Fecha', 'nsfaurora-widgets' ),
                    'views'    => esc_html__( 'Vistas', 'nsfaurora-widgets' ),
                    'comments' => esc_html__( 'Comentarios', 'nsfaurora-widgets' ),
                    'rand'     => esc_html__( 'Aleatorio', 'nsfaurora-widgets' ),
                ],
            ]
        );
    }

    protected function build_query( array $settings ) {
        $args = [
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 4,
            'ignore_sticky_posts' => true,
        ];

        if ( ! empty( $settings['categories'] ) ) {
            $args['category__in'] = array_map( 'absint', (array) $settings['categories'] );
        }

        switch ( $settings['orderby'] ?? 'date' ) {
            case 'views':
                $args['meta_key'] = 'post_views_count';
                $args['orderby']  = 'meta_value_num';
                $args['order']    = 'DESC';
                break;
            case 'comments':
                $args['orderby'] = 'comment_count';
                $args['order']   = 'DESC';
                break;
            case 'rand':
                $args['orderby'] = 'rand';
                break;
            default:
                $args['orderby'] = 'date';
                $args['order']   = 'DESC';
        }

        return new \WP_Query( $args );
    }

    protected function render_card( $post_id, $args = [] ) {
        $args = wp_parse_args(
            $args,
            [
                'image'       => true,
                'square'      => true,
                'title_tag'   => 'h3',
                'show_author' => true,
                'show_video'  => false,
                'show_date'   => false,
                'author_prefix' => 'Por',
            ]
        );

        $title_tag = in_array( $args['title_tag'], [ 'h1', 'h2', 'h3', 'h4' ], true ) ? $args['title_tag'] : 'h3';
        $meta_parts = [];
        if ( ! empty( $args['show_author'] ) ) {
            $byline = Helpers::author_byline( $post_id, $args['author_prefix'] ?? __( 'Por', 'nsfaurora-widgets' ) );
            if ( '' !== $byline ) { $meta_parts[] = $byline; }
        }
        if ( ! empty( $args['show_date'] ) ) {
            $meta_parts[] = get_the_date( '', $post_id );
        }
        ?>
        <article class="card">
            <?php if ( ! empty( $args['image'] ) ) : ?>
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
                    <?php if ( has_post_thumbnail( $post_id ) ) : ?>
                        <?php echo get_the_post_thumbnail( $post_id, 'medium_large', [ 'class' => 'card-img-real', 'loading' => 'lazy' ] ); ?>
                    <?php else : ?>
                        <div class="card-img <?php echo ! empty( $args['square'] ) ? 'sq ' : ''; ?><?php echo esc_attr( Helpers::card_image_class( $post_id ) ); ?>">
                            <?php if ( ! empty( $args['show_video'] ) ) : ?><span class="video-play" aria-hidden="true"></span><?php endif; ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            <<?php echo esc_attr( $title_tag ); ?> class="card-title"><a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a></<?php echo esc_attr( $title_tag ); ?>>
            <?php if ( ! empty( $meta_parts ) ) : ?>
                <p class="card-byline"><?php echo esc_html( implode( ' · ', $meta_parts ) ); ?></p>
            <?php endif; ?>
        </article>
        <?php
    }
}
