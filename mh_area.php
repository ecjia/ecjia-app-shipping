<?php
/**
 * ECJia 配送区域管理程序
 */
defined('IN_ECJIA') or exit('No permission resources.');

class mh_area extends ecjia_merchant {
	private $db_shipping;
	private $db_region;
	private $db_shipping_area;
	private $db_shipping_area_region;
	
	public function __construct() {
		parent::__construct();

		RC_Loader::load_app_func('global');
		RC_Loader::load_app_class('shipping_factory', null, false);
		
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');	
// 		RC_Script::enqueue_script('shopping_admin', RC_App::apps_url('statics/js/shipping_admin.js', __FILE__));
		RC_Script::enqueue_script('merchant_shipping', RC_App::apps_url('statics/js/merchant_shipping.js', __FILE__));
// 		RC_Style::enqueue_style('chosen');
// 		RC_Style::enqueue_style('chosen_style', RC_Theme::get_template_directory_uri().'/assets/chosen/chosen.css', array());
// 		RC_Script::enqueue_script('chosen_script', RC_Theme::get_template_directory_uri().'/assets/chosen/chosen.jquery.min.js', array(), false, false);
		
		RC_Style::enqueue_style('merchant_shipping', RC_App::apps_url('statics/css/merchant_shipping.css', __FILE__), array(), false, false);
		
// 		RC_Style::enqueue_style('uniform-aristo');
// 		RC_Script::enqueue_script('jquery-uniform');
// 		RC_Script::enqueue_script('jquery-chosen');
		
		$this->db_shipping 				= RC_Model::model('shipping/shipping_model');
		$this->db_shipping_area 		= RC_Model::model('shipping/shipping_area_model');
		$this->db_region 				= RC_Model::model('shipping/region_model');
		$this->db_shipping_area_region 	= RC_Model::model('shipping/shipping_area_region_model');
		
		RC_Script::localize_script('merchant_shipping', 'js_lang', RC_Lang::get('shipping::shipping.js_lang'));
		RC_Script::localize_script('shopping_admin', 'js_lang', RC_Lang::get('shipping::shipping.js_lang'));
		
		ecjia_merchant_screen::get_current_screen()->set_parentage('shipping', 'shipping/mh_area.php');
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('我的配送', RC_Uri::url('shipping/merchant/init')));
	}
	
	/**
	 * 配送区域列表
	 */
	public function init() {
		$this->admin_priv('shiparea_manage');
		
		$shipping_id = !empty($_GET['shipping_id']) ? intval($_GET['shipping_id']) : 0;
		$args = array('shipping_id' => $shipping_id);
		$args['keywords'] = !empty($_GET['keywords']) ? trim($_GET['keywords']) : '';

		$ship_areas_list = $this->db_shipping_area->get_shipareas_list($args);

		$shipping_name = $this->db_shipping->shipping_field(array('shipping_id' => $shipping_id), 'shipping_name');

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($shipping_name));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('shipping::shipping_area.overview'),
			'content'	=> '<p>' . RC_Lang::get('shipping::shipping_area.shipping_area_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('shipping::shipping_area.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:配送方式" target="_blank">'. RC_Lang::get('shipping::shipping_area.about_shipping_area') .'</a>') . '</p>'
		);
		
		$code = !empty($_GET['code']) ? trim($_GET['code']) : '';
		
		$this->assign('code',	$code);
		$this->assign('areas',	$ship_areas_list);
		$this->assign('ur_here', $shipping_name);
		
		$this->assign('action_link', array('href' => RC_Uri::url('shipping/mh_area/add', array('shipping_id' => $shipping_id, 'code' => $code)), 'text' => RC_Lang::get('shipping::shipping_area.new_area')));
		$this->assign('shipping_method', array('href' => RC_Uri::url('shipping/merchant/init'), 'text' => '我的配送'));
		
		$this->assign('shipping_id', $shipping_id);
		$this->assign('form_action', RC_Uri::url('shipping/mh_area/multi_remove', array('shipping_id' => $shipping_id, 'code' => $code)));
		$this->assign('search_action', RC_Uri::url('shipping/mh_area/init'));

		$this->display('shipping_area_list.dwt');
	}
	
	/**
	 * 新建配送区域
	 * 
	 */
	public function add() {
	    $this->admin_priv('shiparea_add');
		
		$shipping_id 	= !empty($_GET['shipping_id']) ? intval($_GET['shipping_id']) : 0;
		$code 			= !empty($_GET['code']) ? trim($_GET['code']) : '';
//		$shipping_data  = $this->db_shipping->shipping_find(array('shipping_id' => $shipping_id), 'shipping_name, shipping_code, support_cod');
		$shipping_data  = $this->db_shipping->shipping_find(array('shipping_id' => $shipping_id), array('shipping_name', 'shipping_code', 'support_cod'));

		$fields = array();
		$shipping_handle = new shipping_factory($shipping_data['shipping_code']);
		$fields = $shipping_handle->form_format($fields, true);
		
		$count = count($fields);
		$fields[$count]['name']     = "free_money";
		$fields[$count]['value']    = "0";
		$fields[$count]['label']    = RC_Lang::get('shipping::shipping_area.free_money');
		
		/* 如果支持货到付款，则允许设置货到付款支付费用 */
		if ($shipping_data['support_cod']) {
			$count++;
			$fields[$count]['name']     = "pay_fee";
			$fields[$count]['value']    = "0";
			$fields[$count]['label']    = RC_Lang::get('shipping::shipping_area.pay_fee');
		}
		
		$shipping_area['shipping_id']   = 0;
		$shipping_area['free_money']    = 0;

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($shipping_data['shipping_name'], RC_Uri::url('shipping/mh_area/init', array('shipping_id' => $shipping_id, 'code' => $code))));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('shipping::shipping_area.new_area')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('shipping::shipping_area.overview'),
			'content'	=> '<p>' . RC_Lang::get('shipping::shipping_area.add_area_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('shipping::shipping_area.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:配送方式" target="_blank">'. RC_Lang::get('shipping::shipping_area.about_add_area') .'</a>') . '</p>'
		);
		
		$this->assign('ur_here', RC_Lang::get('shipping::shipping_area.new_area'));		
		$this->assign('shipping_area', array('shipping_id' => $shipping_id, 'shipping_code' => $shipping_data['shipping_code']));
		
		$this->assign('fields', $fields);
		$this->assign('form_action', 'insert');
		$this->assign('countries', $this->db_region->get_regions());

		$this->assign('action_link', array('text' => $shipping_data['shipping_name'].RC_Lang::get('shipping::shipping_area.list'), 'href' => RC_Uri::url('shipping/mh_area/init', array('shipping_id' => $shipping_id, 'code' => $code ))));
		$this->assign('default_country', ecjia::config('shop_country'));
		$this->assign('region_get_url', RC_Uri::url('shipping/region/init'));
		$this->assign('form_action', RC_Uri::url('shipping/mh_area/insert', array('shipping_id' => $shipping_id, 'code' => $code)));
		
		$this->display('shipping_area_info.dwt');
	}
	
	public function insert() {
	    $this->admin_priv('shiparea_add', ecjia::MSGTYPE_JSON);
	    
	    $shipping_id 		= !empty($_GET['shipping_id']) ? intval($_GET['shipping_id']) : 0;
	    $shipping_area_name = !empty($_POST['shipping_area_name']) ? trim($_POST['shipping_area_name']) : '';
	    $code 				= !empty($_GET['code']) ? trim($_GET['code']) : '';

		/* 检查同类型的配送方式下有没有重名的配送区域 */	
		$area_count = $this->db_shipping_area->is_only(array('shipping_id' => $shipping_id, 'shipping_area_name' => $shipping_area_name, 'store_id' => $_SESSION['store_id']));

		if ($area_count > 0) {
		    $this->showmessage(RC_Lang::get('shipping::shipping_area.repeat_area_name'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
//			$shipping_data = $this->db_shipping->shipping_find(array('shipping_id' => $shipping_id), 'shipping_code, support_cod, shipping_name');
			$shipping_data = $this->db_shipping->shipping_find(array('shipping_id' => $shipping_id), array('shipping_code', 'support_cod', 'shipping_name'));

			$config = array();
			$shipping_handle = new shipping_factory($shipping_data['shipping_code']);
			$config = $shipping_handle->form_format($config, true);
			if (!empty($config)) {
    			foreach ($config AS $key => $val) {
    				$config[$key]['name']   = $val['name'];
    				$config[$key]['value']  = $_POST[$val['name']];
    			}
			}
			
			$count = count($config);
			$config[$count]['name']     = 'free_money';
			$config[$count]['value']    = empty($_POST['free_money']) ? '0' : trim($_POST['free_money']);
			$count++;
			$config[$count]['name']     = 'fee_compute_mode';
			$config[$count]['value']    = empty($_POST['fee_compute_mode']) ? '' : trim($_POST['fee_compute_mode']);
			
			/* 如果支持货到付款，则允许设置货到付款支付费用 */
			if ($shipping_data['support_cod']) {
				$count++;
				$config[$count]['name']     = 'pay_fee';
				$config[$count]['value']    =  make_semiangle(empty($_POST['pay_fee']) ? '0' : trim($_POST['pay_fee']));
			}

			$data = array(
				'shipping_area_name'    => $shipping_area_name,
				'shipping_id'           => $shipping_id,
				'configure'             => serialize($config)
			);
			if (isset($_SESSION['store_id'])) {
				$data['store_id'] = $_SESSION['store_id'];
			}
			
			$area_id = $this->db_shipping_area->shipping_area_manage($data);

			/* 添加选定的城市和地区 */
			if (isset($_POST['regions']) && is_array($_POST['regions'])) {
				foreach ($_POST['regions'] as $key => $val) {
					$data = array(
						'shipping_area_id' 	=> $area_id,
					    'region_id' 		=> $val
					);
					$this->db_shipping_area_region->shipping_area_region_insert($data);
				}
			}
			
			ecjia_admin::admin_log($shipping_area_name.'，'.RC_Lang::get('shipping::shipping_area.shipping_way').$shipping_data['shipping_name'], 'add', 'shipping_area');
			$links[] = array('text' => RC_Lang::get('shipping::shipping_area.add_continue'), 'href' => RC_Uri::url('shipping/mh_area/add', array('shipping_id' => $shipping_id, 'code' => $code)));
			
			$refresh_url = RC_Uri::url('shipping/mh_area/edit', array('id' => $area_id, 'shipping_id' => $shipping_id, 'code' => $code));
			$this->showmessage(RC_Lang::get('shipping::shipping_area.add_area_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('links' => $links, 'refresh_url' => $refresh_url));
		}
	}
	
	/**
	 * 编辑配送区域
	 */
	public function edit() {
		$this->admin_priv('shiparea_update');
		
		$dbview = RC_Model::model('shipping/shipping_viewmodel');
		// 调用视图查看
// 		$dbview->view = array (
// 			'shipping_area' => array (
// 				'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
// 				'alias' => 'b',
// 				'field' => 's.shipping_name, s.shipping_code, s.support_cod, b.*',
// 				'on' 	=> 'b.shipping_id = s.shipping_id ' 
// 			) 
// 		);
		$shipping_id 	= !empty($_GET['shipping_id']) 	? intval($_GET['shipping_id']) 	: 0;
		$ship_area_id 	= !empty($_GET['id']) 			? intval($_GET['id']) 			: 0;
		$code 			= !empty($_GET['code']) 		? trim($_GET['code']) 			: '';
		
		$shipping_data = $dbview->shipping_area_find(array('a.shipping_area_id' => $ship_area_id, 'a.store_id' => $_SESSION['store_id']), 's.shipping_name, s.shipping_code, s.support_cod, a.*', 'shipping_area');
		if (empty($shipping_data)) {
			$this->showmessage('未找到相关配送区域！', ecjia::MSGTYPE_HTML | ecjia::MSGSTAT_ERROR);
		}
		$fields = unserialize ($shipping_data['configure']);

		/* 如果配送方式支持货到付款并且没有设置货到付款支付费用，则加入货到付款费用 */
		if ($shipping_data['support_cod'] && $fields[count($fields) - 1]['name'] != 'pay_fee') {
			$fields[] = array (
				'name' => 'pay_fee',
				'value' => 0 
			);
		}
		
		$shipping_handle = new shipping_factory($shipping_data['shipping_code']);
		$fields = $shipping_handle->form_format($fields, true);
		if (! empty( $fields )) {
			foreach ($fields as $key => $val ) {
				/* 替换更改的语言项 */
				if ($val['name'] == 'basic_fee') {
					$val['name'] = 'base_fee';
				}

				if ($val['name'] == 'item_fee') {
					$item_fee = 1;
				}
				if ($val['name'] == 'fee_compute_mode') {
					$this->assign( 'fee_compute_mode', $val ['value'] );
					unset( $fields [$key] );
				} else {
					$fields[$key]['name'] = $val['name'];
					$fields[$key]['label'] = empty($val['label']) ? RC_Lang::get('shipping::shipping_area.'.$val['name']) : $val['label'];
				}
			}
		} 
		if (empty( $item_fee )&& !empty( $fields )) {
			$field = array (
				'name' 	=> 'item_fee',
				'value' => '0',
				'label' => ecjia::config(RC_Lang::get('shipping::shipping_area.item_fee'), ecjia::CONFIG_EXISTS ) ? '' : RC_Lang::get('shipping::shipping_area.item_fee') 
			);
			array_unshift($fields, $field );
		}
		$regions = array ();
// 		$db_region_view = RC_Loader::load_app_model('shipping_area_region_viewmodel');
// 		$db_region->view = array(
// 			'region' => array(
// 				'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
// 				'alias' => 'r',
// 				'field' => 'a.region_id, r.region_name',
// 				'on' 	=> 'r.region_id = a.region_id' 
// 			) 
// 		);
// 		$region_data = $db_region_view->shipping_region_select(array('a.shipping_area_id' => $ship_area_id), 'a.region_id, r.region_name', 'region');

		$region_data = RC_DB::table('area_region')->leftJoin('region', 'area_region.region_id', '=', 'region.region_id')
			->select('area_region.region_id', 'region.region_name')->where('area_region.shipping_area_id', $ship_area_id)->get();
		
		if (!empty($region_data)) {
			foreach ($region_data as $key => $val) {
				if (empty($val['region_name'])) {
					$regions[$val['region_id']] = '<lable  style="color:red">' .RC_Lang::get('shipping::shipping_area.removed_region'). '</lable>';
				} else{
					$regions[$val['region_id']] = $val['region_name'];
				}
			}
		}
		
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here( $shipping_data['shipping_name'], RC_Uri::url('shipping/mh_area/init', array('shipping_id' => $shipping_data['shipping_id']))));
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(RC_Lang::get('shipping::shipping_area.edit_area')));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('shipping::shipping_area.overview'),
			'content'	=> '<p>' . RC_Lang::get('shipping::shipping_area.edit_area_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('shipping::shipping_area.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:配送方式" target="_blank">'. RC_Lang::get('shipping::shipping_area.about_edit_area') .'</a>') . '</p>'
		);
		
		$this->assign('ur_here', RC_Lang::get('shipping::shipping_area.edit_area'));
		$this->assign('area_id', $ship_area_id );
		$this->assign('fields', $fields );
		$this->assign('shipping_area', $shipping_data );
		$this->assign('regions', $regions );
		$this->assign('action_link', array('text' => $shipping_data['shipping_name'].RC_Lang::get('shipping::shipping_area.list'), 'href' => RC_Uri::url('shipping/mh_area/init', array('shipping_id' => $shipping_data['shipping_id'], 'code' => $code))));
		
		$this->assign('countries', $this->db_region->get_regions());
		$this->assign('default_country', 1);
		$this->assign('region_get_url', RC_Uri::url('shipping/region/init'));//区域联动使用
		$this->assign('form_action', RC_Uri::url('shipping/mh_area/update', array('shipping_id' => $shipping_id, 'code' => $code)));
		
		$this->assign_lang();
		$this->display('shipping_area_info.dwt' );
	}
	
	public function update() {
		$this->admin_priv('shiparea_update', ecjia::MSGTYPE_JSON);
		
		/* 检查同类型的配送方式下有没有其他重名的配送区域 */
		$shipping_id 		= !empty($_GET['shipping_id']) ? intval($_GET['shipping_id']) : 0;
		$shipping_area_name = trim($_POST['shipping_area_name']);
		$shipping_area_id 	= !empty($_POST['id']) 	? intval($_POST['id']) 	: 0;
		$code 				= !empty($_GET['code']) ? trim($_GET['code']) 	: '';
		
		$ship_area_count = $this->db_shipping_area->is_only(array('shipping_id' => $shipping_id, 'shipping_area_name' => $shipping_area_name, 'shipping_area_id' => array('neq' => $shipping_area_id), 'store_id' => $_SESSION['store_id']));

		/*判断该配送区域是否属于该商户*/ 
		$shipping_area = $this->db_shipping_area->where(array('shipping_area_id' => $shipping_area_id, 'store_id' => $_SESSION['store_id']))->find();
		if (empty($shipping_area)) {
			$this->showmessage('未找到相关配送区域！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
		if ($ship_area_count > 0) {
			$this->showmessage(RC_Lang::get('shipping::shipping_area.repeat_area_name'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		} else {
//			$shipping_data = $this->db_shipping->shipping_find(array('shipping_id' => $shipping_id), 'shipping_code, shipping_name, support_cod');
			$shipping_data = $this->db_shipping->shipping_find(array('shipping_id' => $shipping_id), array('shipping_code', 'shipping_name', 'support_cod'));

			$shipping_data['configure'] = $this->db_shipping_area->shipping_area_field(array('shipping_id' => $shipping_id, 'shipping_area_name' => $shipping_area_name), 'configure');

			$config = unserialize ( $shipping_data ['configure'] );
			$shipping_handle = new shipping_factory($shipping_data['shipping_code']);
// 			$config = $shipping_handle->form_format($config, true);
			$config = $shipping_handle->form_format($config, false);
			if (!empty($config)) {
				foreach ($config as $key => $val) {
					$config[$key]['name']   = $val['name'];
					$config[$key]['value']  = trim($_POST[$val['name']]);
				}
			}
			
			$count = count($config);
			$config[$count]['name']		= 'free_money';
			$config[$count]['value']  	= empty($_POST['free_money']) ? '0' : trim($_POST['free_money']);
			$count++;
			$config[$count]['name']		= 'fee_compute_mode';
			$config[$count]['value']	= empty($_POST['fee_compute_mode']) ? '' : trim($_POST['fee_compute_mode']);
			
			if ($shipping_data['support_cod']) {
				$count++;
				$config[$count]['name']     = 'pay_fee';
				$config[$count]['value']    =  make_semiangle(empty($_POST['pay_fee']) ? '0' : trim($_POST['pay_fee']));
			}
			$data = array(
// 				'shipping_area_id' 		=> $shipping_area_id,
				'shipping_area_name' 	=> $shipping_area_name,
				'configure'          	=> serialize($config)
			);	
			$this->db_shipping_area->where(array('shipping_area_id' => $shipping_area_id, 'store_id' => $_SESSION['store_id']))->update($data);

			
			ecjia_admin::admin_log($shipping_area_name.'，'.RC_Lang::get('shipping::shipping_area.shipping_way').$shipping_data['shipping_name'], 'edit', 'shipping_area');
		
			/* 过滤掉重复的region */
			$selected_regions = array();
			if (isset($_POST['regions'])) {
				foreach ($_POST['regions'] as $region_id) {
					$selected_regions[$region_id] = $region_id;
				}
			}
		
			// 查询所有区域 region_id => parent_id	
			$data_region = $this->db_region->region_select(array('region_id', 'parent_id'));

		    foreach ($data_region as $key => $val) {
		    	$region_list[$val['region_id']] = $val['parent_id'];
		    }
			
			// 过滤掉上级存在的区域
			foreach ($selected_regions as $region_id) {
				$id = $region_id;
				while ( $region_list[$id] != 0 ) {
					$id = $region_list[$id];
					if (isset($selected_regions[$id])) {
						unset($selected_regions[$region_id]);
						break;
					}
				}
			}
			/* 清除原有的城市和地区 */

			$this->db_shipping_area_region->shipping_area_region_remove($shipping_area_id);

			/* 添加选定的城市和地区 */
			foreach ($selected_regions as $key => $val) {
				$data = array(
					'shipping_area_id' => $shipping_area_id,
					'region_id'        => $val
				);
				$this->db_shipping_area_region->shipping_area_region_insert($data);
			}
		
			$refresh_url = RC_Uri::url('shipping/mh_area/edit', array('id' => $shipping_area_id, 'shipping_id' => $shipping_id, 'code' => $code));
			$this->showmessage(RC_Lang::get('shipping::shipping_area.edit_area') . "&nbsp;" .$shipping_area_name . "&nbsp;" . RC_Lang::get('shipping::shipping.attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('refresh_url' => $refresh_url));
		}
	}
	
	/**
	 * 删除配送区域
	 */
	public function remove_area() { 
		$this->admin_priv('shiparea_delete', ecjia::MSGTYPE_JSON);
		
		$id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
		if ($id > 0) {
			$row = $this->db_shipping_area->shipping_area_find(array('shipping_area_id' => $id, 'store_id' => $_SESSION['store_id']));
			if (empty($row)) {
				$this->showmessage('未找到相关配送区域！' , ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}
			
			$shipping_name = $this->db_shipping->shipping_field(array('shipping_id' => $row['shipping_id']), 'shipping_name');

			if (isset($row['shipping_area_id'])) {
				$this->db_shipping_area_region->shipping_area_region_remove($id);
				$this->db_shipping_area->shipping_area_remove(array('shipping_area_id' => $id));

				ecjia_admin::admin_log($row['shipping_area_name'].'，'.RC_Lang::get('shipping::shipping_area.shipping_way').$shipping_name, 'remove', 'shipping_area');
				$this->showmessage(RC_Lang::get('shipping::shipping_area.remove_success'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
			}
		} else {
			$this->showmessage(RC_Lang::get('shipping::shipping_area.remove_fail'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
	/**
	 * 批量删除配送区域 pjax-post
	 */
	public function multi_remove() {
		$this->admin_priv('shiparea_delete', ecjia::MSGTYPE_JSON);
		
		$ids 			= !empty($_POST['area_ids']) 	? trim($_POST['area_ids']) 		: '';
		$shipping_id 	= !empty($_GET['shipping_id']) 	? intval($_GET['shipping_id']) 	: 0;
		$code 			= !empty($_GET['code']) 		? trim($_GET['code']) 			: '';
		$ids = explode(',', $ids);
// 		$row = $this->db_shipping_area->shipping_area_batch(array('shipping_area_id' => $ids), 'select');
		
		$row = $this->db_shipping_area->where(array('shipping_area_id' => $ids, 'store_id' => $_SESSION['store_id']))->select();
		$ids = $this->db_shipping_area->where(array('shipping_area_id' => $ids, 'store_id' => $_SESSION['store_id']))->get_field('shipping_area_id', true);
	
		if (!empty($ids)) {
			$this->db_shipping_area_region->shipping_area_region_remove($ids, true);
			$this->db_shipping_area->shipping_area_batch(array('shipping_area_id' => $ids), 'delete');
			
			if (!empty($row)) {
				foreach ($row as $v) {
					$shipping_name = $this->db_shipping->shipping_field(array('shipping_id' => $v['shipping_id']), 'shipping_name');
					ecjia_admin::admin_log($v['shipping_area_name'].'，'.RC_Lang::get('shipping::shipping_area.shipping_way').$shipping_name, 'batch_remove', 'shipping_area');
				}
			}
			$refresh_url = RC_Uri::url('shipping/mh_area/init', array('shipping_id' => $shipping_id, 'code' => $code));
			$this->showmessage(RC_Lang::get('shipping::shipping_area.batch_delete'). RC_Lang::get('shipping::shipping.attradd_succed'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS , array('pjaxurl' => $refresh_url));
		}else {
			$this->showmessage(RC_Lang::get('shipping::shipping_area.batch_no_select_falid'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}
	
// 	/**
// 	 *  获取配送区域分页列表
// 	 */
// 	public function get_shipareas_list($args = array()){
// 		/* 过滤条件  为查询*/
// 		$filter['keywords'] = empty($args['keywords']) ? '' : trim($args['keywords']);
// 		$ex_where = array('shipping_id' => $args['shipping_id']);
// 		if ($filter['keywords']) {
// 			$ex_where['shipping_area_name'] = array('like' => "%". mysql_like_quote($filter['keywords']). "%" );
// 		}
// 		$count = $this->db_shipping_area->where($ex_where)->count();
			
// 		$page = new ecjia_page($count, 10, 6);
			
// 		/* 查询所有配送方式信息  */
// 		$shipping_areas_list=array();
// 		$list = $this->db_shipping_area->where($ex_where)->limit($page->limit())->select();
// 		if (!empty($list)) {
// 			foreach ($list as $row) {
// 				$db_region = RC_Loader::load_app_model ( 'shipping_region_viewmodel' );
// 				$region_names = $db_region->join('region')->where(array( 'a.shipping_area_id' => $row['shipping_area_id']))->field('r.region_name')->select();
// 				if (is_array($region_names) && count($region_names)>0 ) {
// 					$region_array = array();
// 					foreach ($region_names as $name) {
// 						//如果所对应的区域已在区域列表设置中被删除
// 						if (empty($name['region_name'])) {
// 							$region_array[] = '<lable  style="color:red">' .RC_Lang::get('shipping::shipping_area.removed_region'). '</lable>';
// 						} else{
// 							$region_array[] = $name['region_name'];
// 						}
// 					}
// 					$regions = implode(',', $region_array);
// 				}
					
// 				$row['shipping_area_regions'] = empty($regions) ? '<lable  style="color:red">' .RC_Lang::get('shipping::shipping_area.empty_regions'). '</lable>': $regions;
// 				$shipping_areas_list[] = $row;
// 			}
// 		}
			
// 		$filter['keywords'] = stripslashes($filter['keywords']);
// 		return array('areas_list' => $shipping_areas_list, 'filter' => $filter, 'page' => $page->show(10), 'desc' => $page->page_desc());
// 	}
}
// end