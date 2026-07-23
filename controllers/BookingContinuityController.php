<?php

namespace app\controllers;

use Yii;
use app\models\BookingContinuity;
use app\models\Booking;
use app\models\User;
use app\models\CheckList;
use app\models\BookingContinuitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BookingContinuityController implements the CRUD actions for BookingContinuity model.
 */
class BookingContinuityController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $isAdmin = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),  
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update'],
                        'roles' => ['@'],
                          'matchCallback' => function ($rule, $action) {
                            return User::isUserAdmin(Yii::$app->user->identity->username);
                        }
                    ],  
                    [
                        'allow' => true,
                        'actions' => ['create' ,'check', 'delete', 'update', 'uncheck', 'setdate', 'deadline'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

 
    /**
     * Lists all BookingContinuity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingContinuitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookingContinuity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */


    /**
     * Creates a new BookingContinuity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($booking_id)
    {
        $isAdmin = User::isUserAdmin(Yii::$app->user->identity->username) ? 'true': 'false' ;
        $model = new BookingContinuity();
        $booking = Booking::findOne($booking_id);
        $checkList = checkList::findOne(['booking'=> $booking_id ]);
        
        if(!$checkList){
            $checkList = new CheckList();
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->render('update', [
              'model' => $model,
              'booking' => $booking,
              'checkList' => $checkList,
              'isAdmin' => $isAdmin
            ]);
        }

        return $this->render('create', [
            'model' => $model,
            'booking' => $booking,
            'checkList' => $checkList,
            'isAdmin' => $isAdmin
        ]);
    }

    /**
     * Updates an existing BookingContinuity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $isAdmin = User::isUserAdmin(Yii::$app->user->identity->username) ? 'true': 'false' ;
        $model = $this->findModel($id);
        $booking = Booking::findOne($model->booking);
        $checkList = checkList::findOne([ 'booking'=> $model->booking ]);

        if(!$checkList){
            $checkList = new CheckList();
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->render('update', [
              'model' => $model,
              'booking' => $booking,
              'checkList' => $checkList,
              'isAdmin' => $isAdmin
            ]);
        } 

        
        return $this->render('update', [
            'model' => $model,
            'booking' => $booking,
            'checkList' => $checkList,
            'isAdmin' => $isAdmin
        ]);
    }

    /**
     * Deletes an existing BookingContinuity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }



    public function actionCheck($booking_id){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $field = $_POST['field'];

        $taskFields = [
                 
              ['label' => 'Empty Pass',        'field' => 'vacuum_maneuver' ],
              ['label' => 'Gated Out',         'field' => 'gated_out'       ],
              ['label' => 'Closing Day',       'field' => 'doc_cut_of'      ],
              ['label' => 'SI',                'field' => 'SI_date'         ],
              ['label' => 'Draft Customer',    'field' => 'draf_client'     ],
              ['label' => 'Gated In',          'field' => 'gated_IN'        ],
              ['label' => 'cleared',           'field' => 'cleared'         ],
              ['label' => 'Departure',         'field' => 'departure'       ],
              ['label' => 'BL Payment',        'field' => 'bl_payment'      ],
              ['label' => 'SWB',               'field' => 'swb'             ],
        ];

        $isAdmin = User::isUserAdmin(Yii::$app->user->identity->username);

        $checkList = checkList::findOne([ 'booking'=> $booking_id ]);

        if(!$checkList){
            $checkList = new CheckList();
        }

        foreach ($taskFields as $key => $tsk) {
            if($tsk['field'] == $field AND $key !=0 ){
                 $prevField = [ 'label' => $taskFields[$key-1]['label'], 'field' => $taskFields[$key-1]['field'] ];
                    if(!$isAdmin AND $this->isAlreadySet($checkList, $prevField['field']) == false ){
                      return ['success'=> false, 'msg'=> 'The previus task '. $prevField['label'] .' has not been completed yet, please complete'];
                    }
                 break;
            };
        }

        if((!$isAdmin) AND $this->isAlreadySet($checkList,$field)){
          return ['success'=> false, 'msg'=> 'This task is already set, please contact the administrator'];
        }

        $checkList[$field.'_chk_date'] = date("Y-m-d H:i:s");
        $checkList[$field.'_chk_by'] = Yii::$app->user->identity->id;
        $checkList->booking = $booking_id;

        if($checkList->save()){
            $date = date('d-m-Y H:i:s',strtotime($checkList[$field.'_chk_date']));
            return ['success' => true, 'date' => $date ];
        }
        return ['success'=> false, 'date' => '' ];
    }


      public function actionUncheck($booking_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->enableCsrfValidation = false; 

        $field = $_POST['field'];
        $checkList = checkList::findOne($booking_id);

        if(!$checkList){
            $checkList = new CheckList();
        }

        $isAdmin = User::isUserAdmin(Yii::$app->user->identity->username);

        if((!$isAdmin) AND $this->isAlreadySet($checkList,$field)){
          return ['success'=> false, 'msg'=> 'This task is already set, please contact the administrator'];
        }

        $checkList[$field.'_chk_date'] = null;
        $checkList[$field.'_chk_by'] = Yii::$app->user->identity->id;
        $checkList->booking = $booking_id;

        if($checkList->save()){
            return ['success' => true ];
        }

        return ['success'=> false, 'msg'=>'An error has been ocurred' ];
    }


    public function actionSetdate($booking_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $field = $_POST['field'];
        $value = $_POST['value'];
        $date = $_POST['date'];
        $checkList = checkList::findOne($booking_id);
        if(!$checkList){
            $checkList = new CheckList();
        }
        $isAdmin = User::isUserAdmin(Yii::$app->user->identity->username);
        if((!$isAdmin) AND $this->isAlreadySet($checkList,$field)){
          return ['success'=> false, 'msg'=> 'This task is already set, please contact the administrator'];
        }

        if(!empty($_POST['date'])){
             $checkList[$field.'_chk_date'] = date('Y-m-d H:i:s', strtotime($date) );
         }else{
             $checkList[$field.'_chk_date'] = null;
         }

        $checkList[$field.'_chk_by'] = Yii::$app->user->identity->id;
        $checkList->booking = $booking_id;

       
        if($checkList->save()){
            $date = date('d-m-Y H:i:s',strtotime($checkList[$field.'_chk_date']));
            return ['success' => true, 'date' => $date ];
        }

        return ['success'=> false, 'msg'=>'An error has been ocurred'];
    }

    /**
     * Finds the BookingContinuity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookingContinuity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


     public function isAlreadySet($checkList,$field){

       if(!$checkList){
          return false;
        } 
         //echo $checkList[$field.'_chk_date'];

         if($checkList[$field.'_chk_date'] != "") {
            return true;
         } 

         return false;

     }

     public function isPreviusSet($checkList,$prefield){
      


       if(!$checkList){
          return false;
        } 
         //echo $checkList[$field.'_chk_date'];

         if($checkList[$prefield.'_chk_date'] != "") {
            return true;
         } 

         return false;

     }



    /**
     * Finds the BookingContinuity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookingContinuity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookingContinuity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /*CRON JOBS */


    public  function actionNotification(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $bookings = Booking::find()
         ->Select('*')
         ->joinWith('continuity')
         ->all();

         $model = new Booking;

        foreach ($bookings as $k => $bk) {

            $checkList = checkList::find()
            ->where(['booking' => $bk->booking_id ])
            ->all();

            if(count($checkList) == 0 AND $bk[$taskFields[0]['field']] != "" ){
                 $estimatedDate = $bk[$taskFields[0]['field']];
                 $this->sendLevel1Notification($bk,$taskFields[0]['label'], $bk[$taskFields[0]['field']] );
                 $result[] =['booking' => $bk->booking_id, 'field' => $task['field'] ];
                 continue;
            }

            foreach ($taskFields as $y => $task) { 
               
                if( $bk[$task['field']] == null   ){
                     continue;
                }
               

                if(empty($checkList[0][$task['field'].'_chk_date']) && !$model->isDeadLineDay( $bk[$task['field']] ) ){

                    $result[] =['booking' => $bk->booking_number, 'field' => $task['field'],  'value' =>   $bk[$task['field']]  ];

                    $this->sendLevel1Notification($bk,$task['label'], $bk[$task['field']] );
                    
                }
            }
        }
        return ['success'=> true,'data'=> $result];
    }   


    public  function actionDeadline(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $taskFields = [

         /* ['label' => 'Booking Number',    'field' => 'booking_number'  ],
          ['label' => 'Customer',          'field' => 'client'          ],
          ['label' => 'Vessel',            'field' => 'vessel'          ],
          ['label' => 'POL',               'field' => 'loading_port'    ],
          ['label' => 'ETD',               'field' => 'loading_EDT'     ],
          ['label' => 'POD',               'field' => 'dicharge_port'   ],
          ['label' => 'ETA',               'field' => 'dicharge_ETA'    ],
          ['label' => 'Container type',    'field' => 'container_type'  ],
          ['label' => 'Commodity',         'field' => 'commodity'       ],*/
          //['label' => 'Pickup Date',       'field' => 'pickup_date'     ],
          ['label' => 'Empty Pass',        'field' => 'vacuum_maneuver' ],
          ['label' => 'Gated Out',         'field' => 'gated_out'       ],
          ['label' => 'Closing Day',       'field' => 'doc_cut_of'      ],
          //['label' => 'SI',              'field' => 'SI_date'       ],
          ['label' => 'Draft Customer',    'field' => 'draf_client'     ],
          ['label' => 'VGM',               'field' => 'vgm'        ],
          ['label' => 'Gated In',          'field' => 'gated_IN'        ],
          ['label' => 'cleared',           'field' => 'cleared'         ],
          ['label' => 'Departure',         'field' => 'departure'       ],
          ['label' => 'BL Payment',        'field' => 'bl_payment'      ],
          ['label' => 'SWB',               'field' => 'swb'             ],
            
        ];


        $bookings = Booking::find()
         ->Select('*')
         ->joinWith('continuity')
         ->all();

        $model = new Booking;

        foreach ($bookings as $k => $bk) {

            $checkList = checkList::find()
            ->where(['booking' => $bk->booking_id ])
            ->all();

            if(count($checkList) == 0 AND $bk[$taskFields[0]['field']] != "" ){
                 $estimatedDate = $bk[$taskFields[0]['field']];
                 $this->sendLevel2Notification($bk,$taskFields[0]['label'], $estimatedDate);
                 $result[] =['booking' => $bk->booking_id, 'field' => $task['field'] ];
                 continue;
            }

            foreach ($taskFields as $y => $task) { 
               
                if( $bk[$task['field']] == null ){
                     continue;
                }
                    

                if(empty($checkList[0][$task['field'].'_chk_date']) && $model->isDeadLineDay( $bk[$task['field']] ) ){

                    $result[] =['booking' => $bk->booking_number, 'field' => $task['field'],  'value' =>   $bk[$task['field']]  ];

                    $this->sendLevel2Notification($bk,$task['label'], $bk[$task['field']] );
                    
                }
            }
        }
        return ['success'=> true,'data'=> $result];
    }   



    public function sendLevel1Notification($booking,$taskLabel,$date){

            Yii::$app->mailer->compose(
                '@app/mail/notificationLv1Mail.php', 
                ['booking' => $booking, 'taskLabel' => $taskLabel, 'date' => $date ]
            )
            ->setFrom(['sistema@frego.com.mx'  => 'Sistema Frego'])
            ->setTo("hector.torres@frego.com.mx")
            ->setCc("jfs.ddd.artist@gmail.com")
            ->setSubject($taskLabel . ' task is not completed booking['. $booking->booking_number. ']' )
            ->send();
            return true;
    }    

    public function sendLevel2Notification($booking,$taskLabel,$date){
            Yii::$app->mailer->compose(
                '@app/mail/notificationLv2Mail.php', 
                ['booking' => $booking, 'taskLabel' => $taskLabel , 'date' => $date ]
            )
            ->setFrom(['sistema@frego.com.mx'  => 'Sistema Frego'])
            ->setTo("hector.torres@frego.com.mx")
            ->setCc("jfs.ddd.artist@gmail.com")
            //->setTo("hector.torres@frego.com.mx")
            ->setSubject($taskLabel . ' task deadline! booking['. $booking->booking_number. ']' )
            ->send();
            return true;
    }
}


