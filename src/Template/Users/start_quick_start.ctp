<div class="row" style="text-align:center;">
	<h2>Decks</h2>
</div>
<div class="row">
<?php
	$counter = 0;
	if($content!=[]){
?>
<ul style="list-style:none;" id="ulDeck">
<?php foreach($content as $deck){ ?>
<?php if($deck['d']['id']!=null) : ?>
<?php $counter++; ?>
	<li class="deckList"><input type="checkbox" id="decknum-<?= $deck['d']['id']; ?>" /><?= $deck['d']['full_name']; ?></li>
<?php endif; ?>
<?php } ?>
</ul>
<?php } ?>
<?php if($counter==0) : ?>
<div class="col-md-12" style="text-align:center;">
	You have not add deck yet. <?= $this->Html->link('Add now?','#',['class'=>'add-deck-now']);?>
</div>
<?php endif; ?>
</div>
<div class="row" style="text-align:center;margin-top:20px;">
	<input class="btn btn-primary" type="text" value="Play" id="checkTest" />
</div>
