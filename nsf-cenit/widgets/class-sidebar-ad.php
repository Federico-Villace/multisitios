<?php
namespace NSFCENIT\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Sidebar_Ad extends Banner_Ad {
    public function get_name() { return 'nsfcenit_sidebar_ad'; }
    public function get_title() { return esc_html__( 'Banner Sidebar', 'nsfcenit-widgets' ); }
    public function get_icon() { return 'eicon-sidebar'; }
}
