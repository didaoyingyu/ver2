<?php if($check==1) : ?>
<div class="row">
	<h3 class="col-md-12">Assign Student(s) to Class <?= $className; ?></h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="student_name">Student(s): <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="" id="student_ids" class="form-control" placeholder="Type Student name here"/></div>
		<div class="col-md-3" id="student_ids_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="addStudentClassStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-3"></div>
	<div class="col-md-6"><input type="button" value="Assign Student(s)" classid="<?= $cid; ?>" id="assignStudentClass" class="btn btn-primary"/></div>
</div>
<?php endif; ?>