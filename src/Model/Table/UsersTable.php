<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class UsersTable extends Table
{




	public function initialize(array $config){


        $this->belongsTo('Accounts', [
            'className' =>'Accounts',
            'foreignKey' => 'account_id',
            'joinType' => 'LEFT',
        ]);

        $this->hasMany('UsersClasses', [
            'className' => 'UsersClasses',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ]);


        $this->hasMany('ClassesStudents', [
            'className' => 'ClassesStudents',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ]);



        $this->hasMany('TeachersUsers', [
            'className' => 'TeachersUsers',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ]);

        $this->hasMany('Logs', [
            'className' => 'Logs',
            'foreignKey' => 'user_id',
            'dependent' => true,
        ]);


    }


    public function validationEditStudent(Validator $validator){
       return $validator
            ->notEmpty('full_name', 'Enter student\'s full name')
            ->notEmpty('sx','Single X is required.')
            ->notEmpty('dx','Double X is required.')
            ->notEmpty('st','Single Tick is required.')
            ->notEmpty('dt','Double Tick is required.')
            ->add('sx','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>0 && $num<1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Single X should be more than 0 and less than 1.',
                ])
            ->add('dx','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>0 && $num<1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Double X should be more than 0 and less than 1.',
                ])
            ->add('st','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Single Tick should be more than 1.',
                ])
             ->add('dt','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Double Tick should be more than 1.',
                ]);

            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }

    public function validationGameSettings(Validator $validator){
       return $validator
            ->notEmpty('sx','Single X is required.')
            ->notEmpty('dx','Double X is required.')
            ->notEmpty('st','Single Tick is required.')
            ->notEmpty('dt','Double Tick is required.')
            ->add('sx','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>0 && $num<1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Single X should be more than 0 and less than 1.',
                ])
            ->add('dx','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>0 && $num<1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Double X should be more than 0 and less than 1.',
                ])
            ->add('st','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Single Tick should be more than 1.',
                ])
             ->add('dt','custom',[
                    'rule' => function($value, $context) {
                       $num = floatval($value);

                       if($num>1)
                            return true;
                        else
                            return false;

                    },
                    'message' => 'The Double Tick should be more than 1.',
                ]);

            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }


    public function validationDefault(Validator $validator){
       return $validator
            ->notEmpty('username', 'Username is required.')
            ->notEmpty('password', 'Password is required.')
            ->notEmpty('sx','Single X is required.')
            ->notEmpty('dx','Double X is required.')
            ->notEmpty('st','Single Tick is required.')
            ->notEmpty('dt','Double Tick is required.');
            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }


    public function validationSaveprofile(Validator $validator){
       return $validator
            ->notEmpty('full_name', 'Full name is required.');
            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }



    public function validationAddStudentValidation(Validator $validator){


        return $validator
               ->notEmpty('full_name', 'Enter student\'s full name')
               ->notEmpty('username','Username is required.')
               ->add('username', 'custom', [
                    'rule' => function($value, $context) {
                        $usersTable = TableRegistry::get('Users');
                        //build query
                        $query =$usersTable->find('all')
                                ->where(['Users.username' => $value]);

                        //create result set from query build
                        if($query->count()>0)
                            return false;
                        return true;
                    },
                    'message' => 'That username is already taken. Please choose another username',
                ]
            )
            ->notEmpty('password', 'Password is required.')
            ->notEmpty('email_address','Email address is required.')
            ->add('email_address', 'validFormat', [
            'rule' => 'email',
            'message' => 'Please enter a valid email address.'
            ])
            ->add('email_address', 'custom', [
                    'rule' => function($value, $context) {

                        $usersTable = TableRegistry::get('Users');

                        //build query
                        $query =$usersTable->find('all')
                                ->where(['Users.email_address' => $value]);

                        //create result set from query build
                        if($query->count()>0)
                            return false;
                         


                        return true;
                    },
                    'message' => 'That email address is already taken. Please choose another email address.',
                ]
            )
            ->notEmpty('repassword','Re enter password is required.')
            ->add('repassword', 'custom', [
                    'rule' => function($value, $context) {
                        if ($value !== $context['data']['password']) {
                            return false;
                        }
                        return true;
                    },
                    'message' => 'Please make sure that Re enter password and Password are equal.',
                ]
            );



    }

    public function validationAddValidation(Validator $validator){


        return $validator
               ->notEmpty('full_name', 'Enter teacher\'s full name')
               ->notEmpty('username','Username is required.')
               ->add('username', 'custom', [
                    'rule' => function($value, $context) {
                        $usersTable = TableRegistry::get('Users');
                        //build query
                        $query =$usersTable->find('all')
                                ->where(['Users.username' => $value]);

                        //create result set from query build
                        if($query->count()>0)
                            return false;
                        return true;
                    },
                    'message' => 'That username is already taken. Please choose another username',
                ]
            )
            ->notEmpty('password', 'Password is required.')
            ->notEmpty('email_address','Email address is required.')
            ->add('email_address', 'validFormat', [
            'rule' => 'email',
            'message' => 'Please enter a valid email address.'
            ])
            ->add('email_address', 'custom', [
                    'rule' => function($value, $context) {

                        $usersTable = TableRegistry::get('Users');

                        //build query
                        $query =$usersTable->find('all')
                                ->where(['Users.email_address' => $value]);

                        //create result set from query build
                        if($query->count()>0)
                            return false;
                         


                        return true;
                    },
                    'message' => 'That email address is already taken. Please choose another email address.',
                ]
            )
            ->notEmpty('repassword','Re enter password is required.')
            ->add('repassword', 'custom', [
                    'rule' => function($value, $context) {
                        if ($value !== $context['data']['password']) {
                            return false;
                        }
                        return true;
                    },
                    'message' => 'Please make sure that Re enter password and Password are equal.',
                ]
            );



    }

    public function validationRegister(Validator $validator){

        


       return $validator
            ->notEmpty('username', 'Username is required.')
            ->add('username', 'custom', [
                    'rule' => function($value, $context) {
                        $usersTable = TableRegistry::get('Users');
                        //build query
                        $query =$usersTable->find('all')
                                ->where(['Users.username' => $value]);

                        //create result set from query build
                        if($query->count()>0)
                            return false;
                         


                        return true;
                    },
                    'message' => 'That username is already taken. Please choose another username',
                ]
            )
            ->notEmpty('password', 'Password is required.')
            ->notEmpty('email_address','Email address is required.')
            ->add('email_address', 'validFormat', [
        	'rule' => 'email',
        	'message' => 'Please enter a valid email address.'
    		])
            ->add('email_address', 'custom', [
                    'rule' => function($value, $context) {

                        $usersTable = TableRegistry::get('Users');

                        //build query
                        $query =$usersTable->find('all')
                                ->where(['Users.email_address' => $value]);

                        //create result set from query build
                        if($query->count()>0)
                            return false;
                         


                        return true;
                    },
                    'message' => 'That email address is already taken. Please choose another email address.',
                ]
            )
            ->notEmpty('full_name','Full name is required.')
            ->notEmpty('repassword','Re enter password is required.')
            ->add('teachersLists', 'custom', [
                    'rule' => function($value, $context) {

                    	if($value==0)
                    		return true;
                    	
                        $usersTable = TableRegistry::get('Users');
				        //build query
				        $query =$usersTable->find('all')
				                ->where(['Users.account_id' => 2,'Users.id' => intval($value)]);

				        //create result set from query build
				        if($query->count()==0)
				         	return false;
				         


                        return true;
                    },
                    'message' => 'Make sure that you are selecting a valid Teacher',
        		]
        	)
            ->add('repassword', 'custom', [
                    'rule' => function($value, $context) {
                        if ($value !== $context['data']['password']) {
                            return false;
                        }
                        return true;
                    },
                    'message' => 'Please make sure that Re enter password and Password are equal.',
        		]
        	);
            /*->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'teacher','student']],
                'message' => 'Please enter a valid role'
            ]);*/
    }

}

?>