<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 商家员工管理菜单
 * @author songqian
 *
 */
class shipping_merchant_menu_api extends Component_Event_Api {

    public function call(&$options) {
        $menus = ecjia_merchant::make_admin_menu('shipping_list', '我的配送', RC_Uri::url('shipping/merchant/init'), 9)->add_icon('fa-truck');

        return $menus;
    }
}

// end