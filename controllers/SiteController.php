<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\VenuesLocation;
use app\models\Venues;

class SiteController extends Controller
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
                        'actions' => ['logout', 'search', 'index', 'update-location-details'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    	return $this->redirect('venue/index');
//        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionUpdateLocationDetails()
    {
    	$sql = 'select dv.id, latitude, longitude from dbs_venues dv left join dbs_venues_location dvl on dvl.id=dv.id 
    				where name is null or name=\'\' limit 100';

    	$connection = \Yii::$app->db;
    	$command = $connection->createCommand($sql);

    	$models = $command->queryAll();

    	$key = "AIzaSyCPxAAfKs4SLAb7lrEGLChn2Ob4Z23ZyHI";

    	foreach ($models as $model)
    	{
    		$lat = $model['latitude'];
    		$lng = $model['longitude'];
	    	$details_url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&key=$key";
	    	$ch = curl_init();
	    	curl_setopt($ch, CURLOPT_URL, $details_url);
	    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    	$geoloc = json_decode(curl_exec($ch), true);
print_r ($geoloc);die;
	    	$venue = Venues::findOne($model['id']);

	    	$venue->address = $geoloc['formatted_address'];
	    	//$venue->name = 
    	}
    }
}
