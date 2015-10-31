<?php
	if($content=='not ok') {
?>
not ok
<?php }else{ ?>
<div class="row">
	<h3 class="col-md-12" style="text-align:center;">Deck - <?= $content[0]['full_name']; ?></h3>
</div>
<div class="row">
	<table class="table table-striped table-bordered table-responsive record-table">
		<thead>
			<tr>
				<th>Card ID</th>
				<th>Question and Question Notes</th>
				<th>Answer and Answer Notes</th>
				<th>Question Sound</th>
				<th>Answer Sound</th>
				<th>Actions:</th>
			</tr>
		</thead>
		<tbody class="deck-container">
			<?php if($content!=[] && $content[0]['c']['id']!=null) : ?>
			<?php foreach($content as $card) : ?>
			<tr id="card-<?=$card['c']['id']; ?>">
				<td><?= $card['c']['id']; ?></td>
				<td class="firsttd"><strong>Q</strong> - <?= $card['c']['question']; ?><br/><strong>N</strong> - <?= $card['c']['question_notes']; ?></td>
				<td class="secondtd"><strong>A</strong> - <?= $card['c']['answer']; ?><br/><strong>N</strong> - <?= $card['c']['answer_notes']; ?></td>
				<td>
					<table>
						<thead>
							<tr>
								<th style="text-align:center;">Slow</th>
								<th style="text-align:center;">Fast</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:center;">
									<button class="btn btn-primary" onclick="startRecording(this,'slow_q',<?=$card['c']['id']; ?>);">Record</button>
		    						<button class="btn btn-warning" onclick="stopRecording(this,'slow_q',<?=$card['c']['id']; ?>);"<?php if($card['c']['question_sound_slow']=='') : ?>disabled<?php endif; ?>><?php if($card['c']['question_sound_slow']!='') : ?>Play<?php else : ?>Stop<?php endif; ?></button>
		    						<?php
		    							if($card['c']['question_sound_slow']!=''){
		    						?>
		    						<audio src="files/<?= $card['c']['question_sound_slow']; ?>" ></audio>
		    						<?php } ?>
								</td>
								<td style="text-align:center;">
									<button class="btn btn-primary" onclick="startRecording(this,'fast_q',<?=$card['c']['id']; ?>);">Record</button>
									<button class="btn btn-warning" onclick="stopRecording(this,'fast_q',<?=$card['c']['id']; ?>);"<?php if($card['c']['question_sound_fast']=='') : ?>disabled<?php endif; ?>><?php if($card['c']['question_sound_fast']!='') : ?>Play<?php else : ?>Stop<?php endif; ?></button>
		    						<?php
		    							if($card['c']['question_sound_fast']!=''){
		    						?>
		    						<audio src="files/<?= $card['c']['question_sound_fast']; ?>" ></audio>
		    						<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<table>
						<thead>
							<tr>
								<th style="text-align:center;">Slow</th>
								<th style="text-align:center;">Fast</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align:center;">
									<button class="btn btn-primary" onclick="startRecording(this,'slow_a',<?=$card['c']['id']; ?>);">Record</button>
									<button class="btn btn-warning" onclick="stopRecording(this,'slow_a',<?=$card['c']['id']; ?>);"<?php if($card['c']['answer_sound_slow']=='') : ?>disabled<?php endif; ?>><?php if($card['c']['answer_sound_slow']!='') : ?>Play<?php else : ?>Stop<?php endif; ?></button>
		    						<?php
		    							if($card['c']['answer_sound_slow']!=''){
		    						?>
		    						<audio src="files/<?= $card['c']['answer_sound_slow']; ?>" ></audio>
		    						<?php } ?>
								</td>
								<td style="text-align:center;">
									<button class="btn btn-primary" onclick="startRecording(this,'fast_a',<?=$card['c']['id']; ?>);">Record</button>
									<button class="btn btn-warning" onclick="stopRecording(this,'fast_a',<?=$card['c']['id']; ?>);"<?php if($card['c']['answer_sound_fast']=='') : ?>disabled<?php endif; ?>><?php if($card['c']['answer_sound_fast']!='') : ?>Play<?php else : ?>Stop<?php endif; ?></button>
		    						<?php
		    							if($card['c']['answer_sound_fast']!=''){
		    						?>
		    						<audio src="files/<?= $card['c']['answer_sound_fast']; ?>" ></audio>
		    						<?php } ?>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<span><?php echo $this->Html->link('Edit','#',['class'=>'edit-card-now','editid'=>$card['c']['id']]);?></span>
					<span><?php echo $this->Html->link('Delete','#',['class'=>'delete-card-now','deleteid'=>$card['c']['id']]);?></span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php else : ?>
			<tr style="display:none;">
				<td colspan=6 style="text-align:center;">No Cards added to this Deck yet. <?php echo $this->Html->link('Add now?','#',['class'=>'add-card-now','adddeckid'=>intval($_GET['deckid'])]);?></td>
			</tr>
			<?php endif; ?>
			<tr id="addCardTd" class="addCardTd" isactive="1">
				<td></td>
				<td><strong>Q</strong> - <textarea id="card_question" style="height:160px;width:100%"></textarea><br/><strong>N</strong><textarea id="card_question_notes" style="height:160px;width:100%;"></textarea></td>
				<td colspan=3><strong>A</strong> - <textarea id="card_answer" style="height:160px;width:100%"></textarea><br/><strong>N</strong><textarea id="card_answer_notes" style="height:160px;width:100%;"></textarea></td>
				<td>
					<?php echo $this->Html->link('Add Card','#',['id'=>'addCard','deckid'=>intval($_GET['deckid'])]);?>
				</td>
			</tr>
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
	<?php echo $this->Html->link('Add Card(s) using CSV File','#',['class'=>'add-multiple-card-now','style'=>'text-align:center;display:block;','adddeckid'=>$_GET['deckid']]);?>
</div>
<div class="row">
	<?php echo $this->Html->link('Back to Decks','#',['class'=>'manage-decks','style'=>'text-align:center;display:block;']);?>
</div>
<?php } ?>
<table id="recordingslist">
</table>

