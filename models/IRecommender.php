<?php 
namespace app\models;

abstract class IRecommender
{
	public abstract function recommend($user);
}

