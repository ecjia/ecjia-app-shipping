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
            'shop_country'          => __('网店-国家', 'shipping'),
            'shop_province'         => __('网店-省份', 'shipping'),
            'shop_city'             => __('网店-城市', 'shipping'),
            'shop_name'             => __('表示网店名称', 'shipping'),
            'shop_district'         => __('网店-区/县', 'shipping'),
            'shop_tel'              => __('网店-联系电话', 'shipping'),
            'shop_address'          => __('网店-地址', 'shipping'),
            'customer_country'      => __('收件人-国家', 'shipping'),
            'customer_province'     => __('表示网店所属省份', 'shipping'),
            'customer_city'         => __('收件人-城市', 'shipping'),
            'customer_district'     => __('收件人-区/县', 'shipping'),
            'customer_tel'          => __('收件人-电话', 'shipping'),
            'customer_mobel'        => __('收件人-手机', 'shipping'),
            'customer_post'         => __('收件人-邮编', 'shipping'),
            'customer_address'      => __('收件人-详细地址', 'shipping'),
            'customer_name'         => __('收件人-姓名', 'shipping'),
            'year'                  => __('年-当日日期', 'shipping'),
            'months'                => __('月-当日日期', 'shipping'),
            'day'                   => __('日-当日日期', 'shipping'),
            'order_no'              => __('订单号-订单', 'shipping'),
            'order_postscript'      => __('备注-订单', 'shipping'),
            'order_best_time'       => __('送货时间-订单', 'shipping'),
            'order_amount'          => __('送货时间-订单', 'shipping'),
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

}