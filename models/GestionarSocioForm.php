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
     * Almacena los socios si hay más de uno en la búsqueda.
     * @var Socios[]
     */
    public $socios;

    /**
     * Los numeros de cada socio separados por (, ).
     * @var string
     */
    public $numeros;

    /**
     * Devuelve el array de socios de la búsqueda.
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
                $value = trim($value);
                $aValues = explode(', ', $value);
                //$aValues  = array_map(mb_strlen($contenido), $aValues);
                $aValues = array_unique($aValues);
                $iNumeros = 0;

                // Comprobar que son numeros
                for ($n = 0; $n < count($aValues); $n++) {
                    if (ctype_digit($aValues[$n])) {
                        if ($n == 0) {
                            $socios = Socios::find()->where(['numero' => $aValues[$n]]);
                        }

                        $socios->orWhere(['numero' => $aValues[$n]]);
                    } else {
                        if ($n == 0) {
                            $socios = Socios::find()->where(['like', 'lower(nombre)', mb_strtolower($aValues[$n])]);
                        }

                        $socios->orWhere(['nombre' => $aValues[$n]]);
                    }

                    $aSocios = $socios->all();

                    if ($aSocios != null) {
                        $this->socios = $aSocios;
                    } else {
                        $value = null;
                    }
                    //$iNumeros += (ctype_digit($aValues[$n]) ? 1 : 0);
                }

                return $value;
            }],
            [['numeros'], function ($attribute, $params, $validator) {
                if ($this->$attribute == null) {
                    $this->addError($attribute, 'No existe ningun socio con esa serie de identificador/es');
                }
            }, 'skipOnEmpty' => false],
        ];
    }
}
