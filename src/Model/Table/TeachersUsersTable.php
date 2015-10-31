<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class TeachersUsersTable extends Table
{
	public function initialize(array $config){


		$this->belongsTo('Users', [
    	'className' => 'Users',
        'foreignKey' => 'user_id',
        'joinType' => 'LEFT',
    	]);

	}

}


?>