<div class="row">
	<h3 class="col-md-12">Add Card</h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="card_question">Question:</label></div>
		<div class="col-md-3"><input type="text" value="" id="card_question" class="form-control" /></div>
		<div class="col-md-3" id="card_question_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="card_question">Question Note:</label></div>
		<div class="col-md-3"><input type="text" value="" id="card_question_notes" class="form-control" /></div>
		<div class="col-md-3" id="card_question_notes_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="card_question">Answer:</label></div>
		<div class="col-md-3"><input type="text" value="" id="card_answer" class="form-control" /></div>
		<div class="col-md-3" id="card_answer_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="card_question">Answer Note:</label></div>
		<div class="col-md-3"><input type="text" value="" id="card_answer_notes" class="form-control" /></div>
		<div class="col-md-3" id="card_answer_notes_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6" id="addCardStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6"><input type="button" value="Add Card" id="addCard" class="btn btn-primary"/></div>
</div>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-6"><?php echo $this->Html->link('Back to Decks','#',['class'=>'manage-decks','style'=>'display:block;']);?></div>
</div>