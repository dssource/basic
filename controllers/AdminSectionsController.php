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

class AdminSectionsController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Section::find(),
                'sort'=> ['defaultOrder' => ['id_parent' => SORT_ASC, 'name' => SORT_ASC]]
            ])
        ]);
    }

    public function actionCreate()
    {
        $model = new Section();
        //Default value

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

        //Default empty model
        $model->isParent = true;
        $model->show_in_catalog = true;

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
       $model = Section::findOne($id);

        if($model == null) throw new NotFoundHttpException("Раздел не найден");

        $model->scenario = Section::SCENARIO_SECTION_FOR_PAGES;

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

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Section::findOne($id);

        if($model == null) throw new NotFoundHttpException("Раздел не найден");
        $model->delete();

        return $this->redirect('/admin/sections');
    }
}