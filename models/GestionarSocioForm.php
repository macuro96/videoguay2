<?php

namespace app\models;

use yii\base\Model;

/**
 * This is the model class for table "socios".
 *
 * @property int $id
 * @property string $numero
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 *
 * @property Alquileres[] $alquileres
 */
class GestionarSocioForm extends Model
{
    /**
     * El número del socio.
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
            'numero' => 'Socio',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero'], 'required'],
            [['numero'], function ($attribute, $params, $validator) {
                if (!ctype_digit($this->numero)) {
                    $nombre = $this->numero;
                    $numero = Socios::find(['nombre' => $nombre])
                                    ->select('numero')
                                    ->column();

                    return $numero;
                }
            }],
            [
                ['numero'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Socios::className(),
                'targetAttribute' => ['numero' => 'numero'],
            ],
        ];
    }
}
