<div class="row">
	<h3 class="col-md-12">Teachers</h3>
</div>
<div class="row">
	<table class="table table-striped table-bordered table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Full name</th>
				<th>Username</th>
				<th>Email Address</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if($teachers!=[]) : ?>
			<?php foreach($teachers as $teacher) : ?>
			<tr id="teacher-<?= $teacher['id']; ?>">
				<td><?= $teacher['id']; ?></td>
				<td><?= $teacher['full_name']; ?></td>
				<td><?= $teacher['username']; ?></td>
				<td><?= $teacher['email_address']; ?></td>
				<td>
					<span><?= $this->Html->link('Delete Teacher','#',['class'=>'deleteTeacher','teacherid'=>$teacher['id']]); ?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=5 style="text-align:center;">No teachers yet.</td>
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