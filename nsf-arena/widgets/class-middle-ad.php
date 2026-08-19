<?php
namespace NSFARENA\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Middle_Ad extends Banner_Ad {
    public function get_name() { return 'nsfarena_middle_ad'; }
    public function get_title() { return esc_html__( 'Banner Medio de Página', 'nsfarena-widgets' ); }
    public function get_icon() { return 'eicon-section'; }
}
