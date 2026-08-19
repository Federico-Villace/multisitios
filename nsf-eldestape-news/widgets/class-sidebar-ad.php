<?php
namespace NSFELDESTAPE\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Sidebar_Ad extends Banner_Ad {
    public function get_name() { return 'nsfeldestape_sidebar_ad'; }
    public function get_title() { return esc_html__( 'Banner Sidebar', 'nsfeldestape-widgets' ); }
    public function get_icon() { return 'eicon-sidebar'; }
}
