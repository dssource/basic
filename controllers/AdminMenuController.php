<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 24.04.2018
 * Time: 17:52
 */

namespace dssource\basic\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;
use dssource\basic\models\Menu;

class AdminMenuController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => Menu::getTreeArray()
            ])
        ]);
    }

    public function actionCreate()
    {
        $model = new Menu();

        if($model->load(Yii::$app->request->post()))
        {
            //exit(var_dump(Yii::$app->request->post()));
            if($model->validate())
            {
                $model->save();
                $this->redirect('/admin/menu');
            }
            else
            {
                Yii::$app->getSession()->setFlash('warning', $model->errors());
            }

        }

        $model->active = true;
        $model->position = 1;
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
       $model = Menu::findOne($id);

        if($model == null) throw new NotFoundHttpException("Страница не найденв");

        if($model->load(Yii::$app->request->post()))
        {
            //exit(var_dump(Yii::$app->request->post()));
            if($model->validate())
            {
                $model->save();
                $this->refresh();
            }
            else
            {
                Yii::$app->getSession()->setFlash('warning', $model->errors());
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Menu::findOne($id);

        if($model == null) throw new NotFoundHttpException("Страница не найденв");
        $model->delete();

        return $this->redirect('/admin/menu');
    }
}