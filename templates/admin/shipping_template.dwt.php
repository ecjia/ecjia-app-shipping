<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->

<!-- {block name="footer"} -->
<script type="text/javascript">
	ecjia.admin.shipTemplate.init_template_1();
</script>
<!-- {/block} -->

<!-- {block name="main_content"} -->
<div class="admin_shipping">
	<div>
		<h3 class="heading">
			<!-- {if $ur_here}{$ur_here}{/if} -->
			{if $action_link}
			<a href="{$action_link.href}" class="btn plus_or_reply data-pjax" id="sticky_a"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
			{/if}
		</h3>
	</div>

	<div class="edit-page">
		<div style="text-align: center;margin:20px 0 20px 0;">
		    <button type="button" id="model_2" {if $shipping.print_model == 2}class="btn btn-gebo" {else} class="btn" {/if} onclick="javascript:ecjia.admin.shipTemplate.template_radio_click('2');" >所见即所得模式</button>&nbsp;&nbsp;&nbsp;
		    <button type="button" id="model_1" {if $shipping.print_model == 1}class="btn btn-gebo" {else} class="btn" {/if} onclick="javascript:ecjia.admin.shipTemplate.template_radio_click('1');">代码模式</button>
		</div>
		
		<!--模板模式-->
		<table id="general-table" {if $shipping.print_model == 2}class="w900"{/if}>
			<tr id="visual" {if $shipping.print_model == 1}style="display:none"{else} style="display:block" {/if}>
				<td colspan="2" style="display: inherit;">
					<!--Flash播放器 start-->
					{assign var=goods_desc_src value=RC_Uri::url('shipping/admin/print_index',"shipping_id={$shipping_id}")}
					<iframe id="goods_desc___Frame" src="{$goods_desc_src}" width="100%" height="800" frameborder="0" ></iframe>
					<!--Flash播放器 end-->
				</td>
			</tr>
		</table>
		
		<!--代码模式-->
		<div class="row-fluid edit-page">
		    <div class="span12">
				<div class="tabbable">
				    <form class="form-horizontal" name="templateForm_1" action='{url path="shipping/admin/do_edit_print_template"}' method="post">
				    	<fieldset>
					        <div class="chat_box library-content">
				                <div id="code_shipping_print" {if $shipping.print_model == 2}style="display:none"{/if} class="span9">
				       				<textarea style="width:750px;height:450px;" id="shipping_print" name="shipping_print" rows="40" cols="3" >{$shipping.shipping_print|escape:html}</textarea>
				                </div>
				                
				                <div class="span3 chat_sidebar" id="code_shipping_help" {if $shipping.print_model == 2}style="display:none"{/if}>
				                    <div class="chat_heading clearfix">
				                        {t}订单模版变量说明{/t}
				                    </div>
				                    <div class="ms-selectable" style="height:425px;" >
				                        <div class="template_list" id="ms-custom-navigation">
				                            {lang key='shipping::shipping.shipping_template_info_all'}
				                        </div>
				                    </div>
				                </div>
					        </div>
					        
			        		<div id="code_submit" {if $shipping.print_model == 2}style="display:none"{/if}>
								<button class="btn btn-gebo m_t15 m_b15" type="button" id="save_template_1">{lang key='system::system.button_submit'}</button>
								<input type="hidden" name="shipping_id" value="{$shipping.shipping_id}">
								<input type="hidden" name="print_model" value="1">
								<input type="hidden" name="shipping_name" value="{$shipping.shipping_name}">
							</div>
						</fieldset>
					 </form>
				 </div>
		    </div>
		</div>
	</div>
</div>
<!-- {/block} -->