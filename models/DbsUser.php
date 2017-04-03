<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dbs_user".
 *
 * @property string $id
 * @property string $username
 * @property string $dob
 *
 * @property Ratings[] $ratings
 * @property Venues[] $venues
 * @property SocialNetwork[] $socialNetworks
 * @property SocialNetwork[] $socialNetworks0
 * @property DbsUser[] $friends
 * @property DbsUser[] $users
 * @property UserLocation $userLocation
 */
class DbsUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dbs_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'number'],
            [['dob'], 'safe'],
            [['username'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'dob' => 'Dob',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Ratings::className(), ['userid' => 'id'])
        		->andWhere('rating > :ratingId', ['ratingId'=>\Yii::$app->params['rating']])
        		->orderBy(['rating' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenues()
    {
        return $this->hasMany(Venues::className(), ['id' => 'venueid'])->viaTable('dbs_ratings', ['userid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialNetworks()
    {
        return $this->hasMany(SocialNetwork::className(), ['userid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialNetworks0()
    {
        return $this->hasMany(SocialNetwork::className(), ['friend_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        return $this->hasMany(DbsUser::className(), ['id' => 'friend_id'])->viaTable('dbs_social_network', ['userid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(DbsUser::className(), ['id' => 'userid'])->viaTable('dbs_social_network', ['friend_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLocation()
    {
        return $this->hasOne(UserLocation::className(), ['id' => 'id']);
    }

    public function getNearByPopularVenues($radius, $type, $rating)
    {
    	$userLattitude = $this->userLocation != null ? $this->userLocation->latitude : 0;
    	$userLongitude = $this->userLocation != null ? $this->userLocation->longitude : 0;

    	$sql = "(SELECT id, latitude, longitude, SQRT(POW(69.1 *  (latitude - $userLattitude), 2) + POW(69.1 * ($userLongitude - longitude) 
    			* COS(latitude / 57.3),2)) AS distance FROM dbs_venues_location HAVING distance < $radius 
    			ORDER BY distance)";

    	$finalSql2 = "select id, avg(rating) as avg_rating, latitude, longitude, distance from dbs_ratings dr right join $sql dvl ".
      				"on dvl.id=dr.venueid group by venueid having avg_rating > $rating order by avg_rating desc limit 100";

    	$str = strlen(trim($type)) > 0 ? " where type like '%$type%'":"";
    	$finalSql3 = "select fnl.id, avg_rating, latitude, longitude, distance, address, type from dbs_venues dv
    					right join ($finalSql2) fnl on dv.id=fnl.id $str order by address desc";
    	$connection = \Yii::$app->db;
    	$model = $connection->createCommand($finalSql3);

    	$venuesLocation = $model->queryAll();

    	return $venuesLocation;
    }

    public function getNearBySocialVenues($radius, $type, $rating)
    {
    	$userLattitude = $this->userLocation != null ? $this->userLocation->latitude : 0;
    	$userLongitude = $this->userLocation != null ? $this->userLocation->longitude : 0;
 
    	$result = [];
    	$friends = $this->getFriends()->all();

    	// get friends
    	foreach ($friends as $friend)
    	{
    		if(! isset($friend->id))
    			continue;

    		$result[$friend->id] = $friend;

    		$friendsOfFriend = $friend->getFriends()->all();
    		foreach ($friendsOfFriend as $friend2)
    		{
    			if(! isset($friend2->id))
    				continue;

    			$result[$friend2->id] = $friend2;
    		}
    	}

    	$friendsKeys = implode (",", array_keys($result));

    	$sql = "(SELECT id, latitude, longitude, SQRT(POW(69.1 *  (latitude - $userLattitude), 2) + POW(69.1 * ($userLongitude - longitude)
		    	* COS(latitude / 57.3),2)) AS distance FROM dbs_venues_location HAVING distance < $radius
		    	ORDER BY distance)";
    
        $finalSql2 = "select id, avg(rating) as avg_rating, latitude, longitude, distance from dbs_ratings dr right join $sql dvl ".
        		"on dvl.id=dr.venueid where userid in (".$friendsKeys.") group by venueid having avg_rating > $rating 
        		order by avg_rating desc limit 100";

        $str = strlen(trim($type)) > 0 ? " where type like '%$type%'":"";
	    $finalSql3 = "select fnl.id, avg_rating, latitude, longitude, distance, address, type from dbs_venues dv
	        right join ($finalSql2) fnl on dv.id=fnl.id $str order by address desc";

    	$connection = \Yii::$app->db;
        $model = $connection->createCommand($finalSql2);

        $venuesLocation = $model->queryAll();
    
        return $venuesLocation;
    }

}
