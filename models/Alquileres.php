<?php

namespace app\models;

/**
 * This is the model class for table "alquileres".
 *
 * @property int $id
 * @property int $socio_id
 * @property int $pelicula_id
 * @property string $created_at
 * @property string $devolucion
 *
 * @property Peliculas $pelicula
 * @property Socios $socio
 */
class Alquileres extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alquileres';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['socio_id', 'pelicula_id'], 'required'],
            [['socio_id', 'pelicula_id'], 'default', 'value' => null],
            [['socio_id', 'pelicula_id'], 'integer'],
            [['created_at', 'devolucion'], 'datetime'],
            [['socio_id', 'pelicula_id', 'created_at'], 'unique', 'targetAttribute' => ['socio_id', 'pelicula_id', 'created_at']],
            [['pelicula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Peliculas::className(), 'targetAttribute' => ['pelicula_id' => 'id']],
            [['socio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socios::className(), 'targetAttribute' => ['socio_id' => 'id']],
            [['pelicula_id'], function ($attribute, $params, $validator) {
                $pelicula = Peliculas::findOne($this->pelicula_id);

                if ($pelicula->estaAlquilada) {
                    $this->addError($attribute, 'La película "' . $pelicula->titulo . '" ya está alquilada');
                }
            }, 'when' => function ($model, $attribute) {
                return $model->id === null;
            }],
            [['pelicula_id'], function ($attribute, $params, $validator) {
                $pelicula = Peliculas::findOne($this->pelicula_id);

                if (!$pelicula->estaAlquilada) {
                    $this->addError($attribute, 'La película "' . $pelicula->titulo . '" no está alquilada');
                }
            }, 'when' => function ($model, $attribute) {
                return $model->id !== null;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'socio_id' => 'Socio ID',
            'pelicula_id' => 'Pelicula ID',
            'created_at' => 'Fecha de alquiler',
            'devolucion' => 'Fecha de devolucion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelicula()
    {
        return $this->hasOne(Peliculas::className(), ['id' => 'pelicula_id'])->inverseOf('alquileres');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocio()
    {
        return $this->hasOne(Socios::className(), ['id' => 'socio_id'])->inverseOf('alquileres');
    }

    public function getEstaPendiente()
    {
        return $this->devolucion === null;
    }
}
