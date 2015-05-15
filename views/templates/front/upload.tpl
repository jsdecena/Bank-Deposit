<div class="rte">
	{capture name=path}{l s='Upload Deposit Slip'}{/capture}
	{if isset($viewable) && $viewable || Tools::getValue('viewable')}
		{if !empty($orders) && $orders}
		<div class="container">
			{if Tools::getValue('success')}
				<p class="alert alert-success">{Tools::getValue('message')}</p>
			{/if}

			{if isset($error)}
				<ul>
					<p class="alert alert-danger">{$error}</p>
				</ul>
			{/if}		
			<h1 class="page-heading">{l s='Upload Deposit Slip'}</h1>
			<form action="{$request_uri}" method="post" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-8">
					<div class="form-group">
						<label for="order">Payment for Order</label>
						<select name="order" id="order" style="border:1px solid #eee; padding: 3px; display:block; min-width:270px">
							{foreach from=$orders item=order}
								<option{if Tools::getValue('id_order') == $order.id_order} selected="selected"{/if} value="{$order.id_order}">Reference #{$order.reference}</option>
							{/foreach}
						</select>
					</div>
					<div class="form-group">
						<label for="deposit">Deposit Slip</label>
						<input type="file" name="deposit" class="form-control" id="deposit" value="{$smarty.post.deposit}" />
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="input-group">
					<button name="upload" class="btn btn-primary" onClick="return confirm('Are you sure you want to upload this file?')">Upload now</button>
					<a href="{$link->getPageLink('history')}" class="btn btn-danger">Look at your Order history</a>
				</div>			
			</form>		
			<div class="clearfix"></div>
		</div>
		{else}
			<p class="alert alert-warning">Sorry, you do not have an order with bank deposit option.</p>
		{/if}
	{else}
		<p class="alert alert-warning">Sorry, this is not your order to update. Contact our Customer Service.</p>
	{/if}
</div>