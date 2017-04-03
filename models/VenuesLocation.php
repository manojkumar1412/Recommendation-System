<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%_venues_location}}".
 *
 * @property string $id
 * @property string $latitude
 * @property string $longitude
 *
 * @property Venues $venue
 */
class VenuesLocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%_venues_location}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'latitude', 'longitude'], 'number'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'id']);
    }
}
