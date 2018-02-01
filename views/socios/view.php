<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Socios */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Socios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socios-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Gestionar', ['alquileres/gestionar', 'numero' => $model->numero], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'numero',
            'nombre',
            'direccion',
            'telefono',
        ],
    ]) ?>

    <h3>Historial de películas alquiladas</h3>
    <?php if ($alquileresSocio == null): ?>
        <h5>No hay películas alquiladas</h5>
    <?php endif ?>
    <table class="table table-striped">
        <thead>
            <th>Código</th>
            <th>Título</th>
            <th>Fecha de alquiler</th>
            <th>Fecha de devolución</th>
        </thead>

        <tbody>
            <?php foreach ($alquileresSocio as $alquiler): ?>
                <tr>
                    <td><?= Html::encode($alquiler->pelicula->codigo) ?></td>
                    <td><?= Html::a(Html::encode($alquiler->pelicula->titulo),
                            Url::to(['peliculas/view', 'id' => $alquiler->pelicula->id]))
                        ?>
                    </td>
                    <td><?= Yii::$app->formatter->asDateTime(Html::encode($alquiler->created_at), 'medium') ?></td>
                    <td>
                        <?php if ($alquiler->estaPendiente): ?>
                            <?= Html::beginForm(['alquileres/devolver']) ?>
                                <?= Html::hiddenInput('alquiler', $alquiler->id) ?>
                                <?= Html::submitButton('Devolver', ['class' => 'btn btn-xs btn-danger']) ?>
                            <?= Html::endForm() ?>
                        <?php else: ?>
                            <?= Yii::$app->formatter->asDateTime(Html::encode($alquiler->devolucion)) ?>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>

    </table>

</div>
