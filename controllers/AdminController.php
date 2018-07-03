<?php

namespace dssource\basic\controllers;

use Yii;
use yii\base\ErrorException;
use yii\web\Controller;
use dssource\basic\models\Module;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $model = new Module();
        return $this->render('index', ['model' => $model]);
    }

    public function actionAssetsClear()
    {
        $this->removeDir(Yii::getAlias('@app/assets'));
        Yii::$app->getSession()->setFlash('success', 'Assets очищены');
        $this->redirect('/admin');
    }

    public static function removeDir($path)
    {
        if(file_exists($path) && is_dir($path))
        {
           foreach(glob($path.'/*') as $file)
           {
               if(is_dir($file))
               {
                   self::removeDir($file);
                   rmdir($file);
               }
               else
               {
                   unlink($file);
               }
           }
        }
        else
            throw new ErrorException("Не верный путь к Assets: ".$path);
    }

}