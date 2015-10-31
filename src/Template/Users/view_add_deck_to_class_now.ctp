<?php if($check==1) : ?>
<div class="row">
	<h3 class="col-md-12">Assign Deck(s) to Class <?= $className; ?></h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-3"><label for="deck_name">Deck(s): <span style="color:red;">*</span></label></div>
		<div class="col-md-3"><input type="text" value="" id="deck_ids" class="form-control" placeholder="Type Deck name here"/></div>
		<div class="col-md-3" id="deck_ids_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-3"></div>
	<div class="col-md-6" id="addDeckClassStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-3"></div>
	<div class="col-md-6"><input type="button" value="Assign Deck(s)" classid="<?= $cid; ?>" id="assignDeckClass" class="btn btn-primary"/></div>
</div>
<?php endif; ?>