<?php
namespace NSFAURORA\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Middle_Ad extends Banner_Ad {
    public function get_name() { return 'nsfaurora_middle_ad'; }
    public function get_title() { return esc_html__( 'Banner Medio de Página', 'nsfaurora-widgets' ); }
    public function get_icon() { return 'eicon-section'; }
}
