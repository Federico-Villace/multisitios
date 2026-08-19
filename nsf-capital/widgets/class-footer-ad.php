<?php
namespace NSFCAPITAL\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Footer_Ad extends Banner_Ad {
    public function get_name() { return 'nsfcapital_footer_ad'; }
    public function get_title() { return esc_html__( 'Banner Footer', 'nsfcapital-widgets' ); }
    public function get_icon() { return 'eicon-footer'; }
}
