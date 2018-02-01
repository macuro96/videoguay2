<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Peliculas */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Peliculas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peliculas-view">

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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codigo',
            'titulo',
            'precio_alq',
        ],
    ]) ?>

    <h3>Historial de alquileres</h3>
    <?php if ($alquileresPelicula == null): ?>
        <h5>No hay alquileres de esta película</h5>
    <?php endif ?>
    <table class="table table-striped">
        <thead>
            <th>Número de socio</th>
            <th>Nombre</th>
            <th>Fecha de alquiler</th>
            <th></th>
        </thead>

        <tbody>
            <?php foreach ($alquileresPelicula as $alquiler): ?>
                <tr>
                    <td><?= Html::encode($alquiler->socio->numero) ?></td>
                    <td><?= Html::a(Html::encode($alquiler->socio->nombre),
                            Url::to(['socios/view', 'id' => $alquiler->socio->id]))
                        ?>
                    </td>
                    <td><?= Yii::$app->formatter->asDateTime(Html::encode($alquiler->created_at)) ?></td>
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
