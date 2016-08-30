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
		return $this->where($where)->field($field)->find();
	}
	
	public function shipping_field($where, $field) {
		return $this->where($where)->get_field($field);
	}
	
	public function shipping_select($field='*', $where='', $order='') {
		if (!empty($where)) {
			return $this->where($where)->field($field)->order($order)->select();
		}
		return $this->field($field)->order($order)->select();
	}
	
	public function is_only($where) {
		return $this->where($where)->count();
	}
	
	public function shipping_update($where, $data) {
		return $this->where($where)->update($data);
	}
	
}

// end