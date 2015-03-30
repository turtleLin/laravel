<?php

class FriendController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /friend
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$user_id = Sentry::getUser()->id;
		$username = Input::get('username');
		$friend = User::where('username',$username)->first();
		if(isset($friend))
		{
			$friend_id = $friend->id;
			$friend1 = new Friend;
			$friend1->user_id = $user_id;
			$friend1->friend_id = $friend_id;

			$friend2 = Friend::where('user_id',$friend_id)->where('friend_id',$user_id)->first();
			if(isset($friend2))
			{
				$friend1->mutual = 1;
				DB::update('update rbpm_friends set mutual = ? where user_id = ? and friend_id = ?',array(1,$friend_id,$user_id));
			}

			if($friend1->save())
			{
				$news = new News;
				$news->sender_id = $user_id;
				$news->user_id = $friend->id;
				$news->code = 0;
				$news->content = Sentry::getUser()->username . '关注了您!';
				$news->save();

				$friend->hot++;
				$friend->save();

				return Response::json(array('errCode' => 0,'message' =>'关注成功!'));
			}else
			{
				return Response::json(array('errCode' => 1,'message' =>'关注失败!'));
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' =>'用户不存在!'));
		}
	}

	public function postDelete()
	{
		$user_id = Sentry::getUser()->id;
		$username = Input::get('username');
		$friend = User::where('username',$username)->first();
		if(isset($friend))
		{
			$friend_id = $friend->id;
			$fri = Friend::where('user_id',$user_id)->where('friend_id',$friend_id)->first();

			if($fri->mutual)
			{
				DB::update('update rbpm_friends set mutual = ? where user_id = ? and friend_id = ?',array(0,$friend_id,$user_id));
			}

			$result = DB::delete('delete from rbpm_friends where user_id = ? and friend_id = ?',array($user_id,$friend_id));

			if(isset($result))
			{
				$news = new News;
				$news->sender_id = $user_id;
				$news->user_id = $friend->id;
				$news->code = 0;
				$news->content = Sentry::getUser()->username . '取消了对您的关注!';
				$news->save();

				$friend->hot--;
				$friend->save();

				return Response::json(array('errCode' => 0,'message' =>'取消关注成功!'));
			}else
			{
				return Response::json(array('errCode' => 1,'message' =>'取消关注失败!'));
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' =>'用户不存在!'));
		}
	}

	public function getAttentionList()
	{
		$username = Input::get('username');
		$user = User::where('username',$username)->first();
		if(isset($user))
		{
			$friends = $user->friends()->paginate(15)->toJson();
			$friends = json_decode($friends)->data;

			foreach ($friends as $friend) {
				$fan = Friend::where('friend_id',$friend->id)->where('user_id',$user->id)->first();
				$friend->mutual = $fan->mutual;
			}

			return Response::json(array('errCode' => 0,'friends' => $friends));
		}else
		{
			return Response::json(array('errCode' => 1,'message' =>'用户不存在!'));
		}
	}

	public function getFans()
	{
		$username = Input::get('username');
		$user = User::where('username',$username)->first();
		if(!isset($user))
			return Response::json(array('errCode' => 1,'message' =>'用户不存在!'));

		$fans = $user->fans()->orderBy('hot','desc')->paginate(15)->toJson();

		$fans = json_decode($fans)->data;

		foreach ($fans as $fan) {
			$friend = Friend::where('friend_id',$user->id)->where('user_id',$fan->id)->first();
			$fan->mutual = $friend->mutual;
		}

		return Response::json(array('errCode' => 0,'fans' => $fans)); 
	}

}