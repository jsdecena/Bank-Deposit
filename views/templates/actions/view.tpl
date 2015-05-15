<div class="bootstrap">
	<div class="col-md-10">
		<a href="{$url}&page=add" class="btn btn-primary">Add a Bank</a> <br /> <br />
        {if Tools::getValue('success')}
            <p class="alert alert-success">{Tools::getValue('message')}</p>
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
	</div>
</div>