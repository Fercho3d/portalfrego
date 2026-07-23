<?php

namespace app\controllers;

use Yii;
use app\models\Containers;
use app\models\ContainersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * ContainersController implements the CRUD actions for Containers model.
 */
class ContainersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create' , 'update', 'delete' ],
                        'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->access == 10 ;
                        }
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Containers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContainersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Containers model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Containers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($booking)
    {
        $model = new Containers();
        $model->booking = $booking;  
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['/booking/update','id'=> $model->booking, 'tab' => 'containers' ]);   

         }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Containers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/booking/update','id'=> $model->booking, 'tab' => 'containers' ]); 
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Containers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {       
        $model = $this->findModel($id);
        $url = '/booking/update'."?id=$model->booking#containers" ;  
        $model->delete();
        return $this->redirect([$url]);     
            
    }

    /**
     * Finds the Containers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Containers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Containers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
