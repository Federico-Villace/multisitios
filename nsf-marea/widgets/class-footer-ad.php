<?php
namespace NSFMAREA\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Footer_Ad extends Banner_Ad {
    public function get_name() { return 'nsfmarea_footer_ad'; }
    public function get_title() { return esc_html__( 'Banner Footer', 'nsfmarea-widgets' ); }
    public function get_icon() { return 'eicon-footer'; }
}
