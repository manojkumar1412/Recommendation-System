<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%_ratings}}".
 *
 * @property string $userid
 * @property string $venueid
 * @property string $rating
 *
 * @property User $user
 * @property Venues $venue
 */
class Ratings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%_ratings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'venueid'], 'required'],
            [['userid', 'venueid', 'rating'], 'number'],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userid' => 'id']],
            [['venueid'], 'exist', 'skipOnError' => true, 'targetClass' => Venues::className(), 'targetAttribute' => ['venueid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => 'Userid',
            'venueid' => 'Venueid',
            'rating' => 'Rating',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenue()
    {
        return $this->hasOne(Venues::className(), ['id' => 'venueid']);
    }
}
