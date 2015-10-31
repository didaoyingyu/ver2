<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class PlayedCardsTable extends Table
{
	public function initialize(array $config){


		$this->belongsTo('Cards', [
    	'className' => 'Cards',
        'foreignKey' => 'card_id',
        'joinType' => 'LEFT',
    	]);


    	$this->belongsTo('Decks', [
    	'className' => 'Decks',
        'foreignKey' => 'deck_id',
        'joinType' => 'LEFT',
    	]);



	}


}


?>