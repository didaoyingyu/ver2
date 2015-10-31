
<?php if($status['status']=='ok') : ?>
<div class="row">
	<h3 class="col-md-12">Student Information</h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="student_name">Student Full Name: <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="<?= $status['student']['full_name']; ?>" id="student_name" class="form-control" /></div>
		<div class="col-md-3" id="student_name_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="student_sx">Single X: <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="<?= $status['student']['sx']; ?>" id="student_sx" class="form-control" /></div>
		<div class="col-md-3" id="student_sx_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="student_sx">Double X: <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="<?= $status['student']['dx']; ?>" id="student_dx" class="form-control" /></div>
		<div class="col-md-3" id="student_dx_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="student_sx">Single Tick: <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="<?= $status['student']['st']; ?>" id="student_st" class="form-control" /></div>
		<div class="col-md-3" id="student_st_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="student_sx">Double Tick: <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="<?= $status['student']['dt']; ?>" id="student_dt" class="form-control" /></div>
		<div class="col-md-3" id="student_dt_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="saveEditStudentStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-3"></div>
	<div class="col-md-6"><input type="button" value="Save" id="saveEditStudent" studentid="<?= $status['student']['id']; ?>" class="btn btn-primary"/></div>
</div>
<?php endif; ?>