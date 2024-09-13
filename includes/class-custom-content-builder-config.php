<?php 
class Custom_Content_Builder_Config{
		public $sections = array();
		
		public function __construct() {
			$section_temp = array('fields'=>array(),'id'=>'setting','label'=>'تنظیمات');
			$section_temp['fields'][] = array(
				'type' => 'textbox',
				'id' => 'telephone',
				'label' => 'تلفن',
				'hint' => 'لطفا تلفن همراه خود را وارد کنید.'
				);
				$section_temp['fields'][] = array(
				'type' => 'textarea',
				'id' => 'address',
				'label' => 'آدرس',
				'hint' => 'لطفا آدرس دفتر کار خود را وارد کنید.'	
				);				
			$this->sections[] = $section_temp;

			$section_temp = array('fields'=>array(),'id'=>'aboutus','label'=>'درباره ما');
			$section_temp['fields'][] = array(
				'type' => 'textarea',
				'id' => 'aboutus_content',
				'label' => 'شرح'			
				);	
			$section_temp['fields'][] = array(
				'type' => 'dropdown',
				'id' => 'aboutus_cipy',
				'label' => 'شهر',	
				'hint'  => 'شهر محل سکونت خود را انتخاب کنید.',
				'options' => array(
						array('text'=>'تهران','value'=>'tehran'),
						array('text'=>'اصفهان','value'=>'esfahan'),
						array('text'=>'شیراز','value'=>'shiraz','is_default'=>true),
					)
				);					
			$this->sections[] = $section_temp;						
		}
	}
