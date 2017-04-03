<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%_venues}}".
 *
 * @property string $id
 * @property string $type
 * @property string $name
 * @property string $address
 *
 * @property Ratings[] $ratings
 * @property User[] $users
 * @property VenuesLocation $venuesLocation
 */
class Venues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%_venues}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'number'],
            [['type', 'name', 'address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'address' => 'Address',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Ratings::className(), ['venueid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userid'])->viaTable('{{%_ratings}}', ['venueid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenuesLocation()
    {
        return $this->hasOne(VenuesLocation::className(), ['id' => 'id']);
    }

    public function getPrimaryKey($asArray = false)
    {
    	if ($asArray)
    		return array('id');
    	return 'id';
    }
    public static function primaryKey()
    {
    	return 'id';
    } 
}
