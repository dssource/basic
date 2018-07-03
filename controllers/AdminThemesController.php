<?php

namespace dssource\basic\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use dssource\basic\core\Themes;

class AdminThemesController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
            'query' => Themes::find(),
            'sort'=> ['defaultOrder' => ['name' => SORT_ASC]]
            ])
        ]);
    }

    public function actionCreate()
    {
        $model = new Themes;
        if($model->load(Yii::$app->request->post()) and $model->validate())
        {
            $model->save();
            Yii::$app->getSession()->addFlash('success', 'Тема добавлена');
            return $this->redirect('/admin/themes');
        }
        else
            return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Themes::findOne($id);

        if($model == null)
            throw new NotFoundHttpException("Тема не найдена");

        if($model->load(Yii::$app->request->post()) and $model->validate())
        {
            $model->save();
            Yii::$app->getSession()->addFlash('success', 'Тема изменена');

        }

            return $this->render('create', ['model' => $model]);
    }

    public function actionSet($id)
    {
        $model = Themes::findOne($id);

        if($model == null)
            throw new NotFoundHttpException("Тема не найдена");

        $model->activate();


        return $this->redirect('/admin/themes');
    }

    public function actionDelete($id)
    {
        $model = Themes::findOne($id);

        if ($model == null)
            throw new NotFoundHttpException("Тема не найдена");

        $model->delete();
        Yii::$app->getSession()->addFlash('success', 'Тема удалена');
        return $this->redirect('/admin/themes');

    }
}