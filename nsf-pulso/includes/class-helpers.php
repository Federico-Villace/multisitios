<?php
namespace NSFPULSO;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Helpers {
    public static function get_categories_options() {
        $options = [];
        $terms = get_terms([
            'taxonomy'   => 'category',
            'hide_empty' => false,
        ]);
        if ( is_wp_error( $terms ) ) {
            return $options;
        }
        foreach ( $terms as $term ) {
            $options[ $term->term_id ] = $term->name;
        }
        return $options;
    }


    /**
     * Opciones de menús creados en Apariencia > Menús.
     * Se usa en widgets que necesitan imprimir wp_nav_menu() por ID.
     *
     * @return array<int|string,string>
     */
    public static function get_menus_options() {
        $options = [ '' => esc_html__( 'Usar ítems fallback', 'nsfpulso-widgets' ) ];

        $menus = wp_get_nav_menus();
        if ( empty( $menus ) || is_wp_error( $menus ) ) {
            return $options;
        }

        foreach ( $menus as $menu ) {
            if ( isset( $menu->term_id, $menu->name ) ) {
                $options[ absint( $menu->term_id ) ] = $menu->name;
            }
        }

        return $options;
    }

    public static function get_menu_locations_options() {
        $locations = get_registered_nav_menus();
        $options = [ '' => esc_html__( 'No usar menú', 'nsfpulso-widgets' ) ];
        foreach ( $locations as $key => $label ) {
            $options[ $key ] = $label;
        }
        return $options;
    }

    /**
     * Construye una línea de autor segura. Evita imprimir "Por" vacío.
     */
    public static function author_byline( $post_id, $prefix = 'Por' ) {
        $author_id = (int) get_post_field( 'post_author', $post_id );
        $author = trim( (string) get_the_author_meta( 'display_name', $author_id ) );
        if ( '' === $author ) {
            return '';
        }
        $prefix = trim( (string) $prefix );
        return '' === $prefix ? $author : sprintf( '%s %s', $prefix, $author );
    }

    public static function trim_words( $text, $words = 18 ) {
        $text = wp_strip_all_tags( (string) $text );
        return wp_trim_words( $text, absint( $words ), '…' );
    }

    public static function post_subtitle( $post_id ) {
        $subtitle = get_post_meta( $post_id, '_news_subtitle', true );
        if ( empty( $subtitle ) ) {
            $subtitle = get_the_excerpt( $post_id );
        }
        return $subtitle;
    }

    public static function card_image_class( $post_id ) {
        $n = ( absint( $post_id ) % 15 ) + 1;
        return 's' . $n;
    }
}
