<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class LogsTypesTable extends Table
{




	public function initialize(array $config){

		$this->hasMany('Logs', [
            'className' => 'Logs',
            'foreignKey' => 'log_type_id',
            'dependent' => true,
        ]);


    }

}

?>