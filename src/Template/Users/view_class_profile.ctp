<?php
	if($content=='not ok') {
?>
not ok
<?php }else{ ?>
<div class="row">
	<h3 class="col-md-12" style="text-align:center;">Class - <?= $content[0]['full_name']; ?></h3>
</div>
<div class="row">
	<table class="table table-striped table-bordered table-responsive record-table">
		<thead>
			<tr>

				<th>ID</th>
				<th>Full name</th>
				<th>Username</th>
				<th>Email Address</th>
				<th>Actions:</th>
			</tr>
		</thead>
		<tbody class="student-container">
			<?php if($content!=[] && $content[0]['u']['id']!=null) : ?>
			<?php foreach($content as $student) : ?>
			<tr id="student-<?=$student['u']['id']; ?>">
				<td><?= $student['u']['id']; ?></td>
				<td><?= $student['u']['full_name']; ?></td>
				<td><?= $student['u']['username']; ?></td>
				<td><?= $student['u']['email_address']; ?></td>
				<td>
					<span><?php echo $this->Html->link('Remove Student from this class','#',['class'=>'removefromclass','removeid'=>$student['u']['id'],'classid'=>intval($_GET['classid'])]);?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=5 style="text-align:center;">No Student added to this Class yet. <?php echo $this->Html->link('Add Student to this Class now?','#',['class'=>'addUsertoClassNow','addclassid'=>intval($_GET['classid'])]);?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="row">
	<ul id="reviewLogPagination" class="pagination">
		<?php echo $this->Paginator->prev(' << ' . __('previous')); ?>
		<?php echo $this->Paginator->numbers(); ?>
		<?php echo $this->Paginator->prev(' >> ' . __('next')); ?>
	</ul>
</div>
<div class="row">
	<?php echo $this->Html->link('Add Student to this Class now?','#',['class'=>'addUsertoClassNow','addclassid'=>intval($_GET['classid']),'style'=>'text-align:center;display:block;']);?>
</div>
<div class="row">
	<?php echo $this->Html->link('Back to Classes','#',['class'=>'manage-classes','style'=>'text-align:center;display:block;']);?>
</div>
<?php } ?>

