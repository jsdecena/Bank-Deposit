<div class="bootstrap">
    {if isset($error)}
        <p class="alert alert-danger">{$error}</p>
    {/if} 	
	<div class="col-md-10">
		<form action="{$action_add}" method="post" class="form-horizontal">
			<div class="form-group">
				<label for="country">Country</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="country" placeholder="Country" />
				</div>
			</div>
			<div class="form-group">
				<label for="account_name">Account Name</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="account_name" placeholder="Account Name" />
				</div>
			</div>
			<div class="form-group">
				<label for="account_number">Account Number</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="account_number" placeholder="Account Number" />
				</div>
			</div>
			<div class="form-group">
				<label for="account_type">Account Type</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="account_type" placeholder="Account Type" />
				</div>
			</div>
			<div class="form-group">
				<label for="bank_name">Bank Name</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="bank_name" placeholder="Bank Name" />
				</div>
			</div>
			<div class="form-group">
				<label for="bank_branch">Bank Branch</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="bank_branch" placeholder="Bank Branch" />
				</div>
			</div>
			<div class="form-group">
				<label for="swift_code">Swift Code</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="swift_code" placeholder="Swift Code" />
				</div>
			</div>
			<div class="form-group">
				<label for="logo">Logo</label>
				<div class="form-controls">
					<input type="text" class="form-control" value="" name="logo" placeholder="Logo Name" />
				</div>
			</div>
			<div class="form-group">
				<label for="active">Enable?</label>
				<div class="form-controls">
					<select name="active" id="active">
						<option value="0" selected="selected">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
			</div>			
			<button type="submit" name="actionAddBank" class="btn btn-primary">Save Bank</button>
		</form>		
	</div>
</div>