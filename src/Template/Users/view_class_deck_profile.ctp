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
				<th>Actions:</th>
			</tr>
		</thead>
		<tbody class="student-container">
			<?php if($content!=[] && $content[0]['d']['id']!=null) : ?>
			<?php foreach($content as $deck) : ?>
			<tr id="deck-<?=$deck['d']['id']; ?>">
				<td><?= $deck['d']['id']; ?></td>
				<td><?= $deck['d']['full_name']; ?></td>
				<td>
					<span><?php echo $this->Html->link('Remove Deck from this class','#',['class'=>'removefromclassdeck','removeid'=>$deck['d']['id'],'classid'=>intval($_GET['classid'])]);?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr>
				<td colspan=5 style="text-align:center;">No Deck added to this Class yet. <?php echo $this->Html->link('Add Deck to this Class now?','#',['class'=>'addDecktoClassNow','addclassid'=>intval($_GET['classid'])]);?></td>
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
	<?php echo $this->Html->link('Add Deck to this Class now?','#',['class'=>'addDecktoClassNow','addclassid'=>intval($_GET['classid']),'style'=>'text-align:center;display:block;']);?>
</div>
<div class="row">
	<?php echo $this->Html->link('Back to Classes','#',['class'=>'manage-classes','style'=>'text-align:center;display:block;']);?>
</div>
<?php } ?>