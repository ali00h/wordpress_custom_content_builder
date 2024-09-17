# Wordpress Custom Content Builder

This is a WordPress plugin that you can use to create your own custom plugin. 

You can define one or more HTML templates as shortcodes. You can also define as many fields as you want for the admin and display these fields in the templates.

# Install
Just download plugin files and put them in `wp-content\plugins\custom-content-builder` directory

# Usage

## Fileds

For example we want to create telephone,address,description and city Fields.

edit `includes\class-custom-content-builder-config.php` file and write this:

```
<?php 
class Custom_Content_Builder_Config{
		public $sections = array();
		
		public function __construct() {
			$section_temp = array('fields'=>array(),'id'=>'setting','label'=>'Setting');
			$section_temp['fields'][] = array(
				'type' => 'textbox',
				'id' => 'telephone',
				'label' => 'Your Phone',
				'hint' => 'Please enter your phone'
				);
				$section_temp['fields'][] = array(
				'type' => 'textarea',
				'id' => 'address',
				'label' => 'Your Address',
				'hint' => 'Please enter your address'	
				);				
			$this->sections[] = $section_temp;

			$section_temp = array('fields'=>array(),'id'=>'aboutus','label'=>'AboutUs');
			$section_temp['fields'][] = array(
				'type' => 'textarea',
				'id' => 'aboutus_content',
				'label' => 'Description'			
				);	
			$section_temp['fields'][] = array(
				'type' => 'dropdown',
				'id' => 'aboutus_cipy',
				'label' => 'City',	
				'hint'  => 'Please enter the city',
				'options' => array(
						array('text'=>'Mexico City','value'=>'mexicocity'),
						array('text'=>'Tokyo','value'=>'tokyo'),
						array('text'=>'Delhi','value'=>'delhi','is_default'=>true),
					)
				);					
			$this->sections[] = $section_temp;						
		}
	}

```
In this example we create textbox,textarea and dropdown fields. and we put these fields in two sections. 


## ShortCodes
Each shortcode should be in seperate file. For example if you want to use `[main_page]` shortcode, you should create main_page.php file in `shortcode` directory and put this code on it:
```
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
```
You can use your custom css and js file on it. In this example we put css and js files in assets directory.
