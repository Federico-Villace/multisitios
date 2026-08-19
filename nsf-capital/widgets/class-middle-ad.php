<?php
namespace NSFCAPITAL\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Middle_Ad extends Banner_Ad {
    public function get_name() { return 'nsfcapital_middle_ad'; }
    public function get_title() { return esc_html__( 'Banner Medio de Página', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-section'; }
}
