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
     * Almacena los socios si hay más de uno en la búsqueda
     * @var Socios[]
     */
    public $socios;

    /**
     * Los numeros de cada socio separados por (, )
     * @var string
     */
    public $numeros;

    /**
     * Devuelve el array de socios de la búsqueda
     * @return Socios[]
     */
    public function getSocios()
    {
        return $this->socios;
    }

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
            'numeros' => 'Socio/s',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numeros'], 'required'],
            [['numeros'], 'filter', 'filter' => function ($value) {
                $value    = trim($value);
                $aValues  = explode(', ', $value);
                //$aValues  = array_map(mb_strlen($contenido), $aValues);
                $aValues  = array_unique($aValues);
                $iNumeros = 0;

                // Comprobar que son numeros
                for ($n = 0; $n < count($aValues); $n++) {
                    $iNumeros += (ctype_digit($aValues[$n]) ? 1 : 0);
                }

                if ($iNumeros == count($aValues)) {
                    $socios = Socios::find()->where(['numero' => $aValues[0]]);

                    for ($i = 1; $i < count($aValues); $i++) {
                        $socios->orWhere(['numero' => $aValues[$i]]);
                    }

                    $aSocios = $socios->all();

                    if ($aSocios != null) {
                        $this->socios = $aSocios;
                    } else {
                        $value = null;
                    }
                }

                return $value;
            }],
            [['numeros'], function ($attribute, $params, $validator) {
                if ($this->$attribute == null) {
                    $this->addError($attribute, 'No existe ningun socio con esos números');
                }
            }, 'skipOnEmpty' => false],
            /*
            [['numero'], 'required'],
            [['numero'], 'filter', 'filter' => function ($value) {
                if (!ctype_digit($value)) {
                    $socios = Socios::find()
                                   ->where(['like', 'lower(nombre)', mb_strtolower($value)])
                                   ->all();

                    if ($socios !== null) {
                        $value = $socios;
                    }
                }

                return $value;
            }],
            */
            /*
            [['numero'], 'filter', 'filter' => function ($value) {

            }]
            */
            /*
            [
                ['numero'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Socios::className(),
                'targetAttribute' => ['numero' => 'numero'],
            ],
            */
        ];
    }
}
