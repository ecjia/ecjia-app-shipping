<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a class="data-pjax" href="{$action_link.href}" class="btn" id="sticky_a" style="float:right;margin-top:-3px;"><i class="fontello-icon-reply"></i>{$action_link.text}</a>
		<!-- {/if} -->
	</h3>
</div>	
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped table-hide-edit">
			<thead>
				<tr>
					<th class="w100">{$lang.shipping_name}</th>
					<th class="w550">{$lang.shipping_desc}</th>
					<th>{$lang.sort_order}</th>
					<th>{$lang.insure}</th>
					<th>{$lang.support_cod}</th>
				</tr>
			</thead>
			<tbody>
				<!-- {foreach from=$modules item=module} -->
				<tr>
					<td>
						<!-- {if $module.enabled == 1} -->
							<span class="shipping_name cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('shipping/admin/edit_name')}" data-name="title" data-pk="{$module.id}"  data-title="编辑配送方式名称">{$module.name}</span>
						<!-- {else} -->
							{$module.name}
						<!-- {/if} -->
					</td>
					<td class="hide-edit-area">
					<!-- {if $module.enabled == 1} -->
							{$module.desc|nl2br}
							<div class="edit-list">
								{assign var=shipping_init value=RC_Uri::url('shipping/admin_area/init',"code={$module.code}&shipping={$module.id}")}
								<a class="data-pjax" href="{$shipping_init}"  title="{$lang.shipping_area}">{t}设置配送区域{/t}</a>&nbsp;|&nbsp;
								{assign var=shipping_edit_print_template value=RC_Uri::url('shipping/admin/edit_print_template',"code={$module.code}&shipping={$module.id}")}
								<a class="data-pjax" href="{$shipping_edit_print_template}" title="{$lang.shipping_print_edit}">{t}编辑打印模板{/t}</a>&nbsp;|&nbsp;
								{assign var=shipping_disable value=RC_Uri::url('shipping/admin/disable',"code={$module.code}")}
								<a class="switch ecjiafc-red" href='javascript:ecjia.admin.shopping_list.plugin_state_click("{$shipping_disable}");' title="{$lang.disable}">{t}禁用{/t}</a>
							</div>
						<!-- {else} -->
							{$module.desc|nl2br}
							<div class="edit-list">
								{assign var=shipping_enable value=RC_Uri::url('shipping/admin/enable',"code={$module.code}")}
								<a class="switch" href='javascript:ecjia.admin.shopping_list.plugin_state_click("{$shipping_enable}");' title="{$lang.enable}">{t}启用{/t}</a>
							</div>
						<!-- {/if} -->
						
					</td>
					<td>
						<!-- {if $module.enabled == 1} -->
							<span class="shipping_order cursor_pointer" data-trigger="editable" data-url="{RC_Uri::url('shipping/admin/edit_order')}" data-name="title" data-pk="{$module.id}"  data-title="编辑配送方式排序">{$module.shipping_order}</span>
						<!-- {else} -->
							{$module.shipping_order}
						<!-- {/if} -->
					</td>
					<td>
						<!-- {if $module.is_insure } -->
							{$module.insure_fee}
						<!-- {else} -->
							不支持
						<!-- {/if} -->
					</td>
					<td>
						{if $module.cod==1}
							{$lang.yes}
						{else}
							{$lang.no}
						{/if}
					</td>
				</tr>
				<!-- {foreachelse} -->
				   <tr><td class="no-records" colspan="10">{t}没有找到任何记录{/t}</td></tr>
				<!-- {/foreach} -->
			</tbody>
		</table>
	</div>
</div>
<!-- {/block} -->
