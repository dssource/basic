<?php

namespace dssource\basic\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use dssource\basic\models\Module;

class AdminModulesController extends Controller
{
    public function actionIndex()
    {
        $model = new Module();
        if($model->load(Yii::$app->request->post()) AND $model->validate())
        {
            $model->save();
            Yii::$app->getSession()->setFlash("success", "Модель [".$model->label."] добавлен!");
        }


        $dataProvider = new ActiveDataProvider([
            'query' => Module::find()->orderBy('position'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id)
    {
        $model = Module::findOne($id);

        if($model == null) throw new NotFoundHttpException("Модуль не найден");

        if($model->load(Yii::$app->request->post()) AND $model->validate())
        {
            $model->save();
            Yii::$app->getSession()->setFlash("success", "Модель [".$model->label."] обновлена!");
        }

        return $this->render('update', ['model' => $model]);

    }

    public function actionDelete($id)
    {
        $model = Module::findOne($id);

        if($model == null) throw new NotFoundHttpException("Модуль не найден");
        $label = $model->label;
        if($model->delete())
        {
            Yii::$app->getSession()->setFlash("info", "Модель [".$label."] удален!");
        }

        return $this->actionIndex();

    }

}