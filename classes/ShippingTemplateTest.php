<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2019-03-20
 * Time: 18:05
 */

namespace Ecjia\App\Shipping;


class ShippingTemplateTest extends ShippingTemplate
{


    public function __construct()
    {
        parent::__construct();


        $this->setTemplateData('shop_country', 'ECJIA');
        $this->setTemplateData('shop_province', '上海市');
        $this->setTemplateData('shop_city', '上海市');
        $this->setTemplateData('shop_district', '普陀区');
        $this->setTemplateData('shop_address', '中山北路3553');
        $this->setTemplateData('shop_name', '');
        $this->setTemplateData('shop_tel', '');
        
        $this->setTemplateData('customer_country', '');
        $this->setTemplateData('customer_province', '');
        $this->setTemplateData('customer_city', '');
        $this->setTemplateData('customer_district', '');
        $this->setTemplateData('customer_tel', '');
        $this->setTemplateData('customer_mobel', '');
        $this->setTemplateData('customer_post', '');
        $this->setTemplateData('customer_address', '');
        $this->setTemplateData('customer_address', '');
        
        $this->setTemplateData('order_no', '');
        $this->setTemplateData('order_amount', '');
        $this->setTemplateData('order_postscript', '');
        $this->setTemplateData('order_best_time', '');
    }


}