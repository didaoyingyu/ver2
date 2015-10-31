<div class="row">
	<h3 class="col-md-12">Decks</h3>
</div>

<?php if($curUser['account_id']==1) : ?>
<div class="row">
	<div class="col-md-7"></div>
	<div class="col-md-3">
		<select class="form-control" id="deckselect">
		  <option value="0">All Decks</option>
		  <option value="<?= $usersLists[0]['value']; ?>"><?= $usersLists[0]['inner']; ?> (Your Deck)</option>
		   <?php foreach($usersLists as $userList) : ?>
		   <?php if($userList['value']!=$usersLists[0]['value']) : ?>
		   <option value="<?= $userList['value']; ?>"><?= $userList['inner']; ?></option>
		   <?php endif; ?>
		   <?php endforeach; ?>
		</select>
	</div>
	<input type="button" value="Filter" class="btn btn-primary col-md-2" id="filterDeck" />
</div>
<?php endif; ?>

<div id="managedecktable">
<div class="row" style="margin-top:10px;">
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
				<td colspan=3 style="text-align:center;">No Decks Added yet. <?= $this->Html->link('Add now?','#',['class'=>'add-deck-now']);?></td>
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