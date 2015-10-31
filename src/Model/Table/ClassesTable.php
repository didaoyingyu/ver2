<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class ClassesTable extends Table
{
	public function initialize(array $config){


        $this->hasMany('UsersClasses', [
            'className' => 'UsersClasses',
            'foreignKey' => 'class_id',
            'dependent' => true
        ]);


        $this->hasMany('ClassesStudents', [
            'className' => 'ClassesStudents',
            'foreignKey' => 'class_id',
            'dependent' => true
        ]);

        $this->hasMany('ClassesDecks', [
            'className' => 'ClassesDecks',
            'foreignKey' => 'class_id',
            'dependent' => true,
        ]);

    }

    public function validationDefault(Validator $validator){
       return $validator
            ->notEmpty('full_name','Class name is required.');
    }

}



?>