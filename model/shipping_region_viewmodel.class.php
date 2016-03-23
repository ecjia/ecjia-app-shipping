<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_region_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'area_region';
		$this->table_alias_name	= 'a';
		
		$this->view = array(
				'region' => array(
						'type' =>Component_Model_View::TYPE_LEFT_JOIN, 
						'alias' => 'r',
						'field' => 'r.region_name', 
						'on' => 'a.region_id = r.region_id',
				)
		);
		
		parent::__construct();
	}
	
}

// end