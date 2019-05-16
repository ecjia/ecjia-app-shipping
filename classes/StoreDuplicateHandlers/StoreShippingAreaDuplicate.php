<?php
/**
 * Created by PhpStorm.
 * User: royalwang
 * Date: 2018/12/12
 * Time: 14:04
 */

namespace Ecjia\App\Shipping\StoreDuplicateHandlers;

use Ecjia\App\Store\StoreDuplicate\StoreDuplicateAbstract;
use ecjia_error;
use RC_Uri;
use RC_DB;
use RC_Api;
use ecjia_admin;

/**
 * 复制店铺中的配送方式和配送地区
 *
 * Class StoreShippingAreaDuplicate
 * @package Ecjia\App\Shipping\StoreDuplicateHandlers
 */
class StoreShippingAreaDuplicate extends StoreDuplicateAbstract
{

    /**
     * 代号标识
     * @var string
     */
    protected $code = 'store_shipping_area_duplicate';

    /**
     * 排序
     * @var int
     */
    protected $sort = 51;

    public function __construct($store_id, $source_store_id)
    {
        $this->name = __('店铺配送区域、运费模板', 'shipping');

        parent::__construct($store_id, $source_store_id);

        $this->data_operator = RC_DB::table('shipping_area')->where('store_id', $this->source_store_id);
    }

    /**
     * 数据描述及输出显示内容
     */
    public function handlePrintData()
    {
        $count = $this->handleCount();
        $text      = sprintf(__('店铺内运费模板总共<span class="ecjiafc-red ecjiaf-fs3">%s</span>个', 'shipping'), $count);
        return <<<HTML
<span class="controls-info w400">{$text}</span>
HTML;

    }

    /**
     * 统计数据条数并获取
     *
     * @return mixed
     */
    public function handleCount()
    {
        //如果已经统计过，直接返回统计过的条数
        if ($this->count) {
            return $this->count;
        }
        // 统计数据条数
        if (!empty($this->data_operator)) {
            $this->count = $this->data_operator->count();
        }
        return $this->count;
    }

    /**
     * 执行复制操作
     *
     * @return mixed
     */
    public function handleDuplicate()
    {
        //检测当前对象是否已复制完成
        if ($this->isCheckFinished()) {
            return true;
        }

        //如果当前对象复制前仍存在依赖，则需要先复制依赖对象才能继续复制
        if (!empty($this->dependents)) { //如果设有依赖对象
            //检测依赖
            $items = $this->dependentCheck();
            if (!empty($items)) {
                return new ecjia_error('handle_duplicate_error', __('复制依赖检测失败！', 'store'), $items);
            }
        }

        //执行具体任务
        $this->startDuplicateProcedure();

        //标记处理完成
        $this->markDuplicateFinished();

        //记录日志
        $this->handleAdminLog();

        return true;
    }

    /**
     * 店铺复制操作的具体过程
     */
    protected function startDuplicateProcedure(){
        $this->data_operator->chunk(50, function ($items) {
            //构造可用于复制的数据
            foreach ($items as &$item) {
                unset($item['shipping_area_id']);

                //将源店铺ID设为新店铺的ID
                $item['store_id'] = $this->store_id;

            }

            dd($items);
            //插入数据到新店铺
            RC_DB::table('shipping_area')->insert($items);
        });
    }

    /**
     * 返回操作日志编写
     *
     * @return mixed
     */
    public function handleAdminLog()
    {
        \Ecjia\App\Store\Helper::assign_adminlog_content();

        $store_info = RC_Api::api('store', 'store_info', array('store_id' => $this->store_id));

        $merchants_name = !empty($store_info) ? sprintf(__('店铺名是%s', 'shipping'), $store_info['merchants_name']) : sprintf(__('店铺ID是%s', 'shipping'), $this->store_id);

        ecjia_admin::admin_log($merchants_name, 'clean', 'store_shipping_area');
    }


}