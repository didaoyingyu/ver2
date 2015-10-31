<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class ClassesDecksTable extends Table
{
	public function initialize(array $config){

    	$this->belongsTo('Classes', [
            'className' =>'Classes',
            'foreignKey' => 'class_id',
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