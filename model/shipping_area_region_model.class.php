<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_area_region_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'area_region';
		parent::__construct();
	}



}

// end