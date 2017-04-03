<?php 
namespace model\app;
use Ds;


class SocialRecommender extends IRecommender
{
	public function recommend ($user, $locations)
	{
		if ($user == null)
		{
			// throw excepion
		}

		$result = [];
		$friends = $user->getFriends();
		
		// get friends
		foreach ($friends as $friend)
		{
			$result[$friend->id] = $friend;

			$friendsOfFriend = $friend->getFriends();
			foreach ($friendsOfFriend as $friend2)
			{
				$result[$friend2->id] = $friend2;
			}
		}

		$ratings = [];
		//get top rated location
		foreach ($result as $key=>$value)
		{
			$ratings = array_merge($ratings, $value->getRatings());
		}

		return $ratings;
	}
}


