<?php

namespace app\models;

/**
 * This is the model class for table "peliculas".
 *
 * @property int $id
 * @property string $codigo
 * @property string $titulo
 * @property string $precio_alq
 *
 * @property Alquileres[] $alquileres
 */
class Peliculas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peliculas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'titulo', 'precio_alq'], 'required'],
            [['codigo', 'precio_alq'], 'number'],
            [['titulo'], 'string', 'max' => 255],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Código',
            'titulo' => 'Título',
            'precio_alq' => 'Precio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlquileres()
    {
        return $this->hasMany(Alquileres::className(), ['pelicula_id' => 'id'])->inverseOf('pelicula');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocios()
    {
        return $this->hasMany(Socios::className(), ['id' => 'socio_id'])
        ->via('alquileres');
    }

    /**
     * Devuelve si una pelicula esta alquilada.
     * @return bool Si esta alquilada o no
     */
    public function getEstaAlquilada()
    {
        return $this->getAlquileres()->where(['devolucion' => null])->exists();
    }
}
