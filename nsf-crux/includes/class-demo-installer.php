<?php
namespace NSFCRUX;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Demo_Installer {
    private $option_key = 'nsfcrux_demo_ids';

    public function install() {
        $existing = get_option( $this->option_key, [] );
        if ( ! empty( $existing ) ) {
            $this->delete();
        }

        $ids = [
            'categories' => [],
            'posts'      => [],
            'pages'      => [],
            'menus'      => [],
        ];

        $categories = [ 'Policiales', 'Política', 'Mundo', 'Paranormal', 'Salud', 'Tecnología', 'Opinión', 'Sociedad', 'Cine y Series', 'Cocina', 'Insólitas', 'Videos', 'Horóscopo' ];
        foreach ( $categories as $cat_name ) {
            $term = term_exists( $cat_name, 'category' );
            if ( ! $term ) {
                $term = wp_insert_term( $cat_name, 'category' );
            }
            if ( ! is_wp_error( $term ) ) {
                $term_id = is_array( $term ) ? absint( $term['term_id'] ) : absint( $term );
                $ids['categories'][] = $term_id;
            }
        }

        $cat_map = [];
        foreach ( $ids['categories'] as $term_id ) {
            $term = get_term( $term_id, 'category' );
            if ( $term && ! is_wp_error( $term ) ) {
                $cat_map[ $term->name ] = $term_id;
            }
        }

        $posts = [
            [ 'La Justicia rechazó citar a declarar a un dirigente por una liberación irregular', 'Política', 'Redacción ' ],
            [ 'Una alta funcionaria criticó al Gobierno por la compra de un avión en mal estado', 'Política', 'Nadia Campos' ],
            [ 'El jefe de la Armada reclamó mayor inversión y alertó por los recursos marítimos', 'Política', 'Julián Rivas' ],
            [ 'Vecinos denunciaron una ola de robos y pidieron más patrullajes en la zona', 'Policiales', 'Martín López' ],
            [ 'Misterio en el conurbano: aseguran haber visto luces extrañas sobre una plaza', 'Paranormal', 'Sofía Vega' ],
            [ 'Salud: cómo prevenir enfermedades respiratorias durante los primeros fríos', 'Salud', 'Carla Méndez' ],
            [ 'Tecnología barrial: una app vecinal ayuda a reportar problemas de seguridad', 'Tecnología', 'Diego Morales' ],
            [ 'Cine y Series: los estrenos populares más esperados de la semana', 'Cine y Series', 'Lucía Bravo' ],
            [ 'Cocina económica: tres recetas para resolver la cena con poco presupuesto', 'Cocina', 'Marta Alonso' ],
            [ 'Insólitas: entró al supermercado con una cabra y se volvió viral', 'Insólitas', 'Redacción' ],
            [ 'El barrio TV prepara una cobertura especial con móviles en vivo', 'Videos', 'Equipo audiovisual' ],
            [ 'Horóscopo de hoy: signo por signo, qué te espera este lunes', 'Horóscopo', 'Astróloga d' ]
        ];

        foreach ( $posts as $item ) {
            $category_id = $cat_map[ $item[1] ] ?? 0;
            $post_id = wp_insert_post(
                [
                    'post_title'    => sanitize_text_field( $item[0] ),
                    'post_status'   => 'publish',
                    'post_type'     => 'post',
                    'post_excerpt'  => sanitize_text_field( 'Nota demo para probar la estética editorial con cards y portada de alto impacto, los listados por sección y los módulos dinámicos de .' ),
                    'post_content'  => wp_kses_post( $this->demo_content( $item[0] ) ),
                    'post_category' => $category_id ? [ $category_id ] : [],
                    'meta_input'    => [
                        '_nsfcrux_demo'      => 1,
                        '_news_subtitle'   => 'Texto bajada demo para probar el módulo de nota individual y los listados del portal.',
                        '_news_caption'    => 'Imagen ilustrativa generada por la maqueta demo.',
                        'post_views_count' => rand( 50, 3500 ),
                    ],
                ],
                true
            );
            if ( ! is_wp_error( $post_id ) ) {
                $ids['posts'][] = absint( $post_id );
            }
        }

        $pages = [
            ' Demo Completo' => '[nsfcrux_demo view="cat" switcher="yes"]',
                        ' Nota'          => '[nsfcrux_demo view="nota" switcher="no"]',
            ' Categoría'     => '[nsfcrux_demo view="cat" switcher="no"]',
        ];

        foreach ( $pages as $title => $shortcode ) {
            $page_id = wp_insert_post(
                [
                    'post_title'   => sanitize_text_field( $title ),
                    'post_type'    => 'page',
                    'post_status'  => 'publish',
                    'post_content' => $shortcode,
                ],
                true
            );
            if ( ! is_wp_error( $page_id ) ) {
                update_post_meta( $page_id, '_nsfcrux_demo_page', 1 );
                $ids['pages'][] = absint( $page_id );
            }
        }



        $blocks_page_id = wp_insert_post(
            [
                'post_title'   => ' Home · Bloques nuevos',
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_content' => '',
            ],
            true
        );
        if ( ! is_wp_error( $blocks_page_id ) ) {
            update_post_meta( $blocks_page_id, '_nsfcrux_demo_page', 1 );
            update_post_meta( $blocks_page_id, '_wp_page_template', 'elementor_canvas' );
            update_post_meta( $blocks_page_id, '_elementor_edit_mode', 'builder' );
            update_post_meta( $blocks_page_id, '_elementor_template_type', 'wp-page' );
            update_post_meta( $blocks_page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
            update_post_meta( $blocks_page_id, '_elementor_data', wp_slash( wp_json_encode( $this->elementor_blocks_data() ) ) );
            $ids['pages'][] = absint( $blocks_page_id );
        }

        $menu_id = wp_create_nav_menu( ' Demo' );
        if ( ! is_wp_error( $menu_id ) ) {
            foreach ( [ 'Policiales', 'Política', 'Mundo', 'Paranormal', 'Salud', 'Tecnología', 'Sociedad' ] as $name ) {
                if ( ! empty( $cat_map[ $name ] ) ) {
                    wp_update_nav_menu_item(
                        $menu_id,
                        0,
                        [
                            'menu-item-title'     => $name,
                            'menu-item-object'    => 'category',
                            'menu-item-object-id' => $cat_map[ $name ],
                            'menu-item-type'      => 'taxonomy',
                            'menu-item-status'    => 'publish',
                        ]
                    );
                }
            }
            $ids['menus'][] = absint( $menu_id );
        }

        update_option( $this->option_key, $ids, false );
        return $ids;
    }

    public function delete() {
        $ids = get_option( $this->option_key, [] );

        foreach ( (array) ( $ids['posts'] ?? [] ) as $post_id ) {
            wp_delete_post( absint( $post_id ), true );
        }
        foreach ( (array) ( $ids['pages'] ?? [] ) as $page_id ) {
            wp_delete_post( absint( $page_id ), true );
        }
        foreach ( (array) ( $ids['menus'] ?? [] ) as $menu_id ) {
            wp_delete_nav_menu( absint( $menu_id ) );
        }
        foreach ( (array) ( $ids['categories'] ?? [] ) as $term_id ) {
            $term_id = absint( $term_id );
            $term = get_term( $term_id, 'category' );
            if ( $term && ! is_wp_error( $term ) && 0 === (int) $term->count ) {
                wp_delete_term( $term_id, 'category' );
            }
        }

        delete_option( $this->option_key );
    }


    private function elementor_blocks_data() {
        $section = function( $widgets ) {
            return [
                'id'       => substr( md5( wp_json_encode( $widgets ) . microtime( true ) ), 0, 7 ),
                'elType'   => 'section',
                'settings' => [ 'layout' => 'full_width', 'gap' => 'default' ],
                'elements' => [
                    [
                        'id'       => substr( md5( 'col' . wp_json_encode( $widgets ) ), 0, 7 ),
                        'elType'   => 'column',
                        'settings' => [ '_column_size' => 100 ],
                        'elements' => $widgets,
                    ],
                ],
            ];
        };
        $widget = function( $type, $settings = [] ) {
            return [
                'id'         => substr( md5( $type . wp_json_encode( $settings ) . microtime( true ) ), 0, 7 ),
                'elType'     => 'widget',
                'widgetType' => $type,
                'settings'   => $settings,
                'elements'   => [],
            ];
        };
        return [
            $section( [ $widget( 'nsfcrux_header_news', [] ) ] ),
            $section( [ $widget( 'nsfcrux_market_indicators', [ 'items' => [ 'oficial', 'tarjeta', 'blue', 'mep', 'ccl', 'riesgo' ], 'source_text' => 'Fuente: DolarAPI / ArgentinaDatos' ] ) ] ),
            $section( [ $widget( 'nsfcrux_middle_ad', [ 'placement' => 'middle', 'format' => 'leaderboard', 'label' => 'PUBLICIDAD', 'placeholder_text' => '728 × 90' ] ) ] ),
            $section( [ $widget( 'nsfcrux_latest_box', [ 'title' => 'Lo Último', 'section_label' => 'Argentina', 'posts_per_page' => 5 ] ) ] ),
            $section( [ $widget( 'nsfcrux_news_row', [ 'posts_per_page' => 4, 'show_opinion_badge' => 'yes' ] ) ] ),
            $section( [ $widget( 'nsfcrux_middle_ad', [ 'placement' => 'middle', 'format' => 'billboard', 'label' => 'PUBLICIDAD', 'placeholder_text' => '970 × 250' ] ) ] ),
            $section( [ $widget( 'nsfcrux_feature_split', [ 'source' => 'manual', 'title' => 'La noticia d que todos están comentando', 'text' => 'Un bloque destacado para campañas, especiales, videos o informes de alto impacto con estética editorial con cards y portada de alto impacto y foco visual.' ] ) ] ),
            $section( [ $widget( 'nsfcrux_footer_ad', [ 'placement' => 'footer', 'format' => 'footer-wide', 'label' => 'PUBLICIDAD', 'placeholder_text' => '970 × 120' ] ) ] ),
            $section( [ $widget( 'nsfcrux_footer_news', [] ) ] ),
        ];
    }

    private function demo_content( $title ) {
        return '<p>' . esc_html( $title ) . ' es una nota demo creada automáticamente para probar la experiencia popular de .</p>'
            . '<p>El contenido permite verificar bajadas, grillas, autores, módulos relacionados, sidebar, banners publicitarios y estilos responsive dentro de Elementor.</p>'
            . '<h2>Contexto de la noticia</h2>'
            . '<p>La maqueta usa una estética editorial con cards y portada de alto impacto, con banda roja de sección, cards con foto cuadrada, menú lateral amplio y módulos publicitarios.</p>'
            . '<blockquote>Este bloque sirve para validar citas destacadas y jerarquía tipográfica.</blockquote>'
            . '<p>Podés reemplazar estos textos por contenido real desde el editor nativo de WordPress o mediante carga masiva.</p>';
    }
}
