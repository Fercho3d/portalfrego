<?php

namespace app\controllers;
use Yii;
use yii\helpers\Url;
use app\models\Charge;
use app\models\Transaction;
use app\models\ChargeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ChargeController implements the CRUD actions for Charge model.
 */
class ChargeController extends Controller
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
                        'actions' => ['index', 'update', 'delete', 'view'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->access == 11 ;
                        }
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Charge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChargeSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $_GET['transaction']
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Charge model.
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
     * Creates a new Charge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Charge();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

          return $this->renderTransaction($model->transaction);

        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }


    function renderTransaction($transaction){

          $tranModel = Transaction::findOne($transaction);   

            $searchModel = new ChargeSearch();
            $dataProvider = $searchModel->search(
                  Yii::$app->request->queryParams,
                  $tranModel->transc_id
            ); 

            return $this->renderAjax('//transaction/update', [
              'model' => $tranModel,
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
            ]);

    }

    /**
     * Updates an existing Charge model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $price = $model->price;

        $model->scenario = 'vendor';

        $load = $model->load( Yii::$app->request->post() );

        $model->price = floatval($model->service->price) == 0 ?  $model->price_confirmation : $price;
        
        if ($load && $model->save() ) {
            $total = $model->quantity * $model->price;
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true, 'price' => '$ '.number_format($model->price,2,',',".") , 'total' => '$ '.number_format($total,2,',',".") ];
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Charge model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model= $this->findModel($id);
        $transaction = $model->transaction;
        $model->delete();
        return $this->renderTransaction($transaction);

    }

    /**
     * Finds the Charge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Charge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Charge::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
