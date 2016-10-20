<?php defined('IN_ECJIA') or exit('No permission resources.');?>
<!-- {extends file="ecjia-merchant.dwt.php"} -->

<!-- {block name="home-content"} -->
<div class="page-header">
	<div class="pull-left">
		<h2><!-- {if $ur_here}{$ur_here}{/if} --></h2>
  	</div>
  	<div class="pull-right ">
  		{if $action_link}
		<a href="{$action_link.href}" class="btn btn-primary data-pjax ">
			<i class="fa fa-search"></i> {$action_link.text}
		</a>
		{/if}
  	</div>
  	<div class="clearfix"></div>
</div>

<div class="row">
	<div class="col-lg-12">
	    <div class="panel">
	    	<div class="panel-body">
				<div class="row-fluid">
					<section class="panel">
						<table class="table table-striped table-hide-edit">
							<thead>
								<tr>
									<th class="w110">{lang key='shipping::shipping.shipping_name'}</th>
									<th>{lang key='shipping::shipping.shipping_desc'}</th>
									<th class="w80">{lang key='system::system.sort_order'}</th>
									<th class="w80">{lang key='shipping::shipping.insure'}</th>
									<th class="w100">{lang key='shipping::shipping.support_cod'}</th>
								</tr>
							</thead>
							<tbody>
								<!-- {foreach from=$modules item=module} -->
								<tr>
									<td>
										{$module.name}
									</td>
									<td class="hide-edit-area">
									<!-- {if $module.enabled == 1} -->
											{$module.desc|nl2br}
											<div class="edit-list">
												<a class="data-pjax" href='{RC_Uri::url("shipping/mh_area/init", "shipping_id={$module.id}&code={$module.code}")}'  title="{lang key='shipping::shipping.shipping_area'}">{lang key='shipping::shipping.set_shipping'}</a>
											</div>
										<!-- {else} -->
											{$module.desc|nl2br}
										<!-- {/if} -->
										
									</td>
									<td>
										{$module.shipping_order}
									</td>
									<td>
										<!-- {if $module.is_insure } -->
											{$module.insure_fee}
										<!-- {else} -->
											{lang key='shipping::shipping.not_support'}
										<!-- {/if} -->
									</td>
									<td>
										{if $module.cod==1}
											{lang key='system::system.yes'}
										{else}
											{lang key='system::system.no'}
										{/if}
									</td>
								</tr>
								<!-- {foreachelse} -->
								<tr><td class="no-records" colspan="10">{lang key='system::system.no_records'}</td></tr>
								<!-- {/foreach} -->
							</tbody>
						</table>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- {/block} -->