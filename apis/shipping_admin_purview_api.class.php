<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author royalwang
 *
 */
class shipping_admin_purview_api extends Component_Event_Api {
    
    public function call(&$options) {
        $purviews = array(
            array('action_name' => __('配送方式管理'), 'action_code' => 'ship_manage', 'relevance'   => ''),
        	array('action_name' => __('配送方式更新'), 'action_code' => 'ship_update', 'relevance'   => ''),
        		
            array('action_name' => __('配送区域管理'), 'action_code' => 'shiparea_manage', 'relevance'   => ''),
        	array('action_name' => __('配送区域添加'), 'action_code' => 'shiparea_add', 'relevance'   => ''),
        	array('action_name' => __('配送区域更新'), 'action_code' => 'shiparea_update', 'relevance'   => ''),
        	array('action_name' => __('配送区域删除'), 'action_code' => 'shiparea_delete', 'relevance'   => ''),
        );
        
        return $purviews;
    }
}

// end