<?php
class main_page_shortcode{
    public static function callback( $atts, $content = null, $shortcode_tag ) {
        wp_enqueue_style('my-module-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        wp_enqueue_script('my-module-script', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), null, true);

        $atts = shortcode_atts( array(
            'param1' => 'default1',
            'param2' => 'default2',
        ), $atts, $shortcode_tag );

        $param1 = $atts['param1'];
        $param2 = $atts['param2'];

        $textbox = get_option( 'custom_content_builder_telephone', '' );
        $textarea = get_option( 'custom_content_builder_aboutus_cipy', '' );

        return '<div class="mcm_main">
                    <p>Textbox: ' . esc_html( $textbox ) . '</p>
                    <p>Textarea: ' . nl2br( esc_html( $textarea ) ) . '</p>
                </div>';    
    }
}
?>