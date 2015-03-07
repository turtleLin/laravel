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
			$friend_id = $friend->friend_id;
			$friend = new Friend;
			$friend->user_id = $user_id;
			$friend->friend_id = $friend_id;
			if($friend->save())
			{
				Response::json(array('errCode' => 0,'message' =>'关注成功!'));
			}else
			{
				Response::json(array('errCode' => 1,'message' =>'关注失败!'));
			}
		}else
		{
			Response::json(array('errCode' => 2,'message' =>'用户不存在!'));
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
			if($fri->delete())
			{
				Response::json(array('errCode' => 0,'message' =>'取消关注成功!'));
			}else
			{
				Response::json(array('errCode' => 1,'message' =>'取消关注失败!'));
			}
		}else
		{
			Response::json(array('errCode' => 2,'message' =>'用户不存在!'));
		}
	}

	public function getList()
	{
		$username = Input::get('username');
		$user = User::where('username',$username)->first();
		if(isset($user))
		{
			$friends = $user->friends()->paginate(15)->toJson();
			$friends = json_decode($friends);

			return Response::json(array('errCode' => 0,'friends' => $friends->data));
		}else
		{
			Response::json(array('errCode' => 2,'message' =>'用户不存在!'));
		}
	}

}