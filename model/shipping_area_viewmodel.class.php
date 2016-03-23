<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_area_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'shipping';
		$this->table_alias_name	= 's';
		
		$this->view = array(
				'shipping_area' => array(
						'type' =>Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'a',
						'field' => 'shipping_name, shipping_code, support_cod',
						'on' => 'a.shipping_id = s.shipping_id ', 
				),
		);
		
		parent::__construct();
	}
	
	

}

// end