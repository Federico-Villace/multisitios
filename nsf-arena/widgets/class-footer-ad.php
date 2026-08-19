<?php
namespace NSFARENA\Widgets;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Footer_Ad extends Banner_Ad {
    public function get_name() { return 'nsfarena_footer_ad'; }
    public function get_title() { return esc_html__( 'Banner Footer', 'nsfarena-widgets' ); }
    public function get_icon() { return 'eicon-footer'; }
}
