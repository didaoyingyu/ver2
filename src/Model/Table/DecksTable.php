<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class DecksTable extends Table
{
	public function initialize(array $config){

		$this->hasMany('CardsDecks', [
			'className' =>'CardsDecks',
            'foreignKey' => 'deck_id',
            'dependent' => true,
        ]);

        $this->hasMany('UsersDecks', [
        	'className' => 'UsersDecks',
            'foreignKey' => 'deck_id',
            'dependent' => true,
        ]);



        $this->hasMany('ClassesDecks', [
            'className' => 'ClassesDecks',
            'foreignKey' => 'deck_id',
            'dependent' => true,
        ]);




        $this->hasMany('PlayedCards', [
            'className' => 'PlayedCards',
            'foreignKey' => 'deck_id',
            'dependent' => true,
        ]);


        $this->hasMany('Logs', [
            'className' => 'logs',
            'foreignKey' => 'deck_id',
            'dependent' => true,
        ]);



	}



	public function validationDefault(Validator $validator){
       return $validator
            ->notEmpty('full_name', 'Deck name is required.');
            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }


}


?>