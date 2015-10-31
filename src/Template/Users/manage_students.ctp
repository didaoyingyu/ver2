<div class="row">
	<h3 class="col-md-12">Students</h3>
</div>
<div class="row">
	<table class="table table-striped table-bordered table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Full name</th>
				<th>Username</th>
				<th>Email Address</th>
				<th>Status</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if($students!=[]) : ?>
			<?php foreach($students as $student) : ?>
			<tr id="student-<?= $student['id']; ?>">
				<td><?= $student['id']; ?></td>
				<td><?= $student['full_name']; ?></td>
				<td><?= $student['username']; ?></td>
				<td><?= $student['email_address']; ?></td>
				<td style="color:<?php if($student['is_approve']==1) : ?>green<?php else : ?>red<?php endif; ?>;">
				<?php
					if($student['is_approve']==1)
						echo 'Approved';
					else
						echo 'Pending';
				?>
				</td>
				<td>
					<span><?= $this->Html->link('Edit Student','#',['class'=>'editStudent','studentid'=>$student['id']]); ?></span>
					<?php if($student['is_approve']==1) : ?>
					<span><?= $this->Html->link('Unapprove Student','#',['class'=>'unapproveStudent','studentid'=>$student['id']]); ?></span>
					<?php else : ?>
					<span><?= $this->Html->link('Approve Student','#',['class'=>'approveStudent','studentid'=>$student['id']]); ?></span>
					<?php endif; ?>
					<span><?= $this->Html->link('Delete Student','#',['class'=>'deleteStudent','studentid'=>$student['id']]); ?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=6 style="text-align:center;">No Students yet.</td>
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