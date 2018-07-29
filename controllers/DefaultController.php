<?php

namespace dssource\basic\controllers;

use dssource\basic\models\Page;
use dssource\basic\models\Section;
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

    // Разделы
    public function actionSectionDefaultView()
    {
        $models = Section::find()->all();

        if($models == NULL) return new NotFoundHttpException("Разделы не созданы");
        else
            return $this->render('all-sections', ['models' => $models]);
    }

    public function actionSectionView($section)
    {
        $model = Section::findOne(['alias' => $section]);

        if($model == NULL) return new NotFoundHttpException("Страница не найдена");
        else
            return $this->render('sections', ['model' => $model]);
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