<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * ECJia 管理中心配送方式管理语言文件
 */
$LANG['03_shipping_list'] = '配送方式';


$LANG['shipping_name'] = '名称';
$LANG['shipping_version'] = '插件版本';
$LANG['shipping_desc'] = '描述';
$LANG['shipping_author'] = '插件作者';
$LANG['insure'] = '保价费用';
$LANG['support_cod'] = '货到付款：';
$LANG['shipping_area'] = '设置区域';
$LANG['shipping_print_edit'] = '编辑打印模板';
$LANG['shipping_print_template'] = '快递单模板';
$LANG['shipping_template_info_t']= '订单模板变量说明';
$LANG['shipping_template_info_l'] = '{$shop_name}表示网店名称<br/>{$province}表示网店所属省份<br/>{$city}表示网店所属城市<br/>{$shop_address}表示网店地址<br/>{$service_phone}表示网店联系电话<br/>';
$LANG['shipping_template_info_c'] = '{$order.order_amount}表示订单金额<br/>{$order.region}表示收件人地区<br/>{$order.tel}表示收件人电话<br/>{$order.mobile}表示收件人手机<br/>';
$LANG['shipping_template_info_r'] = '{$order.zipcode}表示收件人邮编<br/>{$order.address}表示收件人详细地址<br/>{$order.consignee}表示收件人名称<br/>{$order.order_sn}表示订单号';

$LANG['enable'] = '启用';
$LANG['disable'] = '关闭';

/* 表单部分 */
$LANG['shipping_install'] = '安装配送方式';
$LANG['install_succeess'] = '配送方式 %s 安装成功！';
$LANG['del_lable'] = '删除标签';
$LANG['upload_shipping_bg'] = '上传打印单图片';
$LANG['del_shipping_bg'] = '删除打印单图片';
$LANG['save_setting'] = '保存设置';
$LANG['recovery_default'] = '恢复默认';
$LANG['recovery_default'] = '恢复默认';

$LANG['attradd_succed'] = '操作成功!';
$LANG['attradd_faild']  = '操作失败!';

/* 快递单部分 */
$LANG['lable_select_notice'] = '--选择插入标签--';
$LANG['lable_box']['shop_country'] = '网店-国家';
$LANG['lable_box']['shop_province'] = '网店-省份';
$LANG['lable_box']['shop_city'] = '网店-城市';
$LANG['lable_box']['shop_name'] = '网店-名称';
$LANG['lable_box']['shop_district'] = '网店-区/县';
$LANG['lable_box']['shop_tel'] = '网店-联系电话';
$LANG['lable_box']['shop_address'] = '网店-地址';
$LANG['lable_box']['customer_country'] = '收件人-国家';
$LANG['lable_box']['customer_province'] = '收件人-省份';
$LANG['lable_box']['customer_city'] = '收件人-城市';
$LANG['lable_box']['customer_district'] = '收件人-区/县';
$LANG['lable_box']['customer_tel'] = '收件人-电话';
$LANG['lable_box']['customer_mobel'] = '收件人-手机';
$LANG['lable_box']['customer_post'] = '收件人-邮编';
$LANG['lable_box']['customer_address'] = '收件人-详细地址';
$LANG['lable_box']['customer_name'] = '收件人-姓名';
$LANG['lable_box']['year'] = '年-当日日期';
$LANG['lable_box']['months'] = '月-当日日期';
$LANG['lable_box']['day'] = '日-当日日期';
$LANG['lable_box']['order_no'] = '订单号-订单';
$LANG['lable_box']['order_postscript'] = '备注-订单';
$LANG['lable_box']['order_best_time'] = '送货时间-订单';
$LANG['lable_box']['pigeon'] = '√-对号';
//$LANG['lable_box']['custom_content'] = '自定义内容';

/* 提示信息 */
$LANG['no_shipping_name'] = '对不起，配送方式名称不能为空。';
$LANG['no_shipping_desc'] = '对不起，配送方式描述内容不能为空。';
$LANG['change_shipping_desc_faild'] = '配送方式描述内容修改失败。';
$LANG['repeat_shipping_name'] = '对不起，已经存在一个同名的配送方式。';
$LANG['uninstall_success'] = '配送方式 %s 已经成功卸载。';
$LANG['add_shipping_area'] = '为该配送方式新建配送区域';
$LANG['no_shipping_insure'] = '对不起，保价费用不能为空，不想使用请将其设置为0';
$LANG['not_support_insure'] = '该配送方式不支持保价,保价费用设置失败';
$LANG['invalid_insure'] = '配送保价费用不是一个合法价格';
$LANG['no_shipping_install'] = '您的配送方式尚未安装，暂不能编辑模板';
$LANG['edit_template_success'] = '快递模板已经成功编辑。';

/* JS 语言 */
$LANG['js_languages']['lang_removeconfirm'] = '您确定要卸载该配送方式吗？';
$LANG['js_languages']['shipping_area'] = '设置区域';
$LANG['js_languages']['upload_falid'] = '错误：文件类型不正确。请上传“%s”类型的文件！';
$LANG['js_languages']['upload_del_falid'] = '错误：删除失败！';
$LANG['js_languages']['upload_del_confirm'] = "提示：您确认删除打印单图片吗？";
$LANG['js_languages']['no_select_upload'] = "错误：您还没有选择打印单图片。请使用“浏览...”按钮选择！";
$LANG['js_languages']['no_select_lable'] = "操作终止！您未选择任何标签。";
$LANG['js_languages']['no_add_repeat_lable'] = "操作失败！不允许添加重复标签。";
$LANG['js_languages']['no_select_lable_del'] = "删除失败！您没有选中任何标签。";
$LANG['js_languages']['recovery_default_suer'] = "您确认恢复默认吗？恢复默认后将显示安装时的内容。";


// end