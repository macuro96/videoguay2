<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Alquileres */

$this->title = 'Gestionar alquileres';
$this->params['breadcrumbs'][] = ['label' => 'Alquileres', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Gestionar';
?>
<div class="gestionar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $formSocio = ActiveForm::begin([
        'method' => 'get',
        'action' => ['alquileres/gestionar']
    ]); ?>
        <?= $formSocio->field($gestionarSocioForm, 'numero')->textInput() ?>

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
        <?php if ($alquileresPendientes == null): ?>
            <h5>No hay alquileres pendietes</h5>
        <?php endif ?>
        <table class="table table-striped">
            <thead>
                <th>Codigo</th>
                <th>Titulo</th>
                <th></th>
            </thead>
            <tbody>
                <?php foreach ($alquileresPendientes as $alquiler): ?>
                    <tr>
                            <td><?= Html::encode($alquiler->pelicula->codigo) ?></td>
                            <td><?= Html::encode($alquiler->pelicula->titulo) ?></td>
                            <td>
                                <?= Html::beginForm(['alquileres/devolver']) ?>
                                    <?= Html::hiddenInput('alquiler', $alquiler->id) ?>
                                    <?= Html::submitButton('Devolver', ['class' => 'btn btn-xs btn-danger']) ?>
                                <?= Html::endForm() ?>
                            </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>

        <h3>Alquilar una película</h3>

        <?php $formPelicula = ActiveForm::begin([
            'method' => 'get',
            'action' => ['alquileres/gestionar']
        ]); ?>
            <?= Html::hiddenInput('numero', $socio->numero) ?>
            <?= $formPelicula->field($gestionarPeliculaForm, 'codigo')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <?php if (isset($pelicula)): ?>
            <?= DetailView::widget([
                'model' => $pelicula,
                'attributes' => [
                    'codigo',
                    'titulo',
                    'precio_alq',
                ],
            ]) ?>

            <?php if (!$errorPelicula): ?>
                <?php $formAlquilar = ActiveForm::begin([
                    'action' => ['alquileres/alquilar']
                ]); ?>
                    <?= Html::hiddenInput('codigo', $pelicula->codigo) ?>
                    <?= Html::hiddenInput('numero', $socio->numero) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Alquilar', ['class' => 'btn btn-success']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            <?php endif ?>

        <?php endif ?>

    <?php endif ?>

</div>
