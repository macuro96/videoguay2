<?php

namespace app\controllers;

use app\models\Alquileres;
use app\models\AlquileresSearch;
use app\models\GestionarPeliculaForm;
use app\models\GestionarSocioForm;
use app\models\Peliculas;
use app\models\Socios;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AlquileresController implements the CRUD actions for Alquileres model.
 */
class AlquileresController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Alquileres models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlquileresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alquileres model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Alquileres model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Alquileres();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Alquileres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Alquileres model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Alquileres model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Alquileres the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alquileres::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El alquiler no existe.');
    }

    public function actionDevolver()
    {
        $id = Yii::$app->request->post('alquiler');

        if ($id === null) {
            throw new NotFoundHttpException('Falta el alquiler');
        }

        $alquiler = $this->findModel($id);

        $alquiler->devolucion = date('Y-m-d H:i:s');
        $alquiler->save();

        return $this->redirect(
            Yii::$app->request->referrer ?: Yii::$app->homeUrl
        );
    }

    public function actionAlquilar()
    {
        $numero = Yii::$app->request->post('numero');
        $codigo = Yii::$app->request->post('codigo');

        if ($codigo === null) {
            throw new NotFoundHttpException('Falta la película');
        }

        $socio = Socios::findOne(['numero' => $numero]);

        if ($socio === null) {
            throw new NotFoundHttpException('No existe el socio');
        }

        $pelicula = Peliculas::findOne(['codigo' => $codigo]);

        if ($pelicula === null) {
            throw new NotFoundHttpException('No existe el pelicula');
        }

        $alquiler = new Alquileres([
            'socio_id' => $socio->id,
            'pelicula_id' => $pelicula->id,
        ]);

        if (!$alquiler->save()) {
            throw new NotFoundHttpException('Alquiler inválido');
        }

        return $this->redirect(['gestionar', 'numero' => $numero]);
    }

    public function actionGestionar($numero = null, $codigo = null)
    {
        $data = ['errorPelicula' => false];

        $alquileresPendientes = null;

        $gestionarSocioForm = new GestionarSocioForm([
            'numero' => $numero,
        ]);

        $gestionarPeliculaForm = new GestionarPeliculaForm([
            'codigo' => $codigo,
        ]);

        $data['gestionarSocioForm'] = $gestionarSocioForm;
        $data['gestionarPeliculaForm'] = $gestionarPeliculaForm;

        if ($numero !== null && $gestionarSocioForm->validate()) {
            $data['socio'] = Socios::findOne(['numero' => $numero]);

            $alquileresPendientes = Alquileres::find()
                                              ->with('pelicula')
                                              ->where(['devolucion' => null])
                                              ->andWhere(['socio_id' => $data['socio']->id])
                                              ->orderBy('created_at DESC')
                                              ->all();

            $data['alquileresPendientes'] = $alquileresPendientes;
        }

        if ($codigo !== null && $gestionarPeliculaForm->validate()) {
            $data['pelicula'] = Peliculas::findOne(['codigo' => $codigo]);

            $alquiler = new Alquileres([
                'socio_id' => $data['socio']->id,
                'pelicula_id' => $data['pelicula']->id,
            ]);

            if (!$alquiler->validate()) {
                $error = $alquiler->getErrors('pelicula_id')[0];
                $gestionarPeliculaForm->addError('codigo', $error);
                $data['errorPelicula'] = true;
            }
        }

        return $this->render('gestionar', $data);
    }
}
