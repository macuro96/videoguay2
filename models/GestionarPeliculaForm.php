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

    /**
     * El número de socio asociado a la búsqueda de la película.
     * @var string
     */
    public $numero;

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
            [['codigo', 'numero'], 'required'],
            [['codigo', 'numero'], 'number'],
            [
                ['numero'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Socios::className(),
                'targetAttribute' => ['numero' => 'numero'],
            ],
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
