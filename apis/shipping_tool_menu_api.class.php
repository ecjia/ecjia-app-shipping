<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台工具菜单API
 * @author royalwang
 *
 */

class shipping_tool_menu_api extends Component_Event_Api {
	
	public function call(&$options) {	
		$menus = ecjia_admin::make_admin_menu('02_shipping_list', RC_Lang::get('shipping::shipping.shipping'), RC_Uri::url('shipping/admin/init'), 2)->add_purview(array('ship_manage', 'shiparea_manage'));
		return $menus;
	}
}

// end