<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author royalwang
 *
 */
class shipping_merchant_purview_api extends Component_Event_Api {
    
    public function call(&$options) {
        $purviews = array(
            array('action_name' => __('我的配送'), 'action_code' => 'ship_merchant_manage', 'relevance'   => ''),
        );
        
        return $purviews;
    }
}

// end