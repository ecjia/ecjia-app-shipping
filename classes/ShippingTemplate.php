<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-03-20
 * Time: 17:59
 */

namespace Ecjia\App\Shipping;


class ShippingTemplate
{

    protected $default = [];

    protected $setting = [];

    public function __construct()
    {
        
        $this->default = [
	        'shop_country'          => __('表示网店所属国家', 'shipping'),
	        'shop_province'         => __('表示网店所属省份', 'shipping'),
	        'shop_city'             => __('表示网店所属城市', 'shipping'),
	        'shop_district'         => __('表示网店所属区/县', 'shipping'),
	        'shop_address'          => __('表示网店地址', 'shipping'),
	        'shop_name'             => __('表示网店名称', 'shipping'),
	        'shop_tel'              => __('表示网店联系电话', 'shipping'),
	        
	        'customer_country'      => __('表示收件人所属国家', 'shipping'),
	        'customer_province'     => __('表示收件人所属省份', 'shipping'),
	        'customer_city'         => __('表示收件人所属城市', 'shipping'),
	        'customer_district'     => __('表示收件人所属区/县', 'shipping'),
	        'customer_tel'          => __('表示收件人电话', 'shipping'),
	        'customer_mobel'        => __('表示收件人手机', 'shipping'),
	        'customer_post'         => __('表示收件人邮编', 'shipping'),
	        'customer_address'      => __('表示收件人详细地址', 'shipping'),
	        'customer_name'         => __('表示收件人姓名', 'shipping'),
	        
	        'year'                  => __('年-当日日期', 'shipping'),
	        'months'                => __('月-当日日期', 'shipping'),
	        'day'                   => __('日-当日日期', 'shipping'),
	        'order_no'              => __('表示订单号', 'shipping'),
	        'order_amount'          => __('表示订单金额', 'shipping'),
	        'order_postscript'      => __('表示订单备注', 'shipping'),
	        'order_best_time'       => __('表示送货时间', 'shipping'),
        ];
    }

    /**
     * 设置变量数据
     * @param $key
     * @param $value
     * @return $this
     */
    public function setTemplateData($key, $value)
    {
        $keys = array_keys($this->default);
        if (in_array($key, $keys)) {
            $this->setting[$key] = $value;
        }

        return $this;
    }

    /**
     * 获取已经成功设置过的数据
     * @return array
     */
    public function getTemplateData()
    {
        return $this->setting;
    }

    /**
     * 获取默认选项
     */
    public function getDefaults()
    {
        return $this->default;
    }

    
    /**
     * 获取默认选项，并格式化输出
     */
    public function getDefaultsWithFormatted()
    {
    	return collect($this->default)->map(function($item, $key) {
    		return '<font class="ecjiafc-blue">{$'. $key .'}</font> ' . $item;
    	})->all();
    }
}