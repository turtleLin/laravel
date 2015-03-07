<?php

class LikeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /like
	 *
	 * @return Response
	 */
	public function postCreate()
	{
		$user_id = Sentry::getUser()->id;
		$work_id = Input::get('work_id');
		$work = Work::find($work_id);
		if(isset($work))
		{
			if($work->likes()->attach($user_id))
			{
				return Response::json(array('errCode' => 0,'message' => '点赞成功！'));
			}else
			{
				return Response::json(array('errCode' => 1,'message' => '点赞失败！'));
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '没有该作品！'));
		}
		
	}

	public function postDelete()
	{
		$user_id = Sentry::getUser()->id;
		$work_id = Input::get('work_id');
		$work = Work::find($work_id);
		if(isset($work))
		{
			if($work->likes()->detach($user_id))
			{
				return Response::json(array('errCode' => 0,'message' => '取消点赞成功！'));
			}else
			{
				return Response::json(array('errCode' => 1,'message' => '取消点赞失败！'));
			}
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '没有该作品！'));
		}
		
	}

	public function getList()
	{
		$work_id = Input::get('work_id');
		$work = Work::find($work_id);
		if(isset($work))
		{
			$user = $work->likes()->paginate(15)->toJson();
			$user = json_decode($user);

			return Response::json(array('errCode' => 0,'users' => $user->data));
		}else
		{
			return Response::json(array('errCode' => 1,'message' => '没有该作品！'));
		}
	}

}