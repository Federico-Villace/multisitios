<?php
namespace NSFMERIDIANO\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Middle_Ad extends Banner_Ad {
    public function get_name() { return 'nsfmeridiano_middle_ad'; }
    public function get_title() { return esc_html__( 'Banner Medio de Página', 'nsfmeridiano-widgets' ); }
    public function get_icon() { return 'eicon-section'; }
}
