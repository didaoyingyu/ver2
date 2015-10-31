<div class="row">
	<h3 class="col-md-12">Game Settings</h3>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="sx">Single X:</label></div>
		<div class="col-md-3"><input type="text" class="form-control" value="<?= $userInfo['sx']; ?>" id="sx"/></div>
		<div class="col-md-3" id="sx_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="sx">Double X:</label></div>
		<div class="col-md-3"><input type="text" class="form-control" value="<?= $userInfo['dx']; ?>" id="dx"/></div>
		<div class="col-md-3" id="dx_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="st">Single Tick:</label></div>
		<div class="col-md-3"><input type="text" class="form-control" value="<?= $userInfo['st']; ?>" id="st"/></div>
		<div class="col-md-3" id="st_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="st">Double Tick:</label></div>
		<div class="col-md-3"><input type="text" class="form-control" value="<?= $userInfo['dt']; ?>" id="dt"/></div>
		<div class="col-md-3" id="dt_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="form-group">
		<div class="col-md-2"><label for="st">Sound:</label></div>
		<div class="col-md-1" style="margin-left:0px;padding-left:0px;"><input type="checkbox" class="form-control" value="<?= $userInfo['sound']; ?>" id="sound" <?php if($userInfo['sound']==1) : ?>checked="checked"<?php endif; ?>/></div>
		<div class="col-md-3" id="sound_error" style="color:red;"></div>
	</div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6" id="saveGameSettingStatus"></div>
</div>
<div class="row form-row">
	<div class="col-md-2"></div>
	<div class="col-md-6"><input type="button" value="Save" id="saveGameSettingsId" class="btn btn-primary"/></div>
</div>