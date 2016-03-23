<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_area_region_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'shipping_area';
		$this->table_alias_name	= 'sa';
		
		$this->view = array(
				'area_region' => array(
						'type' =>Component_Model_View::TYPE_LEFT_JOIN, 
						'alias' => 'ar',
						'field' => 'ar.shipping_area_id', 
						'on' => 'sa.shipping_area_id = ar.shipping_area_id',
				)
		);
		
		parent::__construct();
	}

}

// end