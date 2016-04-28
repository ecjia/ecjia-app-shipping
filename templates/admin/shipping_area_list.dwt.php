<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia.dwt.php"} -->
<!-- {block name="main_content"} -->
<div>
	<h3 class="heading">
		<!-- {if $ur_here}{$ur_here}{/if} -->
		<!-- {if $action_link} -->
		<a href="{$action_link.href}" class="btn plus_or_reply data-pjax" id="sticky_a"><i class="fontello-icon-plus"></i>{$action_link.text}</a>
		<!-- {/if} -->
		<!-- {if $shipping_method} -->
		<a href="{$shipping_method.href}" class="btn plus_or_reply data-pjax" id="sticky_a"><i class="fontello-icon-reply"></i>{$shipping_method.text}</a>
		<!-- {/if} -->
	</h3>
</div>
<div class="row-fluid">
	<div class="span12">
		<form method="post" action="{$form_action}" name="listForm" >
			<input type="hidden" name="shipping_id" value="{$shipping_id}" />
			<input type="hidden" name="code" value="{$code}" />
			<div class="row-fluid">
				<div class="btn-group ">
					<button data-toggle="dropdown" class="btn dropdown-toggle btnSubmit" >
						<i class="fontello-icon-cog"></i>
						{$lang.batch}
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a class="button_remove" data-name="area_ids" data-noselectmsg="请先选中要删除的配送区域！" data-url="{$form_action}" data-msg="您确定要这么做吗？" data-idclass=".checkbox:checked" data-toggle="ecjiabatch"  href="javascript:;"><i class="fontello-icon-trash"></i>{t}{$lang.remove}{/t}</a></li>
					</ul>
				</div>
				<div class="choose_list f_r" >
					<input type="text" name="keywords" value="{$areas.filter.keywords}" placeholder="请输入配送区域名称关键字"/> 
					<button class="btn" type="button" id="search_btn" onclick='javascript:ecjia.admin.shippingObj.shipping_area_list_search("{$search_action}")'>{t}搜索{/t}</button>
				</div>
			</div>
			<div class="row-fluid">
				<table class="table table-striped smpl_tbl">
					<thead>
						<tr>
							<th class="table_checkbox">
								<input type="checkbox" data-children=".checkbox" data-toggle="selectall" name="select_rows" style="opacity: 0;">
							</th>
							<th class="w250">{$lang.shipping_area_name}</th>
							<th >{$lang.shipping_area_regions}</th>
							<th class="w150">{$lang.handler}</th>
						</tr>
					</thead>
					<tbody>
						<!-- {foreach from=$areas.areas_list item=area} -->
						<tr id="aa">
							<td> 
								<input class="checkbox" type="checkbox" value="{$area.shipping_area_id}"  name="areas[]" style="opacity: 0;">
							</td>
							<td>{$area.shipping_area_name|escape:"html"}</td>
							<td>{$area.shipping_area_regions}</td>
							<td>
								<a class="data-pjax no-underline" href='{RC_Uri::url("shipping/admin_area/edit","id={$area.shipping_area_id}&shipping_id={$shipping_id}&code={$code}")}' class="sepV_a" title="{$lang.edit}"><i class="fontello-icon-edit"></i></a>
								<a class="ajaxremove  no-underline" data-toggle="ajaxremove" data-msg="{t}您确定要删除配送区域[{$area.shipping_area_name}]吗？{/t}" href='{RC_Uri::url("shipping/admin_area/remove_area", "id={$area.shipping_area_id}")}' title="{t}{$lang.remove}{/t}"><i class="fontello-icon-trash"></i></a>
							</td>
						</tr>
						<!-- {foreachelse} -->
					    <tr><td class="no-records" colspan="10">{t}没有找到任何记录{/t}</td></tr>
						<!-- {/foreach} -->
					</tbody>
				</table>
				<!-- {$areas.page} -->	
				<div class="hide">
					<!-- confirmation box -->
					<div id="confirm_dialog" class="cbox_content">
						<div class="sepH_c tac"><strong>{$lang.remove_confirm}</strong></div>
						<div class="tac">
							<a href="javascript:;" class="btn btn-gebo confirm_yes">是</a>
							<a href="javascript:;" class="btn confirm_no">否</a>
						</div>
					</div>
				</div>
			</div>
		</form>	
	</div>
</div>
<!-- {/block} -->