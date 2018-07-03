<?php

use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->params['breadcrumbs'][] = 'Права доступа для '.$model->username;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h3>Текущая роль: <strong><?=implode(', ', $selectedRoles)?></strong></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Роль</div>
                <div class="panel-body">
                    <?php ActiveForm::begin()?>
                    <div class="input-group">
                        <?=Html::dropDownList('userRole', null, $roles, [
                            'class' => 'form-control'
                        ])?>
                        <br>
                        <br>
                    </div>
                    <div class="input-group">
                        <?=Html::submitButton("Установить", [
                            'class' => 'btn btn-success btn-sm',
                        ])?>
                    </div>
                    <?php ActiveForm::end()?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Полномочия</div>
                <div class="panel-body">
                    <?php ActiveForm::begin()?>
                    <div class="input-group">
                        <?php
                        foreach ($permissions as $pname => $pdescription)
                        {
                            echo '<div class="checkbox">'.
                                '<label>'.
                                Html::checkbox('userPermissions['.$pname.']', (isset($selectedPermissions[$pname]))).' '.
                                $pdescription.
                                '</label>'.
                                '</div>';
                        }
                        ?>
                        <br>
                        <br>
                    </div>
                    <div class="input-group">
                        <?=Html::submitButton("Установить", [
                            'class' => 'btn btn-success btn-sm',
                        ])?>
                    </div>
                    <?php ActiveForm::end()?>
                </div>
            </div>
        </div>
    </div>
</div>