<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AlquileresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alquileres-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'socio.numero') ?>

    <?= $form->field($model, 'socio.nombre') ?>

    <?= $form->field($model, 'pelicula.codigo') ?>

    <?= $form->field($model, 'pelicula.titulo') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'devolucion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
