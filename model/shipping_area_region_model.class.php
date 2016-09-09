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
	
	public function shipping_area_region_insert($data) {
//		return $this->insert($data);
		return RC_DB::table('area_region')->insert($data);
	}
	
	public function shipping_area_region_remove($where, $in=false) {
//		if ($in) {
//			return $this->in($where)->delete();
//		}
//		return $this->where($where)->delete();
		$db_area_region = RC_DB::table('area_region');
		if ($in) {
			if (!empty($where)){
				foreach($where as $key => $val){
					$db_area_region->whereIn($key, array($val));
				}
			}
			return $db_area_region->delete();
		}
		if (!empty($where)){
			foreach($where as $key => $val){
				$db_area_region->where($key, $val);
			}
		}
		return $db_area_region->delete();

	}
}

// end