<?php
/*
Plugin Name: Custom Content Builder
Description: A module in which you can enter the desired fields and use the shortcode to place them in the appropriate place in the content.
Version: 1.1
Author: Ali Hashemi
*/


define( 'MY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

include_once( MY_PLUGIN_DIR . 'includes/class-custom-content-builder-config.php' );


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Custom_Content_Builder' ) ) {

    class Custom_Content_Builder {
		private $config = false;
        public function __construct() {
			$this->config = new Custom_Content_Builder_Config();
            add_action( 'admin_menu', array( $this, 'add_menu' ) );
            add_action( 'admin_init', array( $this, 'register_settings' ) );
            add_shortcode( 'custom_content_builder', array( $this, 'shortcode' ) );
        }

        public function add_menu() {
            add_menu_page(
                'Custom Content Builder',
                'محتوای سفارشی',
                'manage_options',
                'custom_content_builder',
                array( $this, 'settings_page' )
            );
        }

        public function settings_page() {
            ?>
			<style>
			h1, h2 {
				font-family: Tahoma,Arial,sans-serif !important;
			}
			.so-main .form-table{
				display: none;
			}
			.form-table:first-of-type {
				display: block;
			}			
			.so-main h2{
				border: 1px solid #ccc;
				background-color: #e4e4e4;
				margin:0;
				padding: 10px;				
				cursor: pointer;
			}
			.so-main h2.active{
				border: 1px solid #aaa;
				background-color: #f5f5f5;				
				border-bottom: 0;
			}
			.so-main .form-table{
				border: 1px solid #aaa;
				background-color: #f5f5f5;
				margin:0;
				border-top: 0;
			}
			.so-main .form-table th{
				padding-right:10px;
			}
			</style>
			<script>
				document.addEventListener('DOMContentLoaded', () => {
					let find_first_h2 = true;
					document.querySelectorAll('.so-main h2').forEach(button => {
						if(find_first_h2){
							button.classList.add('active');
						}
						find_first_h2 = false;
						button.addEventListener('click', () => {
							// Hide all samplediv elements
							document.querySelectorAll('.so-main .form-table').forEach(div => {
								div.style.display = 'none';
							});
							
							document.querySelectorAll('.so-main h2').forEach(div => {
								div.classList.remove('active'); 
							});					

							button.classList.add('active');							

							// Show the next samplediv element
							const nextDiv = button.nextElementSibling;
							if (nextDiv && nextDiv.classList.contains('form-table')) {
								nextDiv.style.display = 'block';
							}
						});
					});
				});			

			</script>			
            <div class="wrap so-main">
                <h1>محتوای سفارشی</h1>
                <form method="post" action="options.php" style="margin-top:10px;">					
                    <?php settings_fields( 'custom_content_builder_options' ); ?>					
					<?php do_settings_sections( 'custom_content_builder' ); ?>					
					<?php submit_button(); ?>
                </form>
            </div>
            <?php
        }

        public function register_settings() {
			foreach($this->config->sections as $section){
				foreach($section['fields'] as $field){
					register_setting( 'custom_content_builder_options', 'custom_content_builder_' . $field['id']);
				}
			}
			
			foreach($this->config->sections as $section){
				add_settings_section(
					'custom_content_builder_section_' . $section['id'],
					$section['label'],
					null,
					'custom_content_builder'
				);		
				
				foreach($section['fields'] as $field){
					//add_settings_field( string $id, string $title, callable $callback, string $page, string $section = ‘default’, array $args = array() )
					add_settings_field(
						'custom_content_builder_' . $field['id'],
						$field['label'],
						array( $this, 'field_callback'),
						'custom_content_builder',
						'custom_content_builder_section_' . $section['id'],
						array( 'section_id' => $section['id'],'field_id' => $field['id'] )
					);					
				}				
			}
			
        }


        public function field_callback($input_arg) {
			$section_id = $input_arg['section_id'];
			$field_id = $input_arg['field_id'];
			$field_obj = false;
			foreach($this->config->sections as $section){
				if($section['id'] == $section_id){
					foreach($section['fields'] as $field){
						if($field['id'] == $field_id){
							$field_obj = $field;
						}
					}
				}
			}			

			if($field_obj){
				if($field_obj['type'] == 'textbox'){
					$value = get_option( 'custom_content_builder_' . $field_obj['id'], '' );
					echo '<input type="text" name="custom_content_builder_' . $field_obj['id'] . '" value="' .  esc_attr( $value ) . '" />';
				}
				if($field_obj['type'] == 'textarea'){
					$value = get_option( 'custom_content_builder_' . $field_obj['id'], '' );
					echo '<textarea style="width:600px;" name="custom_content_builder_' . $field_obj['id'] . '">' . esc_textarea( $value ) . '</textarea>';
				}				
				if($field_obj['type'] == 'dropdown'){
					$value = get_option( 'custom_content_builder_' . $field_obj['id'], '' );
					$check_default = ($value == '');
					$html_temp = '<select name="custom_content_builder_' . $field_obj['id'] . '">';
					foreach($field_obj['options'] as $option){
						$is_select = ($check_default && isset($option['is_default']) && $option['is_default']);
						if($option['value'] == $value) $is_select = true;
						$html_temp .= '<option ' . ($is_select  ? 'selected="selected"' : '') . ' value="' . $option['value'] . '">' . $option['text'] . '</option>';
					}					
					$html_temp .= '</select>';
					echo $html_temp;
				}				
				if(isset($field_obj['hint']) && $field_obj['hint'] != '')
					echo '<p class="description">' . $field_obj['hint'] . '</p>';					
			}

        }

        public function shortcode() {
			wp_enqueue_style('my-module-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
			wp_enqueue_script('my-module-script', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), null, true);
			
            $textbox = get_option( 'custom_content_builder_textbox', '' );
            $textarea = get_option( 'custom_content_builder_textarea', '' );

            return '<div class="mcm_main">
                        <p>Textbox: ' . esc_html( $textbox ) . '</p>
                        <p>Textarea: ' . nl2br( esc_html( $textarea ) ) . '</p>
                    </div>';
        }
    }

    new Custom_Content_Builder();
}