<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class UsersClassesTable extends Table
{

	public function initialize(array $config){

		$this->belongsTo('Users', [
            'className' =>'Users',
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('Classes', [
            'className' =>'Classes',
            'foreignKey' => 'class_id',
            'joinType' => 'LEFT',
        ]);



	}

}



?>