<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
<div class="bootstrap">
	<div class="col-md-10">
		<form action="{$settings}" method="POST" class="form-horizontal">
			<div class="form-group">
				<h3>Payment Logo</h3>
				<input type="text" name="payment_logo" value="{$logo}" class="form-control" placeholder="Absolute path of the image." />
			</div>
			<button type="submit" class="btn btn-primary" name="updateSettings">Submit Settings</button>
		</form> 
	</div>
	<div class="col-md-10"><br />
	    {if Tools::getValue('success')}
	        <br /><p class="alert alert-success">{Tools::getValue('message')}</p>
	    {elseif isset($error)}
	    	<p class="alert alert-danger">{$error}</p>
	    {/if}		
		{if !empty($records)}
		<div class="panel">
			<table class="table">
				<thead>
					<th class="col-md-1">ID</th>
					<th class="col-md-1">Logo</th>
					<th class="col-md-1">Country</th>
					<th class="col-md-1">Account</th>
					<th class="col-md-2">Account Number</th>
					<th class="col-md-1">Account Type</th>
					<th class="col-md-1">Bank Name</th>
					<th class="col-md-1">Bank Branch</th>
					<th class="col-md-1">Swift Code</th>
					<th>Status</th>
					<th class="col-md-2">Actions</th>
				</thead>
				<tbody>
					{foreach from=$records item=record}
					<tr>
						<td>{$record.id}</td>
						<td><img src="{$imgUrl}{$record.logo}" alt="" width="48" height="48" class="img-responsive" /></td>
						<td>{$record.country}</td>
						<td>{$record.account_name}</td>
						<td>{$record.account_number}</td>
						<td>{$record.account_type}</td>
						<td>{$record.bank_name}</td>
						<td>{$record.bank_branch}</td>
						<td>{$record.swift_code}</td>
						<td>
							{if $record.active == 0}
								<a href="javascript:void(0)" class="btn btn-danger"><i class="fa fa-times"></i></a>
							{else}
								<a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-check"></i></a>
							{/if}
						</td>						
						<td>
							<a href="{$url}&page=update&id={$record.id}" class="btn btn-default">Update</a>
							<a onClick="return confirm('Are you really sure?')" href="{$url}&page=delete&id={$record.id}" class="btn btn-danger">Delete</a>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		{else}<br /> <br /> <p class="alert alert-warning">Sorry, no banks found!</p>
		{/if}

		<a href="{$url}&page=add" class="btn btn-primary">Add a Bank</a> <br /> <br />		
	</div>
</div>
<hr />
<script type="text/javascript">
	{literal}$('#myTab a').click(function (e) {
  		e.preventDefault();
  		$(this).tab('show');
	});{/literal}
</script>