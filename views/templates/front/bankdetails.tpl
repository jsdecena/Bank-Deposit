{capture name=path}{l s='Bank Deposit Information'}{/capture}

<h1 class="page-heading">{l s='Bank Deposit Information'}</h1>

<div class="container" id="banks">
	{if !empty($records)}
	<p class="alert alert-warning">
		Reminder: <br /> 
		1. You will get the link to upload your deposit slip in your email once you confirm this mode of payment. <br />
		2. These bank details are also included in the email that will be sent to you upon confirmation. <br />
		3. The order might be a bit delayed as the payment will reflect in our account 3 business days after you have paid the total amount.
	</p>
	<div class="clearfix"></div>
	<ul>
		{foreach from=$records item=record}
		<li class="col-md-3">
			<div class="well">
				<p><img src="{$imgUrl}{$record.logo}" alt="bank logo" width="128" height="128" class="img-responsive" /></p>
				<p><span>Country:</span> {$record.country}</p>
				<p><span>Account Name:</span> {$record.account_name}</p>
				<p><span>Account Number:</span> {$record.account_number}</p>
				<p><span>Account Type:</span> {$record.account_type}</p>
				{if $record.bank_name}<p><span>Bank Name:</span> {$record.bank_name}</p>{/if}
				{if $record.bank_branch}<p><span>Bank Branch:</span> {$record.bank_branch}</p>{/if}
				{if $record.swift_code}<p><span>Swift Code:</span> {$record.swift_code}</p>{/if}
			</div>
		</li>
		{/foreach}
	</ul>
		<div class="clearfix"></div>
		<div class="input-group">
			<form action="{$action}" method="post">
				<a href="{$link->getPageLink('order-opc')}" class="btn btn-info">Choose a different method</a>
				<button type="submit" class="btn btn-success">Confirm payment</button>
			</form>
		</div>
	{else}
		<p class="alert alert-warning">Sorry, no banks defined. Call customer service.</p>
	{/if}
</div>

<style type="text/css">
	#banks ul li span {
		font-weight: bold;
	}
	#banks ul li img {
		display: block;
		width: 128px;
		margin: 0 auto;
	}
	#banks ul li div {
		min-height: 425px;
	}
</style>