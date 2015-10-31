<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class LogsTable extends Table
{




	public function initialize(array $config){

		$this->belongsTo('LogsTypes', [
            'className' =>'LogsTypes',
            'foreignKey' => 'log_type_id',
            'joinType' => 'LEFT',
        ]);

		$this->belongsTo('Users', [
            'className' =>'Users',
            'foreignKey' => 'user_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('Cards', [
            'className' =>'Cards',
            'foreignKey' => 'card_id',
            'joinType' => 'LEFT',
        ]);

        $this->belongsTo('Decks', [
            'className' =>'Decks',
            'foreignKey' => 'deck_id',
            'joinType' => 'LEFT',
        ]);






    }

}

?>