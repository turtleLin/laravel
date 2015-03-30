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
				$user = User::find($work->user_id);
				$user->hot = $user->hot + 1;
				$user->save();
				$work->hot = $work->hot + 1;
				$work->save();

				$news = new News;
				$news->work_id = $work_id;
				$news->code = 1;
				$news->sender_id = $user_id;
				$news->user_id = $work->user_id;
				$news->content = User::find($user_id)->username . '点赞了您的作品!';
				$news->save();

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
				$user = User::find($work->user_id);
				$user->hot = $user->hot - 1;
				$user->save();
				$work->hot = $work->hot - 1;
				$work->save();

				$news = new News;
				$news->work_id = $work_id;
				$news->code = 1;
				$news->sender_id = $user_id;
				$news->user_id = $work->user_id;
				$news->content = User::find($user_id)->username . '取消点赞您的作品!';
				$news->save();
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