<?php

namespace app\models;

use yii\base\Model;

/**
 * @property string $codigo
 *
 * @property Alquileres[] $alquileres
 */
class GestionarPeliculaForm extends Model
{
    /**
     * El código de la película.
     * @var string
     */
    public $codigo;

    public function formName()
    {
        return '';
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Código de la película',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo'], 'required'],
            [['codigo'], 'number'],
            [
                ['codigo'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Peliculas::className(),
                'targetAttribute' => ['codigo' => 'codigo'],
            ],
        ];
    }
}
