<div class="bootstrap">
    {if isset($error)}
        <p class="alert alert-danger">{$error}</p>
    {/if}
	<div class="col-md-10">
		<form action="{$action_update}" method="post" class="form-horizontal">
			<div class="form-group">
				<label for="country">Country</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$country}" name="country" />
				</div>
			</div>
			<div class="form-group">
				<label for="account_name">Account Name</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$account_name}" name="account_name" />
				</div>
			</div>
			<div class="form-group">
				<label for="account_number">Account Number</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$account_number}" name="account_number" />
				</div>
			</div>
			<div class="form-group">
				<label for="account_type">Account Type</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$account_type}" name="account_type" />
				</div>
			</div>
			<div class="form-group">
				<label for="bank_name">Bank Name</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$bank_name}" name="bank_name" />
				</div>
			</div>
			<div class="form-group">
				<label for="bank_branch">Bank Branch</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$bank_branch}" name="bank_branch" />
				</div>
			</div>
			<div class="form-group">
				<label for="swift_code">Swift Code</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$swift_code}" name="swift_code" />
				</div>
			</div>
			<div class="form-group">
				<label for="logo">Logo</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="{$logo}" name="logo" />
				</div>
			</div>
			<div class="input-group">
				<input type="hidden" name="id" value="{$id_bank}" />
				<a href="{$url}" class="btn btn-default">Back to Configure</a>
				<button type="submit" name="actionUpdateBank" class="btn btn-primary">Update bank</button>				
			</div>
		</form>		
	</div>
</div>