<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */

$this->title = 'Gestionar alquileres';
$this->params['breadcrumbs'][] = ['label' => 'Gestionar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gestionar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['alquileres/gestionar']
    ]); ?>
        <?= $form->field($gestionarSocioForm, 'numero')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

    <?php if (isset($socio)): ?>
        <?= DetailView::widget([
            'model' => $socio,
            'attributes' => [
                'numero',
                'nombre',
                'telefono',
            ],
        ]) ?>

    <?php endif ?>

</div>
