<div style="margin-top:10px;" class="row">
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
				<th>sx</th>
				<th>dx</th>
				<th>st</th>
				<th>dt</th>
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
				<td><?= $log['sx']; ?></td>
				<td><?= $log['dx']; ?></td>
				<td><?= $log['st']; ?></td>
				<td><?= $log['dt']; ?></td>
				<td><?= $log['u']['full_name']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=14 style="text-align:center;">No Logs found.</td>
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