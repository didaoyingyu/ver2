<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class CardsTable extends Table
{

    public function initialize(array $config){


        $this->hasMany('CardsDecks', [
            'className' => 'CardsDecks',
            'foreignKey' => 'card_id',
            'dependent' => true,
        ]);

        $this->hasMany('PlayedCards', [
            'className' => 'PlayedCards',
            'foreignKey' => 'card_id',
            'dependent' => true,
        ]);

        $this->hasMany('Logs', [
            'className' => 'Logs',
            'foreignKey' => 'card_id',
            'dependent' => true,
        ]);


    }
	public function validationDefault(Validator $validator){
       return $validator
            //->notEmpty('question', 'Question is required.')
            //->notEmpty('question_notes', 'Question note is required.')
            ->notEmpty('answer', 'Answer is required.');
            //->notEmpty('answer_notes', 'Answer note is required.');
            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }


}


?>