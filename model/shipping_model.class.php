<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'shipping';
		parent::__construct();
	}
	
	public function shipping_find($where, $field='*') {
//		return $this->where($where)->field($field)->find();

		$shipping = RC_DB::table('shipping');
		if (is_array($where)){
			foreach($where as $key => $val){
				$shipping->where($key, $val);
			}
		}
		if($field != '*'){
			return $shipping->select($field)->first();
		}else{
			return $shipping->first();
		}
	}
	
	public function shipping_field($where, $field) {
//		return $this->where($where)->get_field($field);

		$shipping = RC_DB::table('shipping');
		if(is_array($where)){
			foreach($where as $key => $val){
				$shipping->where($key, $val);
			}
		}
		return $shipping->pluck($field);
	}
	
	public function shipping_select($field='*', $where='', $order='') {
//		return $this->field($field)->order($order)->select();
		if(empty($order)){
			return RC_DB::table('shipping')->get();
		}else{
			return RC_DB::table('shipping')->orderBy($order)->select($field)->get();
		}
	}
	
	public function is_only($where) {
//		return $this->where($where)->count();

		$shipping = RC_DB::table('shipping');
		if (!empty($where)) {
			foreach ($where as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $key => $val) {
						if ($key == 'neq') {
							$shipping->where($k, '!=', $val);
						}
					}
				} else {
					$shipping->where($k, $v);
				}
			}
		}
		return $shipping->count();
	}
	
	public function shipping_update($where, $data) {
//		return $this->where($where)->update($data);

		$shipping = RC_DB::table('shipping');
		if (is_array($where)){
			foreach($where as $key => $val){
				$shipping->where($key, $val);
			}
		}
		return $shipping->update($data);
	}
	
}

// end