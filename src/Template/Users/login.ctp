<?=
  $style = "";
  if($error==1)
    $style = "border: 1px solid red;";

?>
<div class="col-md-3 adjustlogin"></div>
<div class="col-md-6 login-form">
          <?= $this->Form->create() ?>
            <div class="row">
              <div class="form-group">
                <div class="col-md-3"><label for="username">Username:</label></div>
                <div class="col-md-6"><?= $this->Form->input('username',['label'=>false,'style'=>$style,'class'=>'form-control']) ?></div>
              </div>
            </div>
            <div class="row">
              <div class="form-group">
                <div class="col-md-3"><label for="password">Password:</label></div>
                <div class="col-md-6"><?= $this->Form->input('password',['label'=>false,'style'=>$style,'class'=>'form-control']) ?></div>
              </div>
            </div>
            <?php if($error==1) : ?>
            <div class="row">
            	<div class="col-md-3"></div>
            	<div class="col-md-6" style="color:red;font-weight:bold;">Username or Password is incorrect.</div>
            </div>
        	<?php endif; ?>
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6"><?= $this->Form->button(__('Login'),['class'=>'btn btn-primary']); ?></div>
            </div>
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6"><?php echo $this->Html->link('Not yet register? Sign up now','/users/register');?></div>
            </div>
          </form>
        </div>
        <div class="col-md-3 adjustlogin"></div>