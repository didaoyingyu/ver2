<div class="row">
	<h3 class="col-md-12">Input Mistake Logs</h3>
</div>
<div class="row">
	<div class="col-md-7"></div>
	<div class="col-md-3">
		<select class="form-control" id="logselect">
		  <option value="0">All Users</option>
		  <option value="<?= $usersLists[0]['value']; ?>"><?= $usersLists[0]['inner']; ?> (Your Log)</option>
		   <?php foreach($usersLists as $userList) : ?>
		   <?php if($userList['value']!=$usersLists[0]['value']) : ?>
		   <option value="<?= $userList['value']; ?>"><?= $userList['inner']; ?></option>
		   <?php endif; ?>
		   <?php endforeach; ?>
		</select>
	</div>
	<input type="button" value="Filter" class="btn btn-primary col-md-2" id="filterInputMistakeLog" />
</div>
<div id="managelogtable">
<div class="row" style="margin-top:10px;">
	<table class="table table-striped table-bordered table-responsive">
		<thead>
			<tr>
				<th>Id</th>
				<th>Question</th>
				<th>Answer</th>
				<th>Before srt</th>
				<th>After srt</th>
				<th>Before History</th>
				<th>After History</th>
				<th>Before Rank</th>
				<th>After Rank</th>
				<th>Last Seen</th>
				<th>Due Date</th>
				<th>Mode Played</th>
				<th>Mark</th>
				<th>User Input</th>
				<th>User who played</th>
			</tr>
		</thead>
		<tbody>
			<?php if($logs!=[]) : ?>
			<?php foreach($logs as $log) : ?>
			<tr id="log-<?= $log['id']; ?>">
				<td><?= $log['id']; ?></td>
				<td><?= $log['c']['question']; ?></td>
				<td><?= $log['c']['answer']; ?></td>
				<td><?= $log['before_srt']; ?></td>
				<td><?= $log['srt']; ?></td>
				<td><?= $log['before_history']; ?></td>
				<td><?= $log['history']; ?></td>
				<td><?= $log['before_rank']; ?></td>
				<td><?= $log['rank']; ?></td>
				<td><?= $log['last_seen']; ?></td>
				<td><?= $log['due_date']; ?></td>
				<td><?= $log['lt']['description']; ?></td>
				<td><?= $log['mark_as']; ?></td>
				<td><?= $log['user_input']; ?></td>
				<td><?= $log['u']['full_name']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=15 style="text-align:center;">No Logs found.</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<div class="row">
	<ul id="reviewLogPagination" class="pagination">
		<?php echo $this->Paginator->prev(' << ' . __('previous')); ?>
		<?php echo $this->Paginator->numbers(); ?>
		<?php echo $this->Paginator->next(' >> ' . __('next')); ?>
	</ul>
</div>
</div>