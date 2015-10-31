<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Security;
use Cake\Network\Email\Email;


// Importing the BotDetectCaptcha class
use CakeCaptcha\Integration\BotDetectCaptcha;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController
{

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */

    public $layout,$paginate;

    
   

    public function quickStandardMistakeLog(){



        $this->layout = 'ajax';

        $logs = [];



        $usersTable = TableRegistry::get('Users');


        $usersLists = [];
        $qry = $usersTable->find('all')
               ->where(['Users.id <>'=>$this->Auth->user('id')]);




        if($qry->count()>0){

            $rows = $qry->all();

            $rows = $rows->toArray();

            $z = 0;
            $usersLists[$z]['value'] = $this->Auth->user('id');
            $usersLists[$z]['inner'] = $this->Auth->user('full_name');

            $z++;

            foreach($rows as $row){
                $usersLists[$z]['value'] = $row['id'];
                $usersLists[$z]['inner'] = $row['full_name'];
                $z++;
            }
            $z = 0;




        }
        
        $this->set('usersLists',$usersLists);




        $reviewLogsTable = TableRegistry::get('Logs');




        if(!isset($_GET['uid'])){


        //    $query = $reviewLogsTable->find('all');

            $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Logs.id' => 'asc'
        ]];



        //build query an get all cards belong this requested deck ID
        $query =$this->paginate($reviewLogsTable->find()
                    ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt'])
                    ->hydrate(false)
                    ->join([
                        'c'=>[
                        'table' => 'cards',
                        'type' => 'LEFT',
                        'conditions' => 'c.id = Logs.card_id',
                        ],
                        'u'=>[
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'u.id = Logs.user_id',
                        ],
                        'd'=>[
                        'table' => 'decks',
                        'type' => 'LEFT',
                        'conditions' => 'd.id = Logs.deck_id',
                        ],
                        'lt'=>[
                        'table' => 'logs_types',
                        'type' => 'LEFT',
                        'conditions' => 'lt.id = Logs.log_type_id',
                        ]
                        
                    ])
                    ->order(['Logs.id' => 'ASC']));
    
            $logs =$query->toArray();


            //update ranks if negative
            foreach($logs as $key=>$log){
                if($log['before_rank']<0){
                    $logs[$key]['before_rank'] = 0;
                }
                if($log['rank']<0){
                    $logs[$key]['rank'] = 0;
                }


                if($log['mark_as']=='sx' || $log['mark_as']=='dx'){

                }else{
                    unset($logs[$key]);
                }

            }
            
          /*  if($query->count()>0){
                //create result set from query build
                $results = $query->all();


                // Once we have a result set we can get all the rows
                $logs = $results->toArray();




               
            }*/




        }



        $this->set('logs',$logs);




    }


    public function searchDeck(){


        $this->layout = 'ajax';


        $output = [];


        if($this->Auth->user('account_id')==1 || $this->Auth->User('account_id')==2){


            $decksTable = TableRegistry::get('Decks');


            if($this->Auth->user('account_id')==1){

                $qry = $decksTable->find()
                    ->select(['full_name','id','cd.id'])
                    ->hydrate(false)
                    ->join([
                        'cd'=>[
                        'table' => 'classes_decks',
                        'type' => 'LEFT',
                        'conditions' => 'cd.deck_id = Decks.id'
                        ]
                        ]);
            }else{


                $qry = $decksTable->find()
                    ->select(['full_name','id','cd.id'])
                    ->hydrate(false)
                    ->join([
                        'cd'=>[
                        'table' => 'classes_decks',
                        'type' => 'LEFT',
                        'conditions' => 'cd.deck_id = Decks.id'
                        ],
                        'ud'=>[
                        'table' => 'users_decks',
                        'type' => 'LEFT',
                        'conditions' => 'ud.deck_id = Decks.id'
                        ]
                        ])
                    ->where(['ud.user_id'=>$this->Auth->user('id')]);
            }

            if($qry->count()>0){


                $results = $qry->all();

                $results = $results->toArray();

                $x = 0;
                foreach($results as $result){
                   /* if($result['ct']['id']==null){
                        $output[$x]['name'] = $result['full_name'];
                        $output[$x]['id'] = $result['id'];
                        $x++;
                    }*/
                    $output[$x]['name'] = $result['full_name'];
                    $output[$x]['id'] = $result['id'];
                    $x++;
                }


            }
        }

        $output = json_encode($output);

        $this->set('content',$output);



    }


    public function searchStudent(){


        $this->layout = 'ajax';


        $output = [];


        if($this->Auth->user('account_id')==1 || $this->Auth->User('account_id')==2){


            $usersTable = TableRegistry::get('Users');


            $qry = $usersTable->find()
                    ->select(['full_name','id','ct.id'])
                    ->hydrate(false)
                    ->join([
                        'ct'=>[
                        'table' => 'classes_students',
                        'type' => 'LEFT',
                        'conditions' => 'ct.user_id = Users.id'
                        ]
                        ])
                   ->where(['Users.account_id' => 3,'Users.is_approve'=>1]);

            if($qry->count()>0){


                $results = $qry->all();

                $results = $results->toArray();

                $x = 0;
                foreach($results as $result){
                   /* if($result['ct']['id']==null){
                        $output[$x]['name'] = $result['full_name'];
                        $output[$x]['id'] = $result['id'];
                        $x++;
                    }*/
                    $output[$x]['name'] = $result['full_name'];
                    $output[$x]['id'] = $result['id'];
                    $x++;
                }


            }
        }

        $output = json_encode($output);

        $this->set('content',$output);



    }



    public function updateGameCardIndividual(){
        $this->layout = 'ajax';

        //check if there is datas
        if(isset($_GET['datas'])){
            
            $data = $_GET['datas'];
            $checkIfError = 0;
            $playedCardsTable = TableRegistry::get('PlayedCards');


            if(isset($data['rank'])){
                $data['rank'] = intval($data['rank']);
                if($data['rank']<0)
                    $data['rank'] = 0;
            }


            $entity = $playedCardsTable->newEntity($data,['validate'=>false]);
            $playedCardsTable->save($entity);
            $status = ['status'=>'ok','check'=>$data];
            
        }else
            $status = ['status'=>'not ok'];
        
        $status = json_encode($status);
        $this->set('content',$status);

    }

    public function unapproveStudent(){
        $this->layout = 'ajax';


        if(isset($_GET['datas']['student_id'])){

            $usersTable = TableRegistry::get('Users');
            $userid = intval($_GET['datas']['student_id']);


            $ffirst = $usersTable->find('all')
                      ->where(['Users.id'=>$userid,'Users.is_approve'=>1]);


            if($ffirst->count()>0){


                $updateStudent = $usersTable->get($userid);

                $updateStudent->is_approve = 0;


                $usersTable->save($updateStudent);



                //Send an email
                $email = new Email('default');
                // $email->profile(['from' => 'fcard@freememorialwebsites.com', 'transport' => 'freememorial']);
                $email->template('unapproveverify')
                    ->emailFormat('html')
                    ->subject('DIDAOYINGYU\'s FlashCard Game - Account Unapproved.')
                    ->to($updateStudent->email_address)
                    //->to('kenjos75@yahoo.com')
                    ->send();



                $status = ['status'=>'ok'];


            }else{
                $status = ['status'=>'not ok'];
            }

        }else{
            $status = ['status'=>'not ok'];
        }


        $status = json_encode($status);

        $this->set('content',$status);


    }


    
    public function assignDecks(){

        $this->layout = 'ajax';


        $status = ['status'=>'ok'];

        if(isset($_GET['deckids']) && isset($_GET['class_id'])){
            

            $deckids = $_GET['deckids'];

            $classid = intval($_GET['class_id']);

            $classesDecksTable = TableRegistry::get('ClassesDecks');


            //assign them to classes
            foreach($deckids as $deckid){


                $newData = ['class_id'=>$classid,'deck_id'=>$deckid];

                $qry = $classesDecksTable->find('all')
                       ->where(['class_id'=>$classid,'deck_id'=>$deckid]);

                if($qry->count==0){
                    $entity = $classesDecksTable->newEntity($newData,['validate'=>false]);
                    $classesDecksTable->save($entity);
                }

            }
            $status = ['status'=>'ok'];
            


        }else{
            $status = ['status'=>'not ok'];
        }

        $status = json_encode($status);


        $this->set('content',$status);


    }


    public function assignStudents(){

        $this->layout = 'ajax';


        $status = ['status'=>'ok'];

        if(isset($_GET['studentids']) && isset($_GET['class_id'])){
            

            $studentids = $_GET['studentids'];

            $classid = intval($_GET['class_id']);

            $classesStudentsTable = TableRegistry::get('ClassesStudents');


            //assign them to classes
            foreach($studentids as $studentid){


                $newData = ['class_id'=>$classid,'user_id'=>$studentid];

                $qry = $classesStudentsTable->find('all')
                       ->where(['class_id'=>$classid,'user_id'=>$studentid]);

                if($qry->count==0){
                    $entity = $classesStudentsTable->newEntity($newData,['validate'=>false]);
                    $classesStudentsTable->save($entity);
                }

            }
            $status = ['status'=>'ok'];
            


        }else{
            $status = ['status'=>'not ok'];
        }

        $status = json_encode($status);


        $this->set('content',$status);


    }

    
    public function viewAddDeckToClassNow(){


        $this->layout = 'ajax';

        $check = 1;

        if(isset($_GET['classid'])){

            //check if that class really existed
            $usersClassesTable = TableRegistry::get('UsersClasses');


            $cid = intval($_GET['classid']);



            if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){


                if($this->Auth->user('account_id')==2){


                    $qry = $usersClassesTable->find()
                        ->select(['c.full_name'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'classes',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = UsersClasses.class_id',
                            ]])
                       ->where(['UsersClasses.class_id'=>$cid,'UsersClasses.user_id'=>$this->Auth->user('id')]);
                }else{
                    $qry = $usersClassesTable->find()
                        ->select(['c.full_name'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'classes',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = UsersClasses.class_id',
                            ]])
                       ->where(['UsersClasses.class_id'=>$cid]);
                }
               
                if($qry->count()>0){

                    $row = $qry->first();



                   $className = $row['c']['full_name'];

                    $this->set(compact('cid','className'));
                }else{
                    $check = 0;
                }
            }else{
                $check = 0;
            }
           
        }else{
            $check = 0;
        }

        $this->set('check',$check);



    }





    public function viewAddUserToClassNow(){


        $this->layout = 'ajax';

        $check = 1;

        if(isset($_GET['classid'])){

            //check if that class really existed
            $usersClassesTable = TableRegistry::get('UsersClasses');


            $cid = intval($_GET['classid']);



            if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){


                if($this->Auth->user('account_id')==2){


                    $qry = $usersClassesTable->find()
                        ->select(['c.full_name'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'classes',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = UsersClasses.class_id',
                            ]])
                       ->where(['UsersClasses.class_id'=>$cid,'UsersClasses.user_id'=>$this->Auth->user('id')]);
                }else{
                    $qry = $usersClassesTable->find()
                        ->select(['c.full_name'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'classes',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = UsersClasses.class_id',
                            ]])
                       ->where(['UsersClasses.class_id'=>$cid]);
                }
               
                if($qry->count()>0){

                    $row = $qry->first();



                   $className = $row['c']['full_name'];

                    $this->set(compact('cid','className'));
                }else{
                    $check = 0;
                }
            }else{
                $check = 0;
            }
           
        }else{
            $check = 0;
        }

        $this->set('check',$check);



    }



    public function approveStudent(){

        $this->layout = 'ajax';


        if(isset($_GET['datas']['student_id'])){

            $usersTable = TableRegistry::get('Users');
            $userid = intval($_GET['datas']['student_id']);


            $ffirst = $usersTable->find('all')
                      ->where(['Users.id'=>$userid,'Users.is_approve'=>0]);


            if($ffirst->count()>0){


                $updateStudent = $usersTable->get($userid);

                $updateStudent->is_approve = 1;


                $usersTable->save($updateStudent);



                //Send an email
                $email = new Email('default');
                // $email->profile(['from' => 'fcard@freememorialwebsites.com', 'transport' => 'freememorial']);
                $email->template('approveverify')
                    ->emailFormat('html')
                    ->subject('DIDAOYINGYU\'s FlashCard Game - Account Approved!')
                    ->to($updateStudent->email_address)
                    //->to('kenjos75@yahoo.com')
                    ->send();









                $status = ['status'=>'ok'];


            }else{
                $status = ['status'=>'not ok'];
            }

        }else{
            $status = ['status'=>'not ok'];
        }


        $status = json_encode($status);

        $this->set('content',$status);

    }

    public function updateLogs(){


        $this->layout = 'ajax';

        if(isset($_GET['datas']) && isset($_GET['gameMode'])){
            
            $data = $_GET['datas'];



            $lastSeen = '';
            $dueDate = '';
            if($data['srs']>0){
                $lastSeen = date('F j, Y - h:i:s a',$data['srs']);
            }

            if($data['due_date']>0){
                $dueDate = date('F j, Y - h:i:s a',$data['due_date']);
            }


            if($_GET['gameMode']=='quick'){
                $logType = 1;
            }elseif($_GET['gameMode']=='quick-input'){
                $logType = 3;
            }




            if($data['before_rank']<0){
                $data['before_rank'] = 0;
            }

            if($data['rank']<0){
                $data['rank'] = 0;
            }
            






            if(isset($data['c']['id'])){
                $newData['card_id'] = $data['c']['id'];
            }


            if(isset($data['cd']['deck_id'])){
                $newData['deck_id'] = $data['cd']['deck_id'];
            }


            if(isset($lastSeen)){
                $newData['last_seen'] = $lastSeen;
            }

            if(isset($data['history'])){
                $newData['history'] = $data['history'];
            }

            if(isset($data['before_history'])){
                $newData['before_history'] = $data['before_history'];
            }


            if(isset($data['before_rank'])){
                $newData['before_history'] = intval($data['before_rank']);
            }

            if(isset($data['rank'])){
                $newData['rank'] = intval($data['rank']);
            }

            if(isset($dueDate)){
                $newData['due_date'] = $dueDate;
            }

            if(isset($logType)){
                $newData['log_type_id'] = $logType;
            }

            
            $newData['user_id'] = $this->Auth->user('id');
            
            if(isset($data['mark_as'])){
                $newData['mark_as'] = $data['mark_as'];
            }

            if(isset($data['user_input'])){
                $newData['user_input'] = $data['user_input'];
            }

            if(isset($data['before_srt'])){
                $newData['before_srt'] = $data['before_srt'];
            }

            if(isset($data['srt'])){
                $newData['srt'] = $data['srt'];
            }

            if(isset($data['sx'])){
                $newData['sx'] = $data['sx'];
            }
            if(isset($data['dx'])){
                $newData['dx'] = $data['dx'];
            }
            if(isset($data['st'])){
                $newData['st'] = $data['st'];
            }
            if(isset($data['dt'])){
                $newData['dt'] = $data['dt'];
            }

            $newData['date_created'] = time();


           // $newData = ['date_created'=>time(),'card_id'=>$data['c']['id'],'deck_id'=>$data['cd']['deck_id'],'last_seen'=>$lastSeen,'history'=>$data['history'],'before_history'=>$data['before_history'],'before_rank'=>intval($data['before_rank']),'rank'=>intval($data['rank']),'due_date'=>$dueDate,'log_type_id'=>$logType,'user_id'=>$this->Auth->user('id'),'mark_as'=>$data['mark_as'],'user_input'=>$data['user_input'],'before_srt'=>$data['before_srt'],'srt'=>$data['srt']];


            $logsTable = TableRegistry::get('Logs');
            $entity = $logsTable->newEntity($newData,['validate'=>false]);
            $logsTable->save($entity);
            $status = json_encode(['status'=>'ok']);
            
        }else{
            $status = json_encode(['status'=>'not ok']);
        }


        $this->set('content',$status);




    }


    public function quickInputMistakeLog(){

        $this->layout = 'ajax';

        $logs = [];



        $usersTable = TableRegistry::get('Users');


        $usersLists = [];
        $qry = $usersTable->find('all')
               ->where(['Users.id <>'=>$this->Auth->user('id')]);




        if($qry->count()>0){

            $rows = $qry->all();

            $rows = $rows->toArray();

            $z = 0;
            $usersLists[$z]['value'] = $this->Auth->user('id');
            $usersLists[$z]['inner'] = $this->Auth->user('full_name');

            $z++;

            foreach($rows as $row){
                $usersLists[$z]['value'] = $row['id'];
                $usersLists[$z]['inner'] = $row['full_name'];
                $z++;
            }
            $z = 0;




        }
        
        $this->set('usersLists',$usersLists);




        $reviewLogsTable = TableRegistry::get('Logs');




        if(!isset($_GET['uid'])){


        //    $query = $reviewLogsTable->find('all');

            $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Logs.id' => 'asc'
        ]];



            //build query an get all cards belong this requested deck ID
           $query =$this->paginate($reviewLogsTable->find()
                    ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt','user_input'])
                    ->hydrate(false)
                    ->join([
                        'c'=>[
                        'table' => 'cards',
                        'type' => 'LEFT',
                        'conditions' => 'c.id = Logs.card_id',
                        ],
                        'u'=>[
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'u.id = Logs.user_id',
                        ],
                        'd'=>[
                        'table' => 'decks',
                        'type' => 'LEFT',
                        'conditions' => 'd.id = Logs.deck_id',
                        ],
                        'lt'=>[
                        'table' => 'logs_types',
                        'type' => 'LEFT',
                        'conditions' => 'lt.id = Logs.log_type_id',
                        ]
                        
                    ])
                    ->order(['Logs.id' => 'ASC']));
    
            $logs =$query->toArray();


            //update ranks if negative
            foreach($logs as $key=>$log){
                if($log['before_rank']<0){
                    $logs[$key]['before_rank'] = 0;
                }
                if($log['rank']<0){
                    $logs[$key]['rank'] = 0;
                }

                if($log['mark_as']=='sx' || $log['mark_as']=='dx'){

                }else{
                    unset($logs[$key]);
                }


            }
            
          /*  if($query->count()>0){
                //create result set from query build
                $results = $query->all();


                // Once we have a result set we can get all the rows
                $logs = $results->toArray();




               
            }*/




        }



        $this->set('logs',$logs);



    }
    public function quickReviewLog(){

        $this->layout = 'ajax';

        $logs = [];



        $usersTable = TableRegistry::get('Users');


        $usersLists = [];
        $qry = $usersTable->find('all')
               ->where(['Users.id <>'=>$this->Auth->user('id')]);




        if($qry->count()>0){

            $rows = $qry->all();

            $rows = $rows->toArray();

            $z = 0;
            $usersLists[$z]['value'] = $this->Auth->user('id');
            $usersLists[$z]['inner'] = $this->Auth->user('full_name');

            $z++;

            foreach($rows as $row){
                $usersLists[$z]['value'] = $row['id'];
                $usersLists[$z]['inner'] = $row['full_name'];
                $z++;
            }
            $z = 0;




        }
        
        $this->set('usersLists',$usersLists);




        $reviewLogsTable = TableRegistry::get('Logs');




        if(!isset($_GET['uid'])){


        //    $query = $reviewLogsTable->find('all');

            $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Logs.id' => 'asc'
        ]];



            //build query an get all cards belong this requested deck ID
           $query =$this->paginate($reviewLogsTable->find()
                    ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt','sx','dx','st','dt'])
                    ->hydrate(false)
                    ->join([
                        'c'=>[
                        'table' => 'cards',
                        'type' => 'LEFT',
                        'conditions' => 'c.id = Logs.card_id',
                        ],
                        'u'=>[
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'u.id = Logs.user_id',
                        ],
                        'd'=>[
                        'table' => 'decks',
                        'type' => 'LEFT',
                        'conditions' => 'd.id = Logs.deck_id',
                        ],
                        'lt'=>[
                        'table' => 'logs_types',
                        'type' => 'LEFT',
                        'conditions' => 'lt.id = Logs.log_type_id',
                        ]
                        
                    ])
                    ->order(['Logs.id' => 'ASC']));
    
            $logs =$query->toArray();


            //update ranks if negative
            foreach($logs as $key=>$log){
                if($log['before_rank']<0){
                    $logs[$key]['before_rank'] = 0;
                }
                if($log['rank']<0){
                    $logs[$key]['rank'] = 0;
                }
            }
            
          /*  if($query->count()>0){
                //create result set from query build
                $results = $query->all();


                // Once we have a result set we can get all the rows
                $logs = $results->toArray();




               
            }*/




        }



        $this->set('logs',$logs);


    }




    public function manageTeachers(){


        $this->layout = 'ajax';
        $students = [];
        if($this->Auth->user('account_id')==1){

            $this->paginate = [
            'limit' => 10,
            //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
            'order' => [
                'Users.id' => 'asc'
            ]];


        

            //build query
            $teachersTable = TableRegistry::get('Users');


            $query = $this->paginate($teachersTable->find('all')->where(['Users.account_id'=>2,'Users.is_email_verify'=>1]));

            $teachers = $query->toArray();

        }


        $this->set('teachers',$teachers);

        




    
    }


    public function manageClasses(){

        $this->layout = 'ajax';

        $classes = [];


        $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Classes.id' => 'asc'
        ]];


        $globalUserId = $this->Auth->user('id');




        if($this->Auth->user('account_id')==1){


            $usersLists = array();


           //build query
            $classesTable = TableRegistry::get('Classes');


            $query = $this->paginate($classesTable->find('all'));

            $classes = $query->toArray();


            $usersTable = TableRegistry::get('Users');


            $validids = [1,2];

            $qry = $usersTable->find('all')
                   ->where(['Users.id <>'=>$this->Auth->user('id'),'Users.is_approve'=>1,'Users.account_id IN'=>$validids]);



            if($qry->count()>0){

                $rows = $qry->all();
                $rows = $rows->toArray();

                $z = 0;
                $usersLists[$z]['value'] = $this->Auth->user('id');
                $usersLists[$z]['inner'] = $this->Auth->user('full_name');

                $z++;



                foreach($rows as $row){
                    $usersLists[$z]['value'] = $row['id'];
                    $usersLists[$z]['inner'] = ucwords(strtolower($row['full_name']));
                    $z++;
                }

                $z = 0;

                //set current user name
                $curUser = ['username'=>$this->Auth->user('username'),'account_id'=>$this->Auth->user('account_id')];


                $this->set(compact('usersLists','curUser'));
            }
           

            /*if($query->count()>0){
                //create result set from query build
                $results = $query->all();


                // Once we have a result set we can get all the rows
                $decks = $results->toArray();
            }*/
        }elseif($this->Auth->user('account_id')==2){
            //build query
            $classesTable = TableRegistry::get('Classes');


            $usersClassesTable = TableRegistry::get('UsersClasses');



            $uc = $usersClassesTable->find('all')
                  ->where(['UsersClasses.user_id'=>$this->Auth->user('id')]);


            if($uc->count()>0){

                $rows = $uc->all();
                $rows = $rows->toArray();


                $ids = array();
                foreach($rows as $row){
                    array_push($ids,$row['class_id']);
                }

                $query = $this->paginate($classesTable->find('all')->where(['Classes.id IN'=>$ids]));


                //$query = $this->paginate($decksTable->find('all'));
                $classes = $query->toArray();



            }


        }


         
        $this->set(compact('classes','globalUserId'));




        
    }


    public function manageStudents(){


        $this->layout = 'ajax';
        $students = [];
        if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){

            $this->paginate = [
            'limit' => 10,
            //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
            'order' => [
                'Users.id' => 'asc'
            ]];


        

            //build query
            $studentsTable = TableRegistry::get('Users');


            $query = $this->paginate($studentsTable->find('all')->where(['Users.account_id'=>3,'Users.is_email_verify'=>1]));

            $students = $query->toArray();

        }


        $this->set('students',$students);

        






    }

    public function filterInputMistakeLog(){

        $this->layout = 'ajax';


        $logs = [];

        $globalUserId = intval($_GET['datas']['user_id']);

         $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Logs.id' => 'asc'
        ]];


        //start
        if($this->Auth->user('account_id')==1){

            $reviewLogsTable = TableRegistry::get('Logs');



            

            if($globalUserId==0){
            //build query an get all cards belong this requested deck ID
               $query =$this->paginate($reviewLogsTable->find()
                       // ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank'])
                       // ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt'])
                        ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt','user_input'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = Logs.card_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'u.id = Logs.user_id',
                            ],
                            'd'=>[
                            'table' => 'decks',
                            'type' => 'LEFT',
                            'conditions' => 'd.id = Logs.deck_id',
                            ],
                            'lt'=>[
                            'table' => 'logs_types',
                            'type' => 'LEFT',
                            'conditions' => 'lt.id = Logs.log_type_id',
                            ]
                            
                        ])
                        ->order(['Logs.id' => 'ASC']));
        
                $logs =$query->toArray();


                //update ranks if negative
                foreach($logs as $key=>$log){
                    if($log['before_rank']<0){
                        $logs[$key]['before_rank'] = 0;
                    }
                    if($log['rank']){
                        $logs[$key]['rank'] = 0;
                    }

                    if($log['mark_as']=='sx' || $log['mark_as']=='dx'){

                    }else{
                        unset($logs[$key]);
                    }

                }


            }else{
                //build query an get all cards belong this requested deck ID
               $query =$this->paginate($reviewLogsTable->find()
                        //->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank'])
                        //->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt'])
                        ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt','user_input'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = Logs.card_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'u.id = Logs.user_id',
                            ],
                            'd'=>[
                            'table' => 'decks',
                            'type' => 'LEFT',
                            'conditions' => 'd.id = Logs.deck_id',
                            ],
                            'lt'=>[
                            'table' => 'logs_types',
                            'type' => 'LEFT',
                            'conditions' => 'lt.id = Logs.log_type_id',
                            ]
                            
                        ])
                        ->where(['Logs.user_id'=>$globalUserId])
                        ->order(['Logs.id' => 'ASC']));
        
                $logs =$query->toArray();


                //update ranks if negative
                foreach($logs as $key=>$log){
                    if($log['before_rank']<0){
                        $logs[$key]['before_rank'] = 0;
                    }
                    if($log['rank']){
                        $logs[$key]['rank'] = 0;
                    }

                    if($log['mark_as']=='sx' || $log['mark_as']=='dx'){

                    }else{
                        unset($logs[$key]);
                    }

                }
            }
        }
        //end
        $this->set(compact('logs','globalUserId'));


    }
    public function filterStandardMistakeLog(){
        $this->layout = 'ajax';


        $logs = [];

        $globalUserId = intval($_GET['datas']['user_id']);

         $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Logs.id' => 'asc'
        ]];


        //start
        if($this->Auth->user('account_id')==1){

            $reviewLogsTable = TableRegistry::get('Logs');



            

            if($globalUserId==0){
            //build query an get all cards belong this requested deck ID
               $query =$this->paginate($reviewLogsTable->find()
                       // ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank'])
                        ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = Logs.card_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'u.id = Logs.user_id',
                            ],
                            'd'=>[
                            'table' => 'decks',
                            'type' => 'LEFT',
                            'conditions' => 'd.id = Logs.deck_id',
                            ],
                            'lt'=>[
                            'table' => 'logs_types',
                            'type' => 'LEFT',
                            'conditions' => 'lt.id = Logs.log_type_id',
                            ]
                            
                        ])
                        ->order(['Logs.id' => 'ASC']));
        
                $logs =$query->toArray();


                //update ranks if negative
                foreach($logs as $key=>$log){
                    if($log['before_rank']<0){
                        $logs[$key]['before_rank'] = 0;
                    }
                    if($log['rank']){
                        $logs[$key]['rank'] = 0;
                    }

                    if($log['mark_as']=='sx' || $log['mark_as']=='dx'){

                    }else{
                        unset($logs[$key]);
                    }
                }


            }else{
                //build query an get all cards belong this requested deck ID
               $query =$this->paginate($reviewLogsTable->find()
                        //->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank'])
                        ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = Logs.card_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'u.id = Logs.user_id',
                            ],
                            'd'=>[
                            'table' => 'decks',
                            'type' => 'LEFT',
                            'conditions' => 'd.id = Logs.deck_id',
                            ],
                            'lt'=>[
                            'table' => 'logs_types',
                            'type' => 'LEFT',
                            'conditions' => 'lt.id = Logs.log_type_id',
                            ]
                            
                        ])
                        ->where(['Logs.user_id'=>$globalUserId])
                        ->order(['Logs.id' => 'ASC']));
        
                $logs =$query->toArray();


                //update ranks if negative
                foreach($logs as $key=>$log){
                    if($log['before_rank']<0){
                        $logs[$key]['before_rank'] = 0;
                    }
                    if($log['rank']){
                        $logs[$key]['rank'] = 0;
                    }

                    if($log['mark_as']=='sx' || $log['mark_as']=='dx'){

                    }else{
                        unset($logs[$key]);
                    }
                }
            }
        }
        //end
        $this->set(compact('logs','globalUserId'));
    }

    public function filterLog(){

        $this->layout = 'ajax';


        $logs = [];

        $globalUserId = intval($_GET['datas']['user_id']);

         $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Logs.id' => 'asc'
        ]];


        //start
        if($this->Auth->user('account_id')==1){

            $reviewLogsTable = TableRegistry::get('Logs');



            

            if($globalUserId==0){
            //build query an get all cards belong this requested deck ID
               $query =$this->paginate($reviewLogsTable->find()
                       // ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank'])
                        ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt','sx','dx','st','dt'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = Logs.card_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'u.id = Logs.user_id',
                            ],
                            'd'=>[
                            'table' => 'decks',
                            'type' => 'LEFT',
                            'conditions' => 'd.id = Logs.deck_id',
                            ],
                            'lt'=>[
                            'table' => 'logs_types',
                            'type' => 'LEFT',
                            'conditions' => 'lt.id = Logs.log_type_id',
                            ]
                            
                        ])
                        ->order(['Logs.id' => 'ASC']));
        
                $logs =$query->toArray();


                //update ranks if negative
                foreach($logs as $key=>$log){
                    if($log['before_rank']<0){
                        $logs[$key]['before_rank'] = 0;
                    }
                    if($log['rank']){
                        $logs[$key]['rank'] = 0;
                    }
                }


            }else{
                //build query an get all cards belong this requested deck ID
               $query =$this->paginate($reviewLogsTable->find()
                        //->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank'])
                        ->select(['c.question','c.answer','history','last_seen','due_date','lt.description','u.full_name','id','before_history','before_rank','rank','mark_as','before_srt','srt'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = Logs.card_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'LEFT',
                            'conditions' => 'u.id = Logs.user_id',
                            ],
                            'd'=>[
                            'table' => 'decks',
                            'type' => 'LEFT',
                            'conditions' => 'd.id = Logs.deck_id',
                            ],
                            'lt'=>[
                            'table' => 'logs_types',
                            'type' => 'LEFT',
                            'conditions' => 'lt.id = Logs.log_type_id',
                            ]
                            
                        ])
                        ->where(['Logs.user_id'=>$globalUserId])
                        ->order(['Logs.id' => 'ASC']));
        
                $logs =$query->toArray();


                //update ranks if negative
                foreach($logs as $key=>$log){
                    if($log['before_rank']<0){
                        $logs[$key]['before_rank'] = 0;
                    }
                    if($log['rank']){
                        $logs[$key]['rank'] = 0;
                    }
                }
            }
        }
        //end
        $this->set(compact('logs','globalUserId'));



    }


    public function filterClass(){

        $this->layout = 'ajax';


        $classes = [];

        $globalUserId = intval($_GET['datas']['user_id']);




        $this->paginate = [
                        'limit' => 10,
                        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
                        'order' => [
                            'Classes.id' => 'asc'
                        ]];


        //start
        if($this->Auth->user('account_id')==1){
            if(isset($_GET['datas']['user_id'])){

                $userid = intval($_GET['datas']['user_id']);


                $usersClassesTable = TableRegistry::get('UsersClasses');

                if($userid!=0){
                    $qry = $usersClassesTable->find('all')
                           ->where(['UsersClasses.user_id'=>$userid]);

                    if($qry->count()>0){

                        $rows = $qry->all();
                        

                        $rows = $rows->toArray();

                        $ids = array();

                        foreach($rows as $row){
                            array_push($ids,$row['class_id']);
                        }

                        


                        $classesTable = TableRegistry::get('Classes');
                        $qry = $this->paginate($classesTable->find('all')
                               ->where(['Classes.id IN '=>$ids]));



                        $classes = $qry->toArray();


                        if($userid==$this->Auth->user('id')){
                            $canAddClass = 1;
                            $this->set('canAddClass',1);
                        }

                    

                    }
                }else{
                  


                    $classesTable = TableRegistry::get('Classes');
                    $qry = $this->paginate($classesTable->find('all'));

                    $classes = $qry->toArray();


                    $canAddClass = 1;

                    $this->set('canAddClass',$canAddClass);


                }


            }
        }

        //end


        $this->set(compact('classes','globalUserId'));



    }

    public function filterDeck(){

        $this->layout = 'ajax';


        $decks = [];

        $globalUserId = intval($_GET['datas']['user_id']);


        $this->paginate = [
                        'limit' => 10,
                        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
                        'order' => [
                            'Decks.id' => 'asc'
                        ]];


        //start
        if($this->Auth->user('account_id')==1){
            if(isset($_GET['datas']['user_id'])){

                $userid = intval($_GET['datas']['user_id']);

                $usersDecksTable = TableRegistry::get('UsersDecks');


                if($userid!=0){
                    $qry = $usersDecksTable->find('all')
                           ->where(['UsersDecks.user_id'=>$userid]);

                    if($qry->count()>0){

                        $rows = $qry->all();
                        

                        $rows = $rows->toArray();

                        $ids = array();

                        foreach($rows as $row){
                            array_push($ids,$row['deck_id']);
                        }

                        


                        $decksTable = TableRegistry::get('Decks');
                        $qry = $this->paginate($decksTable->find('all')
                               ->where(['Decks.id IN '=>$ids]));



                        $decks = $qry->toArray();


                        if($userid==$this->Auth->user('id')){
                            $canAddDeck = 1;
                            $this->set('canAddDeck',1);
                        }

                    

                    }
                }else{
                   


                    $decksTable = TableRegistry::get('Decks');
                    $qry = $this->paginate($decksTable->find('all'));

                    $decks = $qry->toArray();


                    $canAddDeck = 1;

                    $this->set('canAddDeck',$canAddDeck);


                }


            }
        }

        //end


        $this->set(compact('decks','globalUserId'));



    }



    public function manageDecks(){

        $this->layout = 'ajax';

        $decks = [];


        $this->paginate = [
        'limit' => 10,
        //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
        'order' => [
            'Decks.id' => 'asc'
        ]];




        if($this->Auth->user('account_id')==1){


            $usersLists = array();


           //build query
            $decksTable = TableRegistry::get('Decks');


            $query = $this->paginate($decksTable->find('all'));

            $decks = $query->toArray();


            $usersTable = TableRegistry::get('Users');


            $qry = $usersTable->find('all')
                   ->where(['Users.id <>'=>$this->Auth->user('id'),'Users.is_approve'=>1]);



            if($qry->count()>0){

                $rows = $qry->all();
                $rows = $rows->toArray();

                $z = 0;
                $usersLists[$z]['value'] = $this->Auth->user('id');
                $usersLists[$z]['inner'] = $this->Auth->user('full_name');

                $z++;



                foreach($rows as $row){
                    $usersLists[$z]['value'] = $row['id'];
                    $usersLists[$z]['inner'] = ucwords(strtolower($row['full_name']));
                    $z++;
                }

                $z = 0;

                //set current user name
                $curUser = ['username'=>$this->Auth->user('username'),'account_id'=>$this->Auth->user('account_id')];


                $this->set(compact('usersLists','curUser'));
            }
           

            /*if($query->count()>0){
                //create result set from query build
                $results = $query->all();


                // Once we have a result set we can get all the rows
                $decks = $results->toArray();
            }*/
        }else{
            //build query
            $decksTable = TableRegistry::get('Decks');


            $usersDecksTable = TableRegistry::get('UsersDecks');



            $ud = $usersDecksTable->find('all')
                  ->where(['UsersDecks.user_id'=>$this->Auth->user('id')]);


            if($ud->count()>0){

                $rows = $ud->all();
                $rows = $rows->toArray();


                $ids = array();
                foreach($rows as $row){
                    array_push($ids,$row['deck_id']);
                }

                $query = $this->paginate($decksTable->find('all')->where(['Decks.id IN'=>$ids]));


                //$query = $this->paginate($decksTable->find('all'));
                $decks = $query->toArray();



            }


        }


         
        $this->set(compact('decks'));




        
    }


    public function getCurrentTime(){
        $this->layout = 'ajax';
        $time = time();
        $this->set('content',$time);

    }



    public function uploadSound(){
        $this->layout = 'ajax';
        $content = '';
        if(isset($_POST['datas']['file']) && isset($_POST['datas']['type']) && isset($_POST['datas']['cardid'])){
            

            $_POST['datas']['cardid'] = intval($_POST['datas']['cardid']);


            $cardId = $_POST['datas']['cardid'];


            $cardsTable = TableRegistry::get('Cards');

                

            //make sure that the card exist first
            $query = $cardsTable->find('all')
                    ->where(['Cards.id'=>$cardId]);


            if($query->count()>0){


               







                $fileCreate = md5(time() . $_POST['datas']['cardid']) . '.mp3'; 


                $type = $_POST['datas']['type'];


                if($type=='slow_q' || $type=='fast_q' || $type=='slow_a' || $type=='fast_a'){


                    //check if there is existing file in that card, if there is then delete it
                    //create result set from query build
                    $row = $query->first();


                    if($row['question_sound_slow']!='' || $row['question_sound_fast']!='' || $row['answer_sound_slow']!='' || $row['answer_sound_fast']!=''){

                        if($row['question_sound_slow']!='' && $type=='slow_q'){
                            $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $row['question_sound_slow'];
                            unlink($deleteFile);
                        }


                        if($row['question_sound_fast']!='' && $type=='fast_q'){
                            $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $row['question_sound_fast'];
                            unlink($deleteFile);
                        }

                        if($row['answer_sound_slow']!='' && $type=='slow_a'){
                            $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $row['answer_sound_slow'];
                            unlink($deleteFile);
                        }

                        if($row['answer_sound_fast']!='' && $type=='fast_a'){
                            $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $row['answer_sound_fast'];
                            unlink($deleteFile);
                        }
                    
                        
                    }

                    

                    $data = substr($_POST['datas']['file'], strpos($_POST['datas']['file'], ",") + 1);
                    $decodedData = base64_decode($data);


                    $fileName = ROOT . DS . 'webroot' . DS . 'files' . DS . $fileCreate;

                    $fp = fopen($fileName,'wb');
                    $fwrite = fwrite($fp, $decodedData);
                    if ($fwrite == true){
                        fclose($fp);

                        $card = $cardsTable->get($cardId); // Return article with id 12
                        if($type=='slow_q'){
                            $card->question_sound_slow = $fileCreate;
                        }elseif($type=='fast_q'){
                             $card->question_sound_fast = $fileCreate;  
                        }elseif($type=='slow_a'){
                             $card->answer_sound_slow = $fileCreate;
                        }elseif($type=='fast_a'){
                            $card->answer_sound_fast = $fileCreate;
                        }

                        $cardsTable->save($card);



                        $content = json_encode(['status'=>'ok']);
                    




                    }else
                        $content = json_encode(['status'=>'not ok2']);
                    
                    

                }else
                    $content = json_encode(['status'=>'not ok3']);
            }else
                $content = json_encode(['status'=>'not ok4']);



            

        }else
            $content = json_encode(['status'=>'not ok5']);
        

        $this->set('content',$content);


    }





    public function addMultipleCards(){
        $this->layout = 'ajax';
        //$status = json_encode(['status'=>'ok']);
        $status = ['errors'=>'','status'=>'ok'];
        $target_file = basename($_FILES['filetocheck']['name']);
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        $uploadOk = 1;
        if(isset($_POST['addCardMultiple'])) {

            // Check file size
            if ($_FILES['filetocheck']['size'] > 500000) {
                $status['errors'] .='Sorry, your file is too large.';
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType!='csv'){
                $status['errors'] .='Sorry, only CSV Files are allowed.';
                $uploadOk = 0;
            }


        }else
            $status['status'] = 'not ok';
        
        $deckId = intval($_POST['addCardMultiple']);

        $usersDecksTable = TableRegistry::get('UsersDecks');
        $cardsDecksTable = TableRegistry::get('CardsDecks');

        //find deck ID if owned by the current user
        //build query


        $query = $usersDecksTable->find('all')
                ->where(['UsersDecks.user_id'=>$this->Auth->user('id'),'UsersDecks.deck_id'=>$deckId]);


        if($query->count()>0){
            //if no errors
            if($uploadOk==1){

                $row = 1;
               // $handle = $_FILES['filetocheck'];
                $handle = fopen($_FILES['filetocheck']['tmp_name'], "r");
                $dataBuilds = array();
                $x = 0;
                while($data = fgetcsv($handle)){
                    $dataBuilds[$x]['question'] = $data[0]; 
                    $dataBuilds[$x]['question_notes'] = $data[1];
                    $dataBuilds[$x]['answer'] = $data[2];
                    $dataBuilds[$x]['answer_notes'] = $data[3];
                    $dataBuilds[$x]['date_created'] = time();
                    $x++;
                }
                fclose($handle);
               $cardsTable = TableRegistry::get('Cards');
               $insertCards = $cardsTable->newEntities($dataBuilds,['validate'=>false]);
                foreach ($insertCards as $insertCard){
                    if($obtainCardId = $cardsTable->save($insertCard)){
                        

                        $cardId = $newCard->id;
                        $cardDeck = $cardsDecksTable->newEntity(array('card_id'=>$obtainCardId->id,'deck_id'=>$deckId),['validate'=>false]);
                        $cardsDecksTable->save($cardDeck);
                        
                    }
                }
                $status['status'] = 'ok';

            }else
                $status['status'] = 'not ok';


      
        




        }else
            $status['status'] = 'not ok';


        $status = json_encode($status);
        $this->set('content',$status);

    }



    public function viewAddMultipleCards(){

        $this->layout = 'ajax';

    }

    public function viewGameMode(){


        
        $this->layout = 'ajax';


       
        $curUser = ['account_id'=>$this->Auth->user('account_id')];
        $this->set('curUser',$curUser);




    }


    public function viewGameSettings(){

        $this->layout = 'ajax';

        //get users information
        $u = TableRegistry::get('Users');

        $qry = $u->find('all')
               ->where(['Users.id' => $this->Auth->user('id')]);


        if($qry->count()>0){
            $userInfo = $qry->first();
        }


        $this->set('userInfo',$userInfo);

    }



    public function viewAddCard(){

        $this->layout = 'ajax';


    }

    public function viewAddDeck(){

        $this->layout = 'ajax';


    }



    public function viewAddClassFilter(){



        $this->layout = 'ajax';

        $status = ['status'=>'ok'];

        if($this->Auth->user('account_id')==1 ||$this->Auth->user('account_id')==2){

            if(isset($_GET['datas']['user_id'])){


                $userid = intval($_GET['datas']['user_id']);

                $usersTable = TableRegistry::get('Users');


                //check if that user existed

                $validids = [1,2]; 


                $qry = $usersTable->find('all')
                       ->where(['id'=>$userid,'is_approve'=>1,'account_id IN'=>$validids]);

                if($qry->count()>0){
                    $status = ['status'=>'ok','userid'=>$userid];
                }else{
                    $status = ['status'=>'not ok'];
                }



            }   
        }
        

        $this->set('status',$status);

       
    }


    public function viewAddDeckFilter(){



        $this->layout = 'ajax';

        $status = ['status'=>'ok'];

        if($this->Auth->user('account_id')==1){

            if(isset($_GET['datas']['user_id'])){


                $userid = intval($_GET['datas']['user_id']);

                $usersTable = TableRegistry::get('Users');


                //check if that user existed

                $qry = $usersTable->find('all')
                       ->where(['id'=>$userid,'is_approve'=>1]);

                if($qry->count()>0){
                    $status = ['status'=>'ok','userid'=>$userid];
                }else{
                    $status = ['status'=>'not ok'];
                }



            }   
        }
        

        $this->set('status',$status);

       
    }

    public function viewAddClass(){

        $this->layout = 'ajax';


    }

    public function viewAddStudent(){

        $this->layout = 'ajax';


    }


    public function viewAddTeacher(){

        $this->layout = 'ajax';


    }


    
    public function viewClassDeckProfile(){

        $this->layout = 'ajax';


        $users = [];



        if(isset($_GET['classid'])){


            $this->paginate = [
            'limit' => 10,
            //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
            'order' => [
                'd.id' => 'asc'
            ]];


            $classid = intval($_GET['classid']);

            $classesTable = TableRegistry::get('Classes');


            //proceed to getting Students for this Class
            //build query an get all cards belong this requested deck ID
            $query =$this->paginate($classesTable->find()
                    ->select(['d.id','d.full_name','full_name'])
                    ->hydrate(false)
                    ->join([
                        'cd'=>[
                        'table' => 'classes_decks',
                        'type' => 'LEFT',
                        'conditions' => 'cd.class_id = Classes.id'
                        ],
                        'd'=>[
                        'table' => 'decks',
                        'type' => 'LEFT',
                        'conditions' => 'd.id = cd.deck_id'
                        ]
                    ])
                    ->where(['Classes.id'=>$classid]));


            $decks = $query->toArray();


            $this->set('content',$decks);



        }else
            $this->set('content','not ok');


    }




    public function viewClassProfile(){

        $this->layout = 'ajax';


        $users = [];



        if(isset($_GET['classid'])){


            $this->paginate = [
            'limit' => 10,
            //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
            'order' => [
                'u.id' => 'asc'
            ]];


            $classid = intval($_GET['classid']);

            $classesTable = TableRegistry::get('Classes');


            //proceed to getting Students for this Class
            //build query an get all cards belong this requested deck ID
            $query =$this->paginate($classesTable->find()
                    ->select(['u.id','u.full_name','full_name'])
                    ->hydrate(false)
                    ->join([
                        'cs'=>[
                        'table' => 'classes_students',
                        'type' => 'LEFT',
                        'conditions' => 'cs.class_id = Classes.id'
                        ],
                        'u'=>[
                        'table' => 'users',
                        'type' => 'LEFT',
                        'conditions' => 'u.id = cs.user_id'
                        ]
                    ])
                    ->where(['Classes.id'=>$classid]));


            $users = $query->toArray();


            $this->set('content',$users);
    




        }else
            $this->set('content','not ok');


    }









    public function viewDeckProfile(){

        $this->layout = 'ajax';

      $cards = array();

        if(isset($_GET['deckid'])){


            $this->paginate = [
            'limit' => 10,
            //'fields' => ['Cards.question','Cards.answer','history','last_seen','due_date','LogsTypes.description','Users.full_name','id','before_history','before_rank','rank'],
            'order' => [
                'c.id' => 'asc'
            ]];

            $cards = [];
            //get all cards decks table
            $cardsDecksTable = TableRegistry::get('CardsDecks');

            //get decks table first
            $decksTable = TableRegistry::get('Decks');

            $deckId = intval($_GET['deckid']);

             //build query an get all cards belong this requested deck ID
            $query =$this->paginate($decksTable->find()
                    ->select(['c.id','c.question','c.question_notes','c.answer','c.answer_notes','c.date_created','full_name','c.question_sound_slow','c.question_sound_fast','c.answer_sound_slow','c.answer_sound_fast'])
                    ->hydrate(false)
                    ->join([
                        'cd'=>[
                        'table' => 'cards_decks',
                        'type' => 'LEFT',
                        'conditions' => 'cd.deck_id = Decks.id',
                        ],
                        'c'=>[
                        'table' => 'cards',
                        'type' => 'LEFT',
                        'conditions' => 'c.id = cd.card_id',
                        ]
                        
                    ])
                    ->where(['Decks.id' => $deckId]));


            $cards = $query->toArray();


            //now make sure there is a result
          /*  if($query->count()>0){

                //create result set from query build
                $results = $query->all();


                // Once we have a result set we can get all the rows
               // $cardsDecks = $results->toArray();

                $cards = $results->toArray();

                //$cards['path'] =  ROOT . DS . 'webroot' . DS . 'files' . DS;


                
            }*/
            $this->set('content',$cards);

        }else{
            $this->set('content','not ok');
        }

        








    }

    public function addStudent(){

        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];



        /* start */
        if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){
        if(isset($_POST['datas'])){         


            //make sure all required fields are filled up
            if(isset($_POST['datas']['full_name']) && isset($_POST['datas']['email_address']) && isset($_POST['datas']['username']) && isset($_POST['datas']['password']) && isset($_POST['datas']['repassword'])){



                 $studentsTable = TableRegistry::get('Users');


                $student = $studentsTable->newEntity($_POST['datas'],['validate'=>'addstudentvalidation']);


                if(!$student->errors()){



                    $_POST['datas']['account_id'] = 3;
                    $_POST['datas']['created'] = time();
                    $_POST['datas']['is_email_verify'] = 1;
                    $_POST['datas']['is_approve'] = 1;
                    $_POST['datas']['dx'] = 0.6;
                    $_POST['datas']['sx'] = 0.8;
                    $_POST['datas']['st'] = 2;
                    $_POST['datas']['dt'] = 5;


                    $student = $studentsTable->patchEntity($student,$_POST['datas']);

                    if($savetu = $studentsTable->save($student)){

                        $teachersUsersTable = TableRegistry::get('TeachersUsers');


                        if($this->Auth->user('account_id')==1)
                            $dataSave = ['user_id'=>$savetu->id,'teacher_user_id'=>0];
                        elseif($this->Auth->user('account_id')==2)
                          $dataSave = ['user_id'=>$savetu->id,'teacher_user_id'=>$this->Auth->user('id')];   
                        
                        

                        $tu = $teachersUsersTable->newEntity($dataSave,['validate'=>false]);

                        $teachersUsersTable->save($tu);

                        $status = ['status'=>'ok']; 

                    }


                }else{
                    $errors = $student->errors();

                    $errorDisplay = [];

                    if(isset($errors['full_name']))
                        $errorDisplay['full_name'] = $errors['full_name']['_empty'];

                    if(isset($errors['username']['_empty']))
                        $errorDisplay['username'] = $errors['username']['_empty'] . '<br/>';

                    if(isset($errors['username']['custom']))
                        $errorDisplay['username'] .=$errors['username']['custom'];



                    if(isset($errors['email_address']['_empty']))
                        $errorDisplay['email_address'] = $errors['email_address']['_empty'];


                    if(isset($errors['email_address']['validFormat']))
                        $errorDisplay['email_address'] .= $errors['email_address']['validFormat'];


                    if(isset($errors['email_address']['custom']))
                        $errorDisplay['email_address'] .= $errors['email_address']['custom'];



                    if(isset($errors['password']))
                        $errorDisplay['password'] = $errors['password']['_empty'];




                    if(isset($errors['repassword']['_empty']))
                        $errorDisplay['repassword'] = $errors['repassword']['_empty'];


                    if(isset($errors['repassword']['custom']))
                        $errorDisplay['repassword'] .= $errors['repassword']['custom'];



                    


                    $status = ['status'=>'ok','errors'=>$errorDisplay,'realErrors'=>$errors];
                }







            
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];

        }
        /* end */


        $status = json_encode($status);
        $this->set('content',$status);


    }



    public function addTeacher(){

        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];

        /*start */
        if($this->Auth->user('account_id')==1){
        if(isset($_POST['datas'])){         


            //make sure all required fields are filled up
            if(isset($_POST['datas']['full_name']) && isset($_POST['datas']['email_address']) && isset($_POST['datas']['username']) && isset($_POST['datas']['password']) && isset($_POST['datas']['repassword'])){



                 $teachersTable = TableRegistry::get('Users');


                $teacher = $teachersTable->newEntity($_POST['datas'],['validate'=>'addvalidation']);


                if(!$teacher->errors()){



                    $_POST['datas']['account_id'] = 2;
                    $_POST['datas']['created'] = time();
                    $_POST['datas']['is_email_verify'] = 1;
                    $_POST['datas']['is_approve'] = 1;
                    $_POST['datas']['dx'] = 0.6;
                    $_POST['datas']['sx'] = 0.8;
                    $_POST['datas']['st'] = 2;
                    $_POST['datas']['dt'] = 5;


                    $teacher = $teachersTable->patchEntity($teacher,$_POST['datas']);

                    if($teachersTable->save($teacher))
                        $status = ['status'=>'ok']; 


                }else{
                    $errors = $teacher->errors();

                    $errorDisplay = [];

                    if(isset($errors['full_name']))
                        $errorDisplay['full_name'] = $errors['full_name']['_empty'];

                    if(isset($errors['username']['_empty']))
                        $errorDisplay['username'] = $errors['username']['_empty'] . '<br/>';

                    if(isset($errors['username']['custom']))
                        $errorDisplay['username'] .=$errors['username']['custom'];



                    if(isset($errors['email_address']['_empty']))
                        $errorDisplay['email_address'] = $errors['email_address']['_empty'];


                    if(isset($errors['email_address']['validFormat']))
                        $errorDisplay['email_address'] .= $errors['email_address']['validFormat'];


                    if(isset($errors['email_address']['custom']))
                        $errorDisplay['email_address'] .= $errors['email_address']['custom'];



                    if(isset($errors['password']))
                        $errorDisplay['password'] = $errors['password']['_empty'];




                    if(isset($errors['repassword']['_empty']))
                        $errorDisplay['repassword'] = $errors['repassword']['_empty'];


                    if(isset($errors['repassword']['custom']))
                        $errorDisplay['repassword'] .= $errors['repassword']['custom'];



                    


                    $status = ['status'=>'ok','errors'=>$errorDisplay,'realErrors'=>$errors];
                }







            
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];


        }
        /* end */

        $status = json_encode($status);
        $this->set('content',$status);


    }


    public function addClassFilter(){

        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];


        if(isset($_POST['datas'])){         


            //make sure full name is inputted
            if(isset($_POST['datas']['full_name']) && isset($_POST['datas']['user_id'])){



                $ids = [1,2];


                //check if that user really existed
                $qry = $this->Users->find('all')
                       ->where(['Users.id'=>$_POST['datas']['user_id'],'Users.is_approve'=>1,'Users.account_id IN'=>$ids]);


                if($qry->count()>0){
                    $row = $qry->first();
                    //start
                    $_POST['datas']['full_name'] = trim($_POST['datas']['full_name']);


                    $classesTable = TableRegistry::get('Classes');


                    $class = $classesTable->newEntity($_POST['datas'],['validate'=>'default']);


                    if(!$class->errors()){
                        $_POST['datas']['date_time'] = time();
                        $class = $classesTable->patchEntity($class,$_POST['datas']);


                        if($classId = $classesTable->save($class)){
                            
                            $usersClassesTable = TableRegistry::get('UsersClasses');                    
                            $uc = $usersClassesTable->newEntity(array('user_id'=>$row['id'],'class_id'=>$classId->id),['validate'=>false]);
                            $usersClassesTable->save($uc);


                            $status = ['status'=>'ok'];

                        }
                        
                        
                    }else{
                        $errors = $class->errors();

                        $errorDisplay = [];

                        if(isset($errors['full_name']))
                            $errorDisplay['full_name'] = $errors['full_name']['_empty'];
                        


                        $status = ['status'=>'ok','errors'=>$errorDisplay];
                        
                            
                    }

                    //end

                }else
                    $status = ['status'=>'not ok'];
                
                
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];

        $status = json_encode($status);
        $this->set('content',$status);


    }


    public function addDeckFilter(){

        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];


        if(isset($_POST['datas'])){         


            //make sure full name is inputted
            if(isset($_POST['datas']['full_name']) && isset($_POST['datas']['user_id'])){


                //check if that user really existed
                $qry = $this->Users->find('all')
                       ->where(['Users.id'=>$_POST['datas']['user_id'],'Users.is_approve'=>1]);


                if($qry->count()>0){
                    $row = $qry->first();
                    //start
                    $_POST['datas']['full_name'] = trim($_POST['datas']['full_name']);


                    $decksTable = TableRegistry::get('Decks');


                    $deck = $decksTable->newEntity($_POST['datas'],['validate'=>'default']);


                    if(!$deck->errors()){
                        $_POST['datas']['date_created'] = time();
                        $deck = $decksTable->patchEntity($deck,$_POST['datas']);


                        if($deckId = $decksTable->save($deck)){
                            
                            $usersDecksTable = TableRegistry::get('UsersDecks');                    
                            $ud = $usersDecksTable->newEntity(array('user_id'=>$row['id'],'deck_id'=>$deckId->id),['validate'=>false]);
                            $usersDecksTable->save($ud);


                            $status = ['status'=>'ok'];

                        }
                        
                        
                    }else{
                        $errors = $deck->errors();

                        $errorDisplay = [];

                        if(isset($errors['full_name']))
                            $errorDisplay['full_name'] = $errors['full_name']['_empty'];
                        


                        $status = ['status'=>'ok','errors'=>$errorDisplay];
                        
                            
                    }

                    //end

                }else
                    $status = ['status'=>'not ok'];
                
                
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];

        $status = json_encode($status);
        $this->set('content',$status);


    }


    public function addClass(){

        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];




        if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){

            $classesTable = TableRegistry::get('Classes');


            if(isset($_POST['datas']['full_name'])){
                

                $classes = $classesTable->newEntity($_POST['datas'],['validate'=>'default']);


                $_POST['datas']['full_name'] = trim($_POST['datas']['full_name']);

                if($_POST['datas']['full_name']==''){
                    $errorDisplay['full_name'] = 'Class name is required.';
                    $status = ['status'=>'ok','errors'=>$errorDisplay];
                }else{

                    if(!$classes->errors()){

                        $_POST['datas']['date_time'] = time();
                        $saveClass = $classesTable->patchEntity($classes,$_POST['datas']);
                        
                        if($cid = $classesTable->save($saveClass)){


                            $usersClassesTable = TableRegistry::get('UsersClasses');



                            $data = ['user_id'=>$this->Auth->user('id'),'class_id'=>$cid->id];



                            $uc = $usersClassesTable->newEntity($data,['validate'=>false]);


                            $usersClassesTable->save($uc);
                            

                            $status = ['status'=>'ok'];
                        }

                    }else{

                        $errors = $classes->errors();

                        $errorDisplay = [];

                        if(isset($errors['full_name']))
                            $errorDisplay['full_name'] = $errors['full_name']['_empty'];

                        $status = ['status'=>'ok','errors'=>$errorDisplay];


                    }
                }


                


            }else{
                $status = ['status'=>'not ok'];
            }



        }else{
            $status = ['status'=>'not ok'];
        }






        $status = json_encode($status);

        $this->set('content',$status);


    }



    public function addDeck(){

        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];


        if(isset($_POST['datas'])){         






            //make sure full name is inputted
            if(isset($_POST['datas']['full_name'])){


                //find ID of the current user
                //build query
                $query =$this->Users->find('all')
                        ->where(['Users.username'=>$this->Auth->user('username')]);

                //create result set from query build
                $row = $query->first();


                $_POST['datas']['full_name'] = trim($_POST['datas']['full_name']);


                $decksTable = TableRegistry::get('Decks');


                $deck = $decksTable->newEntity($_POST['datas'],['validate'=>'default']);


                if(!$deck->errors()){
                    $_POST['datas']['date_created'] = time();
                    $deck = $decksTable->patchEntity($deck,$_POST['datas']);


                    if($deckId = $decksTable->save($deck)){
                        
                        $usersDecksTable = TableRegistry::get('UsersDecks');                    
                        $ud = $usersDecksTable->newEntity(array('user_id'=>$row['id'],'deck_id'=>$deckId->id),['validate'=>false]);
                        $usersDecksTable->save($ud);


                        $status = ['status'=>'ok'];

                    }
                    
                    
                }else{
                    $errors = $deck->errors();

                    $errorDisplay = [];

                    if(isset($errors['full_name']))
                        $errorDisplay['full_name'] = $errors['full_name']['_empty'];
                    


                    $status = ['status'=>'ok','errors'=>$errorDisplay];
                    
                        
                }

                
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];

        $status = json_encode($status);
        $this->set('content',$status);


    }



    public function viewEditStudent(){


        $this->layout = 'ajax';

        $student = [];

        $status = ['status'=>'ok'];

        if(isset($_GET['studentid'])){

            $studentid = intval($_GET['studentid']);


            $usersTable = TableRegistry::get('Users');

            $qry = $usersTable->find('all')
                   ->where(['account_id'=>3,'is_approve'=>1,'id'=>$studentid]);

            if($qry->count()>0){
                $status['student'] = $qry->first();
            }else{
                $status['status'] = 'not ok';
            }

        }else{
            $status['status'] = 'not ok';
        }


        $this->set('status',$status);
    }




    public function saveEditCard(){

        $this->layout = 'ajax';


        if(isset($_GET['datas']['card_id'])){

            $cardId = intval($_GET['datas']['card_id']); 
            

            $CardsDecksTable = TableRegistry::get('CardsDecks');

            //check if the current user owns that card


            //find card ID if owned by the current user
            //build query
            $query = $CardsDecksTable->find('all')
                    ->hydrate(false)
                    ->select(['c.question','c.question_notes','c.answer','c.answer_notes'])
                    ->join([
                            'ud'=>[
                            'table' => 'users_decks',
                            'type' => 'LEFT',
                            'conditions' => 'ud.deck_id = CardsDecks.deck_id',
                            ],
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = CardsDecks.card_id',
                            ]
                        ])
                    ->where(['CardsDecks.card_id'=>$cardId]);

            if($query->count()>0){

                //now proceed to editing the card and saving it


                $checkIfLack = 0;


                $lists = ['question','question_notes','answer','answer_notes'];


                foreach($lists as $val){
                    if(!isset($_GET['datas'][$val]))
                        $checkIfLack++;
                    else
                        $_GET['datas'][$val] = trim($_GET['datas'][$val]);
                }


                if($checkIfLack==0){
                    $CardsTable = TableRegistry::get('Cards');

                   // $card = $CardsTable->newEntity($_GET['datas'],['validate'=>'default']);
                    $card = $CardsTable->newEntity($_GET['datas'],['validate'=>false]);

                    if(!$card->errors()){


                        $cardSave = $CardsTable->get(intval($_GET['datas']['card_id']));


                        $cardSave->question = $_GET['datas']['question'];
                        $cardSave->question_notes = $_GET['datas']['question_notes'];
                        $cardSave->answer = $_GET['datas']['answer'];
                        $cardSave->answer_notes = $_GET['datas']['answer_notes'];

                        if($CardsTable->save($cardSave))
                            $status = ['status'=>'ok'];
                        else
                            $status = ['status'=>'not ok'];
                        
                    }else{
                        $errors = $card->errors();

                        $errorDisplay = [];


                        foreach($lists as $val){
                            if(isset($errors[$val]))
                                $errorDisplay[$val] = $errors[$val]['_empty'];
                        }
                        

                        $status = ['status'=>'ok','errors'=>$errorDisplay];
                        
                            
                    }

                }else{
                    $status = ['status'=>'not ok'];
                }
                











            }else
                $status = ['status'=>'not ok'];
            



        }else
            $status = ['status'=>'not ok'];
       
        


        $status = json_encode($status);

        $this->set('content',$status);




    }



    public function editCard(){

        //use ajax layout
        $this->layout = 'ajax';



        if(isset($_GET['datas']['card_id'])){

            $cardId = intval($_GET['datas']['card_id']); 
            

            $CardsDecksTable = TableRegistry::get('CardsDecks');

            //check if the current user owns that card


            //find card ID if owned by the current user
            //build query
            $query = $CardsDecksTable->find('all')
                    ->hydrate(false)
                    ->select(['c.question','c.question_notes','c.answer','c.answer_notes'])
                    ->join([
                            'ud'=>[
                            'table' => 'users_decks',
                            'type' => 'LEFT',
                            'conditions' => 'ud.deck_id = CardsDecks.deck_id',
                            ],
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'LEFT',
                            'conditions' => 'c.id = CardsDecks.card_id',
                            ]
                        ])
                    ->where(['CardsDecks.card_id'=>$cardId]);

            if($query->count()>0){

                //create result set from query build
                $cardInformation = $query->first();


                $status = $cardInformation;
                $status['status'] = 'ok';





            }else
                $status = ['status'=>'not ok'];
            



        }else
            $status = ['status'=>'not ok'];
       
        


        $status = json_encode($status);

        $this->set('content',$status);


    }


    public function addCard(){

        //use ajax layout
        $this->layout = 'ajax';


        //set status default to ok
        $status = ['status'=>'ok'];

        if(isset($_POST['datas'])){


            $lists = ['question','question_notes','answer','answer_notes','deck_id'];
            //make sure full name is inputted

            $checkIfLack = 0;
            foreach($lists as $list){
                if(!isset($_POST['datas'][$list]))
                    $checkIfLack++;
                else
                    $_POST['datas'][$list] = trim($_POST['datas'][$list]);
            }


            if($checkIfLack==0){

                $CardsTable = TableRegistry::get('Cards'); 
                $DecksTable = TableRegistry::get('Decks');
                $UsersDecksTable = TableRegistry::get('UsersDecks');
                $CardsDecksTable = TableRegistry::get('CardsDecks'); 




                //find deck ID if owned by the current user
                //build query
                //exception on Admin

                if($this->Auth->user('account_id')!=1){
                    $query = $UsersDecksTable->find('all')
                        ->where(['UsersDecks.user_id'=>$this->Auth->user('id'),'UsersDecks.deck_id'=>$_POST['datas']['deck_id']]);
                }else{
                    $query = $UsersDecksTable->find('all')
                        ->where(['UsersDecks.deck_id'=>$_POST['datas']['deck_id']]);
                }
               


                if($query->count()>0){


                   // $card = $CardsTable->newEntity($_POST['datas'],['validate'=>'default']);
                    $card = $CardsTable->newEntity($_POST['datas'],['validate'=>false]);
                    if(!$card->errors()){
                        $_POST['datas']['date_created'] = time();
                        $card = $CardsTable->newEntity($_POST['datas'],['validate'=>false]);
                        if($newCard = $CardsTable->save($card)){

                            $cardId = $newCard->id;
                            $cardDeck = $CardsDecksTable->newEntity(array('card_id'=>$cardId,'deck_id'=>$_POST['datas']['deck_id']),['validate'=>false]);
                            if($CardsDecksTable->save($cardDeck))
                                $status = ['status'=>'ok','card'=>$newCard];




                        }else
                            $status = ['status'=>'not ok'];
                        
                    }else{
                        $errors = $card->errors();

                        $errorDisplay = [];

                        foreach($lists as $list){
                            if(isset($errors[$list]))
                                $errorDisplay[$list] = $errors[$list]['_empty'];
                        }

                        $status = ['status'=>'ok','errors'=>$errorDisplay];
                    }

                }else{
                    $status = ['status'=>'not ok'];
                }

                
            }else
                $status = ['status'=>'not ok'];
            


        }else
            $status = ['status'=>'not ok'];

        $status = json_encode($status);
        $this->set('content',$status);
    }


    public function saveGameSettings(){

         //use ajax layout
        $this->layout = 'ajax';

        //set status default to ok
        $status = ['status'=>'ok'];


        if(isset($_POST['datas']['sx']) && isset($_POST['datas']['dx']) && isset($_POST['datas']['st']) && isset($_POST['datas']['dt']) && isset($_POST['datas']['sound'])){         


                $_POST['datas']['sx'] = trim($_POST['datas']['sx']);
                $_POST['datas']['dx'] = trim($_POST['datas']['dx']);

                $_POST['datas']['st'] = floatval($_POST['datas']['st']);
                $_POST['datas']['dt'] = floatval($_POST['datas']['dt']);



                $_POST['datas']['sound'] = intval($_POST['datas']['sound']);



                if($_POST['datas']['sound']>1)
                    $_POST['datas']['sound'] = 0;
                

                

                $user = $this->Users->newEntity($_POST['datas'],['validate'=>'gamesettings']);


                if(!$user->errors()){


                    //find ID of the current user
                    //build query
                    $query =$this->Users->find('all')
                            ->where(['Users.username'=>$this->Auth->user('username')]);

                    //create result set from query build
                    $row = $query->first();



                    $userSave = $this->Users->get($row['id']);
                    

                    $userSave->sx = $_POST['datas']['sx'];
                    $userSave->dx = $_POST['datas']['dx'];
                    $userSave->st = $_POST['datas']['st'];
                    $userSave->dt = $_POST['datas']['dt'];
                    $userSave->sound = $_POST['datas']['sound'];


                    if($this->Users->save($userSave))
                        $status = ['status'=>'ok'];
                    
                    
                }else{
                    $errors = $user->errors();

                    $errorDisplay = [];

                    if(isset($errors['sx']['_empty']))
                        $errorDisplay['sx'] = $errors['sx']['_empty'];

                    if(isset($errors['sx']['custom']))
                        $errorDisplay['sx'] .= ' ' . $errors['sx']['custom'];

                    if(isset($errors['dx']['_empty']))
                        $errorDisplay['dx'] = $errors['dx']['_empty'];

                    if(isset($errors['dx']['custom']))
                        $errorDisplay['dx'] .= ' ' . $errors['dx']['custom'];
                    
                    if(isset($errors['st']['_empty']))
                        $errorDisplay['st'] = $errors['st']['_empty'];

                    if(isset($errors['st']['custom']))
                        $errorDisplay['st'] .= ' ' . $errors['st']['custom'];

                    if(isset($errors['dt']['_empty']))
                        $errorDisplay['dt'] = $errors['dt']['_empty'];

                    if(isset($errors['st']['custom']))
                        $errorDisplay['sx'] .= ' ' . $errors['st']['custom'];




                    $status = ['status'=>'ok','errors'=>$errorDisplay];
                    
                        
                }

        }else
            $status = ['status'=>'not ok','datas'=>$_POST['datas']];

        $status = json_encode($status);
        $this->set('content',$status);

    }


    //start
    public function saveEditClass(){


        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];



        if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){



            if(isset($_POST['datas']['full_name']) && isset($_POST['datas']['class_id'])){

                $usersClassesTable = TableRegistry::get('UsersClasses');
                $classesTable = TableRegistry::get('Classes');

                if($this->Auth->user('account_id')==2){
                    $qry = $usersClassesTable->find('all')
                       ->where(['class_id'=>intval($_POST['datas']['class_id']),'user_id'=>$this->Auth->user('id')]);
                }else{
                    $qry = $usersClassesTable->find('all')
                       ->where(['class_id'=>intval($_POST['datas']['class_id'])]);
                }

                    if($qry->count()>0){

                        $datas = ['full_name'=>trim($_POST['datas']['full_name'])];

                        $c = $classesTable->newEntity($datas,['validate'=>'default']);

                        if($c->errors()){
                            $status = ['status'=>'ok','errors'=>1];
                        }else{


                            $entity = $classesTable->get(intval($_POST['datas']['class_id']));


                            $entity->full_name = $datas['full_name'];


                            $classesTable->save($entity);



                            $status = ['status'=>'ok'];
                        }





                    }else{
                        $status = ['status'=>'not ok'];
                    }


                
            }else{
                 $status = ['status'=>'not ok'];
            }



        }else{
            $status = ['status'=>'not ok'];
        }


        $status = json_encode($status);
        $this->set('content',$status);
       

    }



    //start
    public function saveEditStudent(){


        $this->layout = 'ajax';
        //set status default to ok
        $status = ['status'=>'ok'];






        /* start */
        if($this->Auth->user('account_id')==1){
        if(isset($_POST['datas'])){         


            //make sure all required fields are filled up
            if(isset($_POST['datas']['full_name']) && isset($_POST['datas']['sx']) && isset($_POST['datas']['dx']) && isset($_POST['datas']['st']) && isset($_POST['datas']['dt']) && isset($_POST['datas']['student_id'])){



                $studentsTable = TableRegistry::get('Users');



                //check if existed

                $qry = $studentsTable->find('all')
                       ->where(['account_id'=>3,'id'=>intval($_POST['datas']['student_id'])]);


                if($qry->count()>0){


                    $student = $studentsTable->newEntity($_POST['datas'],['validate'=>'editstudent']);


                    $_POST['datas']['sx'] = trim($_POST['datas']['sx']);
                    $_POST['datas']['dx'] = trim($_POST['datas']['dx']);

                    $_POST['datas']['st'] = floatval($_POST['datas']['st']);
                    $_POST['datas']['dt'] = floatval($_POST['datas']['dt']);

                    if(!$student->errors()){


                        $stid = intval($_POST['datas']['student_id']);

                        $sEntity = $studentsTable->get($stid);


                        $sEntity->full_name = trim($_POST['datas']['full_name']);
                        $sEntity->sx = $_POST['datas']['sx'];
                        $sEntity->dx = $_POST['datas']['dx'];
                        $sEntity->st = $_POST['datas']['st'];
                        $sEntity->dt = $_POST['datas']['dt'];

                        $studentsTable->save($sEntity);

                        $status = ['status'=>'ok'];

                    }else{
                        $errors = $student->errors();

                        $errorDisplay = [];

                        if(isset($errors['full_name']['_empty']))
                            $errorDisplay['full_name'] = $errors['full_name']['_empty'];


                        if(isset($errors['sx']['_empty']))
                            $errorDisplay['sx'] = $errors['sx']['_empty'];

                        if(isset($errors['sx']['custom']))
                            $errorDisplay['sx'] .= ' ' . $errors['sx']['custom'];

                        if(isset($errors['dx']['_empty']))
                            $errorDisplay['dx'] = $errors['dx']['_empty'];

                        if(isset($errors['dx']['custom']))
                            $errorDisplay['dx'] .= ' ' . $errors['dx']['custom'];
                        
                        if(isset($errors['st']['_empty']))
                            $errorDisplay['st'] = $errors['st']['_empty'];

                        if(isset($errors['st']['custom']))
                            $errorDisplay['st'] .= ' ' . $errors['st']['custom'];

                        if(isset($errors['dt']['_empty']))
                            $errorDisplay['dt'] = $errors['dt']['_empty'];

                        if(isset($errors['st']['custom']))
                            $errorDisplay['sx'] .= ' ' . $errors['st']['custom'];
                        

                        $status = ['status'=>'ok','errors'=>$errorDisplay,'realErrors'=>$errors];
                    }

                }else{
                    $status = ['status'=>'not ok'];

                }

 

            
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];

        }
        /* end */


        $status = json_encode($status);
        $this->set('content',$status);
       

    }
    //end



    public function saveProfileDashboard(){

         //use ajax layout
        $this->layout = 'ajax';

        //set status default to ok
        $status = ['status'=>'ok'];


        if(isset($_POST['datas'])){         

            //make sure full name is inputted
            if(isset($_POST['datas']['full_name'])){

                $_POST['datas']['full_name'] = trim($_POST['datas']['full_name']);
                $user = $this->Users->newEntity($_POST['datas'],['validate'=>'saveprofile']);


                if(!$user->errors()){


                    //find ID of the current user
                    //build query
                    $query =$this->Users->find('all')
                            ->where(['Users.username'=>$this->Auth->user('username')]);

                    //create result set from query build
                    $row = $query->first();



                    $userSave = $this->Users->get($row['id']);
                    $userSave->full_name = $_POST['datas']['full_name'];
                    if($this->Users->save($userSave))
                        $status = ['status'=>'ok'];
                    
                    
                }else{
                    $errors = $user->errors();

                    $errorDisplay = [];

                    if(isset($errors['full_name']))
                        $errorDisplay['full_name'] = $errors['full_name']['_empty'];
                    


                    $status = ['status'=>'ok','errors'=>$errorDisplay];
                    
                        
                }



                
            }else
                $status = ['status'=>'not ok'];

        }else
            $status = ['status'=>'not ok'];

        $status = json_encode($status);
        $this->set('content',$status);

    }


    public function viewProfile(){

        //build query
        $query =$this->Users->find('all')
                ->where(['Users.username' =>$this->Auth->user('username')]);

        //create result set from query build
        $data = $query->first();




        $curUser = ['username'=>$this->Auth->user('username'),'full_name'=>$data['full_name']];
        $this->layout = 'ajax';

        
        $this->set('curUser',$curUser);
    }



    //verify email
    public function verifyEmail(){

        //set layout to ajax
       $this->layout= 'ajax';

       //check if these datas were set
       if(isset($this->request->query['username']) && isset($this->request->query['verification_code'])){
            //get all users
            $usersTable = TableRegistry::get('Users');

            //build query make sure that his account is not yet expired
            $query =$usersTable->find('all')
                    ->where(['Users.username' => $this->request->query['username'],'Users.verification_code'=>$this->request->query['verification_code'],'Users.is_email_verify'=>0,'Users.expiry >'=> time()]);

            //now make sure there is a result
            if($query->count()>0){
                //get the first row
                $row= $query->first();


                $userSave = $usersTable->get($row['id']);
                $userSave->is_email_verify = 1;
                $usersTable->save($userSave);

                //get session
                $session = $this->request->session();
                $session->write('verifySuccess',1);
                
                //redirect to verification success page
                return $this->redirect('/verify-success');

               

              


            }else
                return $this->redirect('/');
            
            



       }else
            return $this->redirect('/');
       



    }


    




    public function startQuickStart(){
        
        $this->layout = 'ajax';

        $content = [];


        $usersDecksTable = TableRegistry::get('UsersDecks');
        $decksTable = TableRegistry::get('Decks');
        $usersTable = TableRegistry::get('Users');




        $query =$usersDecksTable->find()
                    ->select(['d.id','d.full_name'])
                    ->hydrate(false)
                    ->join([
                        'd'=>[
                        'table' => 'decks',
                        'type' => 'LEFT',
                        'conditions' => 'd.id = UsersDecks.deck_id',
                        ]
                    ])
                    ->where(['UsersDecks.user_id' => $this->Auth->user('id')])
                    ->order(['UsersDecks.deck_id' => 'ASC']);

        if($query->count()>0){


            //create result set from query build
            $results = $query->all();


            // Once we have a result set we can get all the rows
            $data = $results->toArray();

            $content = $data;

        }


        $this->set('content',$content);
    }

    public function startQuickGame(){

        //load all decks cards without sound
        $this->layout = 'ajax';


        $path = ROOT . DS . 'webroot' . DS . 'files' . DS;

        $cardsTable = TableRegistry::get('Cards');
        $usersDecksTable = TableRegistry::get('UsersDecks');
        $cardsDecksTable = TableRegistry::get('CardsDecks');
        $playedCardsTable = TableRegistry::get('PlayedCards');




      /*  if(isset($_GET['data']['decksid'])){
            $whereqry = ['UsersDecks.deck_id IN '=>$_GET['data']['decksid']];
        }else{
            $whereqry = ['UsersDecks.user_id'=>$this->Auth->user('id')];
        }*/

         $whereqry = ['UsersDecks.user_id'=>$this->Auth->user('id')];


        //check if that user owns that card
        $query =$usersDecksTable->find()
                    ->select(['c.id','c.question','deck_id'])
                    ->hydrate(false)
                    ->join([
                        'cd'=>[
                        'table' => 'cards_decks',
                        'type' => 'LEFT',
                        'conditions' => 'cd.deck_id = UsersDecks.deck_id',
                        ],
                        'c'=>[
                        'table' => 'cards',
                        'type' => 'LEFT',
                        'conditions' => 'c.id = cd.card_id',
                        ]
                        
                    ])
                    ->where($whereqry)
                    ->order(['UsersDecks.deck_id' => 'ASC']);


        //create result set from query build
        $results = $query->all();


        if($query->count()>0){

            // Once we have a result set we can get all the rows
            $data = $results->toArray();

            $datos = json_encode($data);


            $cardBuilds = array();
            $x = 0;
            foreach($data as $key=>$obj){



                //build query
                $qry = $playedCardsTable->find('all')
                        ->where(['card_id' =>$obj['c']['id']]);

                //create result set from query build
                $results = $query->first();


                //if results is 0 of course we will insert it in the played cards
                if($qry->count()==0){
                    if($obj['c']['id']!=null){
                        $cardBuilds[$x]['card_id'] = $obj['c']['id'];
                        $cardBuilds[$x]['deck_id'] = $obj['deck_id'];
                        $cardBuilds[$x]['srt'] = 0;
                        $cardBuilds[$x]['srs'] = 0;
                        $cardBuilds[$x]['stcounter'] = 0;
                        $cardBuilds[$x]['dtcounter'] = 0;
                        $cardBuilds[$x]['sxcounter'] = 0;
                        $cardBuilds[$x]['dxcounter'] = 0;
                        $cardBuilds[$x]['user_input'] = '';
                        $cardBuilds[$x]['due_date'] = 0;
                        $cardBuilds[$x]['aet'] = 0;
                        $cardBuilds[$x]['mark_as'] = '';
                        $cardBuilds[$x]['history'] = '';
                        $cardBuilds[$x]['rank'] = 0;
                        $cardBuilds[$x]['date_created'] = time();
                        $x++;
                    }
                }

            }



            //insert all cards in the playedcards table
            $oQuery = $playedCardsTable->query();
            foreach($cardBuilds as $cardBuild){
                $oQuery->insert(['card_id','deck_id','srt','srs','due_date','aet','history','stcounter','dtcounter','sxcounter','dxcounter','date_created','rank','mark_as','user_input'])
                    ->values($cardBuild);
            }
            $oQuery->execute();
            




            //now after insertion select now all cards from played cards QUICK MODE


            if(isset($_GET['deckids'])){
                $whereqry = ['ud.user_id' => $this->Auth->user('id'),'ud.deck_id IN'=>$_GET['deckids']];
            }else{
                $whereqry = ['ud.user_id' => $this->Auth->user('id')];
            }



            $query = $playedCardsTable->find()
                        ->select(['c.question','c.id','c.question_notes','c.answer_notes','c.question_sound_slow','c.answer_sound_slow','c.question_sound_fast','c.answer_sound_fast','srs','srt','due_date','c.answer','u.sx','u.dx','u.dt','u.st','id','aet','history','sxcounter','dxcounter','stcounter','dtcounter','first_time_marked_already_know','first_time_marked_logical_guess','first_time_marked_dont_know','cd.deck_id','rank','u.sound','mark_as','user_input'])
                        ->hydrate(false)
                        ->join([
                            'c'=>[
                            'table' => 'cards',
                            'type' => 'INNER',
                            'conditions' => 'c.id = PlayedCards.card_id',
                            ],
                            'cd'=>[
                            'table' => 'cards_decks',
                            'type' => 'INNER',
                            'conditions' => 'cd.card_id = PlayedCards.card_id',
                            ],
                            'ud'=>[
                            'table' => 'users_decks',
                            'type' => 'INNER',
                            'conditions' => 'ud.deck_id = cd.deck_id',
                            ],
                            'u'=>[
                            'table' => 'users',
                            'type' => 'INNER',
                            'conditions' => 'u.id = ud.user_id',
                            ]

                            
                        ])
                        ->where($whereqry)
                        ->order(['PlayedCards.due_date' => 'ASC']);


          

            if($query->count()>0){


                //create result set from query build
                $results = $query->all();

                // Once we have a result set we can get all the rows
                $cards = $results->toArray();

              /*  if(isset($_GET['deckids'])){
                    $decksid = $_GET['deckids'];
                    foreach($cards as $key=>$card){
                        foreach($decksid as $id){
                            if($card['cd']['deck_id']!=intval($id))
                                unset($cards[$key]);
                        }
                    }
                }*/
                



                $status = ['status'=>'ok','cards'=>$cards,'deckids'=>$cards,'path'=>$path];

            }else{
                $status = ['status'=>'not ok','renew'=>2];
            }









            
        }else{
            $status = ['status'=>'not ok','renew'=>1];
        }

        $status = json_encode($status);
       
        $this->set('content',$status);


    }


    public function beforeFilter(Event $event){
        parent::beforeFilter($event);

        //allow register,logout for display 
        //doesn't require login
        //$this->Auth->allow('register','logout','verifyEmail');
       



        $this->layout = 'flash-card-default';

    }



    // get captcha instance to handle for the example page
    private function CaptchaInstance() {
        // Captcha parameters
        $captchaConfig = [
          'CaptchaId' => 'ExampleCaptcha', // a unique Id for the Captcha instance
          'UserInputId' => 'captcha_code', // Id of the Captcha code input textbox
          // path of the Captcha config file inside your Controller folder
          'CaptchaConfigFilePath' => 'captcha_config/RegisterCaptchaConfig.php', 
        ];
        $captcha = BotDetectCaptcha::GetCaptchaInstance($captchaConfig);

        return $captcha;
    }


    public function login(){


        if($this->Auth->user('id')!=null)
            return $this->redirect($this->Auth->redirectUrl());
        else{
            //set error code and header title
            $error = 0;
            $headerTitle = 'Flash Card Game - Login';

            $this->set('curPage','home');


            //if login is submitted
            if ($this->request->is('post')) {

                //Identify if the user matches on the database
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());
                }
                $error = 1;
               // $this->Flash->error(__('Invalid username or password, try again'));
            } 
        }
        
        $this->set(compact('error','headerTitle'));
    }



    public function dashboard(){


        //build query
        $query =$this->Users->find('all')
                ->where(['Users.username' =>$this->Auth->user('username')]);

        //create result set from query build
        $results = $query->first();


        // Once we have a result set we can get all the rows
        $data = $results->toArray();



        //set current user name
        $curUser = ['username'=>$this->Auth->user('username'),'full_name'=>$data['full_name'],'account_id'=>$this->Auth->user('account_id')];
        $error = 0;
        $headerTitle = 'Flash Card Game - Dashboard';
        $this->layout = 'flash-card-dashboard';
        $curPage = 'dashboard';

        $this->set(compact('error','headerTitle','curUser','curPage'));

    }




    //logout users
    public function logout(){

        //when logout, redirect to the url where you set the logout redirection in AppController
        return $this->redirect($this->Auth->logout());
    }
    




    public function deleteClass(){


        $this->layout = 'ajax';
        if(isset($_GET['datas']['class_id'])){

            $cid = intval($_GET['datas']['class_id']);

            $usersClassesTable = TableRegistry::get('UsersClasses');
            $classesTable = TableRegistry::get('Classes');

            if($this->Auth->user('account_id')==1 || $this->Auth->user('account_id')==2){

                if($this->Auth->user('account_id')==2){
                    $qry = $usersClassesTable->find('all')
                       ->where(['UsersClasses.class_id'=>$cid,'UsersClasses.user_id'=>$this->Auth->user('id')]);
                }else{
                     $qry = $usersClassesTable->find('all')
                       ->where(['UsersClasses.class_id'=>$cid]);
                }

                if($qry->count()>0){


                    $ct = $classesTable->get($cid);
                    $classesTable->delete($ct);


                    $status = ['status'=>'ok'];

                }else
                    $status = ['status'=>'not ok'];
            

            }else{
                 $status = ['status'=>'not ok'];
            }


        }else
            $status = ['status'=>'not oka'];
        
        


        $status = json_encode($status);

        $this->set('content',$status);


    }



    public function deleteTeacher(){
        
        $this->layout = 'ajax';




        if(isset($_GET['datas']['student_id'])){

            $studentId = intval($_GET['datas']['student_id']);


            $usersTable = TableRegistry::get('Users');
            $usersDecksTable = TableRegistry::get('UsersDecks');
            $decksTable = TableRegistry::get('Decks');
            $cardsTable = TableRegistry::get('Cards');
            $cardsDecksTable = TableRegistry::get('CardsDecks');
            $classesTable = TableRegistry::get('Classes');
            $classesStudentsTable = TableRegistry::get('ClassesStudents');
            $usersClassesTable = TableRegistry::get('UsersClasses');

            //check if Teacher existed
            $chid = $usersTable->find('all')
                    ->where(['Users.id'=>$studentId,'Users.account_id'=>2]);

            //if there is a teacher
            if($chid->count()>0){


                /* start */



                $query = $usersDecksTable->find('all')
                ->where(['UsersDecks.user_id'=>$studentId]);


                if($query->count()>0){



                    $rows = $query->all();


                    $rows = $rows->toArray();


                    foreach($rows as $row){

                        //start foreach

                        $dd = $decksTable->get($row['deck_id']);
                        $decksTable->delete($dd);

                
                        $resultHere = $cardsDecksTable->find('all');


                




                        if($resultHere->count()>0){
                            //create result set from query build
                            $results = $resultHere->all();


                            // Once we have a result set we can get all the rows
                            $data = $results->toArray();

                            $keep = [];
                            $z = 0;
                            foreach($data as $store){
                                $keep[$z] = $store['card_id'];
                                $z++;
                            }



                            $query = $cardsTable->find('all')
                                    ->where(['id NOT IN' => $keep]);




                            $resultHere = $query->all();
                            $datal = $resultHere->toArray();



                            foreach($datal as $dato){
                                $cd = $cardsTable->get($dato['id']);
                                $cardsTable->delete($cd);
                            }


                        }else{
                            $query = $cardsTable->find('all');
                            $results = $query->all();


                            if($query->count()>0){
                                // Once we have a result set we can get all the rows
                                $data = $results->toArray();

                                foreach ($data as $key => $dato) {



                                    $cd = $cardsTable->get($dato['id']);


                                    if($cd->question_sound_slow!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->question_sound_slow;
                                        unlink($deleteFile);
                                    }


                                    if($cd->question_sound_fast!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->question_sound_fast;
                                        unlink($deleteFile);
                                    }

                                    if($cd->answer_sound_slow!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->answer_sound_slow;
                                        unlink($deleteFile);
                                    }

                                    if($cd->answer_sound_fast!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->answer_sound_fast;
                                        unlink($deleteFile);
                                    }



                                   $cardsTable->delete($cd);
                                }

                                
                            }
                           
                        }



                        //end foreach


                    }



                    
                         
                       
                }


                //start usersclasses
                $query = $usersClassesTable->find('all')
                ->where(['UsersClasses.user_id'=>$studentId]);


                if($query->count()>0){

                    $rows = $query->all();


                    $rows = $rows->toArray();


                    foreach($rows as $row){


                        //delete all of his classes
                        $ct = $classesTable->get($row['class_id']);
                        $classesTable->delete($ct);



                         //delete class students
                       /* $query = $classesStudentsTable->find('all')
                        ->where(['ClassesStudents.class_id'=>$row['class_id']]);


                        if($query->count()>0){

                            $rowHere = $query->first();


                            //delete all of his classes
                            $cs = $classesStudentsTable->get($rowHere['id']);
                            $classesStudentsTable->delete($cs);

                           
                                 
                               
                        }*/




                    }
                         
                       
                }


               





                //end usersclasses


                //delete now the users
                $st = $usersTable->get($studentId);
                $usersTable->delete($st);

                $status = ['status'=>'ok'];


                /* end */





            }else{
                $status = ['status'=>'not ok'];
            }


            


        }else{
            $status = ['status'=>'ok'];
        }

        $status = json_encode($status);
        $this->set('content',$status);

    }
    






    public function deleteStudent(){
        
        $this->layout = 'ajax';




        if(isset($_GET['datas']['student_id'])){

            $studentId = intval($_GET['datas']['student_id']);


            $usersTable = TableRegistry::get('Users');
            $usersDecksTable = TableRegistry::get('UsersDecks');
            $decksTable = TableRegistry::get('Decks');
            $cardsTable = TableRegistry::get('Cards');
            $cardsDecksTable = TableRegistry::get('CardsDecks');

            //check if Student existed
            $chid = $usersTable->find('all')
                    ->where(['Users.id'=>$studentId,'Users.account_id'=>3]);

            //if there is a student
            if($chid->count()>0){


                /* start */



                $query = $usersDecksTable->find('all')
                ->where(['UsersDecks.user_id'=>$studentId]);


                if($query->count()>0){



                    $rows = $query->all();


                    $rows = $rows->toArray();


                    foreach($rows as $row){

                        //start foreach

                        $dd = $decksTable->get($row['deck_id']);
                        $decksTable->delete($dd);

                
                        $resultHere = $cardsDecksTable->find('all');


                




                        if($resultHere->count()>0){
                            //create result set from query build
                            $results = $resultHere->all();


                            // Once we have a result set we can get all the rows
                            $data = $results->toArray();

                            $keep = [];
                            $z = 0;
                            foreach($data as $store){
                                $keep[$z] = $store['card_id'];
                                $z++;
                            }



                            $query = $cardsTable->find('all')
                                    ->where(['id NOT IN' => $keep]);




                            $resultHere = $query->all();
                            $datal = $resultHere->toArray();



                            foreach($datal as $dato){
                                $cd = $cardsTable->get($dato['id']);
                                $cardsTable->delete($cd);
                            }


                        }else{
                            $query = $cardsTable->find('all');
                            $results = $query->all();


                            if($query->count()>0){
                                // Once we have a result set we can get all the rows
                                $data = $results->toArray();

                                foreach ($data as $key => $dato) {



                                    $cd = $cardsTable->get($dato['id']);


                                    if($cd->question_sound_slow!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->question_sound_slow;
                                        unlink($deleteFile);
                                    }


                                    if($cd->question_sound_fast!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->question_sound_fast;
                                        unlink($deleteFile);
                                    }

                                    if($cd->answer_sound_slow!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->answer_sound_slow;
                                        unlink($deleteFile);
                                    }

                                    if($cd->answer_sound_fast!=''){
                                        $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->answer_sound_fast;
                                        unlink($deleteFile);
                                    }



                                   $cardsTable->delete($cd);
                                }

                                
                            }
                           
                        }



                        //end foreach


                    }



                    
                         
                       
                }

                $st = $usersTable->get($studentId);
                $usersTable->delete($st);

                $status = ['status'=>'ok'];




                /* end */





            }else{
                $status = ['status'=>'not ok'];
            }


            


        }else{
            $status = ['status'=>'ok'];
        }

        $status = json_encode($status);
        $this->set('content',$status);

    }





    public function deleteDeck(){
        $this->layout = 'ajax';
        if(isset($_GET['datas']['deck_id'])){

            $deckId = intval($_GET['datas']['deck_id']);

            $usersDecksTable = TableRegistry::get('UsersDecks');
            $decksTable = TableRegistry::get('Decks');
            $cardsTable = TableRegistry::get('Cards');
            $cardsDecksTable = TableRegistry::get('CardsDecks');


            if($this->Auth->user('account_id')!=1){
                $query = $usersDecksTable->find('all')
                ->where(['UsersDecks.deck_id'=>$deckId,'UsersDecks.user_id'=>$this->Auth->user('id')]);
            }else{
                $query = $usersDecksTable->find('all')
                ->where(['UsersDecks.deck_id'=>$deckId]);
            }
            


            if($query->count()>0){


                //create result set from query build
                $row = $query->first();

                

                $dd = $decksTable->get($row['deck_id']);
                $decksTable->delete($dd);

        
                $resultHere = $cardsDecksTable->find('all');


        




                if($resultHere->count()>0){
                    //create result set from query build
                    $results = $resultHere->all();


                    // Once we have a result set we can get all the rows
                    $data = $results->toArray();

                    $keep = [];
                    $z = 0;
                    foreach($data as $store){
                        $keep[$z] = $store['card_id'];
                        $z++;
                    }



                    $query = $cardsTable->find('all')
                            ->where(['id NOT IN' => $keep]);




                    $resultHere = $query->all();
                    $datal = $resultHere->toArray();



                    foreach($datal as $dato){
                        $cd = $cardsTable->get($dato['id']);
                        $cardsTable->delete($cd);
                    }


                }else{
                    $query = $cardsTable->find('all');
                    $results = $query->all();


                    if($query->count()>0){
                        // Once we have a result set we can get all the rows
                        $data = $results->toArray();

                        foreach ($data as $key => $dato) {


                            $cd = $cardsTable->get($dato['id']);


                            if($cd->question_sound_slow!=''){
                                $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->question_sound_slow;
                                unlink($deleteFile);
                            }


                            if($cd->question_sound_fast!=''){
                                $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->question_sound_fast;
                                unlink($deleteFile);
                            }

                            if($cd->answer_sound_slow!=''){
                                $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->answer_sound_slow;
                                unlink($deleteFile);
                            }

                            if($cd->answer_sound_fast!=''){
                                $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $cd->answer_sound_fast;
                                unlink($deleteFile);
                            }



                           $cardsTable->delete($cd);
                        }

                        
                    }
                   
                }
                


               
                
                

               


                





                $status = ['status'=>'ok'];



            }else{
                $status = ['status'=>'not ok'];
            }


        }else{
            $status = ['status'=>'ok'];
        }

        $status = json_encode($status);
        $this->set('content',$status);

    }

    public function deleteCard(){
        
        $this->layout = 'ajax';
        if(isset($_GET['datas']['card_id'])){
            
            $cardId = intval($_GET['datas']['card_id']);
            
            $CardsDecksTable = TableRegistry::get('CardsDecks');
            $UsersDecksTable = TableRegistry::get('UsersDecks');
            $CardsTable = TableRegistry::get('Cards');

            //check if that user owns that card
            $query =$CardsDecksTable->find()
                    ->hydrate(false)
                    ->join([
                        'ud'=>[
                        'table' => 'users_decks',
                        'type' => 'LEFT',
                        'conditions' => 'ud.deck_id = CardsDecks.deck_id',
                        ]
                        
                    ])
                    ->where(['CardsDecks.card_id' => $cardId,'ud.user_id'=>$this->Auth->user('id')]);


            if($query->count()>0){

                //create result set from query build
                $row = $query->first();



                $qry = $CardsTable->find()
                        ->where(['Cards.id'=>$row['id']]);

                $rw = $qry->first();


                //delete corresponding card mp3 files
                if($rw['question_sound_slow']!=''){
                    $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $rw['question_sound_slow'];
                    unlink($deleteFile);
                }


                if($rw['question_sound_fast']!=''){
                    $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $rw['question_sound_fast'];
                    unlink($deleteFile);
                }

                if($rw['answer_sound_slow']!=''){
                    $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $rw['answer_sound_slow'];
                    unlink($deleteFile);
                }

                if($rw['answer_sound_fast']!=''){
                    $deleteFile = ROOT . DS . 'webroot' . DS . 'files' . DS . $rw['answer_sound_fast'];
                    unlink($deleteFile);
                }


                $cd = $CardsDecksTable->get($row['id']);
                $CardsDecksTable->delete($cd);
                $c = $CardsTable->get($cardId);
                $CardsTable->delete($c);
                $status = ['status'=>'ok'];



            }else{
                $status = ['status'=>'not ok'];
            }


        }else
            $status = ['status'=>'not ok'];


        $status = json_encode($status);
        $this->set('content',$status);




    }


    public function testPurpose(){
        
        $this->layout = 'ajax';

       
        $content = '';

       
        $this->set('content',$content);

    }

    public function updateGameCard(){
        
        $this->layout = 'ajax';




        if(isset($_POST['datas'])){
            
            $datas = $_POST['datas'];
            $checkIfError = 0;
            foreach($datas as $key=>$data){
                if(isset($data['srt']) && isset($data['due_date']) && isset($data['srs']) && isset($data['c']['id']) && isset($data['id']) && isset($data['aet']) && isset($data['history']) && isset($data['stcounter']) && isset($data['dtcounter']) && isset($data['sxcounter']) && isset($data['dxcounter'])){
                }else{
                    $checkIfError = 1;
                }
            }

            if($checkIfError==0){
                $playedCardsTable = TableRegistry::get('PlayedCards');
                $entities = $playedCardsTable->newEntities($datas);
                foreach($entities as $key=>$pcard){
                    $playedCardsTable->save($pcard);
                }

                //update again because of bugs
                $dataNews = [];
                $x = 0;
                foreach($datas as $data){
                    $dataNews[$x]['id'] = $data['id'];
                    $dataNews[$x]['stcounter'] = intval($data['stcounter']);
                    $dataNews[$x]['dtcounter'] = intval($data['dtcounter']);
                    $dataNews[$x]['sxcounter'] = intval($data['sxcounter']);
                    $dataNews[$x]['dxcounter'] = intval($data['dxcounter']);
                    $x++;
                }

                $x = 0;

                //create again entities and save again
                $entities = $playedCardsTable->newEntities($dataNews);
                foreach($entities as $key=>$pcard){
                    $playedCardsTable->save($pcard);
                }






                $status = ['status'=>'ok','check'=>json_encode($dataNews)];
            }else
                $status = ['status'=>'not ok'];
            


        }else
            $status = ['status'=>'not ok'];
        
        $status = json_encode($status);
        $this->set('content',$status);


    }
    


    //registers users
     public function register(){


        /*set page name for page identifier
        purpose for conditional javascript loading*/
        $this->set('curPage','register');

         // captcha instance of the example page
        $captcha = $this->CaptchaInstance();

        // passing Captcha Html to example view
        $this->set('captchaHtml', $captcha->Html());



        //get session
        $session = $this->request->session();


        //set error code and header title
        $error = 0;
        $headerTitle = 'Flash Card Game - Registration';

       
        $teachersLists = array();


        //get all users
        $usersTable = TableRegistry::get('Users');



        //delete all expired users who did not verify their account within one day.
         $usersTable->deleteAll(['Users.expiry <'=> time(),'Users.is_email_verify'=>0]);

        



       



        //build query
        $query =$usersTable->find('all')
                ->where(['Users.account_id' => 2]);

        //create result set from query build
        $results = $query->all();


        // Once we have a result set we can get all the rows
        $data = $results->toArray();
        

        $x = 0;
        //loop all data
        foreach ($data as $key => $value) {
           $teachersLists[$value['id']] = $value['full_name'];
        }
       
        $teachersLists[0] = 'No teacher';
        if($this->request->data!=null){

            //make sure to trim all fields in order to avoid empty data
            $dataLists = array('username','password','full_name','email_address');
            foreach($this->request->data as $key=>$val){
                foreach($dataLists as $value){
                    if($key==$value){
                        $this->request->data[$key] = trim($this->request->data[$key]);
                    }
                }
            }
        }
        $user = $usersTable->newEntity($this->request->data,['validate'=>'register']);
        if ($this->request->is('post')) {

            //check captcha
             // validate the user-entered Captcha code
            $isHuman = $captcha->Validate($this->request->data['captcha_code']);

            unset($this->request->data['captcha_code']); // clear previous user input


            //if not valid captcha
            if (!$isHuman) {
                 $this->set('captchaError',1);
            }



            if(!$user->errors()){

                //add additional fields to the User
                $this->request->data['verification_code'] = Security::hash($this->request->data['username'] . time());
                $this->request->data['created'] = time();
                $this->request->data['expiry'] = strtotime('+1 day',$this->request->data['created']);
                $this->request->data['is_email_verify'] = 0;
                $this->request->data['is_approve'] = 0;
                $this->request->data['account_id'] = 3;
                $this->request->data['dx'] = 0.6;
                $this->request->data['sx'] = 0.8;
                $this->request->data['st'] = 2;
                $this->request->data['dt'] = 5;
                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($newResult = $this->Users->save($user)){

                    $teachersUsersTable = TableRegistry::get('TeachersUsers');                    
                    $tu = $teachersUsersTable->newEntity(array('user_id'=>$newResult->id,'teacher_user_id'=>$this->request->data['teachersLists']),['validate'=>false]);

                    $teachersUsersTable->save($tu);

                    //Send an email
                   $email = new Email('default');
                   // $email->profile(['from' => 'fcard@freememorialwebsites.com', 'transport' => 'freememorial']);
                    $email->template('registerverify')
                    ->emailFormat('html')
                    ->subject('DIDAOYINGYU\'s FlashCard Game - Email Verification')
                    ->to($this->request->data['email_address'])
                    //->to('kenjos75@yahoo.com')
                    ->viewVars(['verification_code'=>$this->request->data['verification_code'],'username'=>$this->request->data['username']])
                    ->send();



                    //write session to register success page and delete it when already visited the page
                    $session->write('registerSuccess',1);
                    return $this->redirect('/register-success');

                    //$this->Flash->success(__('The user has been saved.'));
                    //return $this->redirect(['action' => 'register']);
                }
            }else{
                $error = 1;
            }

           
           
        }
        $this->set(compact('user','error','headerTitle','teachersLists'));
        //$this->set('user', $user);
    }





}
