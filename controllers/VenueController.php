<?php

namespace app\controllers;

use Yii;
use app\models\Venues;
use app\models\ApplicationUtil;
use app\models\VenuesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;


/**
 * VenueController implements the CRUD actions for Venues model.
 */
class VenueController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        'access' => [
	        'class' => AccessControl::className(),
	        'only' => ['logout'],
	        'rules' => [
		        [
			        'actions' => ['index'],
			        'allow' => true,
			        'roles' => ['@'],
		        ],
	        ],
        ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            
        ];
    }

    /**
     * Lists all Venues models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$searchModel = new VenuesSearch();

    	if(isset(\Yii::$app->user->identity) &&  \Yii::$app->user->identity != null)
    		$user = \Yii::$app->user->identity->getDbsUser();
    	else 
    	{
    		\Yii::error("manoj".print_r(\Yii::$app->user->identity , true));
    		return $this->redirect(['site/login']);
    	}

    	$popular= true;
    	$dist = ApplicationUtil::getDefaultRadius();
    	$type = '';
    	$rating = ApplicationUtil::getDefaultRatingLevel();
		if ($searchModel->load(Yii::$app->request->post()))
		{
			if ($searchModel->choices == 1)
				$popular = false;

			if(isset($searchModel->distance_limit) && strlen(trim($searchModel->distance_limit)) > 0)
				$dist = $searchModel->distance_limit;

			if (isset($searchModel->type))
				$type = $searchModel->type;

			if (isset($searchModel->rating) && strlen (trim($searchModel->rating) > 0))
				$rating = $searchModel->rating;
		}

		if ($popular)
			$venueLocation = $user->getNearByPopularVenues($dist, $type, $rating);
		else 
			$venueLocation = $user->getNearBySocialVenues($dist, $type, $rating);

		$dataProvider = new ArrayDataProvider([
				'allModels' => $venueLocation,
				]);

        //$dataProvider = $searchModel->search(Yii::$app->request->post());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'user' =>$user	
        ]);
    }

    /**
     * Displays a single Venues model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Venues model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Venues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Venues model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Venues model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Venues model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Venues the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Venues::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
