<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AccountsTable extends Table
{
	public function initialize(array $config){
		$this->hasMany('Users', [
            'foreignKey' => 'account_id',
        ]);
	}


}


?>