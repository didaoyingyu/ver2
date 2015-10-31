<div class="row">
	<h3 class="col-md-12">Classes</h3>
</div>
<?php if($curUser['account_id']==1) : ?>
<div class="row">
	<div class="col-md-7"></div>
	<div class="col-md-3">
		<select class="form-control" id="classselect">
		  <option value="0">All Classes</option>
		  <option value="<?= $usersLists[0]['value']; ?>"><?= $usersLists[0]['inner']; ?> (Your Class)</option>
		   <?php foreach($usersLists as $userList) : ?>
		   <?php if($userList['value']!=$usersLists[0]['value']) : ?>
		   <option value="<?= $userList['value']; ?>"><?= $userList['inner']; ?></option>
		   <?php endif; ?>
		   <?php endforeach; ?>
		</select>
	</div>
	<input type="button" value="Filter" class="btn btn-primary col-md-2" id="filterClass" />
</div>
<?php endif; ?>

<div id="manageclasstable">
<div class="row" style="margin-top:10px;">
	<table class="table table-striped table-bordered table-responsive">
		<thead>
			<tr>
				<th>Class ID</th>
				<th>Name</th>
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
				<td colspan=3 style="text-align:center;">No Classes Added yet. <?= $this->Html->link('Add now?','#',['class'=>'add-class-now-filter','userid'=>$globalUserId]);?></td>
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
</div>