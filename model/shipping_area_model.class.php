<?php
defined('IN_ECJIA') or exit('No permission resources.');

class shipping_area_model extends Component_Model_Model {
	public $table_name = '';
	public function __construct() {
		$this->db_config = RC_Config::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'shipping_area';
		parent::__construct();
	}
	
	/**
	 *  获取配送区域列表
	 */
	public function get_shipareas_list($args = array()){
		/* 过滤条件  为查询*/
		$filter['keywords'] = empty($args['keywords']) ? '' : trim($args['keywords']);
		$ex_where = array('shipping_id' => $args['shipping_id']);
		if ($filter['keywords']) {
			$ex_where['shipping_area_name'] = array('like' => "%". mysql_like_quote($filter['keywords']). "%" );
		}
		$count = $this->where($ex_where)->count();

		$page = new ecjia_page($count, 10, 6);

		/* 查询所有配送方式信息  */
		$shipping_areas_list=array();
		$list = $this->where($ex_where)->limit($page->limit())->select();

		if (!empty($list)) {
			foreach ($list as $row) {
				$db_region = RC_Loader::load_app_model('shipping_area_region_viewmodel');
				$region_names = $db_region->join('region')->where(array( 'a.shipping_area_id' => $row['shipping_area_id']))->field('r.region_name')->select();
				if (is_array($region_names) && count($region_names)>0 ) {
					$region_array = array();
					foreach ($region_names as $name) {
						//如果所对应的区域已在区域列表设置中被删除
						if( empty($name['region_name']) ) {
							$region_array[] = '<lable  style="color:red">' .RC_Lang::get('shipping::shipping_area.removed_region'). '</lable>';
						} else{
							$region_array[] = $name['region_name'];
						}
					}
					$regions = implode(',', $region_array);
				}
					
				$row['shipping_area_regions'] = empty($regions) ? '<lable  style="color:red">' .RC_Lang::get('shipping::shipping_area.empty_regions'). '</lable>': $regions;
				$shipping_areas_list[] = $row;
			}
		}
			
		$filter['keywords'] = stripslashes($filter['keywords']);
		return array('areas_list' => $shipping_areas_list, 'filter' => $filter, 'page' => $page->show(10), 'desc' => $page->page_desc());
	}
	
	public function is_only($where) {
//		return $this->where($where)->count();
		$db_shipping_area = RC_DB::table('shipping_area');
		if(!empty($where)){
			foreach($where as $key => $vla){
				if (is_array($vla)){
					foreach($vla as $k => $v){
						if ($k == 'neq'){
							$db_shipping_area->where($key,'!=','$v');
						}
					}
				}else{
					$db_shipping_area->where($key, $val);
				}
			}
		}
		return $db_shipping_area->count();
	}
	
	public function shipping_area_find($where, $field='*') {
//		return $this->where($where)->field($field)->find();

		$db_shipping_area = RC_DB::table('shipping_area');
		if (!empty($where)){
			foreach($where as $key => $val){
				$db_shipping_area->where($key, $val);
			}
		}
		if( $field == '*'){
			return $db_shipping_area->first();
		}
	}
	
	public function shipping_area_manage($parameter) {
//		if (!isset($parameter['shipping_area_id'])) {
//			$id = $this->insert($parameter);
//		} else {
//			$where = array('shipping_area_id' => $parameter['shipping_area_id']);
//			$this->where($where)->update($parameter);
//			$id = $parameter['shipping_area_id'];
//		}
//		return $id;
		if (!isset($parameter['shipping_area_id'])){
			$id = RC_DB::table('shipping_area')->insertGetId($parameter);
		}else{
			RC_DB::table('shipping_area')->where('shipping_area_id', $parameter['shipping_area_id'])->update($parameter);
			$id = $parameter['shipping_area_id'];
		}
		return $id;
	}
	
	public function shipping_area_field($where, $field) {
//		return $this->where($where)->get_field($field);

		$db_shipping_area = RC_DB::table('shipping_area');
		if (!empty($where)){
			foreach($where as $key => $val){
				$db_shipping_area->where($key, $val);
			}
		}
		return $db_shipping_area->pluck($field);
	}
	
	public function shipping_area_remove($where) {
//		return $this->where($where)->delete();

		$db_shipping_area = RC_DB::table('shipping_area');
		if (!empty($where)){
			foreach($where as $key => $val){
				$db_shipping_area->where($key, $val);
			}
		}
		return $db_shipping_area->delete();
	}
	
	public function shipping_area_batch($where, $type) {
//		if ($type == 'select') {
//			return $this->in($where)->select();
//		} elseif ($type == 'delete') {
//			return $this->in($where)->delete();
//		}

		$db_shipping_area = RC_DB::table('shipping_area');
		if ($type == 'select') {
			foreach($where as $key => $val){
				$db_shipping_area->whereIn($key, $val);
			}
			return $db_shipping_area->get();
		} elseif ($type == 'delete') {
			foreach($where as $key => $val){
				$db_shipping_area->whereIn($key, array($val));
			}
			return $db_shipping_area->delete();
		}
	}
}

// end