<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_order_viewmodel extends Component_Model_View {
	public $table_name = '';
	public $view = array();
	
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'shipping';
		$this->table_alias_name = 'sp';
		
		$this->view = array(
				'order_info' => array(
						'type' =>Component_Model_View::TYPE_LEFT_JOIN,   
						'alias'=> 'i',
						'field'=> 'sp.shipping_id, sp.shipping_name AS ship_name, i.shipping_time, COUNT(i.order_id) AS order_num',
						'on' => 'sp.shipping_id = i.shipping_id', 
				)
		);
		
		parent::__construct();
	}
	
}

// end