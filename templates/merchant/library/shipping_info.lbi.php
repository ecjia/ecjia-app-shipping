{if $shipping_area.shipping_code != 'ship_cac'}
<!-- {foreach from=$fields item=field} -->
	{if $in && $field.name == 'fee_compute_mode'}
		<div class="form-group" id="{$field.name}">
			<label class="control-label col-lg-4">{$field.label}</label>
			<div class="controls col-lg-6">
				<input type="radio" id="fee_compute_mode_by_weight" name="fee_compute_mode" {if $field.value eq 'by_weight'}checked{/if} value="by_weight" data-code="{$shipping_area.shipping_code}"/>
				<label for="fee_compute_mode_by_weight">按重量</label>
				<input type="radio" id="fee_compute_mode_by_number" name="fee_compute_mode" {if $field.value eq 'by_number'}checked{/if} value="by_number" data-code="{$shipping_area.shipping_code}"/>
				<label for="fee_compute_mode_by_number">按件数</label>
			</div>
		</div>
	{/if}
	
	{if $config.fee_compute_mode == 'by_number'}
		{if $field.name == 'item_fee' || $field.name == 'free_money' || $field.name == 'pay_fee'}
			<div class="form-group" id="{$field.name}">
				<label class="control-label col-lg-4">{$field.label}</label>
				<div class="controls col-lg-6">
					<input class="form-control" name="{$field.name}" type="text" value="{if $config.value}{$config.value}{else}{if $field.value}{$field.value}{else}0{/if}{/if}"/>
				</div>
				<span class="input-must">*</span>
			</div>
		{else if $field.name != 'fee_compute_mode'}
			<div class="form-group" id="{$field.name}" style="display:none;">
				<label class="control-label col-lg-4">{$field.label}</label>
				<div class="controls col-lg-6">
					<input class="form-control" name="{$field.name}" type="text" value="{if $config.value}{$config.value}{else}{if $field.value}{$field.value}{else}0{/if}{/if}"/>
				</div>
				<span class="input-must">*</span>
			</div>
		{/if}
	{else}
		{if $field.name != 'item_fee' && $field.name != 'fee_compute_mode'}
			<div class="form-group" id="{$field.name}">
				<label class="control-label col-lg-4">{$field.label}</label>
				<div class="controls col-lg-6">
					<input class="form-control" name="{$field.name}" type="text" value="{if $config.value}{$config.value}{else}{if $field.value}{$field.value}{else}0{/if}{/if}"/>
				</div>
				<span class="input-must">*</span>
			</div>
		{else if $field.name == 'item_fee'}
			<div class="form-group" id="{$field.name}" style="display:none;">
				<label class="control-label col-lg-4">{$field.label}</label>
				<div class="controls col-lg-6">
					<input class="form-control" name="{$field.name}" type="text" value="{if $config.value}{$config.value}{else}{if $field.value}{$field.value}{else}0{/if}{/if}"/>
				</div>
				<span class="input-must">*</span>
			</div>
		{/if}
	{/if}
<!-- {/foreach} -->
{/if}