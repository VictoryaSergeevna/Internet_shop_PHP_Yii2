<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $code
 * @property string $symbol
 * @property int $is_main
 * @property float $rate
 */
class Currency extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
    }
  public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'symbol'], 'required'],
            [['is_main'], 'integer'],
            [['rate'], 'number'],
            [['code', 'symbol'], 'string', 'max' => 128],
             [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
   
     protected $fillable = ['rate'];
   

    public function isMain()
    {
        return $this->is_main === 1;
    }
}
