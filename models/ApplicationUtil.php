<?php
namespace app\models;

class ApplicationUtil
{
	public static function getDefaultRatingLevel()
	{
		return \Yii::$app->params['rating'];
	}

	public static function getDefaultRadius ()
	{
		return \Yii::$app->params['distance'];;
	}
}

