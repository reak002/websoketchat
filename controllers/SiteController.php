<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Messages;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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
     * {@inheritdoc}
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
    	if(!Yii::$app->user->isGuest){
			$user_name = Yii::$app->user->identity->username;
			$user_id = Yii::$app->user->getId();
		}
    	else{
			$user_name = 'Guest';
			$user_id = 0;
		}

		$model = new Messages();

		if (Yii::$app->request->isAjax){
			if($model->load(Yii::$app->request->post()) && $model->save()){
				$model = new Messages();
			}
		}

		$query = Messages::find();
		$messages = $query->all();
		if (Yii::$app->request->isAjax){
			return $this->renderAjax('index',array(
				'username'=>$user_name,
				'userId'=>$user_id,
				'messages'=>$messages,
				'model' => $model,
			));
		}else{
			return $this->render('index',array(
				'username'=>$user_name,
				'userId'=>$user_id,
				'messages'=>$messages,
				'model' => $model,
			));
		}

    }


    /**
     * Login action.
     *
     * @return Response|string
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

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
