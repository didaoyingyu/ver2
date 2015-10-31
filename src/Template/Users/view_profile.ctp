<div class="row">
	<h3 class="col-md-12">Profile Information</h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="full_name">Full Name:</label></div>
		<div class="col-md-3"><input type="text" class="form-control" value="<?= $curUser['full_name']; ?>" id="full_name"/></div>
		<div class="col-md-3" id="full_name_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6" id="saveStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6"><input type="button" value="Save" id="saveProfileDashboard" class="btn btn-primary"/></div>
</div>