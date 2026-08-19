<?php
namespace NSFMERIDIANO\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Footer_Ad extends Banner_Ad {
    public function get_name() { return 'nsfmeridiano_footer_ad'; }
    public function get_title() { return esc_html__( 'Banner Footer', 'nsfmeridiano-widgets' ); }
    public function get_icon() { return 'eicon-footer'; }
}
