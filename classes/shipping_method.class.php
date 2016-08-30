<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 配送方法
 * @author royalwang
 *
 */
class shipping_method  
{
	private $db;
    
	public function __construct() 
	{
		$this->db = RC_Loader::load_app_model('shipping_model', 'shipping');
	}
	
	
	/**
     * 取得可用的配送方式列表
     * @param   array   $region_id_list     收货人地区id数组（包括国家、省、市、区）
     * @return  array   配送方式数组
     */
    public function available_shipping_list($region_id_list) 
    {  	
    	$dbview = RC_Loader::load_app_model('shipping_viewmodel', 'shipping');
        // $dbview->view = array(
        // 	'shipping_area' => array(
        // 		'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
        // 		'alias' => 'a',
        // 		'field' => 's.shipping_id, s.shipping_code, s.shipping_name,s.shipping_desc, s.insure, s.support_cod, a.configure',
        // 		'on' 	=> 'a.shipping_id = s.shipping_id', 
        // 	),
        // 	'area_region' => array(
        // 		'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,	
        // 		'alias' => 'r',
        // 		'on' 	=> 'r.shipping_area_id = a.shipping_area_id ', 
        // 	)
        // );
        $data = $dbview->join(array('shipping_area', 'area_region'))->field('s.shipping_id, s.shipping_code, s.shipping_name,s.shipping_desc, s.insure, s.support_cod, a.configure')->where(array('s.enabled' => 1))->in(array('r.region_id' => $region_id_list))->order(array('s.shipping_order' => 'asc'))->select();
        $plugins = $this->available_shipping_plugins();
        
        $pay_list = array();
        if (!empty($data)) {
        	foreach ($data as $row) {
        		if (isset($plugins[$row['shipping_code']])) {
        			$pay_list[] = $row;
        		}
        	}
        }
        return $pay_list;
    }
	
    /**
     * 激活的支付插件列表
     */
    public function available_shipping_plugins()
    {
    	return ecjia_config::instance()->get_addon_config('shipping_plugins', true);
    }
    
    /**
     * 取得某配送方式对应于某收货地址的区域信息
     * @param   int     $shipping_id        配送方式id
     * @param   array   $region_id_list     收货人地区id数组
     * @return  array   配送区域信息（config 对应着反序列化的 configure）
     */
    public function shipping_area_info($shipping_id, $region_id_list) 
    {
        $dbview = RC_Loader::load_app_model('shipping_viewmodel', 'shipping');
        // $dbview->view = array(
        //     'shipping_area' => array(
        //         'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
        //         'alias' => 'a',
        //         'field' => 's.shipping_code, s.shipping_name,s.shipping_desc, s.insure, s.support_cod, a.configure',
        //         'on' 	=> 'a.shipping_id = s.shipping_id',
        //     ),
        //     'area_region' => array(
        //         'type' 	=> Component_Model_View::TYPE_LEFT_JOIN,
        //         'alias' => 'r',
        //         'on' 	=> 'r.shipping_area_id = a.shipping_area_id ',
        //     )
        // );
    
        $row = $dbview->join(array('shipping_area', 'area_region'))->field('s.shipping_code, s.shipping_name,s.shipping_desc, s.insure, s.support_cod, a.configure')->in(array('r.region_id' => $region_id_list))->find(array('s.shipping_id' => $shipping_id, 's.enabled' => 1));
        if (!empty($row)) {
            $shipping_config = $this->unserialize_config($row['configure']);
            if (isset($shipping_config['pay_fee'])) {
                if (strpos($shipping_config['pay_fee'], '%') !== false) {
                    $row['pay_fee'] 	= floatval($shipping_config['pay_fee']) . '%';
                } else {
                    $row['pay_fee'] 	= floatval($shipping_config['pay_fee']);
                }
            } else {
                $row['pay_fee'] 	= 0.00;
            }
        }
        return $row;
    }

	
    /**
     * 取得已安装的配送方式
     * @return  array   已安装的配送方式
     */
    public function shipping_list() 
    {
    	$data = $this->db->field('shipping_id, shipping_name, shipping_code')->where(array('enabled' => 1))->select();
    	$plugins = $this->available_shipping_plugins();
    	$pay_list = array();
    	if (!empty($data)) {
    		foreach ($data as $row) {
    			if (isset($plugins[$row['shipping_code']])) {
    				$pay_list[] = $row;
    			}
    		}
    	}
    	return $pay_list;
    }
    
    
    /**
     * 取得配送方式信息
     * @param   int     $shipping_id    配送方式id
     * @return  array   配送方式信息
     */
    public function shipping_info($shipping_id) 
    {
        return $this->db->find(array('shipping_id' => $shipping_id , 'enabled' => 1));
    }
	
    
    /**
     * 计算运费
     * @param   string  $shipping_code	  配送方式代码
     * @param   mix	 $shipping_config	配送方式配置信息
     * @param   float   $goods_weight	   商品重量
     * @param   float   $goods_amount	   商品金额
     * @param   float   $goods_number	   商品数量
     * @return  float   运费
     */
    function shipping_fee($shipping_code, $shipping_config, $goods_weight, $goods_amount, $goods_number='') {
    	if (!is_array($shipping_config)) {
    		$shipping_config = unserialize($shipping_config);
    	}
    	
    	RC_Loader::load_app_class('shipping_factory', 'shipping', false);
    	$handler = new shipping_factory($shipping_code, $shipping_config);
    	$shipping_fee = $handler->calculate($goods_weight, $goods_amount, $goods_number);

    	if (empty($shipping_fee)) {
    		return 0;
    	} else {
    		return $shipping_fee;
    	}
    }
    
    /**
     * 处理序列化的支付、配送的配置参数
     * 返回一个以name为索引的数组
     *
     * @access  public
     * @param   string       $cfg
     * @return  void
     */
    public function unserialize_config($cfg)
    {
    	if (is_string($cfg) && ($arr = unserialize($cfg)) !== false) {
    		$config = array();
    		foreach ($arr AS $key => $val) {
    			$config[$val['name']] = $val['value'];
    		}
    		return $config;
    	} else {
    		return false;
    	}
    }
}

// end