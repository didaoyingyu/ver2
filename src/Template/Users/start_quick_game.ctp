<?php

	$data = json_decode($content);


	if(isset($data->renew) && $data->renew==1) :
?>
<?php 
	$data->content = '<div class="row" style="text-align:center;"><h2>Decks</h2></div><div class="row"><div class="col-md-12" style="text-align:center;">You have not add deck yet.' . $this->Html->link('Add now?','#',['class'=>'add-deck-now']) . '</div></div>'; 

	$content = json_encode($data);
	echo $content;
?>
<?php else : ?>
<?= $content; ?>
<?php endif; ?>