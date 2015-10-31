<div class="gameButtonContainer">
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-3 col-sm-12 col-xs-12 btn btn-primary" value="QUICK START" id="playQuickAll"/>
		<input type="button" class="col-md-3 col-sm-12 col-xs-12 btn btn-primary" value="Select Decks" id="playQuick"/>
	</div>
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-3 col-sm-12 col-xs-12 btn btn-primary" value="Quick with Input" id="playQuickInputAll"/>
		<input type="button" class="col-md-3 col-sm-12 col-xs-12 btn btn-primary" value="Select Decks" id="playQuickInput"/>
	</div>
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-3 col-sm-12 col-xs-12 btn btn-primary" value="Quick Reverse Mode" id="playQuickReverseAll" />
		<input type="button" class="col-md-3 col-sm-12 col-xs-12 btn btn-primary" value="Select Decks" id="playQuickReverse"/>
	</div>
	<?php if($curUser['account_id']==1 || $curUser['account_id']==2) : ?>
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-6 col-sm-12 col-xs-12 btn btn-primary superVisedTest" value="Supervised Test" />
	</div>
	<?php elseif($curUser['account_id']==3) : ?>
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-6 col-sm-12 col-xs-12 btn btn-primary selfTest" value="Self Test" />
	</div>
	<?php endif; ?>
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-6 col-sm-12 col-xs-12 btn btn-primary add-deck" value="New Deck" />
	</div>
	<div class="row gameButtonRow">
		<div class="col-md-3"></div>
		<input type="button" class="col-md-6 col-sm-12 col-xs-12 btn btn-primary manage-decks" value="Manage Decks" />
	</div>
</div>