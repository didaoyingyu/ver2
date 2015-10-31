<table class="table table-striped table-bordered table-responsive">
		<thead>
			<tr>
				<th>Deck ID</th>
				<th>Deck Name</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if($decks!=[]) : ?>
			<?php foreach($decks as $deck) : ?>
			<tr id="deck-<?= $deck['id']; ?>">
				<td><?= $deck['id']; ?></td>
				<td><?= $deck['full_name']; ?></td>
				<td>
					<span><?= $this->Html->link('View Deck','#',['class'=>'viewDeckInfo','deckid'=>$deck['id']]); ?></span>
					<span><?= $this->Html->link('Delete Deck','#',['class'=>'deleteDeck','deckid'=>$deck['id']]); ?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=3 style="text-align:center;">No Decks Added yet.<?= $this->Html->link('Add now?','#',['class'=>'add-deck-now-filter','userid'=>$globalUserId]);?></td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>
<div style="text-align:center;">
	<?= $this->Html->link('Add Deck to this user?','#',['class'=>'add-deck-now-filter','userid'=>$globalUserId]);?>
</div>
<div class="row">
	<ul id="reviewLogPagination" class="pagination">
		<?php echo $this->Paginator->prev(' << ' . __('previous')); ?>
		<?php echo $this->Paginator->numbers(); ?>
		<?php echo $this->Paginator->prev(' >> ' . __('next')); ?>
	</ul>
</div>