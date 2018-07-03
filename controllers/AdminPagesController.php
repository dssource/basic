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
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use dssource\basic\models\Section;
use dssource\basic\models\Page;

class AdminPagesController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Page::find(),
                'sort'=> ['defaultOrder' => ['name' => SORT_ASC]]
            ])
        ]);
    }

    public function actionCreate()
    {
        $model = new Page();

        if($model->load(Yii::$app->request->post()))
        {
            //exit(var_dump(Yii::$app->request->post()));
            if($model->validate())
            {
                if($model->imageFile = UploadedFile::getInstance($model, 'imageFile'))
                    $model->uploadImage();

                $model->save();
                $this->actionIndex();

            }
            else
            {
                Yii::$app->getSession()->setFlash('warning', $model->errors());
            }

        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
       $model = Page::findOne($id);

        if($model == null) throw new NotFoundHttpException("Страница не найденв");

        if($model->load(Yii::$app->request->post()))
        {
            //exit(var_dump(Yii::$app->request->post()));
            if($model->validate())
            {
                if($model->imageFile = UploadedFile::getInstance($model, 'imageFile'))
                    $model->uploadImage();

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
        $model = Page::findOne($id);

        if($model == null) throw new NotFoundHttpException("Страница не найденв");
        $model->delete();

        return $this->redirect('/admin/pages');
    }
}