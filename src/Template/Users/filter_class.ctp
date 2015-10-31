<table class="table table-striped table-bordered table-responsive">
		<thead>
			<tr>
				<th>Class ID</th>
				<th>Class Name</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if($classes!=[]) : ?>
			<?php foreach($classes as $class) : ?>
			<tr id="class-<?= $class['id']; ?>">
				<td><?= $class['id']; ?></td>
				<td><?= $class['full_name']; ?></td>
				<td>
					<span><?= $this->Html->link('Assign Deck','#',['class'=>'assignDeck','classid'=>$class['id']]); ?></span>
					<span><?= $this->Html->link('Edit Class','#',['class'=>'editClass','classid'=>$class['id']]); ?></span>
					<span><?= $this->Html->link('View Students','#',['class'=>'viewClassInfo','classid'=>$class['id']]); ?></span>
					<span><?= $this->Html->link('View Decks','#',['class'=>'viewClassDeckInfo','classid'=>$class['id']]); ?></span>
					<span><?= $this->Html->link('Delete Class','#',['class'=>'deleteClass','classid'=>$class['id']]); ?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=3 style="text-align:center;">No Classes Added yet.<?= $this->Html->link('Add now?','#',['class'=>'add-class-now-filter','userid'=>$globalUserId]);?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
<div style="text-align:center;">
	<?= $this->Html->link('Add Class to this user?','#',['class'=>'add-class-now-filter','userid'=>$globalUserId]);?>
</div>
<div class="row">
	<ul id="reviewLogPagination" class="pagination">
		<?php echo $this->Paginator->prev(' << ' . __('previous')); ?>
		<?php echo $this->Paginator->numbers(); ?>
		<?php echo $this->Paginator->prev(' >> ' . __('next')); ?>
	</ul>
</div>