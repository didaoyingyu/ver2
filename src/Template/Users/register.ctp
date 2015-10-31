<div class="col-md-12 login-form">
		<div class="row">
				<div class="col-md-12" style="font-weight:bold;">All fields with <span style="color:red;">*</span> are required.</div>
		</div>

          <?= $this->Form->create($user) ?>
            <div class="row" style="margin-top:20px;">
              <div class="form-group">
              <div class="col-md-2"><label for="full_name">Full name: </label><span style="color:red;">*</span></div>
              <?php
              	$style = "";
              	if($this->Form->isFieldError('full_name')){
              		$style = "border: 1px solid red;";
              	}
              ?>
              <div class="col-md-2"><?= $this->Form->input('full_name',['label'=>false,'style'=>$style,'error'=>false,'class'=>'form-control']) ?></div>
              <div class="col-md-3" style="color:red;">
              <?php 
              	if ($this->Form->isFieldError('full_name')) {
    				echo $this->Form->error('full_name');
				} 
				?>
      </div>
				</div>
            </div>
            <div class="row">
              <div class="form-group">

            	<?php
              	$style = "";
              	if($this->Form->isFieldError('email_address')){
              		$style = "border: 1px solid red;";
              	}
              ?>
              <div class="col-md-2"><label for="email_address">Email Address: </label><span style="color:red;">*</span></div>
              <div class="col-md-2"><?= $this->Form->input('email_address',['label'=>false,'style'=>$style,'error'=>false,'class'=>'form-control']) ?></div>
             	<div class="col-md-3" style="color:red;">
              <?php 
              	if ($this->Form->isFieldError('email_address')) {
    				echo $this->Form->error('email_address');
				} 
				?>
      </div>
				</div>
            </div>
            <div class="row">
              <div class="form-group">
            	 <?php
              	$style = "";
              	if($this->Form->isFieldError('username')){
              		$style = "border: 1px solid red;";
              	}
              ?>
              <div class="col-md-2"><label for="username">Desired Username: </label><span style="color:red;">*</span></div>
              <div class="col-md-2"><?= $this->Form->input('username',['label'=>false,'style'=>$style,'error'=>false,'class'=>'form-control']) ?></div>
              <div class="col-md-3" style="color:red;">
              <?php 
              	if ($this->Form->isFieldError('username')) {
    				echo $this->Form->error('username');
				} 
				?>
      </div>
				</div>
            </div>
            <div class="row">
              <div class="form-group">
            	 <?php
              	$style = "";
              	if($this->Form->isFieldError('teachersLists')){
              		$style = "border: 1px solid red;";
              	}
              ?>
              <div class="col-md-2"><label for="teachersLists">Teacher: </label><span style="color:red;">*</span></div>
              <div class="col-md-2"><?= $this->Form->select('teachersLists', $teachersLists,['style'=>$style,'class'=>'form-control']); ?></div>
              <div class="col-md-3" style="color:red;">
              <?php 
              	if ($this->Form->isFieldError('teachersLists')) {
    				echo $this->Form->error('teachersLists');
				}
				?>
      </div>
				</div>
            </div>
            <div class="row">
              <div class="form-group">
            	 <?php
              	$style = "";
              	if($this->Form->isFieldError('password')){
              		$style = "border: 1px solid red;";
              	}
              ?>
              <div class="col-md-2"><label for="password">Password: </label><span style="color:red;">*</span></div>
              <div class="col-md-2">
              	<?= $this->Form->input('password',['label'=>false,'style'=>$style,'error'=>false,'class'=>'form-control']) ?>
              </div>
              <div class="col-md-3" style="color:red;">
              <?php 
              	if ($this->Form->isFieldError('password')) {
    				echo $this->Form->error('password');
				} 
				?>
      </div>
				</div>
            </div>
            <div class="row">
              <div class="form-group">
            	 <?php
              	$style = "";
              	if($this->Form->isFieldError('repassword')){
              		$style = "border: 1px solid red;";
              	}
              ?>
              <div class="col-md-2"><label for="password">Re enter Password: </label><span style="color:red;">*</span></div>
              <div class="col-md-2">
              	<?= $this->Form->input('repassword',['label'=>false,'style'=>$style,'error'=>false,'type'=>'password','class'=>'form-control']) ?>
              </div>
              <div class="col-md-3" style="color:red;">
              <?php 
              	if ($this->Form->isFieldError('repassword')) {
    				      echo $this->Form->error('repassword');
				        } 
				      ?>
            </div>
				</div>
            </div>
            <div class="row">
              <div class="col-md-2"><label for="captcha_code">Captcha Image: </label></div>
              <div class="col-md-2"><?= $captchaHtml; ?></div>
            </div>
            <div class="row captchaInput">
              <div class="form-group">
              <?php
                $style = "";
                if(isset($captchaError))
                  $style = "border: 1px solid red;";
              ?>
              <div class="col-md-2"><label for="captcha_code">Captcha Code: </label><span style="color:red;">*</span></div>
              <div class="col-md-2"><?= $this->Form->input('captcha_code',['label'=>false,'error'=>false,'style'=>$style,'class'=>'form-control']) ?></div>
              <div class="col-md-3" style="color:red;">
              <?php 
                if (isset($captchaError)) {
                  echo 'Please make sure to enter properly the captcha code.';
                } 
              ?>
            </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2"></div>
              <div class="col-md-6"><?= $this->Form->button(__('Register'),['class'=>'btn btn-primary']); ?></div>
            </div>
          </form>
        </div>