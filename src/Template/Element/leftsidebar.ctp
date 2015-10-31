<div class="col-md-2">
		<ul class="nav nav-list dropdown" id="fcard-menu">
			<li><a href="#" class="viewProfile"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="menu-text"> My Profile</span></a></li>

			<?php if($curUser['account_id']==1) : ?>
			<li><a href="#" class="dropdown-toggle" id="teachers-id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="menu-text"> Teachers</span>
				<span class="caret"></span>
			</a>
				<ul class="dropdown-menu" aria-labelledby="teachers-id">
					<li><a class="manageTeachers" href="#">Manage Teachers</a></li>
					<li><a href="#" class="add-teacher">Add Teacher</a></li>
				</ul>
			</li>
			<?php endif; ?>
			<?php if($curUser['account_id']==1 || $curUser['account_id']==2) : ?>
			<li><a href="#" class="dropdown-toggle" id="students-id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="menu-text"> Students</span>
				<span class="caret"></span>
			</a>
				<ul class="dropdown-menu" aria-labelledby="students-id">
					<li><a href="#" class="manageStudents">Manage Students</a></li>
					<li><a href="#" class="add-student">Add Student</a></li>
				</ul>
			</li>
			<?php endif; ?>
			<li><a href="#" class="dropdown-toggle" id="decks-id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="menu-text"> Decks</span>
				<span class="caret"></span>
			</a>
				<ul class="dropdown-menu" aria-labelledby="decks-id">
					<li><a href="#" class="manage-decks">Manage Decks</a></li>
					<li><a href="#" class="add-deck">Add Deck</a></li>
				</ul>
			</li>
			<?php if($curUser['account_id']==1 || $curUser['account_id']==2) : ?>
			<li><a href="#" class="dropdown-toggle" id="classes-id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="menu-text"> Classes</span>
				<span class="caret"></span>
			</a>
				<ul class="dropdown-menu" aria-labelledby="classes-id">
					<li><a href="#" class="manageClasses">Manage Classes</a></li>
					<?php if($curUser['account_id']==1 || $curUser['account_id']==2) : ?>
					<li><a href="#" class="add-class">Add Class</a></li>
					<?php endif; ?>
				</ul>
			</li>
		<?php endif; ?>
			<li><a href="#" class="viewGameMode"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Play Game</a></li>
			<?php if($curUser['account_id']==1) : ?>
			<li><a href="#" class="viewGameSettings"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Game Settings</a></li>
			<li><a href="#" class="dropdown-toggle" id="classes-id" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="menu-text"> Logs</span>
				<span class="caret"></span>
			</a>
				<ul class="dropdown-menu" aria-labelledby="classes-id">
					<li><a href="#" class="quick-review-logs">Complete Log</a></li>
					<li><a href="#" class="quickInputMistakeLog">Input Mistake Log</a></li>
					<li><a href="#" class="quickStandardMistakeLog">Standard Mistake Log</a></li>
				</ul>
			</li>
			<?php endif; ?>
		</ul>
	</div>