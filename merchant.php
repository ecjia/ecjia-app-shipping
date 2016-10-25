<?php

/**
 * ECJIA 配送方式管理程序
 */

defined('IN_ECJIA') or exit('No permission resources.');

class merchant extends ecjia_merchant {
	private $db_shipping;

	public function __construct() {
		parent::__construct();

		RC_Loader::load_app_func('global');
		assign_adminlog_content();

		$this->db_shipping 	= RC_Model::model('shipping/shipping_model');
		
		/* 加载全局 js/css */
		RC_Script::enqueue_script('jquery-validate');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('smoke');
		
		RC_Script::enqueue_script('merchant_shipping', RC_App::apps_url('statics/js/merchant_shipping.js', __FILE__));
// 		RC_Style::enqueue_style('bootstrap-editable', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/css/bootstrap-editable.css'));
// 		RC_Script::enqueue_script('bootstrap-editable.min', RC_Uri::admin_url('statics/lib/x-editable/bootstrap-editable/js/bootstrap-editable.min.js'));
		RC_Script::enqueue_script('ecjia.utils');
		RC_Script::enqueue_script('ecjia.common');
		RC_Style::enqueue_style('chosen');
// 		RC_Style::enqueue_style('uniform-aristo');
// 		RC_Script::enqueue_script('jquery-uniform');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Loader::load_app_class('shipping_factory', null, false);
		
		RC_Script::localize_script('merchant_shipping', 'js_lang', RC_Lang::get('shipping::shipping.js_lang'));
		RC_Script::localize_script('shopping_admin', 'js_lang', RC_Lang::get('shipping::shipping.js_lang'));
		
		ecjia_merchant_screen::get_current_screen()->set_parentage('shipping', 'shipping/merchant.php');
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('我的配送', RC_Uri::url('shipping/admin/init')));
	}

	/**
	 * 配送方式列表  get
	 */
	public function init() { 
		$this->admin_priv('ship_manage');

		ecjia_screen::get_current_screen()->remove_last_nav_here();
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here('我的配送'));
		ecjia_screen::get_current_screen()->add_help_tab(array(
			'id'		=> 'overview',
			'title'		=> RC_Lang::get('shipping::shipping.overview'),
			'content'	=> '<p>' . RC_Lang::get('shipping::shipping.shipping_list_help') . '</p>'
		));
		
		ecjia_screen::get_current_screen()->set_help_sidebar(
			'<p><strong>' . RC_Lang::get('shipping::shipping.more_info') . '</strong></p>' .
			'<p>' . __('<a href="https://ecjia.com/wiki/帮助:ECJia智能后台:配送方式" target="_blank">'. RC_Lang::get('shipping::shipping.about_shipping_list') .'</a>') . '</p>'
		);
		$this->assign('ur_here', '我的配送');
		
		//替换数据库获取数据方式
// 		$data = $this->db_shipping->shipping_select(array('shipping_id', 'shipping_code', 'shipping_name', 'shipping_desc', 'insure', 'support_cod', 'shipping_order', 'enabled'), '', 'shipping_order');
		$type = isset($_GET['type']) ? trim($_GET['type']) : '';
		$shipping_db = RC_Model::model('shipping/shipping_viewmodel');
		$shipping_db->view = array(
				'shipping_area' => array(
						'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
						'alias' => 'a',
						'on' 	=> 'a.shipping_id = s.shipping_id and a.store_id ='.$_SESSION['store_id'],
				),
		);
		$where = array('enabled' => 1);
// 		, 'store_id' => $_SESSION['store_id']
		$shppint_count	= $shipping_db->join(array('shipping_area'))->where($where)->group(array('s.shipping_id'))->select();
		$enable_count	= $shipping_db->join(array('shipping_area'))->where(array_merge($where, array('a.shipping_area_id is not null')))->group(array('s.shipping_id'))->select();
		$unable_count	= $shipping_db->join(array('shipping_area'))->where(array_merge($where, array('a.shipping_area_id is null')))->group(array('s.shipping_id'))->select();
		$shppint_count['count']		   = count($shppint_count);
		$shppint_count['enable_count'] = count($enable_count);
		$shppint_count['unable_count'] = count($unable_count);
		$count = $shppint_count['count'];
		if ($type == 'enable') {
			$count = $shppint_count['enable_count'];
			$where[] = 'a.shipping_area_id is not null';
		} elseif ($type == 'unable') {
			$count = $shppint_count['unable_count'];
			$where[] = 'a.shipping_area_id is null';
		}
		
		$page = new ecjia_merchant_page($count, 10, 3);
		
		$data = $shipping_db->field(array('s.*', 'a.shipping_area_id'))->join(array('shipping_area'))->where($where)->order(array('shipping_order' => 'asc'))->group(array('s.shipping_id'))->limit($page->limit())->select();
		
		$plugins = ecjia_config::instance()->get_addon_config('shipping_plugins', true);
		
		/* 插件已经安装了，获得名称以及描述 */
		$modules = $enable_shipping = $unable_shipping = array();
		if (!empty($data)) {
			foreach ($data as $_key => $_value) {
				if (isset($plugins[$_value['shipping_code']])) {
					$modules[$_key]['id']      			= $_value['shipping_id'];
					$modules[$_key]['code']      		= $_value['shipping_code'];
					$modules[$_key]['name']    			= $_value['shipping_name'];
					$modules[$_key]['desc']    			= $_value['shipping_desc'];
					$modules[$_key]['cod']     			= $_value['support_cod'];
					$modules[$_key]['shipping_order'] 	= $_value['shipping_order'];
					$modules[$_key]['insure_fee']  		= $_value['insure'];
					$modules[$_key]['enabled'] 			= $_value['enabled'];
					 
					/* 判断该派送方式是否支持保价 支持报价的允许在页面修改保价费 */
					$shipping_handle = new shipping_factory($_value['shipping_code']);
					$config          = $shipping_handle->configure_config();
			
					/* 只能根据配置判断是否支持保价  只有配置项明确说明不支持保价，才是不支持*/
					if (isset($config['insure']) && ($config['insure'] === false)) {
						$modules[$_key]['is_insure'] = false;
					} else {
						$modules[$_key]['is_insure'] = true;
					}
				}
			}
		}
		$this->assign('shipping_list', $modules);
		$this->assign('page', $page->show(5));
		$this->assign('shipping_count', $shppint_count);
		
		$this->display('shipping_list.dwt');
	}
	
	
	
}	

// end