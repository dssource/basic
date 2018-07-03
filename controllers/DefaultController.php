<?php

namespace dssource\basic\controllers;

use dssource\basic\models\Page;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSectionView($section)
    {
        return $this->render('sections', ['section' => $section]);
    }

    public function actionPageView($section = 'main', $page)
    {
        $model = Page::findOne(['alias' => $page]);

        if($model == null)
            return new NotFoundHttpException("Страница не найдена");

        if(Yii::$app->request->url != $model->url)
        {
            return new NotFoundHttpException("Страница не найдена");
        }

        if(!$model->publish)
            return new HttpException(400, "Страница закрыта от общего доступа или не опубликована");

        return $this->render('page', ['page' => $page, 'model' => $model]);
    }

}