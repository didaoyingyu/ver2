<?php if($status['status']=='ok') : ?>
<div class="row">
	<h3 class="col-md-12">Add Class</h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="class_name">Class Name:</label></div>
		<div class="col-md-3"><input type="text" value="" id="class_name" class="form-control" /></div>
		<div class="col-md-3" id="class_name_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6" id="addClassStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6"><input type="button" value="Add Class" id="addClassFilter" userid="<?= $status['userid']; ?>" class="btn btn-primary"/></div>
</div>
<?php endif; ?>