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

        <h3>Alquileres pendientes</h3>

        <table class="table table-striped">
            <thead>
                <th>Codigo</th>
                <th>Titulo</th>
                <th></th>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($alquileresPendientes as $alquiler): ?>
                        <td><?= Html::encode($alquiler->pelicula->codigo) ?></td>
                        <td><?= Html::encode($alquiler->pelicula->titulo) ?></td>
                        <td><?= Html::encode($alquiler->pelicula->titulo) ?></td>
                        <td>
                            <?= Html::beginForm(['alquileres/devolver']) ?>
                                <?= Html::hiddenInput('alquiler', $alquiler->id) ?>
                                <?= Html::submitButton('Devolver', ['class' => 'btn btn-xs btn-danger']) ?>
                            <?= Html::endForm() ?>
                        </td>
                    <?php endforeach ?>
                </tr>
            </tbody>

        </table>

    <?php endif ?>

</div>
